<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                    <div class="big_module">
                        <h2><?php echo $layout->i18n()->getContent('News') ?>: <?php echo $title ?></h2>
                        <div class="textt">
                            <div class="news">
                                <span class="data">
                                    <?php echo date ('d.m.Y', strtotime($created_at)) ?>
                                </span>
                                <img src="<?php echo $layout->draw()->image('news', $id, false) ?>" width="120" height="86" border="0" alt="" class="left_img" />
                                <p><?php echo $content?$content:$layout->i18n()->getContent('Not localized yet') ?></p>
                            </div>
                            <div class="pagenav">
                                <a href="/news/">&lt; <?php echo $layout->i18n()->getContent('Back to News') ?></a>
                            </div>
                        </div>
                    </div>