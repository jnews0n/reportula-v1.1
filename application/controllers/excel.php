<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class excel extends MY_Controller
{
    function __construct()
    {
         parent::__construct();
         
    }

    function index()
    {
        
        $dbHistory = $this->load->database('history', TRUE); 
        $servername=$this->db->hostname;
        
        $dbHistory->where('server', $servername);
        $query = $dbHistory->get('hoursstats');
        $this->to_excel($query,$servername);
    }
    
    function to_excel($query, $servername,$fields=FALSE)
    {
     $headers = ''; // just creating the var for field headers to append to below
     $data = ''; // just creating the var for field data to append to below
     
     $obj =& get_instance();
     
     if (!$fields) {
       //   $fields = $query->list_fields();
     }
     
     if ($query->num_rows() == 0) {
          echo '<p>The table appears to have no data.</p>';
     } else {
         /* foreach ($fields as $field) {
             $headers .= $field . "\t";
          }*/
         
          foreach ($query->result() as $row) {
               $line = '';
               foreach($row as $value) {                                            
                    if ((!isset($value)) OR ($value == "")) {
                         $value = "\t";
                    } else {
                         $value = str_replace('"', '""', $value);
                         $value = '"' . $value . '"' . "\t";
                    }
                    $line .= $value;
               }
               $data .= trim($line)."\n";
          }
          
          $data = str_replace("\r","",$data);
           
          header("Content-type: application/x-msdownload");
          header("Content-Disposition: attachment; filename=$servername.xls");
          echo "$headers\n$data";  
     }
    }
}