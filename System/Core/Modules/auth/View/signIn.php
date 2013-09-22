<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                        <div class="login_content">
                            <span id="login_icon"></span>
                            <div>
                                <p class="account_title"><?php echo $layout->i18n()->getContent('Have an account') ?>?</p>
                            </div>
                            <a href="#" onclick="showSignIn();return false;" class="regg">
                                <span><span><?php echo $layout->i18n()->getContent('Sign In') ?></span></span>
                            </a>			
                            <a href="#" onclick="showSignUp();return false;" class="regg">
                                <span><span><?php echo $layout->i18n()->getContent('Sign Up') ?></span></span>
                            </a>
                        </div>

                        <div style="display: none">
                            <div id="login_registr" class="tab1 singInPouUp">
                                <h2>
                                    <?php echo $layout->i18n()->getContent('Enter to the site') ?>
                                    
                                </h2>
                                <div class="text">
                                    <div class="inside">
                                        <div class="left">
                                            <a href="#" onclick="showSignUp();" class="regg">
                                                <span><span><?php echo $layout->i18n()->getContent('Sign Up') ?></span></span>
                                            </a>
                                        </div>
                                        <div class="right">
                                            <form method="post" style="margin: 0" id="signInForm">
                                                <div style="float:left; width:272px">
                                                    <dl>
                                                        <dt>
                                                            <?php echo $layout->asterisk('email', 'signIn') ?>
                                                            <?php echo $layout->i18n()->getContent('Email') ?>:
                                                        </dt>
                                                        <dd>
                                                            <?php echo $layout->input()->text('email', '', array('class'=>'signIn')) ?>
                                                            
                                                        </dd>
                                                    </dl>
                                                    <dl>
                                                        <dt>
                                                            <?php echo $layout->asterisk('passwd', 'signIn') ?>
                                                            <?php echo $layout->i18n()->getContent('Password') ?>:
                                                            </dt>
                                                        <dd>
                                                            <?php echo $layout->input()->password('passwd', '', array('class'=>'signIn')) ?>
                                                            
                                                        </dd>
                                                    </dl>

                                                    <div style="padding-left:118px">
                                                        <a href="/forgotpassword/"><?php echo $layout->i18n()->getContent('Forgot password') ?></a>
                                                    </div>
                                                    <br class="clear" />

                                                    <dl class="soc">
                                                        <dt>
                                                            <?php echo $layout->i18n()->getContent('Enter using accounts') ?>:
                                                        </dt>
                                                        <dd>
                                                            <div id="social">
                                                                <a href="<?php echo $fbLorin ?>" class="facebook"><span>facebook</span></a>
                                                                <a href="#" class="twitter"><span>twitter</span></a>
                                                            </div>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                <dl style="width:124px; padding-left:10px">
                                                    <dt></dt>
                                                    <dd>
                                                        <?php echo $layout->input()->hidden('signIn','Sign In') ?>
                                                        <a class="more" href="#" onclick="$('#signInForm').submit()"><?php echo $layout->i18n()->getContent('Sign In') ?></a>
                                                    </dd>
                                                </dl>
                                            </form>
                                        </div>
                                    </div>
                                    <br class="clear" />
                                </div>
                            </div>
                            <div id="login_registr" class="tab2 singUpPopUp">
                                <h2>
                                    <?php echo $layout->i18n()->getContent('Sign Up') ?>
                                    
                                </h2>
                                <div class="text">
                                    <div class="inside">
                                        <div class="left">
                                            <form method="post" style="margin: 0" id="signUpForm" action="/signup/">
                                                <input type="hidden" id="signUpType" name="type" value="0" />
                                                <dl>
                                                    <dt><?php echo $layout->i18n()->getContent('Email') ?>:</dt>
                                                    <dd><input type="text" class="log_pass" name="email"></dd>
                                                </dl>
                                                    
                                                <dl>
                                                    <dt><?php echo $layout->i18n()->getContent('Password') ?>:</dt>
                                                    <dd><input type="password" class="log_pass" name="passwd"></dd>
                                                </dl>
                                                <dl>
                                                    <dt></dt>
                                                    <dd class="simple average good">
                                                        <div class="progressbar" id="pb">
                                                            <div class="pr"></div>
                                                        </div>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt><?php echo $layout->i18n()->getContent('Re-type Password') ?>:</dt>
                                                    <dd><input type="password" class="log_pass" name="repasswd"></dd>
                                                        
                                                </dl>
                                                <dl class="noerror"> 
                                                    <dt></dt>
                                                </dl>
                                                <dl>
                                                    <dt></dt>
                                                    <dd>
                                                        <input type="checkbox" id="Choice5" name="agree" />
                                                        <label for="Choice5"><?php echo $layout->i18n()->getContent('I agree with') ?> <a href="#"><?php echo $layout->i18n()->getContent('Terms of Use') ?></a></label>
                                                    </dd>
                                                </dl>
                                                <dl>
                                                    <dt></dt>
                                                    <dd>
                                                        <a onclick="$('#signUpForm').submit()" class="more_big" href="#"><?php echo $layout->i18n()->getContent('Sign Up') ?></a>
                                                    </dd>
                                                </dl>
                                                <dl class="soc">
                                                    <dt><?php echo $layout->i18n()->getContent('Sign Up Using') ?>:</dt>
                                                    <dd>
                                                        <div id="social">
                                                            <a href="#" onclick="$('#signUpType').val(1); return false;" class="google" title="google"><span>google</span></a>
                                                            <a href="<?php echo $fbLorin ?>" class="facebook"><span>facebook</span></a>
                                                            <a href="#" onclick="$('#signUpType').val(3); return false;" class="twitter"><span>twitter</span></a>
                                                            <a href="#" onclick="$('#signUpType').val(4); return false;" class="vk"><span>vkontakte</span></a>
                                                        </div>
                                                    </dd>
                                                </dl>
                                            </form>
                                        </div>
                                        <div class="right">		
                                            <div style="width:auto">
                                                <a href="#" onclick="showSignIn();" class="regg">
                                                    <span><span><?php echo $layout->i18n()->getContent('Sign In') ?></span></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div><br class="clear" />
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            function showSignIn() {
                                $('#modalDialog').colorbox({
                                    open:true,
                                    inline:true,
                                    href:$('#login_registr.singInPouUp'),
                                    innerWidth:'616',
                                    innerHeight:'253',
                                    scrolling:false,
                                    overlayClose:true,
                                    escKey:true,
                                    arrowKey:false,
                                    slideshow:false,
                                    //html: false,
                                    iframe: false,
                                    photo: false
                                });
                            }
                            function showSignUp() {
                                $('#modalDialog').colorbox({
                                    open:true,
                                    inline:true,
                                    href:$('#login_registr.singUpPopUp'),
                                    innerWidth:'615',
                                    innerHeight:'271',
                                    scrolling:false,
                                    overlayClose:true,
                                    escKey:true,
                                    arrowKey:false,
                                    slideshow:false,
                                    //html: false,
                                    iframe: false,
                                    photo: false
                                });
                            }
                        </script>