<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Group') ?>: <?php echo $group['group'] ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->asterisk('group', 'edGroup')?>
                <?php echo $layout->i18n()->getContent('Group') ?>

            </td>
            <td>
                <?php echo $group['id']>3?$layout->input()->text('group', $group['group']):$group['group'] ?>

            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->asterisk('container', 'edGroup')?>
                <?php echo $layout->i18n()->getContent('Container') ?>

            </td>
            <td>
                <?php echo $group['id']>3?$layout->input()->text('container', $group['container']):$group['container'] ?>

            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?php echo $layout->input()->submit('save','Save') ?>
                <?php echo $layout->input()->submit('cancel','Cancel') ?>

            </td>
        </tr>
    </table>
</form>
<table>
    <tr>
        <td>
            <a href="/accounts/groupRoles/<?php echo $group['id'] ?>/"><?php echo $layout->i18n()->getContent('Edit Assigned Roles') ?></a>
        </td>
    </tr>
</table>