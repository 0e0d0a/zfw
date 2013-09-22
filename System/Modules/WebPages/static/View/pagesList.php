<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
                        <table class="productDetails" width="100%">
                            <tr>
                                <td>
                                    <a href="<?php echo $layout->cfg()->get('url','base') ?>"><?php echo $layout->i18n()->getContent('Home') ?></a> &gt;
                                    <?php echo $layout->i18n()->getContent('Pages') ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%">
                                        <colgroup>
                                            <col width="100">
                                            <col width="*">
                                        </colgroup>
                                        <tr>
                                            <td colspan="2">
                                                <span class="pageHeader"><?php echo $layout->i18n()->getContent('Please select page for view') ?></span>
                                            </td>
                                        </tr>
                                        <?php if ($pages) foreach ($pages as $page): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $layout->draw()->image('page', $page['id'], true) ?>" />
                                            </td>
                                            <td valign="middle">
                                                <a href="<?php echo $layout->cfg()->get('url','base').'static/'.$page['name'] ?>/">
                                                    <span class="pageHeader"><?php echo $page['title'] ?></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </table>
                                </td>
                            </tr>
                        </table>