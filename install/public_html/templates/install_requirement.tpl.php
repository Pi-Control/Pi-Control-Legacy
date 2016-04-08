<?php if (!defined('PICONTROL')) exit(); ?>
<style>
@-webkit-keyframes move {
    0% {
       width: 20%;
    }
    30% {
       width: 40%;
    }
}
</style>
<div class="sidebar">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Fortschritt'); ?></span>
		</div>
		<div class="inner">
			<div class="progressbar"><div style="width: 40%; -webkit-animation: move 1.5s linear forwards;">&nbsp;</div></div>
		</div>
		<div class="inner text-center">
			<strong>40%</strong>
		</div>
	</div>
</div>
<div class="container-600">
	<div class="box">
		<div class="inner-header">
			<span><?php _e('2. Schritt'); ?></span>
		</div>
		<div class="inner">
			<?php _e('Um das bestm&ouml;gliche aus dem Pi Control herausholen zu k&ouml;nnen, m&uuml;ssen bestimmte Anforderungen erf&uuml;llt sein. Nachfolgend findest du eine Liste dieser.'); ?>
		</div>
	</div>
	<div class="box">
		<div class="inner-header">
			<span><?php _e('Anforderungen'); ?></span>
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('PHP'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td style="width: 50%;"><?php _e('Version %s', '>= 5.4'); ?></td>
					<td class="<?php echo ($data['phpVersion']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpVersion']['status'] == true) ? '&#10004;' : '&#10006;'; echo ' '.$data['phpVersion']['version']; ?></td>
				</tr>
				<tr>
					<td><?php _e('SSH2-Erweiterung installiert'); ?></td>
					<td class="<?php echo ($data['phpSSH']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpSSH']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('Mcrypt-Erweiterung installiert'); ?></td>
					<td class="<?php echo ($data['phpMcrypt']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpMcrypt']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('CLI-Erweiterung installiert'); ?></td>
					<td class="<?php echo ($data['phpCLI']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpCLI']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('cURL-Erweiterung installiert'); ?></td>
					<td class="<?php echo ($data['phpCURL']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpCURL']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('ZipArchive-Erweiterung installiert'); ?></td>
					<td class="<?php echo ($data['phpZipArchive']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpZipArchive']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
				<tr>
					<td><?php _e('Laden externer Inhalte aktiviert'); ?></td>
					<td class="<?php echo ($data['phpAllowUrlFopen']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['phpAllowUrlFopen']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
			</table>
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Dateien und Ordner'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td style="width: 50%;"><?php _e('Vorhanden'); ?></td>
					<td class="<?php echo ($data['filesFoldersExist']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['filesFoldersExist']['status'] == true) ? '&#10004;' : '&#10006; <a style="color: inherit; text-decoration: underline;" href="?s=install_troubleshooting'. $data['langUrl'].'" target="_blank">'._t('Mindestens %d Fehler', $data['filesFoldersExist']['count']).'</a>'; ?></td>
				</tr>
				<tr>
					<td><?php _e('Berechtigungen'); ?></td>
					<td class="<?php echo ($data['filesFoldersPermission']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['filesFoldersPermission']['status'] == true) ? '&#10004;' : '&#10006; <a style="color: inherit; text-decoration: underline" href="?s=install_troubleshooting'.$data['langUrl'].'" target="_blank">'._t('Mindestens %d Fehler', $data['filesFoldersPermission']['count']).'</a>'; ?></td>
				</tr>
			</table>
		</div>
		<div class="inner">
			<span class="subtitle"><?php _e('Sonstiges'); ?></span>
		</div>
		<div class="inner-table">
			<table class="table table-borderless table-form">
				<tr>
					<td style="width: 50%;"><?php _e('Distribution'); ?></td>
					<td class="<?php echo ($data['otherDistribution']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['otherDistribution']['status'] == true) ? '&#10004;' : '&#10006;'; echo ' '.$data['otherDistribution']['version'] ; ?></td>
				</tr>
				<tr>
					<td><?php _e('Cookies aktiviert'); ?></td>
					<td class="<?php echo ($data['otherCookie']['status'] == true) ? 'green' : 'red'; ?>"><?php echo ($data['otherCookie']['status'] == true) ? '&#10004;' : '&#10006;'; ?></td>
				</tr>
			</table>
		</div>
		<div class="inner-end">
<?php if ($data['error'] === false) { ?>
			<a href="?s=install_user" class="button"><?php _e('N&auml;chster Schritt'); ?></a>
<?php } else { ?>
			<strong class="red"><?php _e('Probleme beheben, um fortzufahren!'); ?></strong> <a href="?s=install_requirement<?php echo $data['langUrl']; ?>" class="button"><?php _e('Seite aktualisieren'); ?></a>
<?php } ?>
		</div>
	</div>
</div>