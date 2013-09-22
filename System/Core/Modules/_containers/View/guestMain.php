<?php if (false) /* * @var View* */ $layout = new View('', '', '') // for code completion   ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>.:: ZFW - <?php echo $layout->i18n()->getContent($title) ?> ::.</title>
        <link type="text/css" rel="stylesheet" href="css/template.css" />
        <meta name="Description" content="<?php echo $meta ?>"/>
        <meta name="Keywords" content="<?php echo $meta ?>"/>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    </head>
    <body>
        <div id="mask"></div>
        <div id="modalDialog" style="display: none"></div>
        <div id="messageBus"></div>
        <div id="all">
            <!-- header -->
            <div class="header">
                <div class="header_left">
                    <?php $auth = $this->loadController('auth') ?>
                    <div class="lang">
                        <?php echo $auth->getLangSelector() ?>
                        
                    </div>
                    <div class="login">
                        <div class="lbg"></div>
                        <?php echo $auth->getSignInBlock() ?>
                        
                        <div class="rbg"></div>
                    </div>	
                </div>
                <div class="clear"></div>
            </div>

            <div class="main center">
                <div id="left_sidebar">
                    <div class="module">
                        <?php echo $layout->loadController('news')->getNewsSidebar() ?>
                    </div>
                </div>
                <!--end left_sidebar -->
                <div id="content">
                    <?php echo $content ?>
                    
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('#messageBus').html('<?php $mBus = $this->loadController('messageBus');
            echo $mBus->getMessageBus() ?>');
        </script>
        <?php echo $stat ?>
    </body>
</html>