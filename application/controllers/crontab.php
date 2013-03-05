<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class crontab extends CI_Controller
{
    function __construct()
    {
         parent::__construct();
    }

    function index()
    {
         
        //* Load Database Configurations
        
        $dbDefault = $this->load->database('default', TRUE);
        $dbHistory = $this->load->database('history', TRUE); 
        
        
        // Read Data From Server
        
        // Get Database Size
       $query = $dbDefault->query("SELECT pg_database_size('". $dbDefault->database."')");
        $dbsize=$query->result_array();
        $dbsize=$dbsize[0]['pg_database_size'];
        
        // Get Server Hostname
        $servername=$dbDefault->hostname;
        
        // Get Number of Clients 
        $clientsNumber=$dbDefault->count_all('client');
        
        // Get Number of Files Transfered
        $filesNumber=$dbDefault->count_all('filename');
        
        
        // Get Storage Bytes
        $dbDefault->select_sum('volbytes');
        $bytesStorage = $dbDefault->get('media');
        $bytesStorage = $bytesStorage->row()->volbytes;
        
        
        //* Query For Hour Starts
        $dataInicio = date('Y-m-d', strtotime("-1 days")).(' 18:29');
        $dataFim = date('Y-m-d').(' 18:29');
      
        
        $dbDefault->where('starttime >= ' , $dataInicio );
        $dbDefault->where('endtime <= ', $dataFim);
        $dbDefault->select_sum('job.jobbytes', 'bytes')
                  ->select('min (job.starttime)', 'startime')
                  ->select('max (job.endtime)', 'endtime')
                  ->select('(max(job.starttime) - min(job.starttime)) as timediff')
                  ->select("date_part('hour',  (max(job.starttime) - min(job.starttime))) as hours")
                  ->select("(sum(job.jobbytes)/date_part('hour',  (max(job.starttime) - min(job.starttime)))) as byteshour");
        $query = $dbDefault->get('job');
        
        $hours = $query->row();
        
        //vd::dump($query);
        
         // Close DataBase
        $dbDefault->close();
         
        // Insert on History Database
        $data = array(
            'date' => date('Y-m-d') ,
            'server' => $servername ,
            'bytes' => $bytesStorage,
            'files' => $filesNumber,
            'clients' => $clientsNumber,
            'databasesize' => $dbsize
        );
  
                  
        $hourstats = array(
                'date'      => date('Y-m-d') ,
                'server'    => $servername ,
                'bytes'     => $hours->bytes,
                'starttime' => $hours->min,
                'endtime'   => $hours->max,
                'timediff'  => $hours->timediff,
                'hoursdiff' => $hours->hours,
                'hourbytes' => $hours->byteshour
        );
        
        // Search if already there is the day
        $dbHistory->where('date', date('Y-m-d'));
        $dbHistory->where('server', $servername);
        $query = $dbHistory->get('daystats');
        
        if ($query->num_rows() <= 0 ) { 
            $query=$dbHistory->insert('daystats', $data); 
            $query=$dbHistory->insert('hoursstats',  $hourstats );
             //$str = $dbHistory->insert_string('hoursstats', $hourstats); 
       }
        $dbHistory->close();
    }
    
}

?>

