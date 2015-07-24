<?php
$tpl = new RainTPL;

if (isset($_GET['next']) && $_GET['next'] == '')
{
	if (file_exists(TEMP_PATH.'/config_datas.php'))
	{
		if (($config_datas = file(TEMP_PATH.'/config_datas.php')))
		{
			if (($file = fopen($config['paths']['main'].'/../resources/config/config_ssh.php', 'w+')) && ($file2 = fopen($config['paths']['main'].'/../resources/config/config_uniqid.php', 'w+')))
			{
				if (fwrite($file, '<?php'."\n".'$config_ssh_port = '.str_replace(array("\r\n", "\r", "\n"), "", $config_datas[0]).';'."\n".
									'$config_ssh_username = \''.str_replace(array("\r\n", "\r", "\n"), "", $config_datas[1]).'\';'."\n".
									'$config_ssh_password = \''.str_replace(array("\r\n", "\r", "\n"), "", $config_datas[2]).'\';'."\n".'?>') &&
					fwrite($file2, '<?php'."\n".'$config_uniqid = \''.str_replace(array("\r\n", "\r", "\n"), "", $config_datas[3]).'\';'."\n".'?>'))
				{
					include_once $config['paths']['main'].'/resources/library/main/ssh_connection.php';

					$cron_entry = '* * * * * www-data php -f "'.CRON_PATH.'/init.php" # By Pi Control';
					
					switch (addCronToCrontab($cron_entry, $ssh))
					{
						case 0:
							$clear_file = fopen(TEMP_PATH.'/config_datas.php', 'w'); 
							fclose($clear_file);
							
							if (trim(exec('dpkg -s php5 | grep Status: ')) != '')
								setConfigValue('config_last_cron_execution', time());
	
							if (!headers_sent($filename, $linenum))
								exit(header('Location: ../resources/library/main/install_complete.php'));
							else
								$tpl->error('Weiterleitung', 'Header bereits gesendet. Redirect nicht m&ouml;glich, klicken Sie daher stattdessen <a href="../resources/library/main/remove_install.php">diesen Link</a> an.');
								break;
						case 1:
							$tpl->msg('red', '', 'Konnte Cron-Konfiguration nicht an Raspberry Pi übermitteln! Bitte befolge diese <a href="'.$config['urls']['helpUrl'].'#cron-fuer-pi-control-anlegen" target="_blank">Anleitung</a>. <a href="?s=install_create&amp;next=skip">Installation dennoch abschließen.</a>');
								break;
						case 2:
							$tpl->msg('green', '', 'Der Cron ist bereits angelegt. <a href="?s=install_create&amp;next=skip">Installation dennoch abschließen.</a>');
								break;
						case 3:
							$tpl->msg('red', '', 'Fehler beim anlegen des Crons. Konnte Datei nicht öffnen. <a href="?s=install_create&amp;next=skip">Installation dennoch abschließen.</a>');
								break;
						case 4:
							$tpl->msg('red', '', 'Leider ist ein Fehler beim erstellen des Cron aufgetreten. Bitte befolge diese <a href="'.$config['urls']['helpUrl'].'#cron-fuer-pi-control-anlegen" target="_blank">Anleitung</a>. <a href="?s=install_create&amp;next=skip">Installation dennoch abschließen.</a>');
								break;
					}
				}
				else
					$tpl->msg('red', '', 'Konnte in Konfigurationsdateien nicht schreiben!');
			}
			else
				$tpl->msg('red', '', 'Konnte Konfigurationsdateien nicht öffnen bzw. erstellen!');
			
			fclose($file);
			fclose($file2);
		}
		else
			$tpl->msg('red', '', 'Konnte Temp-Dateien nicht auslesen!');
	}
	else
		$tpl->msg('red', '', 'Konnte Temp-Dateien nicht finden! Bitte Installation wiederholen.');
}
elseif (isset($_GET['next']) && $_GET['next'] == 'skip')
{
	$clear_file = fopen(TEMP_PATH.'/config_datas.php', 'w'); 
	fclose($clear_file);
	
	if (trim(exec('dpkg -s php5 | grep Status: ')) != '')
		setConfigValue('config_last_cron_execution', time());
	
	if (!headers_sent($filename, $linenum))
		exit(header('Location: ../resources/library/main/install_complete.php'));
	else
		$tpl->error('Weiterleitung', 'Header bereits gesendet. Redirect nicht m&ouml;glich, klicke daher stattdessen <a href="../resources/library/main/remove_install.php">diesen Link</a> an.');
}

$tpl->assign('config_mail_url', $config['urls']['baseUrl']);

$tpl->draw('install_create');
?>