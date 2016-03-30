<?php if (!defined('PICONTROL')) exit(); ?>
<!DOCTYPE HTML>
<html style="height: 100%;">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-title" content="Pi Control" />
	<meta name="application-name" content="Pi Control" />
	<meta name="theme-color" content="#1565C0" />
	<link type="text/css" rel="stylesheet" href="public_html/css/style.css.php" />
	<link rel="apple-touch-icon" sizes="57x57" href="/public_html/img/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="/public_html/img/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="/public_html/img/favicon/apple-touch-icon-152x152.png" />
	<link rel="apple-touch-icon" sizes="180x180" href="/public_html/img/favicon/apple-touch-icon-180x180.png" />
	<link rel="icon" type="image/png" href="public_html/img/favicon/android-chrome-192x192.png" sizes="192x192" />
	<link rel="icon" type="image/png" href="public_html/img/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="manifest" href="public_html/img/favicon/manifest.json" />
	<link rel="mask-icon" href="public_html/img/favicon/safari-pinned-tab.svg" color="#1975d0" />
	<link rel="shortcut icon" href="public_html/img/favicon/favicon.ico" />
	<title><?php _e('Login'); ?> | Pi Control</title>
</head>
<body class="login-body">
	<div class="login-wrapper">
		<div class="login-container">
			<div class="login-container-inner">
				<div class="login-logo"></div>
<?php if (isset($data['errorMsg'])) { ?>
				<div class="login-error"><?php _e($data['errorMsg']); ?></div>
<?php } ?>
				<form action="?i=login" method="post">
					<table class="login-table">
						<tr>
							<td colspan="2"><input type="text" name="username" class="login-input-text" placeholder="<?php _e('Benutzername'); ?>" /></td>
						</tr>
						<tr>
							<td colspan="2"><input type="password" name="password" class="login-input-text" placeholder="<?php _e('Passwort'); ?>" /></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="rememberMe" value="checked" id="rememberMe" /><label class="checkbox login-input-checkbox" for="rememberMe" title="<?php _e('Cookies m&uuml;ssen f&uuml;r diese Funktion aktiviert sein.'); ?>"><?php _e('Angemeldet bleiben'); ?></label></td>
							<td style="text-align: right;"><input type="submit" name="submit" class="login-input-button" value="<?php _e('Anmelden'); ?>"<?php if ($data['externalAccess'] == false) echo 'disabled="disabled"'; ?> /></td>
						</tr>
					</table>
					<input type="hidden" name="referer" value="<?php echo $data['referer']; ?>" />
				</form>
			</div>
		</div>
		<div class="login-footer">
			<div class="login-footer-inner"><?php _e('Mit %s entwickelt von %s.', '&#10084;', 'Willy Fritzsche'); ?> 2013-2016<br /><?php _e('Raspberry Pi ist ein Markenzeichen der Raspberry Pi Foundation.'); ?></div>
		</div>
	</div>
</body>
</html>