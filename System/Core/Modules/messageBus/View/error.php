<div style="width:98%; font-family: tahoma;font-size:10.5pt;background-color:  #69E8CC;font-style: italic; border:2px solid #888888;padding:5px;">

    <div id="mainErrSection">
        <div style="float:left;padding-right:10px;"><img src="_layouts/images/info_validation.png" alt="" ></div>
<?php
$js = array();
$cnt = 0;
foreach ($errors as $name=>$fields):
    foreach ($fields as $field=>$error):
        ++$cnt;
        if (!empty($error['id'])): ?>
        <div class="errFields" style="display: none"><?php echo $error['id'] ?></div>
			<?php endif?>
        <div style="text-align:left;margin-top:5px;">
            <strong<?php echo $error['id']?' style="cursor:pointer" onclick="document.getElementById(\''.$error['id'].'\').focus()"':'' ?>>
                <?php echo $error['msg'] ?>
            </strong>
        </div>
<?php
    endforeach;
endforeach ?>
		<div style="text-align:right">
			HIDE error messagess <img src="../images/minus.gif" style="cursor:pointer" onclick="jQuery('#mainErrSection').hide();jQuery('#hiddenErrSection').show()">
		</div>
	</div>
	<div id="hiddenErrSection" style="display:none;">
		<table width="100%">
			<tr>
				<td>
					<img src="_layouts/images/icon_warning.gif"> <?php echo $cnt?> Errors Ocured
				</td>
				<td align="right">
					SHOW error messagess <img src="../images/add.gif" style="cursor:pointer" onclick="jQuery('#hiddenErrSection').hide();jQuery('#mainErrSection').show()">
				</td>
			</tr>
		</table>
	</div>
</div>
