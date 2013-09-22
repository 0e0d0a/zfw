<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
    <form id="editorForm" method="post" enctype="multipart/form-data">
        <div class="curse open_curse design" style="display: block; ">
            <table style="width: 100%;">
                <tr>
                    <td width="80">
                        <img src="<?php echo $layout->draw()->image('page', $id, true) ?>" width="66" height="66" border="0" alt="" style="border:1px gray solid; border-radius:3px; padding:3px; margin: 0 5px 2px 0;   background: none repeat scroll 0 0 #FFFFFF;">
                    </td>
                    <td>
                        <input type="file" name="file">
                    </td>
                    <td>
                        name:
                        <?php echo $layout->input()->text('name', $name, array('class'=>'linktext')) ?>
                    </td>
                    <td align="right">
                        <a href="/adminArea/pages/page" class="button">back</a>
                    </td>
                </tr>
            </table>
            <?php foreach ($layout->lang()->getArray() as $lid=>$lang): ?>
            <table style="width: 100%; border-top: 1px dotted #aaa;">
                <tbody>
                <tr>
                    <td style="width: 300px;">
                        <span style="font-weight: bold">
                            language:
                            <?php echo $lang ?>
                        </span><br /><br /><br />
                        name:
                        <?php echo $layout->input()->text('title['.$lid.']',$locale[$lid]['title'], array('class'=>'linktext')) ?>
                    </td>
                    <td>
                        teaser:<br />
                        <?php echo $layout->input()->textarea('teaser['.$lid.']',$locale[$lid]['teaser'], array('style'=>'width:100%','rows'=>5)) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        content:<br />
                        <?php echo $layout->input()->textarea('content['.$lid.']',$locale[$lid]['content'], array('style'=>'width:100%;margin: auto','rows'=>15)) ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php endforeach ?>
            <br style="clear:left">
            <a href="#" class="button" onclick="$('#editorForm').submit();return false;">save</a>
        </div>
    </form>
    <br style="clear:right">