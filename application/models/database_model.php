<?php
class Database_model extends MY_Model {
    function Database_model()
    {
    	parent::__construct();
    }
    /**
     * Returns the database size in bytes     *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_database_size() {//$this->db->database
        if ($this->db->dbdriver == 'postgre') {
            $query = $this->db->query("SELECT pg_database_size('". $this->db->database."') as dbsize");
            $dbsize=$query->result_array();
            return ($dbsize[0]['dbsize']);
            
        }else{
            $query = $this->db->query("SELECT table_schema AS 'database', sum( data_length + index_length) AS 'dbsize' 
                                        FROM information_schema.TABLES
                                        WHERE table_schema = '". $this->db->database."' 
                                       GROUP BY table_schema");
            $dbsize=$query->result_array();
            return ($dbsize[0]['dbsize']);
            
        }
    }
     
     
     /**
     * Returns Number of Clients
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetNumberClients() {
        return $this->db->count_all('client');
    }
     
     
    /**
     * Returns Number of Files
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetNumberFiles() {
        return $this->db->count_all('filename');
    }

     /**
     * Returns Stored Size
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetStoredSize() {
        $this->db->select_sum('volbytes');
        $query = $this->db->get('media');
        return $query->row()->volbytes;
    }
     
      /**
     * Returns Sum Job Bytes and Files in date intervbal 
     *
     * @param string $dataMenor The value of starting period
     * @param string $dataMaior The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
     
    function Sum_Files_Bytes_Date_Interval( $dataMenor, $dataMaior) {
         
         
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
             {
                $this->db->where('endtime <= ' , $dataMaior );
                $this->db->where('endtime > ', $dataMenor);
            }

        $this->db->select_sum('job.jobbytes', 'bytes')
                 ->select_sum('job.jobfiles', 'files')
                 
                 ->select ('date(job.starttime) as data')
                 ->order_by('data','asc')
                 ->group_by('data');

        $query = $this->db->get('job');
        return $query->result_array();
    }
    
    
    ///// Clients Models
    
     /**
     * Returns Jobs Associated to i Clients
     *
     * @param string $poolname The name of pool to search by
     * @return array
     * @author Pedro Oliveira
     */
    function Get_Client_Data($clientName, $dataMenor, $dataMaior) {

         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }
          $this->db->select(' job.jobid,
                            job.name,
                            job.starttime,
                            job.endtime,
                            media.volumename,
                            job.level,
                            job.jobfiles,
                            job.jobbytes,
                            job.jobstatus
                           ')
                 ->from('jobmedia')
                 ->join('job', 'jobmedia.jobid = job.jobid')
                 ->join('media', 'media.mediaid = jobmedia.mediaid')
                 ->join('client', 'job.clientid = client.clientid')
                 ->where('client.name =',$clientName )
                ->group_by('job.jobid,
                            job.starttime,
                            job.endtime,
                            job.jobstatus,
                            job.jobfiles,
                            job.jobbytes,
                            job.level,
                            media.volumename,
                            job.name
                            ')
                 ->order_by('job.starttime', 'dsc');
        $query = $this->db->get();
        return $query->result_array();
    }
  
    /**
     * Returns the Jobs Logs 
     * @jobid integer      *
     * @return array
     * @author Pedro Oliveira
     */
    function get_job_logs($jobid) {//$this->db->database
        
       $this->db->select('log.logtext')
                ->from('log')
                ->where('log.jobid = ' , $jobid );
                
        $query = $this->db->get();
        
        foreach ($query->result_array() as $row)
        {
            $logs[] = preg_replace("/[\t\n]+/", '</br>', $row['logtext']);  
        }
       
        return $logs;
    }
    
    /////////////// Jobs ///////////////////////

    /**
     * Returns Number of the status of the jobs from a i client
     *
     * @param string $clientName The name of Client to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function get_Running_Jobs($jobsName , $dataMenor, $dataMaior ) {

        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }

        $this->db->select('
                    job.jobid,
                    job.starttime,
                    job.realendtime,
                    job.level,
                    job.jobbytes,
                    job.jobstatus,
                    job.jobfiles
                     ')

                   ->where('job.name =',$jobsName);

        $query = $this->db->get('job');
        return $query->result_array();

    }//end function
    
    
    /**
     * Returns the Volumes used by i job ID    *
     * @return volumes
     * @author Pedro Oliveira
     */
    function get_volumes_jobid($jobid) {
        $this->db->select('job.jobid, media.volumename ')
        ->distinct()
        ->from('jobmedia')
        ->join('media', 'jobmedia.mediaid = media.mediaid')
        ->join('job', 'job.jobid = jobmedia.jobid')
        
        ->where('job.jobid =',$jobid)
        
         ->order_by('job.jobid', 'asc');
        
        $query=$this->db->get();
        $query=$query->row();
        if ( $query != Null) return  $query->volumename;
        else return "";
    }
    
    /**
     * Returns Sum Job Bytes and Files in date interval 
     * 
     * @param string $jobname the name of the job
     * @param string $dataMenor The value of starting period
     * @param string $dataMaior The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
     
    function Get_JobName_Sum_Files_Bytes_Date_Interval( $jobsName, $dataMenor, $dataMaior) {
         
         
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
             {
                $this->db->where('endtime <= ' , $dataMaior );
                $this->db->where('endtime > ', $dataMenor);
            }

        $this->db->select('job.jobbytes as bytes')
                 ->select('job.jobfiles as files')
                 ->select ('date(job.starttime) as data')
                 ->order_by('data','asc')
                 ->where('job.name', $jobsName);

        $query = $this->db->get('job');
        return $query->result_array();
    }
    
    
     /**
     * Returns the Longest Jobs Time      *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_Longest_jobs_time() {//$this->db->database
        
            $query = $this->db->query("SELECT job.name,  MAX(((job.endtime) - (job.starttime))) as Time
                                        FROM public.job
                                        GROUP BY job.name
                                        ORDER BY Time DESC;");
            return $query->result_array();
    }
   
    /**
     * Returns the list of jobs errors     *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_jobs_errors() {//$this->db->database
        
            $query = $this->db->query("select job.name, count(job.name) as error
                                        FROM job 
                                        WHERE jobstatus <> 'T'
                                        GROUP BY job.name
                                        ORDER BY error DESC;");
            return $query->result_array();
    }
    
    /**
     * Returns Name of Clients
     *
     * @return Name Clientes
     * @author Pedro Oliveira
     */
    function Get_Clients_Name($name="") {
        $this->db->select('name')
                 ->from('client')
                 ->order_by('name','asc');
        if ($name!="") {
            $this->db->like('name', $name['keywords'], 'after');
        }
        
        $query=$this->db->get();
       
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
            {
                $clients[]=$row->name;
            }
            return $clients;
        }else {
            $clients[] ="No Client Match Found";
            return $clients;
        }
    }
    
    
    
    /**
     * Returns Jobs By Date Interval
     *
     * @return Array
     * @author Pedro Oliveira
     */
    function Get_Server_Jobs($dataMenor, $dataMaior) {

         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('starttime >= ', $dataMenor);
         }

        $this->db->select('job.level,job.jobfiles, job.jobbytes,job.jobstatus')
                ->from('job');

        $query = $this->db->get();
      
        return $query->result_array();
         
    }
    
    /**
     * Returns Name of Jobs
     *
     * @return Name Jobs
     * @author Pedro Oliveira
     */
    
     function get_jobs_name() {

                $this->db->select('job.name ')
                ->distinct()
                ->from('job')

                ->order_by('job.name', 'asc');

                $query=$this->db->get();

                 foreach ($query->result() as $row)
                 {
                    $jobs[]=$row->name;
                 }
                 return $jobs;
                
    }
    
    
     /**
     * Returns a result array of Bytes transferred in a period.
     *
     * @param string $jobname The name of job to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function Calc_Bytes_Files_Client($clientName, $dataMenor, $dataMaior) {
         
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }



        $this->db->select('client.name')
                 ->select_sum('job.jobbytes')
                 ->select_sum('job.jobfiles')
                 ->where('client.name', $clientName)
                 ->join('client', 'job.clientid = client.clientid')
                 ->group_by('client.name');

        $query = $this->db->get('job');
        return $query->row();

    }//end function
    
    
     /**
     * Returns Jobs Failed Jobs Data Associated to i Clients
     *
     * @param string $poolname The name of pool to search by
     * @return array
     * @author Pedro Oliveira
     */
    function Get_Client_Failed_Jobs_Data($clientName, $dataMenor, $dataMaior) {

         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }

        $this->db->select(' job.jobid,
                            job.name,
                            job.starttime,
                            job.endtime,
                            job.level,
                            job.jobstatus
                           ')

                 ->from('client')
                 ->join('job', 'client.clientid = job.clientid')
                 ->where('client.name =',$clientName )
                 ->where('job.jobstatus <>','T')

                 ->group_by('job.jobid,
                            job.starttime,
                            job.endtime,
                            job.jobstatus,
                            job.level,
                            job.name
                            ')

                 ->order_by('job.starttime', 'asc');
        $query = $this->db->get();
        return $query->result_array();

    }
     
   
    function Get_Pool_Volumes_Stored_Size() {
        $this->db->select ('pool.name')
                ->select('count (media.name) as media')
                ->join('media', 'pool.poolid = media.poolid')
                ->order_by('pool.name', 'asc')
                ->group_by('pool.name');

        $query = $this->db->get('pool');

        return $query->result_array();
    }
    
  
}