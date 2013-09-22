<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<div class="big_module">
    <h2><?php echo $title ?></h2>
    <div class="textt">
        <div class="content">
            <img src="<?php echo $layout->draw()->image('page', $id, false) ?>" width="120" height="86" border="0" alt="" class="left_img" />
            <?php echo $content?$content:$layout->i18n()->getContent('Not localized yet') ?>
            
        </div>
    </div>
</div>