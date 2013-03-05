<div id="clientdata">   
    <div  class="grid_4 clearfix">
            <header class="clearfix">
                <ul class="fr action-buttons">
                   <li><a href="<?= base_url() ?>index.php/clients/clientdata/<?=$clientName?>/Today" class="current button button-gray no-text" title="Today's Stats"><span class="calendar-view-day"></span></a></li>
                    <li><a href="<?= base_url() ?>index.php/clients/clientdata/<?=$clientName?>/Week" class="button button-gray no-text" title="This Week's Stats"><span class="calendar-view-week"></span></a></li>
                    <li><a href="<?= base_url() ?>index.php/clients/clientdata/<?=$clientName?>/Month" class="button button-gray no-text" title="This Month's Stats"><span class="calendar-view-month"></span></a></li>
                </ul>
                <h3><?=$Data ?>'s Stats - <font color="blue"> <?=$clientName ?></font></h3>
            </header>
            <section>
                <div class="grid_1  omega">
                    <div class="widget black ac">
                        <header><h2>Successful Jobs</h2></header>
                        <section><h2><label id="txtOkJobs" ><font color="green"><?=$nTerminatedJobs ?></font></label></h2></section>
                    </div>
                </div>
                <div class="grid_1 omega">
                    <div class="widget black ac">
                        <header><h2>Jobs With Errors</h2></header>
                        <section><h2><label id="txtFailJobs" ><font color="red"><?=$nFailedJobs ?></font></h2></label></section>
                    </div>
                </div>
                <div class="grid_1 omega">
                    <div class="widget black ac">
                        <header><h2>Transfered Files</h2></header>
                        <section><h2><label id="txtTransFiles" ><?=$nTransFiles ?></label></h2></section>
                    </div>
                </div>
                <div class="grid_1 omega">
                    <div class="widget black ac">
                        <header><h2>Transfered Bytes</h2></header>
                        <section><h2><label id="txtTransBytes" ><?=$nTransBytes ?></label></h2></section>
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
            <header class="clearfix">
                <h3>List of the Succefful Jobs</h3>
            </header>

            <section class="with-table">
                <?=$gridRunningJobs ?>
            </section>

        </div>
          <!-- End Grid -->

</div>