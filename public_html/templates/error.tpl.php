<!-- Error -->
<div id="message_box">
	<div class="box">
		<?php if ($data['title'] != '') { ?><div class="inner-header">
			<span><?php echo $data['title']; ?></span>
		</div><?php } ?>
		<div class="inner">
			<strong class="red"><?php echo $data['msg']; ?></strong>
		</div>
	</div>
</div>
