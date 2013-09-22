<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                    <?php echo $layout->loadController('news')->getNewCoursesWidget() ?>
                    <!--feedback -->
                    <div class="feed_stat">
                        <div class="feedback">
                            <div class="feeds">
                                <a href="#" class="left_button"></a>
                                <a href="#" class="right_button"></a>
                                <img src="/img/frontend/man.jpg" border="0" alt="">
                                <img src="/img/frontend/man.jpg" border="0" alt="">
                                <img src="/img/frontend/man.jpg" border="0" alt="">
                                <img src="/img/frontend/man.jpg" border="0" alt="">
                                <blockquote>
                                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                </blockquote>
                            </div>
                        </div>
                        
                        <div class="module">
                            <h2>Статистика</h2>
                            <div class="text">
                                <div class="inside">
                                    <span class="data">06.10.2011 г.</span><br />
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <span class="data">06.10.2011 г.</span><br />
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end feedback -->

                    <?php echo $layout->loadController('static')->ourPartnersWidget() ?>