<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                    <div class="big_module">
                        <h2><?php echo $layout->i18n()->getContent('News') ?></h2>
                        <div class="textt">
                            <?php if ($news) foreach ($news as $page): ?>
                            <div class="news">
                                <h4><?php echo $page['title'] ?></h4>
                                <span class="data">
                                    <?php echo date ('d.m.Y', strtotime($page['created_at'])) ?>
                                </span>
                                <img src="<?php echo $layout->draw()->image('page', $page['id'], true) ?>" />
                                <p><?php echo $page['content'] ?></p>
                            </div>
                            <?php endforeach ?>

                        </div>
                    </div>