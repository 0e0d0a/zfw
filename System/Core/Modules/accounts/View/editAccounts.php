<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<br />
<br />
<span class="pageHeader"><?php echo $layout->i18n()->getContent('Edit Accounts') ?></span>
<br />
<form method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo $layout->i18n()->getContent('Login') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('First Name') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Last Name') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Email') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Group') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Is Active') ?>

            </td>
            <td>
                <?php echo $layout->i18n()->getContent('Delete') ?>

            </td>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td>
                <a href="/accounts/users/<?php echo $user['id'] ?>/"><img src="/img/edit.png" /></a>
                <?php echo $user['login'] ?>

            </td>
            <td>
                <?php echo $user['fname'] ?>

            </td>
            
            <td>
                <?php echo $user['lname'] ?>

            </td>
            
            <td>
                <?php echo $user['email'] ?>

            </td>
            <td>
                <?php echo $user['group_id']?$groups[$user['group_id']]:'---' ?>

            </td>
            <td>
                <?php echo $layout->input()->img($user['is_active']?'ok.png':'no.png', 'Toggle Active', array(
                    'id'        => 'isActive_'.$user['id'],
                    'onclick'   => 'toggleActive('.$user['id'].')',
                )) ?>

            </td>
            <td>
                <?php echo $layout->input()->checkbox('rm['.$user['id'].']') ?>

            </td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="5">
                <?php echo $layout->i18n()->getContent('Add New:') ?>
                <table>
                    <tr>
                        <td>
                            <?php echo $layout->asterisk('login', 'edAccounts') ?>
                            <?php echo $layout->i18n()->getContent('Login') ?>:
                        </td>
                        <td>
                            <?php echo $layout->input()->text('login') ?>
                        </td>
                        <td>
                            <?php echo $layout->asterisk('email', 'edAccounts') ?>
                            <?php echo $layout->i18n()->getContent('Email') ?>:
                        </td>
                        <td>
                            <?php echo $layout->input()->text('email') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $layout->asterisk('passwd', 'edAccounts') ?>
                            <?php echo $layout->i18n()->getContent('Password') ?>:
                        </td>
                        <td>
                            <?php echo $layout->input()->text('passwd') ?>
                        </td>
                        <td>
                            <?php echo $layout->asterisk('group', 'edAccounts') ?>
                            <?php echo $layout->i18n()->getContent('Group') ?>:
                        </td>
                        <td>
                            <?php echo $layout->input()->select('group', $groups, '', array(), true) ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
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
        url: "/accounts/users/toggleActive/",
        data : {'id':id},
        success : function (r) {
            if(r.status) {
                $('#isActive_'+id).attr('src', r.active?'/img/ok.png':'/img/no.png');
            }
        }
    });
    }
</script>