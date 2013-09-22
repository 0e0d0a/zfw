<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Change Password') ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->asterisk('passwd', 'chPass') ?>
                <?php echo $layout->i18n()->getContent('Current Passord') ?>

            </td>
            <td>
                <?php echo $layout->input()->password('passwd')?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('passwd1', 'chPass') ?>
                <?php echo $layout->i18n()->getContent('New Passord') ?>

            </td>
            <td>
                <?php echo $layout->input()->password('passwd1')?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('passwd2', 'chPass') ?>
                <?php echo $layout->i18n()->getContent('Retype Passord') ?>

            </td>
            <td>
                <?php echo $layout->input()->password('passwd2')?>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $layout->input()->submit('save','Save') ?>
            </td>
        </tr>
    </table>
</form>