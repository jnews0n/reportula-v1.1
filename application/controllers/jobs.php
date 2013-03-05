<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class jobs extends MY_Controller
{
    function __construct()
    {
         parent::__construct();
         
        // Get Jobs Name from Database
         $this->data['jobsname']=$this->database_model->get_jobs_name();
 
    }

    function index()
    {
        $this->data['main_content'] = 'jobs/jobssearch'; // Dashboard
        $this->load->view('template/template', $this->data);
    }
    
    function logs($jobid)
    {
        $text="";
        $logs=$this->database_model->get_job_logs($jobid); // Get Logs From Job
        
        $this->data['logs'] = implode($logs);
        $this->data['jobid'] = $jobid;
        
        $this->load->view('jobs/jobslogs', $this->data);
    }
    
    
     function jobsdata($jobName, $data="")
     {
         $this->data['from']="";
         $this->data['to']="";
         
     //if ($data == "" ) { $data = "Today"; } 
     
       if ($data == "Today" ) {
            $dataMenor= unix_to_human(now()- 86400);
            $dataMaior= unix_to_human(now());

        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());

        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior= unix_to_human(now());
        } else  {
           $this->data['from']= $dataMenor = $this->input->post('datafrom');
           $this->data['to']= $dataMaior = $this->input->post('datato');
        } 
        
        // Get Jobs Data
        $jobsData=$this->database_model->get_Running_Jobs($jobName, $dataMenor, $dataMaior);
       
     
        $okJobs=0;
        $errorJobs=0;
        $nTransBytes=0;
        $nTransFiles=0;
        
        // Get Numbers 
        foreach ($jobsData as $row)
        {
            $nTransFiles += $row['jobfiles'];;
            $nTransBytes += $row['jobbytes'];
            if ($row['jobstatus'] == "T" ){
                 $okJobs++;
            }else {  
                $errorJobs++;
            }
        }
        // Display Transfered Files & Bytes 
       $this->data['nTransFiles']=preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$nTransFiles); 
       $this->data['nTransBytes']=byte_format($nTransBytes);
        
        
        // Diplayy Terminated Jobs
        $this->data['nTerminatedJobs']=$okJobs;
        $this->data['nFailedJobs']=$errorJobs;
        
        // Display for Graphs
        $this->data['graphOkJob'] = ($okJobs <> 0) ?  ($okJobs/($okJobs+$errorJobs))*100 : 0 ;
        $this->data['graphFailedJob']= ($errorJobs<> 0) ? ($errorJobs/($errorJobs+$okJobs))*100 : 0;
      
        /* Draw Grid Runnig Jobs Values */
        $tmpl = array('table_open' =>
            '<table id="gridClientsData" class="datatable style1 selectable" cellpadding="0" cellspacing="0" border="0" style="width: 100%">');
        $this->table->set_caption('Jobs History');
        $this->table->set_heading('Number', 'Start Time','End Time','Volumes Used','Type','Bytes', 'Status');
        $this->table->set_template($tmpl);
        
        // Convert o Job Bytes Em megas e Gigas e Adciona Icon
        foreach ($jobsData as $row)
        {
            array_splice($row, 3, 0, array( $this->database_model->get_volumes_jobid($row['jobid'])) );
            $row['jobbytes'] = byte_format($row['jobbytes']);
            unset( $row['jobfiles'] );
            $row['jobid'] = '<a href=javascript:showLogs('.$row["jobid"].');> '.$row["jobid"].' </a>';
            if ($row['jobstatus'] == "T" ){
               $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_active_16.png'>");
            }else if ($row['jobstatus'] == "A" ) {
               $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_stop_16.png'>");
            } else {
                $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_inactive_16.png'>");
            }

            if ($row['level'] == "I" ){
                $row['level']= ("I<img BORDER=0 src='".base_url()."assets/images/incBackup.png'>");
            }else{
                $row['level']= ("F<img BORDER=0 src='".base_url()."assets/images/fullBackup.png'>");
            }
            $a_values[]=$row;
        }
             
         if ( $jobsData == NULL ) {
                $this->data['gridRunningJobs'] =$this->table->generate("");
            }else{
                $this->data['gridRunningJobs'] = $this->table->generate($a_values);
            }
        $this->table->clear();

        $this->data['Data'] = $data;
        $this->data['clientName']=$jobName;
        
        $this->data['main_content'] = 'jobs/jobs'; // Dashboard
        $this->data['clientsdata'] = 'jobs/jobsdata';

        
        $this->load->view('template/template', $this->data);
     }
     
     
     
     //* Function to Draw Dashboar bytes Tranfer */
    
     public function get_graphbytes($data="") {
        $this->data['from']="";
         $this->data['to']="";
        
        
	   $data=$this->input->post('data');
       $jobname=$this->input->post('jobname');
        
         
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
        } else  {
            $this->data['from']= $dataMenor = $this->input->post('datafrom');
           $this->data['to']= $dataMaior = $this->input->post('datato');
        } 

        $graphs=$this->database_model->Get_JobName_Sum_Files_Bytes_Date_Interval($this->uri->segment(3),$dataMenor, $dataMaior);
        
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


    public function get_graphfiles($data="") {
        $this->data['from']="";
         $this->data['to']="";
		
        
        $data=$this->input->post('data');
        $jobname=$this->input->post('jobname');
        
         
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
        }else  {
           $this->data['from']= $dataMenor = $this->input->post('datafrom');
           $this->data['to']= $dataMaior = $this->input->post('datato');
        } 

        $graphs=$this->database_model->Get_JobName_Sum_Files_Bytes_Date_Interval($this->uri->segment(3),$dataMenor, $dataMaior);
        
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


}

?>

