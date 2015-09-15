<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no" />
	<link type="text/css" rel="stylesheet" href="public_html/css/style.css.php" />
	<link type="text/css" rel="stylesheet" href="../css/style.css.php" />
	<link rel="shortcut icon" href="public_html/img/favicon.png" type="image/png" />
	<script type="text/javascript" src="public_html/js/jquery.min.js"></script>
	<title>Pi Control | <?php _e('Login'); ?></title>
</head>
<body class="login-body">
    <div class="login-wrapper">
        <div class="login-logo"></div>
		<form action="?i=login" method="post">
            <table class="login-table">
                <tr>
                    <td colspan="2"><input type="text" name="username" class="login-input-text" placeholder="Benutzername" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="password" name="password" class="login-input-text" placeholder="Passwort" /></td>
                </tr>
                <tr>
                    <td><input type="checkbox" name="keepLoggedIn" value="checked" id="keepLoggedIn" /><label class="checkbox login-input-checkbox" for="keepLoggedIn" title="Cookies müssen für diese Funktion aktiviert sein.">Angemeldet bleiben</label></td>
                    <td style="text-align: right;"><input type="submit" name="submit" class="login-input-button" value="Anmelden" /></td>
                </tr>
            </table>
			<input type="hidden" name="referer" value="<?php echo $data['referer']; ?>" />
		</form>
    </div>
	<div class="login-footer">Entwickelt von Willy Fritzsche. 2013-2015.<br />Das Raspberry Pi Logo steht unter der Lizenz von www.raspberrypi.org</div>
</body>
</html>