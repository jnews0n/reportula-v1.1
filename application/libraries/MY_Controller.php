<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Code here is run before ALL controllers
class MY_Controller extends CI_Controller
{
	var $module;
	var $controller;
	var $method;
	
	function  __construct() {
            parent::__construct();
            
            
            $this->data['Data']="";
            $this->config->load('profiler', false, true);
            if ($this->config->config['enable_profiler']) {
              //  $this->output->enable_profiler(true);
            }
            $this->data['server'] = $this->db->name;
           
             
            // load the Datatables library
            $this->load->library('datatables');       
           
            
            $this->load->library('table');
            
            $this->load->helper('file');
            
            $this->load->library('vd');
            
        
        }

     


	
}