<!-- Content -->
<div>
	<div class="box">
		<div class="inner-header">
			<span>Hostname</span>
			<?php echo showGoBackIcon('?s=network'); ?>
		</div>
		<form action="?s=network&amp;hostname=save" method="post">
			<div class="inner-bottom">
				<strong>Hostname:</strong> <input type="text" name="hostname" value="<?php echo $data['hostname']; ?>" />
			</div>
			<div class="inner">
				<input type="submit" name="submit" value="Speichern" />
			</div>
		</form>
	</div>
</div>