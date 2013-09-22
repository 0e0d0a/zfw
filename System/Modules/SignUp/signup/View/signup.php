<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                        <form method="post" style="margin: 0" name="signUpUser">
                            <input type="hidden" id="signUpType" name="type" value="0" />
                            <table>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('login', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('Login') ?>:</span>
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->text('login', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('fname', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('First Name') ?>:</span>
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->text('fname', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('lname', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('Last Name') ?>:</span>
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->text('lname', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('email', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('Email') ?>:</span>
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->text('email', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('passwd', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('Password') ?>:</span> 
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->password('passwd', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $layout->asterisk('passwd2', 'userSignUp') ?>
                                        <span style="font-size:12px"><?php echo $layout->i18n()->getContent('Retype Password') ?>:</span> 
                                    </td>
                                    <td>
                                        <?php echo $layout->input()->password('passwd2', '', array('class'=>'signIn')) ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">
                                        <?php echo $layout->input()->submit('signUp','Sign Up') ?>

                                    </td>
                                </tr>
                            </table>
                        </form>