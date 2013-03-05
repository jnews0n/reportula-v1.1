<? /* Change Log 
  
 * + Update Jquery Version to 1.7
 * + Update JqueryUI Version to 1.8.16
 * + Update Date/Time Picker Plugin
 * + Update Limit Time/Date Picker 
 * + Resolved Problem Division by Zero on Dashboard
 * + Changed the Charts Graph Library to KendoUI Graphs Library
 * + Added KendoUI Charts
 * + Added Volumes/Pool Graph To Dashboard
 * - Recode of the Model
 * - Removed the pg_size_prety from SQL Querys , all numbers converted using javascript or php
 * + Fix the Selection on client Combobox client Selection
 * + Add "EndTime" Column on Client Grid List
 * + Correct Css Images Path to show image Correctly 
 * + Add Charts to Jobs Menu and Description
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
       <?  echo link_tag('assets/css/reset.css'); ?>
        <?  echo link_tag('assets/css/grid.css'); ?>
        <?  echo link_tag('assets/css/style.css'); ?>
        <?  echo link_tag('assets/css/messages.css'); ?>
        <?  echo link_tag('assets/css/forms.css'); ?>
        <?  echo link_tag('assets/css/tables.css'); ?>
        <?  echo link_tag('assets/css/TableTools.css'); ?>
        <?  echo link_tag('assets/css/TableTools_JUI.css'); ?>
        <?  echo link_tag('assets/css/fancybox.css'); ?>
        <?  echo link_tag('assets/css/chosen.css'); ?>
        <?  echo link_tag('assets/css/kendo.kendo.min.css'); ?>
        <?  echo link_tag('assets/css/kendo.common.min.css'); ?>
        
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.ui.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/global.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.datatables.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/TableTools.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/ZeroClipboard.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.fancybox.js"></script>
        
        
        

        <!--[if lt IE 8] <script type="text/javascript" src="dataTables.numericCommaSort.js"></script>
<script type="text/javascript" src="dataTables.numericCommaTypeDetect.js"></script>>
        <?  echo link_tag('assets/css/ie.css'); ?>
        <![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Pedro Miguel Oliveira" />
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta name="keywords" content=""></meta>
        <meta name="description" content="Reportula - Bacula Backups Reporting Tool"></meta>
        <meta http-equiv="imagetoolbar" content="no" />
        <meta name="robots" content="ALL,FOLLOW"/>
        <title>Reportula - Bacula Backups Reporting Tool</title>
    </head>
<body >
    <div id="wrapper">
        <header>
            <div class="clearfix">
                <div class="clear"></div>
                <nav>
                    <ul class="clearfix">
                        <li class="active"><img border="0" src="<?= base_url() ?>assets/images/logoreportula.png"/></li>
                        <li class="active"><a href="<?= base_url() ?>index.php/main">Dashboard</a></li>
                        <li class="active"><a href="<?= base_url() ?>index.php/clients/index/">Clients</a></li>
                        <li class="active"><a href="<?= base_url() ?>index.php/jobs/index/">Jobs</a></li>
                        <li><a href="#" class="arrow-down">Server</a>
                            <ul>
                                <li><a href="<?= base_url() ?>index.php/statistics/">Server Statistics</a></li>
                                <li><a href="<?= base_url() ?>index.php/statistics/jobsstats/">Jobs Statistics</a></li>
                            </ul>
                        </li>
                        <li>
                            <h3>Server - <font color="blue"> <?=$server ?></font></h3>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <?php $this->load->view($main_content); ?>
        
    </div>
       <footer>
        <div id="footer-inner" class="container_8 clearfix">
            Reportula @ 2011 All Rights Reserved - Version 1.1
        </div>
    </footer>
</body>
    <!-- THIS SHOULD COME LAST -->
    <!--[if lt IE 9]>
        <script type="text/javascript" src="js/ie.js"></script>
    <![endif]-->
   
        
<script type="text/javascript" charset="utf-8">
// Setup the ajax indicator And Center Function
   jQuery.fn.center = function () {  
          this.css("position","absolute");  
          this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");  
          this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");  
          return this;  
      }   


 // Ajax activity indicator bound to ajax start/stop document events
$(document).ajaxStart(function(){
      $('#ajaxBusy').center();
      $('#ajaxBusy').show();
}).ajaxStop(function(){
    $('#ajaxBusy').hide();
});



var urlmaster= "<?= base_url() ?>index.php";
var urls; 


function bytesToSize(bytes, precision)
{  
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;
   
    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B';
 
    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
        return (bytes / kilobyte).toFixed(precision) + ' KB';
 
    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return (bytes / megabyte).toFixed(precision) + ' MB';
 
    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
        return (bytes / gigabyte).toFixed(precision) + ' GB';
 
    } else if (bytes >= terabyte) {
        return (bytes / terabyte).toFixed(precision) + ' TB';
 
    } else {
        return bytes + ' B';
    }
}


function FormatLongNumber(value) {
  if(value == 0) {
    return 0;
  }
  else
  {
  
      // hundreds
      if(value <= 999){
        return value;
      }
      // thousands
      else if(value >= 1000 && value <= 999999){
        return (value / 1000) + ' Thousand';
      }
      // millions
      else if(value >= 1000000 && value <= 999999999){
        return (value / 1000000) + ' Milion';
      }
      // billions
      else if(value >= 1000000000 && value <= 999999999999){
        return (value / 1000000000) + ' Bilion';
      }
      else
        return value;
  }
}

$(document).ready(function(){
      $('body').append('<div id="ajaxBusy" style="display:none"><p><img src="<?= base_url() ?>assets/images/loading25x25.gif"></p></div>');   

      $("#clientid").chosen();
      $("#jobsnameid").chosen();
        
        
 // Draws Grids
 
        $('#gridStats').dataTable({
            "bDeferRender": true,
            'bFilter'        : true,
            'bLengthChange'  : true,
            'bProcessing'    : false,
            'bPaginate'      : true,
            'sPaginationType': 'full_numbers',
            'iDisplayLength' : 100,
            'bServerSide'    : false,
            "bAutoWidth": false,
            "sDom": '<"clear">lrptT',
		"oTableTools": {
			"sSwfPath":  "<?= base_url() ?>assets/swf/copy_cvs_xls_pdf.swf"
                }
        });
 
 
        $('#gridClientsData').dataTable({
            "bDeferRender": true,
            'bFilter'        : true,
            'bLengthChange'  : true,
            'bProcessing'    : false,
            'bPaginate'      : true,
            'sPaginationType': 'full_numbers',
            'iDisplayLength' : 10,
            'bServerSide'    : false,
            "bAutoWidth": false,
            "sDom": '<"clear">lrptT',
		"oTableTools": {
			"sSwfPath":  "<?= base_url() ?>assets/swf/copy_cvs_xls_pdf.swf"
                }
        });
    
});

/******************************************
 * Add DateTime Picker to textbox Data    *
 ******************************************/

$(function() {
	    var dates = $( "#from, #to" ).datetimepicker({
            timeFormat: 'h:mm:ss TT',
            ampm: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 2,
                        maxDate: "+1d",
            dateFormat: 'yy-mm-dd',
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});


/**********************
 * Get DashBoard Data    *
 **********************/
function showDashboard (data){
     $('#dashboard').empty();
    
    
    $.ajax({
        global: false,
        type: "post",
        url: urlmaster+"/main/index/"+data,
        async: false,
        dataType: "html",
            data: {
                    datadate : data
                  },
            error: function(request,error){
                    alert("ERROR !");
             },
             success: function(response) {
                    $('#dashboard').append(response);
                    $('#gridRunningJobs').dataTable().fnDraw();
                    $('#gridVolumes').dataTable().fnDraw();
                    
             }
        
    });
}

/**********************
 * Get Logs Data    *
 **********************/
function showLogs (data){
    
   $.fancybox({
       'padding' : 10,
        'href' : "<?= base_url() ?>index.php/jobs/logs/"+data,
        'autoDimensions': true,
        'centerOnScroll' :true,
        'transitionIn' : 'elastic',
        'transitionOut' : 'elastic'
    }); 
   $.fancybox.resize
}



/**********************
 * Get Jobs Data    *
 **********************/
function showJobs (data){

    var jobsid=$("#jobsnameid option:selected").text();
    var datafrom = $("#from").val();
    var datato = $("#to").val();
   
    $.ajax({
        url: "<?= base_url() ?>index.php/jobs/jobsdata/"+jobsid+"/",
        global: true,
        cache: true,
        type: "post",
        async: true,    
        dataType: "html",
            data: {
                    datafrom    : $("#from").val(),
                    datato      : $("#to").val(),
                    datadate : data
                  },
            error: function(request,error){
                   alert("Please choose date interval !");
                   
             },
             success: function(response) {
                    $('body').empty();
                    $('body').append(response);
                    $("#from").val(datafrom) ;
                    $("#to").val(datato);
                    
            }
            
    });
    

}

/**********************
 * Get Client Data    *
 **********************/
function showClient (data){
    var clientid=$("#clientid option:selected").text();
  
    var datafrom = $("#from").val();
    var datato = $("#to").val();
   
    $.ajax({
        cache :true,
        global: true,
        type: "post",
        url: "<?= base_url() ?>index.php/clients/clientdata/"+clientid+"/",
        async: true,
        dataType: "html",
            data: {
                    client   : $("clientid option:selected").val(),
                    datafrom : $("#from").val(),
                    datato   : $("#to").val(),
                    datadate : data,
                  },
            error: function(request,error){
                    alert("Please choose date interval !");
             },
             success: function(response) {
                    $('body').empty();
                    $('body').append(response); //$('body').append(response);
                    $("#from").val(datafrom) ;
                    $("#to").val(datato);
            }
        
    });
}







</script>
   
</body>
</html>