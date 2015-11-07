<!-- Error -->
<div>
	<div class="box error">
		<div>
			<span></span>
			<?php if ($data['title'] != '') { ?><div class="inner-header">
				<span><?php echo $data['title']; ?></span>
			</div><?php } ?>
			<div class="inner">
				<?php echo $data['msg']; ?>
			</div>
		</div>
	</div>
</div>
