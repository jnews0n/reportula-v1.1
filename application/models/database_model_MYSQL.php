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
        $query = $this->db->query("SELECT table_schema AS 'database', sum( data_length + index_length) AS 'dbsize' 
                                    FROM information_schema.TABLES
                                    WHERE table_schema = '". $this->db->database."' 
                                   GROUP BY table_schema");
        $dbsize=$query->result_array();
        return ($dbsize[0]['dbsize']);
    }
 
     /**
     * Returns Number of Clients
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetNumberClients() {
        return $this->db->count_all('Client');
    }

    
    /**
     * Returns Number of Files
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetNumberFiles() {
        return $this->db->count_all('Filename');
    }

    /**
     * Returns Stored Size
     *
     * @return Number
     * @author Pedro Oliveira
     */
    function GetStoredSize() {
        $this->db->select_sum('volbytes');
        $query = $this->db->get('Media');
        
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

        $this->db->select_sum('Job.jobbytes', 'bytes')
                 ->select_sum('Job.jobfiles', 'files')
                 
                 ->select ('date(Job.starttime) as data')
                 ->order_by('data','asc')
                 ->group_by('data');
                 
                 //->where('Job.name', $jobsName);

        $query = $this->db->get('Job');
        return $query->result_array();
    }
   

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
       
          $this->db->select(' Job.jobid,
                            Job.name,
                            Job.starttime,
                            Job.endtime,
                            Media.volumename,
                            Job.level,
                            Job.jobfiles,
                            Job.jobbytes,
                            Job.jobstatus
                           ')

                 ->from('JobMedia')
                 ->join('Job', 'JobMedia.jobid = Job.jobid')
                 ->join('Media', 'Media.mediaid = JobMedia.mediaid')
                 ->join('Client', 'Job.clientid = Client.clientid')


                 ->where('Client.name =',$clientName )

                ->group_by('Job.jobid,
                            Job.starttime,
                            Job.endtime,
                            Job.jobstatus,
                            Job.jobfiles,
                            Job.jobbytes,
                            Job.level,
                            Media.volumename,
                            Job.name
                            ')
                 ->order_by('Job.starttime', 'dsc');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Returns the Jobs Logs 
     * @jobid integer      *
     * @return array
     * @author Pedro Oliveira
     */
    function get_job_logs($jobid) {
        
       $this->db->select('Log.logtext')
                ->from('Log')
                ->where('Log.jobid = ' , $jobid );
                
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
                    Job.jobid,
                    Job.starttime,
                    Job.realendtime,
                    Job.level,
                    pretty_size(Job.jobbytes),
                    Job.jobstatus,
                    Job.jobfiles

                     ')

                   ->where('Job.name =',$jobsName);


        $query = $this->db->get('Job');
        return $query->result_array();

    }//end function

    /**
     * Returns the Volumes used by i job ID    *
     * @return volumes
     * @author Pedro Oliveira
     */
    function get_volumes_jobid($jobid) {

                $this->db->select('Job.jobid, media.volumename ')
                ->distinct()
                ->from('JobMedia')
                ->join('Media', 'JobMedia.mediaid = Media.mediaid')
                ->join('Job', 'Job.jobid = JobMedia.jobid')
                
                ->where('Job.jobid =',$jobid)

                 ->order_by('Job.jobid', 'asc');

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

        $this->db->select('Job.jobbytes as bytes')
                 ->select('Job.jobfiles as files')
                 ->select ('Date(Job.starttime) as data')
                 ->order_by('data','asc')
                 ->where('Job.name', $jobsName);

        $query = $this->db->get('Job');
        return $query->result_array();
    }
    
    /**
     * Returns the Longest Jobs Time      *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_Longest_jobs_time() {//$this->db->database
        
            $query = $this->db->query("SELECT Job.name,  MAX(((Job.endtime) - (Job.starttime))) as Time
                                        FROM Job
                                        GROUP BY Job.name
                                        ORDER BY Time DESC;");
            return $query->result_array();
    }
    
    /**
     * Returns the list of jobs errors     *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_jobs_errors() {//$this->db->database
        
            $query = $this->db->query("select Job.name, count(Job.name) as error
                                        FROM Job 
                                        WHERE jobstatus <> 'T'
                                        GROUP BY Job.name
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
                 ->from('Client')
                 ->order_by('name','asc');
        if ($name!="") {
            $this->db->like('name', $name['keywords'], 'after');
        }
        
        $query=$this->db->get();
       // vd::dump($query);
       // break;
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

        $this->db->select('Job.level,Job.jobfiles, Job.jobbytes,Job.jobstatus')
                ->from('Job');
                


        $query = $this->db->get();
      
        return $query->result_array();
         
    }
    
    
    function get_jobs_name() {

                $this->db->select('Job.name ')
                ->distinct()
                ->from('Job')

                ->order_by('Job.name', 'asc');

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

        $this->db->select('Client.name')
                 ->select_sum('Job.jobbytes')
                 ->select_sum('Job.jobfiles')

                 ->where('Client.name', $clientName)
                 ->join('Client', 'Job.clientid = Client.clientid')
                 ->group_by('Client.name');

        $query = $this->db->get('Job');
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

        $this->db->select(' Job.jobid,
                            Job.name,
                            Job.starttime,
                            job.endtime,
                            Job.level,
                            Job.jobstatus
                           ')

                 ->from('Client')
                 ->join('Job', 'Client.clientid = Job.clientid')
                 ->where('Client.name =',$clientName )
                 ->where('Job.jobstatus <>','T')

                 ->group_by('Job.jobid,
                            Job.starttime,
                            job.endtime,
                            Job.jobstatus,
                            Job.level,
                            Job.name
                            ')

                 ->order_by('Job.starttime', 'asc');
        $query = $this->db->get();
        return $query->result_array();

    }

 
      function Get_Pool_Volumes_Stored_Size() {
        $this->db->select ('Pool.name')
                ->select_sum('Media.volbytes ')
                ->join('Media', 'Pool.poolid = Media.poolid')
                ->order_by('Pool.name', 'asc')
                ->group_by('Pool.name');

        $query = $this->db->get('Pool');

        return $query->result_array();
    }

}
