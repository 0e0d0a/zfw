<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                    <div class="module">
                        <h2><?php echo $layout->i18n()->getContent('News') ?></h2>
                        <div class="text">
                            <div class="inside">
                                <?php if ($news) foreach ($news as $page): ?>
                                <h4><a href="/news/<?=$page['name']?>"><?php echo $page['title'] ?></a></h4>
                                <span class="data"><?php echo date ('d.m.Y', strtotime($page['created_at'])) ?></span><br>
                                <p><?php echo strlen($page['teaser'])>150?(substr($page['teaser'], 0, 147).'...'):$page['teaser'] ?></p>
                                <?php endforeach ?>
                                <a href="/news/" class="more"><?php echo $layout->i18n()->getContent('Read All') ?> &gt;</a>
                            </div>
                        </div>
                    </div>