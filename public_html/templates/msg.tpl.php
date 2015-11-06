<!-- Msg -->
<div id="message_box_<?php echo $data['id']; ?>">
	<div class="box <?php echo $data['type']; ?>">
		<div>
			<?php if ($data['title'] != '') { ?><div class="inner-header">
				<span><?php echo $data['title']; ?></span>
			</div><?php } ?>
			<div class="inner<?php if ($data['title'] == '') { echo '-single inner-cross'; } ?>">
				<?php echo $data['msg']; ?>
			</div>
		</div>
	</div>
</div>
