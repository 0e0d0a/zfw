<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Roles for Account:') . ' ' . $user['login'] ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Assigned') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Role') ?>

            </td>
            <?php foreach ($resources as $resource): ?>
            <td>
                <?php echo '<strong>'.$resource['module'].'</strong><br />'.$resource['action'] ?>

            </td>
            <?php endforeach ?>
        </tr>
        <?php foreach ($roles as $id=>$role): ?>
        <tr>
            <td>
                <?php echo $layout->input()->checkbox('assign['.$id.']', $role['assigned']) ?>

            </td>
            <td>
                <?php echo $role['role'] ?>
                
            </td>
            <?php foreach ($resources as $rId=>$resource): ?>
            <td>
                <?php 
                if (empty($role['permissions'][$rId]) || $role['permissions'][$rId]==1)
                    echo $layout->i18n()->getContent('None');
                elseif ($role['permissions'][$rId]==3)
                    echo $layout->i18n()->getContent('Write');
                elseif ($role['permissions'][$rId]==2)
                    echo $layout->i18n()->getContent('Read');
                ?>

            </td>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="<?php echo (count($resources)+2) ?>">
                <?php echo $layout->input()->submit('save','Save') ?>
                <?php echo $layout->input()->submit('cancel','Cancel') ?>

            </td>
        </tr>
    </table>
</form>