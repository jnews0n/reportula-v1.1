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
                    url: "<?= base_url() ?>index.php/jobs/get_graphbytes/<?=$clientName ?>",
                    dataType: "json",
                    cache: true,
                    data: {
                            data: function() {  return $("#data").val(); },
                            jobname: function() {  return $("#jobname").val(); },
                            datafrom : function() { return $("#from").val(); },
                            datato   : function() { return $("#to").val(); }
                            
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
            majorUnit: 10000000000
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
                    url: "<?= base_url() ?>index.php/jobs/get_graphfiles/<?=$clientName ?>",
                    dataType: "json",
                    type: "POST",
                    cache: true,
                    data: {
                        data     : function() {  return $("#data").val(); },
                        jobname  : function() {  return $("#jobname").val(); },
                        datafrom : function() { return $("#from").val(); },
                        datato   : function() { return $("#to").val(); }
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
            majorUnit: 1000000
        }
    });
   

});


</script>


<div id="jobsdata">
    <div  class="grid_4 clearfix">
        <header class="clearfix">
            <ul class="fr action-buttons">
                <li><a href="<?= base_url() ?>index.php/jobs/jobsdata/<?=$clientName?>/Today" class="current button button-gray no-text" title="Today's Stats"><span class="calendar-view-day"></span></a></li>
                <li><a href="<?= base_url() ?>index.php/jobs/jobsdata/<?=$clientName?>/Week" class="button button-gray no-text" title="This Week's Stats"><span class="calendar-view-week"></span></a></li>
                <li><a href="<?= base_url() ?>index.php/jobs/jobsdata/<?=$clientName?>/Month" class="button button-gray no-text" title="This Month's Stats"><span class="calendar-view-month"></span></a></li>
            </ul>
            <h3><?=$Data ?>'s Stats - <font color="blue"> <?=$clientName ?></font></h3>
            <input type="text" id="data" value="<?=$Data ?>" style="visibility: hidden;"/>
            <input type="text" id="jobname" value="<?=$clientName ?>" style="visibility: hidden;"/>
            
            <input type="text" id="from" value="<?=$from ?>" style="visibility: hidden;"/>
            <input type="text" id="to" value="<?=$to ?>" style="visibility: hidden;"/>
            
            
            
        </header>
        <section>
            <div class="grid_1  omega">
                <div class="widget black ac">
                    <header><h2>Successful Jobs</h2></header>
                    <section><h1><label id="txtOkJobs" ><font color="green"><?=$nTerminatedJobs ?></font></label></h1></section>
                </div>
            </div>
            <div class="grid_1 omega">
                <div class="widget black ac">
                    <header><h2>Jobs With Errors</h2></header>
                    <section><h1><label id="txtFailJobs" ><font color="red"><?=$nFailedJobs ?></font></h1></label></section>
                </div>
            </div>
            <div class="grid_1 omega">   
                <div class="widget black ac">
                    <header><h2>Transfered Files</h2></header>
                    <section><h1><label id="txtTransFiles" ><?=$nTransFiles ?></label></h1></section>
                </div>
            </div>
            <div class="grid_1 omega">
                <div class="widget black ac">
                    <header><h2>Transfered Bytes</h2></header>
                    <section><h1><label id="txtTransBytes" ><?=$nTransBytes ?></label></h1></section>
                </div>
            </div>
        </section>
    </div>
    <!-- Progress Bars -->
    <div class="grid_2">
        <h3>Goals</h3>
        <table class="simple full">
            <tr>
                <td style="width: 30%">Okay Jobs</td>
                <td style="width: 10%" class="ar"><?=$nTerminatedJobs ?>/<?=$nTerminatedJobs+$nFailedJobs ?></td>
                <td style="width: 60%">
                    <div class="progress progress-green">
                       <span style="width: <?=$graphOkJob ?>%">
                            <b><?=round($graphOkJob) ?>%</b>
                       </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Failed Jobs</td>
                <td class="ar"><?=$nFailedJobs ?>/<?=$nTerminatedJobs+$nFailedJobs ?></td>
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
    <!-- End Progress Bars -->
    <!-- Begin Grid  -->
    <div class="grid_6 clearfix">
        <h3>History</h3>
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
        
        

        <div class="clearfix"></div>
        <header class="clearfix">
            <h3>List of the Sucefful Jobs</h3>
        </header>
        <section class="with-table">
            <?=$gridRunningJobs ?>
        </section>
    </div>
    <!-- End Grid -->
</div>


