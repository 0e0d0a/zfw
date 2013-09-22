<div style="width:98%; font-family: tahoma;font-size:10.5pt;background-color: #69E8CC;font-style: italic; border:2px solid #888888;padding:5px;margin-top:5px;">
    <div id="mainMsgSection">
        <div style="float:left;padding-right:10px;"><img src="_layouts/images/info_validation.png" alt="" ></div>
<?php
	$cnt = 0;
	foreach ($messages as $msg):
			++$cnt;
			?>
		<div style="text-align:left;">

			<b>
				<?php echo $msg ?>
			</b>
		</div>
<?php
	endforeach ?>
		<div style="text-align:right">
			HIDE messagess <img src="../images/minus.gif" style="cursor:pointer" onclick="jQuery('#mainMsgSection').hide();jQuery('#hiddenMsgSection').show()">
		</div>
	</div>
	<div id="hiddenMsgSection" style="display:none;">
		<table width="100%">
			<tr>
				<td>
					<img src="_layouts/images/icon_warning.gif"> <?php echo $cnt?> messages
				</td>
				<td align="right">
					SHOW messagess <img src="../images/add.gif" style="cursor:pointer" onclick="jQuery('#hiddenMsgSection').hide();jQuery('#mainMsgSection').show()">
				</td>
			</tr>
		</table>
	</div>
</div>