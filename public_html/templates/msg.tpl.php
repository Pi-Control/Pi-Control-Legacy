<!-- Msg -->
<div id="message_box_<?php echo $data['id']; ?>">
	<div class="box info_<?php echo $data['type']; ?>">
		<?php if ($data['title'] != '') { ?><div class="inner-header">
			<?php if ($data['cancelable'] === true) { ?><img src="public_html/img/delete.png" class="info_cancel" title="Schließen" alt="Schließen" onClick="document.getElementById('message_box_<?php echo $data['id']; ?>').style.display='none'" /><?php } ?>
			<span><?php echo $data['title']; ?></span>
		</div><?php } ?>
		<div class="inner"<?php if ($data['title'] != '') { ?> style="background: #FFFFFF"<?php } ?>>
			<?php if ($data['cancelable'] === true && ($data['title'] == '' || $data['title'] === NULL)) { ?><img src="public_html/img/delete.png" class="info_cancel" onClick="document.getElementById('message_box_<?php echo $data['id']; ?>').style.display='none'" /><?php } ?>
			<strong><?php echo $data['msg']; ?></strong>
		</div>
	</div>
</div>
