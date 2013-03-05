<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.core.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.data.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.chart.min.js"></script>

<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
     
   
   var chartbytes = $("#graphbytes").kendoChart({
        title: {
            text: "Transfered Bytes"
        },
        legend: {
            visible: false
        },
        dataSource:{
            transport:{
                read:{
                    async: true,
                    type: "POST",
                    url: "<?= base_url() ?>index.php/main/get_graphbytes",
                    dataType: "json",
                    cache: true,
                    data: {
                        data: function() {
                        return $("#data").val();
                        }
                    },
                }
            }
        },
        series: [{
            type: "line",
            field: "value",
            name: "Bytes"
        }],
        categoryAxis:{
            field: "legend",
            name: "Data",
            labels: {
                
                rotation: -90,
                font: "10px Arial"
            },
        },
        tooltip: {
         visible: true,
         template: "#=  bytesToSize(value,2) #",
        },
        valueAxis: {
            labels: {
                template: "#=  bytesToSize(value,2) #",
            },
            majorUnit: 1000000000000
        }
    });
    
    var chartfiles = $("#graphfiles").kendoChart({
        title: {
            text: "Transfered Files"
        },
        legend: {
            visible: false
        },

        dataSource:{
            transport:{
                read:{
                    async: true,
                    url: "<?= base_url() ?>index.php/main/get_graphfiles",
                    dataType: "json",
                    type: "POST",
                    cache: true,
                    data: {
                        data: function() {
                        return $("#data").val();
                        }
                    },
                }
            }
        },
        series: [{
            type: "line",
            field: "value",
            name: "Files"
        }],
        categoryAxis:{
            field: "legend",
            name: "Data",
            labels: {
                
                rotation: -90,
                font: "10px Arial"
            },
        },
        tooltip: {
         visible: true,
         //template: "#=  bytesToSize(value,2) #",
        },
        valueAxis: {
            labels: {
                
                template: "#= FormatLongNumber(value) #"
           //     template: "#=  bytesToSize(value,2) #",
            },
            majorUnit: 10000000
        }
    });
    
  $("#chart").kendoChart({
                 title: {
                            text: "Pools and volumes status"
                        },
                    legend: {
                        position: "bottom",
                        visible: true
                    },
                    
                    
                    
                    seriesDefaults: {
                        labels: {
                            visible: true,
                            format: "{0}%"
                        }
                    },
                     dataSource:{
                            transport:{
                                read:{
                                    async: true,
                                    url: "<?= base_url() ?>index.php/main/get_graphvolumes",
                                    dataType: "json",
                                    type: "POST"
                                    //cache: true
                                }
                            }
                        },
                        seriesDefaults: {
                            type: "pie",
                            labels: {
                                visible: true,
                                format: "{0} Volumes"
                            }

                            
                            
                        },
                    series: [{
                        categoryField: "name",
                        field: "volbytes"
                    }],
                    tooltip: {
                            visible: true,
                       }
               });

    
    $('#gridRunningJobs').dataTable({
            "bDeferRender"   : true,
            'bFilter'        : true,
            'bLengthChange'  : true,
            'bProcessing'    : false,
            'bPaginate'      : true,
            'sPaginationType': 'full_numbers',
            'iDisplayLength' : 10,
            "bAutoWidth": false,
            'bServerSide': true,
            //"sDom": '<"clear">lrtTpf',
            "sDom": '<"clear">lrptT',
            "oTableTools": { "sSwfPath": "<?= base_url() ?>assets/swf/copy_cvs_xls_pdf.swf" },
            
		    'sAjaxSource' : "<?php echo base_url();?>index.php/main/getJobs",
            'fnServerData': function ( sSource, aoData, fnCallback ) {
                aoData.push( { "name": "data", "value": "<?=$Data ?>" } );
                $.ajax( {
			        "dataType": 'json', 
			        "type": "POST", 
			        "url": sSource, 
			        "data": aoData, 
			        "success": fnCallback
		         })
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                  type = $('.Type', nRow).text();
                  
                  // Incrementais e Full
                  if (type == 'I') {
                    typeLabel = $("I<img BORDER=0 src=<?= base_url() ?>/assets/images/icons/Ilevel.png>");
                   } else { 
                    typeLabel = $("F<img BORDER=0 src=<?= base_url() ?>/assets/images/icons/Flevel.png>");
                  }
                  $('.Type', nRow).html(typeLabel);
                  
                  // Covert Bytes Em Megas 
                  bytes = $('.Bytes', nRow).text();
                  $('.Bytes', nRow).html(bytesToSize(bytes,2));
                  return nRow;
                },

            
               'aoColumns': [
                { "sClass": "Number", },
                { "sClass": "Name" },
                { "sClass": "Volume" },
                { "sClass": "Start" },
                { "sClass": "End" },
                { "sClass": "Type" },
                { "sClass": "Files" },
                { "sClass": "Bytes" },
                { "sClass": "Status" }
                
            ],
            
            
    });
    
            
            $('#gridVolumes').dataTable({
            "bDeferRender": true,
            'bFilter'        : true,
            'bLengthChange'  : true,
            'bProcessing'    : false,
            'bPaginate'      : true,
            'sPaginationType': 'full_numbers',
            'iDisplayLength' : 10,
		    "sDom": '<"clear">lrptT',
            "oTableTools": { "sSwfPath":  "<?= base_url() ?>assets/swf/copy_cvs_xls_pdf.swf" },
            "bAutoWidth": false,
            'bServerSide': true,
		    'sAjaxSource' : "<?php echo base_url();?>index.php/main/getVolumes",
            'fnServerData': function ( sSource, aoData, fnCallback ) {
			         $.ajax( {
				        "dataType": 'json', 
				        "type": "POST", 
				        "url": sSource, 
				        "data": aoData, 
				        "success": fnCallback
			         })
            },
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    status = $('.Status', nRow).text();
                  
                  // Incrementais e Full
                  if (status == 'Full') {
                    statusLabel = $("Full<img BORDER=0 src=<?= base_url() ?>/assets/images/icons/database.png>");
                   } 
                   else if (status == 'Append') { 
                    statusLabel = $("Append<img BORDER=0 src=<?= base_url() ?>/assets/images/icons/database_add.png>");
                  }
                  else if (status == 'Error') { 
                    statusLabel = $("Error<img BORDER=0 src=<?= base_url() ?>/assets/images/icons/database_delete.png>");
                  }
                  else { 
                    statusLabel = $("Not Difined");
                  }
                  $('.Status', nRow).html(statusLabel);
                  
                
                
                
                
                  bytes = $('.Bytes', nRow).text();
                  $('.Bytes', nRow).html(bytesToSize(bytes,2));
                  return nRow;
                },
             'aoColumns': [
                    null,
                    null,
                    null,
                { "sClass": "Bytes" },
                    null,
                    null,
                    
                { "sClass": "Status" }
            ],
        });

});


</script>
<section>
    <div id="dashboard" class="container_8 clearfix">                
        <!-- Main Section -->
        <section class="main-section grid_8">
            <!-- Statistics Section -->
            <div class="main-content">
                <header><h2>Statistics</h2></header>
                <section class="container_6 clearfix">  
                    <div class="grid_4 clearfix">
                        <header class="clearfix">
                            <ul class="fr action-buttons"> <!-- Butoes  -->
                                <!--
                                <li><button onclick="showDashboard('Today')" class="current button button-gray no-text" title="Today's Stats"> <span class="calendar-view-day"></button></li>
                                 <li><button onclick="showDashboard('Week')" class="current button button-gray no-text" title="This Week's Stats"> <span class="calendar-view-week"></button></li>
                                <li><button onclick="showDashboard('Month')" class="current button button-gray no-text" title="This Month's Stats"> <span class="calendar-view-month"></button></li>
                                -->
                                <li><a href="<?= base_url() ?>index.php/main/index/Today" class="current button button-gray no-text" title="Today's Stats"><span class="calendar-view-day"></span></a></li>
                                <li><a href="javascript:showDashboard('Week');" class="button button-gray no-text" title="This Week's Stats"><span class="calendar-view-week"></span></a></li>
                                <li><a href="javascript:showDashboard('Month');" class="button button-gray no-text" title="This Month's Stats"><span class="calendar-view-month"></span></a></li>
                            </ul>
                            <h3><?=$Data ?>'s Stats</h3><input type="text" id="data" value="<?=$Data ?>" style="visibility: hidden;"/>
                        </header>
                        <section>
                            <div class="grid_1  omega">
                                <div class="widget black ac">
                                    <header><h2>Successful Jobs</h2></header>
                                        <section><h2><font color="green"><?=$nTerminatedJobs ?></font></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Jobs With Errors</h2></header>
                                    <section><h2><font color="red"><a id="inline" href="#divFailedJobs"><?=$nFailedJobs ?></a></font></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Transfered Files</h2></header>
                                    <section><h2><?=$nTransFiles ?></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Transfered Bytes</h2></header>
                                    <section><h2><?=$nTransBytes ?></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Clients Number</h2></header>
                                    <section><h2><?=$nClients ?></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Total Files</h2></header>
                                    <section><h2><?=$nFiles ?></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Total Bytes Stored</h2></header>
                                    <section><h2><?=$nStoredSize ?></h2></section>
                                </div>
                            </div>
                            <div class="grid_1 omega">
                                <div class="widget black ac">
                                    <header><h2>Database Size</h2></header>
                                    <section><h2><?=$databaseSize ?></h2></section>
                                </div>
                            </div>
                    </section>
                </div>
                
                <!-- Progress Bars -->
                <div class="grid_2">
                    <h3>Goals</h3>
                    <table class="simple full">
                        <tr>
                            <td style="width: 30%">Okay Jobs</td><td style="width: 10%" class="ar">
                                <?=$nTerminatedJobs ?>/<?=$nTerminatedJobs+$nFailedJobs ?>
                            </td>
                            <td style="width: 60%">
                                <div class="progress progress-green">
                                    <span style="width: <?=$graphOkJob ?>%">
                                        <b><?=round($graphOkJob) ?>%</b>
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Failed Jobs</td><td class="ar">
                                <?=$nFailedJobs ?>/<?=$nTerminatedJobs+$nFailedJobs ?>
                            </td>
                            <td>
                                <div class="progress progress-red">
                                    <span style="width: <?=$graphFailedJob ?>%">
                                        <b><?=round($graphFailedJob) ?>%</b>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    
                </div>
                 

                 <div class="grid_2">
                    
                    <table class="simple full">
                        <tr>
                            <td style="width: 50%">
                           
                            <div style="width: 300px; height: 250px;" id="chart" ></div>
                            
                            </td>
                        <tr>
                    </table>
                </div>
                
                <!-- End Progress Bars -->
                
                 <!-- Draw Graphs -->
                <div class="grid_6 clearfix">
                        <table class="simple full">
                            <tr>
                                <td style="width: 50%">
                                     <div id="graphfiles" style="width: 480px; height: 250px; margin: 0 auto"></div>
                                </td>    
                                <td style="width: 50%">
                                    <div id="graphbytes" style="width: 480px; height: 250px; margin: 0 auto"></div>
                                </td>    
                            </tr>
                        </table>
                    
                </div>
            </section>
        </div>
        <!-- End Statistics Section -->

        <!-- Jobs 24 H  List -->
        <div class="main-content">
            <header><h2><?=$Data ?> Running Jobs</h2></header>
            <section class="with-table"><?//=$gridRunningJobs ?>
                <table id="gridRunningJobs" class="datatable style1 selectable" cellspacing="0" cellpadding="0" border="0" style="width: 100%">
                    <caption>Jobs</caption>
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Volume Name</th>
                            <th>Start Date</th>
                            <th>End Time</th>
                            <th>Type</th>
                            <th>Files</th>
                            <th>Bytes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </section>
        </div>
        <!-- End of Jobs Section -->

        <!-- Volumes  List -->
        <div class="main-content">
            <header><h2>Volumes</h2></header>
            <section class="with-table">
                <table id="gridVolumes" class="datatable style1 selectable" cellspacing="0" cellpadding="0" border="0" style="width: 100%">
                    <caption>Jobs</caption>
                    <thead>
                        <tr>
                            <th>Pool</th>
                            <th>Name</th>
                            <th>Slot</th>
                            <th>Bytes</th>
                            <th>Type</th>
                            <th>Last Writen</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            
            </section>
        </div>
        <!-- End of Jobs Section -->
    </section>
</div>
</section>







