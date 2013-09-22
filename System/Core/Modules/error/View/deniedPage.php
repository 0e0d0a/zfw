<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<html>
    <head>
        <title>.:: <?php echo $layout->i18n()->getContent($title) ?> - ZFW ::.</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    </head>


    <body>
        <h1>403 - <?php echo $layout->i18n()->getContent('Permission denied') ?></h1>
        <a href="<?php echo $layout->cfg()->get('url','base') ?>"><? echo $layout->i18n()->getContent('Go To Home Page') ?></a>
        <hr />
    </body>
</html>