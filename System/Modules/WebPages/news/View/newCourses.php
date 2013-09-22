<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                    <!-- new curses -->
                    <div class="big_module">
                        <h2><?php echo $layout->i18n()->getContent('New Courses') ?></h2>
                        <div class="textt">
                            <?php if ($newCourses) foreach ($newCourses as $course): ?>
                            <div class="new_curse">
                                <div class="inside">
                                    <h3><a href="/materials/course/<?php echo $course['course_id'] ?>/"><?php echo $course['title'] ?></a></h3>
                                    <?php echo $layout->draw()->stars(3) ?>
                                    
                                    <div class="text">
                                        <?php echo $course['descr'] ?>
                                        
                                    </div>
                                    <a class="more" href="/materials/course/<?php echo $course['course_id'] ?>/">
                                        <?php echo $layout->i18n()->getContent('Read More') ?>
                                        
                                    </a>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <!--end new curses -->