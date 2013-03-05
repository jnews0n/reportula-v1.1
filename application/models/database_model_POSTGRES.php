<?php
class Database_model extends MY_Model {
    function Database_model()
    {
    	parent::__construct();
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
     * Returns the database size in bytes     *
     * @return bytes
     * @author Pedro Oliveira
     */
    function get_database_size() {//$this->db->database
        if ($this->db->dbdriver == 'postgre') {
            $query = $this->db->query("SELECT pg_size_pretty(pg_database_size('". $this->db->database."'))");
            $dbsize=$query->result_array();
            return ($dbsize[0]['pg_size_pretty']);
            
        }else{
            
            // Code For Mysql Not Done Yet
            //$query = $this->db->query("SHOW TABLE STATUS");
            return 0;
        }
    }

    
     /**
     * Returns Jobs By Date Interval
     *
     * @return Array
     * @author Pedro Oliveira
     */
    function Get_Server_Jobs($dataMenor, $dataMaior) {

        //vd::dump($dataMenor,$dataMaior);
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('starttime >= ', $dataMenor);
         }

       $this->db->select('job.jobid,job.name,media.volumename,job.starttime,job.endtime')
                ->select('job.level,job.jobfiles,job.jobbytes,job.jobstatus')
                ->from('job')

                ->join('jobmedia', 'jobmedia.jobid = job.jobid')
                ->join('media', 'media.mediaid = jobmedia.mediaid')

               ->order_by('jobid', 'asc')

               ->group_by('job.jobid,job.name,job.starttime,job.endtime,
                            job.jobfiles,job.jobbytes,job.jobstatus,job.level,
                            jobmedia.mediaid,media.volumename');

        $query = $this->db->get();
      
        return $query->result_array();
         
    }
    
    
    
    /**
     * Returns a result array of Bytes transferred in a period.
     *
     * @param string $jobname The name of job to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array ['jobfiles'],['jobbytes']
     * @author Pedro Oliveira
     */
    function Calc_Files_Bytes_Period($jobname,  $dataMenor, $dataMaior) {

         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }

        $this->db->select_sum('jobbytes');
        $this->db->select_sum('jobfiles');
        
        if   ($jobname!='') $this->db->where('name', $jobname);
        $query = $this->db->get('job');
        
        
        
        $query->row()->jobbytes;
        
        $calc['jobfiles']=$query->row()->jobfiles;
        $calc['jobbytes']=$query->row()->jobbytes;
        
        return $calc;
    }//end function
    
    
    
    /**
     * Returns a result array of Bytes transferred in a period.
     *
     * @param string $jobname The name of job to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function CalculateBytesPeriod($jobname,  $dataMenor, $dataMaior) {

         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }

        $this->db->select_sum('jobbytes');
     
        if   ($jobname!='') $this->db->where('name', $jobname);
        $query = $this->db->get('job');
        return $query->row()->jobbytes;
    }//end function


    /**
     * Returns Number of files transferred in a period..
     *
     * @param string $jobname The name of job to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function CalculateFilesPeriod($jobname,  $dataMenor, $dataMaior) {
         if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }

         $this->db->select_sum('jobfiles');
               
        if   ($jobname!='') $this->db->where('name', $jobname);
       
        $query = $this->db->get('job');
        return $query->row()->jobfiles;
    }//end function
    
    
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
     * Returns Number of Volumes in a pool with some data
     *
     * @return array
     * @author Pedro Oliveira
     */
    function GetDataVolumes() {
        $this->db->select('pool.name')
                 ->select('media.volumename,media.slot,pg_size_pretty(media.volbytes),media.mediatype')
                 ->select('media.volretention, media.lastwritten,media.volstatus')
                 ->where('media.poolid = pool.poolid')
                 ->group_by('media.poolid,media.volumename,media.slot,media.volbytes,media.mediatype,media.volretention,media.lastwritten,media.volstatus,pool.name')
                 ->order_by('media.volumename', 'asc');
        $query = $this->db->get('media,pool');
        return $query->result_array();
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
                    pg_size_pretty(job.jobbytes),
                    job.jobstatus,
                    job.jobfiles
                     ')

                   ->where('job.name =',$jobsName);


        $query = $this->db->get('job');
        return $query->result_array();

    }//end function


    /**
     * Returns Number of the status of the jobs from a i client
     *
     * @param string $clientName The name of Client to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function count_failed_jobs($jobsName, $dataMenor, $dataMaior ) {
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
             {
                $this->db->where('endtime <= ' , $dataMaior );
                $this->db->where('endtime > ', $dataMenor);
            }


                $this->db->select(' count(name) as count' )

                //->where('endtime <=', $EndPeriod)
                 //->where('endtime >', $StartDate);
                ->where('jobstatus <>','T')
                ->where('name =',$jobsName);


        $query = $this->db->get('job');
        $result=$query->result_array();
        $result[0]['count'];

    }//end function

 /**
     * Returns Number of the status of the jobs from a i client
     *
     * @param string $clientName The name of Client to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function count_ok_jobs($jobsName, $dataMenor, $dataMaior ) {

        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
         {
            $this->db->where('endtime <= ' , $dataMaior );
            $this->db->where('endtime > ', $dataMenor);
         }



                $this->db->select('count(name) as count' )

                //->where('endtime <=', $EndPeriod)
                 //->where('endtime >', $StartDate);
                ->where('jobstatus =','T')
                ->where('name =',$jobsName);


        $query = $this->db->get('job');
        $result=$query->result_array();
        $result[0]['count'];

    }//end function
    
    
    
    
   function Calc_Bytes_Files_Jobs($jobsName, $dataMenor, $dataMaior) {
         
        if ( $dataMenor != "0"  ||  $dataMaior != "0" )
             {
                $this->db->where('endtime <= ' , $dataMaior );
                $this->db->where('endtime > ', $dataMenor);
            }

        $this->db->select_sum('job.jobbytes')
                 ->select_sum('job.jobfiles')
                
                 ->where('job.name', $jobsName);

        $query = $this->db->get('job');
        return $query->row();
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
                 
                 //->where('job.name', $jobsName);

        $query = $this->db->get('job');
        return $query->result_array();
    }


    function get_volumes_jobid($jobid) {

                $this->db->select('job.jobid, media.volumename ')
                ->distinct()
                ->from('jobmedia')
                ->join('media', 'jobmedia.mediaid = media.mediaid')
                ->join('job', 'job.jobid = jobmedia.jobid')
                
                ->where('job.jobid =',$jobid)

                 ->order_by('job.jobid', 'asc');

               /*->group_by('job.jobid,job.name,job.starttime,job.endtime,
                            job.jobfiles,job.jobbytes,job.jobstatus,
                            jobmedia.mediaid,media.volumename');
                */

                $query=$this->db->get();
                $query=$query->row();
                if ( $query != Null) return  $query->volumename;
                else return "";
  
    }
    
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


    /////////////// Clients ///////////////////////


    /**
     * Returns Number of the status of the jobs from a i client 
     *
     * @param string $clientName The name of Client to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function get_Ok_Jobs_Client($clientName, $EndPeriod, $StartDate) {
        $this->db->select('client.name, count(job.name) as number')
                 ->select_sum('job.jobbytes')
                 ->select_sum('job.jobfiles')

                 //->where('endtime <=', $EndPeriod)
                 //->where('endtime >', $StartDate);
                 ->where('jobstatus =','T')
                 ->where('client.name', $clientName)
                 ->join('client', 'job.clientid = client.clientid')
                 ->group_by('client.name');

        $query = $this->db->get('job');
        return $query->row();
        
    }//end function

     /**
     * Returns Number of the status of the jobs from a i client
     *
     * @param string $clientName The name of Client to search by
     * @param string $StartDate The value of starting period
     * @param string $EndPeriod The value of ending period
     * @return array
     * @author Pedro Oliveira
     */
    function get_Not_Jobs_Client($clientName, $EndPeriod, $StartDate) {
       $this->db->select('client.name, count(job.name) as number')
                 ->select_sum('job.jobbytes')
                 ->select_sum('job.jobfiles')

                 //->where('endtime <=', $EndPeriod)
                 //->where('endtime >', $StartDate);
                 ->where('jobstatus !=','T')
                 ->where('client.name', $clientName)
                 ->join('client', 'job.clientid = client.clientid')
                 ->group_by('client.name');

        $query = $this->db->get('job');
        return $query->row();

    }//end function


    

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
     * Returns Last 24H Failed Jobs
     *
     * @return Array
     * @author Pedro Oliveira
     */
    function Get_Failed_Jobs_Clients($clientName) {
        $this->db->select('media.volumename,job.jobid,job.name,job.starttime,job.endtime')
                ->select('job.jobfiles,pg_size_pretty(job.jobbytes),job.jobstatus')
                ->select('media.volumename')
                ->from('job')

                ->join('jobmedia', 'jobmedia.jobid = job.jobid')
                ->join('media', 'media.mediaid = jobmedia.mediaid')

                ->where('endtime <= ' , unix_to_human(now()))
                ->where('endtime > ', unix_to_human(now()- 86400))
                ->where('jobstatus !=','T')

               ->order_by('jobid', 'asc')

               ->group_by('job.jobid,job.name,job.starttime,job.endtime,
                            job.jobfiles,job.jobbytes,job.jobstatus,
                            jobmedia.mediaid,media.volumename');

        $query = $this->db->get();
        return $query->result_array();
    }

    /////////////////////////////////////////////////////////////////////



    

   
     /** 
     * Returns Number of Volumes in a pool with some data
     *
     * @param string $poolname The name of pool to search by
     * @return array
     * @author Pedro Oliveira
     */
    function GetJobsData() {
        $this->db->select('jobid,job,name,type,level,clientid,jobstatus,schedtime')
                 ->select('starttime,endtime,jobtdate,volsessionid,volsessiontime')
                 ->select('jobfiles,jobbytes,readbytes,joberrors,jobmissingfiles')
                 ->select('poolid,filesetid,reviewed,comment,jobmissingfiles')
                 ->order_by('jobid', 'asc');
        $query = $this->db->get('job');
        return $query->result();
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
       


          $this->db->select(' job.jobid,
                            job.name,
                            job.starttime,
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
                            job.jobstatus,
                            job.jobfiles,
                            job.jobbytes,
                            
                            job.level,
                           media.volumename,

                            job.name
                            ')
                 ->order_by('job.starttime', 'asc')
                ;
                
                //$this->db->limit(5);
        $query = $this->db->get();
        return $query->result_array();
    }

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
                            job.level,
                            job.jobstatus
                           ')

                 ->from('client')
                 ->join('job', 'client.clientid = job.clientid')
                 ->where('client.name =',$clientName )
                 ->where('job.jobstatus <>','T')

                 ->group_by('job.jobid,
                            job.starttime,
                            job.jobstatus,
                            job.level,
                            job.name
                            ')

                 ->order_by('job.starttime', 'asc');
        $query = $this->db->get();
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



   


    /////////////// Query For GRaphics ////////////////

  

    function Get_Pool_Volumes_Stored_Size() {
        $this->db->select ('pool.name')
                ->select_sum('media.volbytes ')
                ->join('media', 'pool.poolid = media.poolid')
                ->order_by('pool.name', 'asc')
                ->group_by('pool.name');

        $query = $this->db->get('pool');

        return $query->result_array();
    }









}