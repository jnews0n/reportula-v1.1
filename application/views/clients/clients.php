           <section>
            <div  class="container_8 clearfix">

                <!-- Main Section -->

                <section class="main-section grid_8">

                    <!-- Statistics Section -->
                    <div class="main-content">
                        <header>
                            <ul class="action-buttons clearfix fr">
                                <li>
                                    <label for="client">Client :</label>
                                    
                                    <?
                                        $attributes = "id=clientid size = 70";
                                        echo form_dropdown('clientid',$clients,$client,$attributes);
                                    ?>
                                </li>
                                
                                
                                <li>
                                    <label for="from">From</label>
                                    <input type="text" id="from" name="from"/>
                                    <label for="to">to</label>
                                    <input type="text" id="to" name="to"/>
                                    <label for="from">From</label>
                                    
                                </li>
                                <li><button class="button button-gray" onclick=showClient() ><span class="accept"></span>OK</button></li>
                            </ul>
                            <h2>Client Statistics</h2>
                                  
                        </header>
                        <section  class="container_6 clearfix" >
                            <div id="clientsdata">
                                <?php $this->load->view($clientsdata); ?>
                            </div>
                        </section>
                    </div>
                    <!-- End Statistics Section -->
                </section>
            </div>
        </section>

