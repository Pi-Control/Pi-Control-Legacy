<?php if (!defined('PICONTROL')) exit(); ?>
<!-- Msg -->
<div id="message_box_<?php echo $data['id']; ?>">
	<div class="box <?php echo $data['type']; ?>">
		<div>
			<?php if ($data['title'] != '') { ?><div class="inner-header">
				<span><?php echo $data['title']; ?></span>
				<?php if ($data['cancelable'] === true) { ?><div><span class="cancel"></span></div><?php } ?>
			</div><?php } ?>
			<div class="inner<?php if ($data['title'] == '') { echo '-single'; } ?>">
				<?php echo $data['msg']; ?>
			</div>
			<?php if ($data['title'] == '' && $data['cancelable'] === true) { ?><div><span class="cancel"></span></div><?php } ?>
		</div>
	</div>
</div>
