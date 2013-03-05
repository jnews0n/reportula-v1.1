<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends MY_Controller
{
    function __construct()
    {
         parent::__construct();
    }

    function index($data="")
    {
        $this->db->close();
        
        if ($data == "" ) { $data = "Today"; }
        
        $dataMenor ="0";
        $dataMaior = "0";
        
        if ($data == "Today" ) {
            
            $dataMenor=date('Y-m-d', strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ));
            $dataMaior=date('Y-m-d');
         
        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());

        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior= unix_to_human(now());
        }
       
        // Get Size of the Database
        $this->data['databaseSize']= byte_format($this->database_model->get_database_size());
        
        // Get Terminated Jobs 
        $tJobs=$this->database_model->Get_Server_Jobs($dataMenor, $dataMaior);
      
        // Indicates Failed and Okay Jobs
        $okJobs=0;
        $errorJobs=0;
        $nTransBytes=0;
        $nTransFiles=0;
        
        // Get Numbers 
        foreach ($tJobs as $row)
        {
            $nTransFiles += $row['jobfiles'];;
            $nTransBytes += $row['jobbytes'];
            if ($row['jobstatus'] == "T" ){
                 $okJobs++;
            }else {  
                $errorJobs++;
            }
        }
      
        // Get Numer of Clients
        $this->data['nClients']=$this->database_model->GetNumberClients();
        
        // Get number of Files
        $this->data['nFiles']=$this->database_model->GetNumberFiles();
        
        // Get Number of Stored Size
        $this->data['nStoredSize']= byte_format($this->database_model->GetStoredSize());
        
                
        // Display Transfered Files & Bytes 
       $this->data['nTransFiles']=preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$nTransFiles); 
       $this->data['nTransBytes']=byte_format($nTransBytes);
        
        
        // Diplayy Terminated Jobs
        $this->data['nTerminatedJobs']=$okJobs;
        $this->data['nFailedJobs']=$errorJobs;
        
        // Display for Graphs
        $this->data['graphOkJob'] = ($okJobs <> 0) ?  ($okJobs/($okJobs+$errorJobs))*100 : 0 ;
        $this->data['graphFailedJob']= ($errorJobs<> 0) ? ($errorJobs/($errorJobs+$okJobs))*100 : 0;
     
       $this->data['Data'] = $data;
        
        if ($data == "Week" || $data == "Month") { 
            $this->load->view('dashboard/dashboard',$this->data);
        }else{
           $this->data['main_content'] = 'dashboard/dashboard';
           $this->load->view('template/template', $this->data); 
            
        }
    }
    
    
    //* Function to Draw Dashboar bytes Tranfer */
    
     public function get_graphbytes($data="") {
		
        
        $data=$this->input->post('data');
        
        if ($data == "" ) { $data = "Today"; }
        
        $dataMenor ="0";
        $dataMaior = "0";
        
        if ($data == "Today" ) {
            
            $dataMenor=  date('Y-m-d', strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ));
            $dataMaior=date('Y-m-d');
         
        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());

        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior= unix_to_human(now());
        }

        $graphs=$this->database_model->Sum_Files_Bytes_Date_Interval($dataMenor, $dataMaior);
        
        $i=0;
        //Get Values For Drawing Bar Graphs  
        foreach ($graphs as $row)
        {
            $graf[$i]['value'] = $row['bytes'];
            $graf[$i]['legend'] = $row['data'];
            $i++;
        }   
	    echo json_encode ($graf);
	}
    
    
    /* Function to Draw Dashboar Files Tranfer */
    
    public function get_graphfiles($data="") {
		
        $data=$this->input->post('data');
        
        if ($data == "" ) { $data = "Today"; }
        
        $dataMenor ="0";
        $dataMaior = "0";
        
        if ($data == "Today" ) {
            
            $dataMenor=  date('Y-m-d', strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ));
            $dataMaior=date('Y-m-d');
         
        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());

        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior= unix_to_human(now());
        }

        $graphs=$this->database_model->Sum_Files_Bytes_Date_Interval( $dataMenor, $dataMaior);
        $i=0;
        //Get Values For Drawing Bar Graphs  
        foreach ($graphs as $row)
        {
            $graf[$i]['value'] = $row['files'];
            $graf[$i]['legend'] = $row['data'];
            $i++;
        }   
	    echo json_encode ($graf);
     
	}
    
    
     /* Function to Draw Dashboar Files Tranfer */
    
    public function get_graphvolumes() {
		$graphs=$this->database_model->Get_Pool_Volumes_Stored_Size();
        $i=0;
        //Get Values For Drawing Bar Graphs  
        foreach ($graphs as $row)
        {
            $graf[$i]['volbytes'] = intval($row['media']);
            $graf[$i]['name'] = $row['name'];
            $i++;
        }   
	    echo json_encode ($graf);
     
	}
    
    
       
    
    /* Ajax Fuction to get Volumes Data */
    function getVolumes()
    {  //media.volretention ,
        $this->datatables->select('name, volumename,slot,volbytes,mediatype,
                                    lastwritten,volstatus 
                                    ')
        ->where ( 'media.poolid = pool.poolid')
        ->from('media,pool');
      // $this->datatables->edit_column('media.volretention', unix_to_human(intval('$1')),  'media.volretention');
        echo $this->datatables->generate();
    }
    
    
    
    
    /* Ajax Fuction to get Volumes Data */
    function getJobs()
    {
    $data=$this->input->post('data');
        
        
      
       if ($data == "" ) { $data = "Today"; }
        
        $dataMenor ="0";
        $dataMaior = "0";
        
        if ($data == "Today" ) {
            
            $dataMenor=  date('Y-m-d', strtotime ( '-1 day' , strtotime ( date('Y-m-d') ) ));
            $dataMaior=date('Y-m-d');
         
        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());

        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior = unix_to_human(now());
        }
        
        $this->datatables->select('job.jobid,job.name,media.volumename,job.starttime,job.endtime,
                                   job.level,job.jobfiles,job.jobbytes,job.jobstatus                         
                                    ')
                         ->where('endtime <= ' , $dataMaior )
                         ->where('starttime >= ', $dataMenor)
                                    
                         ->group_by('job.jobid,job.name,job.starttime,job.endtime,
                            job.jobfiles,job.jobbytes,job.jobstatus,job.level,
                             jobmedia.mediaid,media.volumename')
                         ->join('jobmedia', 'jobmedia.jobid = job.jobid')
                         ->join('media', 'media.mediaid = jobmedia.mediaid')
                         ->from('job');
                       //<button onclick="showClient('data')" class="button button-gray"><span class="accept"></span>OK</button>
        $this->datatables->edit_column('job.jobid', '<a href="javascript:showLogs($1);"> $1</a>', 'job.jobid');
        //$this->datatables->edit_column('job.jobid', '<a href="'.base_url().'index.php/jobs/logs/$1"> $1</a>', 'job.jobid');
       // $this->datatables->edit_column('job.level', '<img BORDER=0 src='.base_url().'/assets/images/icons/$1level.png>', 'job.level');               
        $this->datatables->edit_column('job.jobstatus', '<img BORDER=0 src='.base_url().'/assets/images/icons/$1.png>', 'job.jobstatus');                         
        echo $this->datatables->generate();
    }
        
    
    
}

?>

