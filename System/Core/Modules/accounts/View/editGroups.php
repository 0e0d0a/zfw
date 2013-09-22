<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Groups') ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Group Name') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Is Active') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Delete') ?>

            </td>
        </tr>
        <?php foreach ($groups as $group): ?>
        <tr>
            <td>
                <a href="/accounts/groups/<?php echo $group['id'] ?>/"><img src="/img/edit.png" /></a>
                <?php echo $group['group'] ?>

            </td>
            <td>
                <?php echo $layout->input()->img($group['is_active']?'ok.png':'no.png', 'Toggle Active', array(
                    'id'        => 'isActive_'.$group['id'],
                    'onclick'   => 'toggleActive('.$group['id'].')',
                )) ?>

            </td>
            <td>
                <?php echo $group['id']>3?$layout->input()->checkbox('rm['.$group['id'].']'):'' ?>

            </td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="3">
                <?php echo $layout->i18n()->getContent('Add New:') ?>
                <?php echo $layout->asterisk('group', 'edGroups') ?><?php echo $layout->input()->text('group') ?>

            </td>
            <td>
                <?php echo $layout->input()->submit('save','Save') ?>

            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    function toggleActive(id) {
        $.ajax({
        async : false,
        dataType: 'json',
        type: 'POST',
        url: "/accounts/groups/toggleActive/",
        data : {'id':id},
        success : function (r) {
            if(r.status) {
                $('#isActive_'+id).attr('src', r.active?'/img/ok.png':'/img/no.png');
            }
        }
    });
    }
</script>