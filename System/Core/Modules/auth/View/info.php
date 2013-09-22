<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
    <div class="login_content log_in">
        <div>
            <p class="account_title log_in">
                <?php echo $layout->i18n()->getContent('You are Loged In as') ?>: <span><?php echo $layout->tools()->getLimitedString($name, 20) ?></span>
            </p>
            <form method="post" style="margin: 0" id="currentUserForm"><input type="hidden" id="signOut" name="signOut" value="0" /></form>
        </div>
        <a onclick="$('#signOut').val(1);$('#currentUserForm').submit()" href="#" class="regg">
            <span><span><?php echo $layout->i18n()->getContent('Sign Out') ?></span></span>
        </a>
        <a href="#" class="regg">
            <span><span><?php echo $layout->i18n()->getContent('Profile') ?></span></span>
        </a>
    </div>
