<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class clients extends MY_Controller
{
    function __construct()
    {
         parent::__construct();
         // Get Clients Name from Database
         $this->data['clients']= $this->database_model->Get_Clients_Name();
    }

    function index( )
    {
        $this->data['main_content'] = 'clients/clientssearch'; // Dashboard
        $this->load->view('template/template', $this->data);
    }
    
    function clientdata($clientName, $data="Date Interval")
     {
        if ($data == "Today" ) {
            $dataMenor= unix_to_human(now()- 86400);
            $dataMaior= unix_to_human(now());
        }elseif ($data == "Week") {

            $dataMenor = unix_to_human( strtotime ( '-1 week' , strtotime ( unix_to_human(now()) ))) ;
            $dataMaior = unix_to_human(now());
        }elseif ($data =="Month"){
            $dataMenor = unix_to_human(strtotime ( '-1 month' , strtotime ( unix_to_human(now() )))) ;
            $dataMaior= unix_to_human(now());
            $this->firephp->log($data);
        }
        else  {
            $dataMenor = $this->input->post('datafrom');
            $dataMaior = $this->input->post('datato');
        } 
        // Gets Cancel and Okay Jobs data
        $jobsData=$this->database_model->Get_Client_Data($clientName,  $dataMenor, $dataMaior );
        
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
         
        // Get Failed Jobs data from Database
        $failedJobsData=$this->database_model->Get_Client_Failed_Jobs_Data($clientName, $dataMenor, $dataMaior);

        // Counts Number Ok Jobs
        $this->data['nTerminatedJobs']=count($jobsData);

        // Counts Number Failed Jobs
        $this->data['nFailedJobs']=count($failedJobsData);
        
        
             // Display for Graphs
        $this->data['graphOkJob'] = ($okJobs <> 0) ?  ($okJobs/($okJobs+$this->data['nFailedJobs']))*100 : 0 ;
        $this->data['graphFailedJob']= ($this->data['nFailedJobs']<> 0) ? ($this->data['nFailedJobs']/($this->data['nFailedJobs']+$okJobs))*100 : 0;   

        // Get Files And Bytes data
        $calcFilesBytes=$this->database_model->Calc_Bytes_Files_Client($clientName, $dataMenor, $dataMaior);
       

        // Get Last Number Transfered Files
        if ($calcFilesBytes!=NULL) { $this->data['nTransFiles']=$rows['jobfiles'] = preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$calcFilesBytes->jobfiles);
        }else { $this->data['nTransFiles']='0'; }

        // Get Last Bytes Tranfered Bytes
        if ($calcFilesBytes!=NULL) { $this->data['nTransBytes']=byte_format($calcFilesBytes->jobbytes);
        } else { $this->data['nTransBytes']= '0'; }

        $this->load->library('table');

        /* Draw Grid Runnig Jobs Values */
        $tmpl = array('table_open' =>
            '<table id="gridClientsData" class="datatable style1 selectable" cellpadding="0" cellspacing="0" border="0" style="width: 100%">');
        $this->table->set_caption('Jobs History');
        $this->table->set_heading('Number', 'Job Name','Start Time','End Time','Volume Name','Type','Files','Bytes', 'Status');
        $this->table->set_template($tmpl);

            // Convert o Job Bytes Em megas e Gigas e Adciona Icon
            foreach ($jobsData as $row)
            {
                $row['jobid'] = '<a href=javascript:showLogs('.$row["jobid"].');> '.$row["jobid"].' </a>';
                $row['jobfiles'] = preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$row['jobfiles']);
                $row['jobbytes']=byte_format($row['jobbytes']);
                $row['name']="<a href='".base_url()."index.php/jobs/index/".$row['name']."/Today'>".$row['name']."</a>" ;
                if ($row['jobstatus'] == "T" ){
                   $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_active_16.png'>");
                }else if ($row['jobstatus'] == "A" ) {
                   $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_stop_16.png'>");
                } else {
                    $row['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_inactive_16.png'>");
                }

                if ($row['level'] == "I" ){
                    $row['level']= ("<img BORDER=0 src='".base_url()."assets/images/incBackup.png'>");
                }else{
                    $row['level']= ("<img BORDER=0 src='".base_url()."assets/images/fullBackup.png'>");
                }

                $a_values[]=$row;
            }

            // For The Failed Jobs
            foreach ($failedJobsData as $rows)
            {
                $rows['jobid'] = '<a href=javascript:showLogs('.$rows["jobid"].');> '.$rows["jobid"].' </a>';
                $rows['name']="<a href='".base_url()."index.php/jobs/index/".$rows['name']."/Today'>".$rows['name']."</a>" ;
                //array_splice($rows, , 0, array("VOLUME"));
                array_splice($rows, 4, 0, array(""));
                array_splice($rows, 6, 0, array("0","0 Bytes"));
                
                if ($rows['jobstatus'] == "A" ) {
                   $rows['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_stop_16.png'>");
                } else {
                    $rows['jobstatus']= ("<img BORDER=0 src='".base_url()."assets/images/ico_inactive_16.png'>");
                }

                if ($rows['level'] == "I" ){
                    $rows['level']= ("<img BORDER=0 src='".base_url()."assets/images/incBackup.png'>");
                }else{
                    $rows['level']= ("<img BORDER=0 src='".base_url()."assets/images/fullBackup.png'>");
                }
                $a_values[]=$rows;
            }


            if ( $jobsData == NULL && $failedJobsData==NULL) { 
                $this->data['gridRunningJobs'] =$this->table->generate("");
            }else{
                $this->data['gridRunningJobs'] = $this->table->generate($a_values);
            }


        $this->table->clear();

        $this->data['main_content'] = 'clients/clients'; 
        $this->data['clientsdata'] = 'clients/clientsdata';

        $this->data['Data'] = $data;
        $this->data['clientName']=$clientName;
        
        $this->data['client']=$this->input->post('client'); // Client to Fix the combobox name
        
        $this->load->view('template/template', $this->data);
       }
    
       
}

?>

