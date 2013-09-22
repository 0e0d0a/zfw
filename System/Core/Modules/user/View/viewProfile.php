<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('View Profile') ?>: <?php echo $user['login'] ?></span>
<br />
<table>
    <tr>
        <td>
            <?php echo $layout->i18n()->getContent('Group') ?>:
        </td>
        <td>
            <?php echo $groups[$user['group_id']] ?>
            
        </td>
    </tr>
</table>