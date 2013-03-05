<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class statistics extends MY_Controller
{
    function __construct()
    {
         parent::__construct();
         
    }

    function index()
    {
        
        $dbHistory = $this->load->database('history', TRUE); 
        $servername=$this->db->name;
        
        $dbHistory->where('server', $servername);
        $query = $dbHistory->get('hoursstats');
        
        
        $query = $query->result_array();
        
         // Convert o Job Bytes Em megas e Gigas e Adciona Icon
        
        
        foreach ($query as $row)
        {
            
            // Convert bytes to Gb Mb Kb
            $row['bytes']=byte_format(($row['bytes']));
            $row['hourbytes']=byte_format($row['hourbytes']);
            unset( $row['server'] );
            $a_values[]=$row;
            
        }
        
        
        $this->load->library('table');

        /* Draw Grid Runnig Jobs Values */
        $tmpl = array('table_open' =>
            '<table id="gridStats" class="datatable style1 selectable" cellpadding="0" cellspacing="0" border="0" style="width: 100%">');
        $this->table->set_caption('Transfered Hour Statistics');
        $this->table->set_heading('Id', 'Colection Date',' Started Time','Ended Time','Bytes','Backup Hours', 'Bytes/Hour', 'Full Time');
        $this->table->set_template($tmpl);
        $this->data['gridStats'] = $this->table->generate($a_values);
        
        $this->data['main_content'] = 'statistics/statistics'; // Dashboard
        $this->load->view('template/template', $this->data);
        
    }
    
    
    public function get_statsbytes() {
		
        $dbHistory = $this->load->database('history', TRUE); 
        $servername=$this->db->name;
                   
        $query = $dbHistory->query("SELECT sum(daystats.bytes) as bytes,
                        date_trunc('month',daystats.date) as date1
                        FROM daystats WHERE daystats.server = '". $servername."' 
                        group by date1 order by date1 asc;");                  
        $query =$query->result_array();
        $i=0;
        
        //Get Values For Drawing Bar Graphs  
        foreach ($query as $row)
        {
            $graf[$i]['value'] = $row['bytes'];
            $graf[$i]['legend'] = date('M-Y', strtotime($row['date1']));
            $i++;
        }   
	    echo json_encode ($graf);
        
  	}
    
    
    /* Function to Draw Dashboar Files Tranfer */
    
    public function get_statsfiles() {
		
        $dbHistory = $this->load->database('history', TRUE); 
        $servername=$this->db->name;
        
        $query = $dbHistory->query("SELECT sum(daystats.files) as files,
                        date_trunc('month',daystats.date) as date1
                        FROM daystats WHERE daystats.server = '". $servername."' 
                        group by date1 order by date1 asc;");                  
        
        
        $query = $query->result_array();
        
        
        $i=0;   
        //Get Values For Drawing Bar Graphs  
        foreach ($query  as $row)
        {
            $graf[$i]['value'] = $row['files'];
            $graf[$i]['legend'] = date('M-Y', strtotime($row['date1']));
            $i++;
        }   
        
        echo json_encode ($graf);
        
	
	}
       
    /* Function to Draw Dashboar Files Tranfer */
    
    public function get_hourstats() {
		
        $dbHistory = $this->load->database('history', TRUE); 
        $servername=$this->db->name;
        
        //$dbHistory->where('server', $servername);
        //$query = $dbHistory->get('hoursstats');
        
        $query = $dbHistory->query("SELECT sum(hourbytes) as hourbytes,
                        date_trunc('month',hoursstats.date) as date1
                        FROM hoursstats WHERE hoursstats.server = '". $servername."' 
                        group by date1 order by date1 asc;");  
        $query = $query->result_array();
        
          $i=0;      
        //Get Values For Drawing Bar Graphs  
        foreach ($query  as $row)
        {
             $graf[$i]['value'] = $row['hourbytes'];
             $graf[$i]['legend'] = date('M-Y', strtotime($row['date1']));
             $i++;
        }   
        
        echo json_encode ($graf);
       
	
	}
        
   function jobsstats()
   {
       
       $tmpl = array('table_open' =>
            '<table id="gridStats" class="datatable style1 selectable" cellpadding="0" cellspacing="0" border="0" style="width: 100%">');
        $this->table->set_caption('Average Running Time Job Statistics');
        $this->table->set_heading('Job Name', 'Time');
        $this->table->set_template($tmpl);
        $this->data['gridStats'] = $this->table->generate($this->database_model->get_Longest_jobs_time());
        
        
        
        
         $tmpl = array('table_open' =>
            '<table id="gridJobsErrors" class="datatable style1 selectable" cellpadding="0" cellspacing="0" border="0" style="width: 100%">');
        $this->table->set_caption('Average Error Jobs Statistics');
        $this->table->set_heading('Job Name', 'Errors');
        $this->table->set_template($tmpl);
        $this->data['gridJobsErrors'] = $this->table->generate($this->database_model->get_jobs_errors());
        
        
        
        $this->data['main_content'] = 'statistics/jobsstats'; // Dashboard
        $this->load->view('template/template', $this->data);
       
       
    
   }
    
    
    
}

?>

