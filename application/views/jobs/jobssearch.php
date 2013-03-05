   <section>
            <div  class="container_8 clearfix">

                <!-- Main Section -->

                <section class="main-section grid_8">

                    <!-- Statistics Section -->
                    <div class="main-content">
                        <header>
                            <ul class="action-buttons clearfix fr">
                                <li>
                                    <label for="client">Job :</label>
                                   <?
                                        $attributes = " id=jobsnameid size = 60";
                                        echo form_dropdown('name',$jobsname,'0',$attributes);
                                    ?>
                                </li>

                                <li>
                                    <label for="from">From</label>
                                    <input type="text" id="from" name="from"/>
                                    <label for="to">to</label>
                                    <input type="text" id="to" name="to"/>
                                    <label for="from">From</label>
                                </li>
                                <li><button class="button button-gray" onclick=showJobs('data') ><span class="accept"></span>OK</button></li>
                            </ul>
                            <h2>Job Statistics</h2>

                        </header>
                       
                    </div>
                    <!-- End Statistics Section -->
                </section>
            </div>
        </section>