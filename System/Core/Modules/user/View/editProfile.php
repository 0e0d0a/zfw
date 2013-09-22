<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Profile') ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->asterisk('login', 'edProfile') ?>
                <?php echo $layout->i18n()->getContent('Login') ?>

            </td>
            <td>
                <?php echo $layout->input()->text('login', $user['login'])?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('fname', 'edProfile') ?>
                <?php echo $layout->i18n()->getContent('First Name') ?>

            </td>
            <td>
                <?php echo $layout->input()->text('fname', $user['fname'])?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('lname', 'edProfile') ?>
                <?php echo $layout->i18n()->getContent('Last Name') ?>

            </td>
            <td>
                <?php echo $layout->input()->text('lname', $user['lname'])?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('email', 'edProfile') ?>
                <?php echo $layout->i18n()->getContent('Email') ?>

            </td>
            <td>
                <?php echo $layout->input()->text('email', $user['email'])?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Group') ?>

            </td>
            <td>
                <?php echo $groups[$user['group_id']] ?>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo $layout->input()->submit('save','Save') ?>

            </td>
        </tr>
    </table>
</form>