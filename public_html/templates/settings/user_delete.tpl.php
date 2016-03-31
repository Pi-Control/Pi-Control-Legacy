<?php if (!defined('PICONTROL')) exit(); ?>
<div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Einstellungen zum Benutzer - Benutzer l&ouml;schen'); ?></span>
			<?php showGoBackIcon('?s=settings&amp;do=user'); ?>
		</div>
		<form action="?s=settings&amp;do=user&amp;delete=<?php echo $data['lowerUsername']; ?>" method="post">
			<div class="inner">
				<?php _e('Bitte gebe zur Best&auml;tigung das Passwort des Benutzers an.'); ?>
			</div>
			<div class="inner-table">
				<table class="table table-borderless table-form">
					<tr>
						<td><?php _e('Benutzername'); ?></td>
						<td><?php echo $data['username']; ?></td>
					</tr>
					<tr>
						<td><?php _e('Passwort'); ?></td>
						<td><input type="password" name="password" maxlength="64" /></td>
					</tr>
				</table>
			</div>
			<div class="inner-end">
				<input type="submit" name="submit" value="<?php _e('L&ouml;schen'); ?>" />
			</div>
		</form>
	</div>
</div>