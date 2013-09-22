<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>

    <a href="/adminArea/pages/news/new" class="button_small" >new</a>

    <?php if ($news): ?>
    <?php foreach ($news as $page): ?>
    <div class="curse design minimal">
        <img src="<?php echo $layout->draw()->image('news', $page['id'], true) ?>" width="66" height="66" border="0" alt="" style="border:1px gray solid; border-radius:3px; padding:3px; float:left; margin: 0 5px 2px 0;   background: none repeat scroll 0 0 #FFFFFF;">
        <h3 class="complex4"><?=$page['title'] ?></h3>
        <p><?=$page['teaser'] ?></p>

        <div class="prog">
            <div>
                <a class="button" href="/adminArea/pages/news/<?=$page['id'] ?>">edit</a>
                <?php if ($page['is_allowed']): ?>
                <a class="button" href="#">deactivate</a>
                <?php else: ?>
                <a class="button" href="#">activate</a>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <?php endif ?>
    <br style="clear:right">