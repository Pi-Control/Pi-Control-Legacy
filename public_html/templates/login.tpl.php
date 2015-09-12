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
    <!--<div style="width: 300px; height: 350px; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;">
        <div style="height: 100px; margin-bottom: 20px; background: url('../img/logo.png') center center no-repeat #1976D2; background-size: 80% auto; border-radius: 2px"></div>
        <div class="box">
            <div class="inner-header">
                <span>Anmelden</span>
            </div>
            <div class="inner-table">
                <table class="table table-borderless">
                    <tr>
                        <td><input type="text" style="width: 100%; box-sizing: border-box; padding: 10px;" placeholder="Benutzername" /></td>
                    </tr>
                    <tr>
                        <td><input type="password" style="width: 100%; box-sizing: border-box; padding: 10px;" placeholder="Passwort" /></td>
                    </tr>
                </table>
            </div>
            <div class="inner-end">
                <input type="submit" value="Anmelden" />
            </div>
        </div>
    </div>-->
    <div class="login-wrapper">
            <div class="login-logo"></div>
			<form action="?i=login" method="post">
	            <table class="login-table">
	                <tr>
	                    <td><input type="text" name="username" class="login-input-text" placeholder="Benutzername" /></td>
	                </tr>
	                <tr>
	                    <td><input type="password" name="password" class="login-input-text" placeholder="Passwort" /></td>
	                </tr>
	                <tr>
	                    <td style="text-align: right;"><input type="submit" name="submit" class="login-input-button" value="Anmelden" /></td>
	                </tr>
	            </table>
				<input type="hidden" name="referer" value="<?php echo $data['referer']; ?>" />
			</form>
    </div>
	<div style="position: absolute; bottom: 0; left: 0; right: 0; margin: auto; width: 300px; text-align: center; padding-bottom: 5px; font-size: 11px; color: #1565C0;">Entwickelt von Willy Fritzsche. 2013-2015.<br />
Das Raspberry Pi Logo steht unter der Lizenz von www.raspberrypi.org</div>
    <!--<div class="login-wrapper" style="display: table; width: 570px; height: 200px;">
        <div style="display: table-cell; width: 270px; vertical-align: top;">
            <div class="login-logo"></div>
            <div style="width: 250px; margin: 0px auto; text-align: justify; color: #FFFFFF;">
                Dein Pi Control ist durch ein Passwort geschützt. Bitte melde dich erst an, damit du das Pi Control nutzen kannst.
            </div>
        </div>
        <div style="display: table-cell; width: 30px; vertical-align: middle;">
            <div style="width: 1px; background: #1976D2; height: 180px; margin: 0px auto 0px;"></div>
        </div>
        <div style="display: table-cell; width: 270px; vertical-align: top;">
            <table class="login-table">
                <tr>
                    <td><input type="text" class="login-input-text" placeholder="Benutzername" /></td>
                </tr>
                <tr>
                    <td><input type="password" class="login-input-text" placeholder="Passwort" /></td>
                </tr>
                <tr>
                    <td style="text-align: right;"><input type="submit" class="login-input-button" value="Anmelden" /></td>
                </tr>
            </table>
        </div>
    </div>-->
</body>
</html>