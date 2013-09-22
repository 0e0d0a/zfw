<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Groups') ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Role') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Parent Role') ?>
                
            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Delete') ?>

            </td>
        </tr>
        <?php foreach ($roles as $role): ?>
        <tr>
            <td>
                <a href="/accounts/roles/<?php echo $role['id'] ?>/"><img src="/img/edit.png" /></a>
                <?php echo $role['role'] ?>

            </td>
            <td>
                <?php echo $role['parents'] ?>
                
            </td>
            <td>
                <?php echo $layout->input()->checkbox('rm['.$role['id'].']') ?>

            </td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Add New:') ?>
                <?php echo $layout->asterisk('role', 'edRoles') ?><?php echo $layout->input()->text('role') ?>

            </td>
            <td>
                <?php echo $layout->input()->submit('save','Save') ?>

            </td>
        </tr>
    </table>
</form>