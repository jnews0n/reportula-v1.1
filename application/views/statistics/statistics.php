<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.core.min.js"></script> 
<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.data.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/kendo.chart.min.js"></script>
<script type="text/javascript" charset="utf-8">
            
$(document).ready(function(){              
              
var chartbytes = $("#bytesStats").kendoChart({
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
                    cache: true,
                    type: "POST",
                    url: "<?= base_url() ?>index.php/statistics/get_statsbytes/",
                    dataType: "json",
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
            majorUnit: 1000000000000000
        }
    });   
    
    
    //****** TRANFERES FILES **********
    
    var chartfiles = $("#filesStats").kendoChart({
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
                    cache: true,
                    type: "POST",
                    url: "<?= base_url() ?>index.php/statistics/get_statsfiles/",
                    dataType: "json",
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
         template: "#= FormatLongNumber(value) #",
        },
        valueAxis: {
            labels: {
                template: "#= FormatLongNumber(value) #",
            },
             majorUnit: 100000000
        }
    });   
    
     //****** Hour Stats **********
    
    var chartfiles = $("#hourStats").kendoChart({
        title: {
            text: "Average Transfered Gigabytes per Hour "
        },
        legend: {
            visible: false
        },
        dataSource:{
            transport:{
                read:{
                    async: true,
                    cache: true,
                    type: "POST",
                    url: "<?= base_url() ?>index.php/statistics/get_hourstats/",
                    dataType: "json",
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
    
    
         
});
</script>

<section>
    <div class="container_8 clearfix">                
        <!-- Main Section -->
        <section class="main-section grid_8">
            <!-- Statistics Section -->
            <div class="main-content">
                <header><h2>Statistics</h2></header>
                <section class="container_6 clearfix">  
                
                 <!-- Draw Graphs -->
                <div class="grid_6 clearfix">
                        <table class="simple full">
                            <tr>
                                <td style="width: 100%">
                                    <div id="bytesStats" style="width: 900px; height: 220px; margin: 0 auto"></div>
                                </td>    
                            </tr>
                        </table>
                        <table class="simple full">
                            <tr>
                                <td style="width: 100%">
                                    <div id="filesStats" style="width: 900px; height: 220px; margin: 0 auto"></div>
                                </td>    
                            </tr>
                        </table>
                        <table class="simple full">
                            <tr>
                                <td style="width: 100%">
                                    <div id="hourStats" style="width: 900px; height: 220px; margin: 0 auto"></div>
                                </td>    
                            </tr>
                        </table>
                    <?=$gridStats ?>
                    
                    
                </div>
          </section>
        </div>
        <!--
        <td style="width: 50%">
                                    <div id="filesStats" style="width: 480px; height: 220px; margin: 0 auto"></div>
                                </td>   
        
        
        End Statistics Section -->

        
       
    </section>
</div>
</section>