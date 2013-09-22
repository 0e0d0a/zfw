<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Role') ?>: <?php echo $role['role'] ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->asterisk('role', 'edRole') ?>
                <?php echo $layout->i18n()->getContent('Role Name') ?>:
            </td>
            <td>
                <?php echo $layout->input()->text('role', $role['role']) ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Parent Role') ?>:
            </td>
            <td>
                <?php echo $layout->input()->select('parent', $roles, (int)$role['parent_id'], array(), true) ?>
            </td>
        </tr>
    </table>
     
    <table>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Module') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Action') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Access') ?>

            </td>
        </tr>
        <?php foreach ($resources as $resource): ?>
        <tr>
            <td>
                <?php echo $resource['module'] ?>

            </td>
            <td>
                <?php echo $resource['action'] ?>

            </td>
            <td>
                <?php echo $layout->input()->select('permissions['.$resource['id'].']', array(1=>'Deny','Read','Write'), isset($permissions[$resource['id']])?(int)$permissions[$resource['id']]:0, array()) ?>

            </td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="3">
                <?php echo $layout->input()->submit('save','Save') ?>
                <?php echo $layout->input()->submit('cancel','Cancel') ?>

            </td>
        </tr>
    </table>
</form>