<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

if (is_array($SSHReturn = $tpl->executeSSH('ls -l', true, 2)))
	list ($SSHError, $SSHOutput) = $SSHReturn;

$stdio = ssh2_shell($tpl->getSSHResource(), 'vt102', NULL, 80, 40, SSH2_TERM_UNIT_CHARS);
//stream_set_blocking($stdio, true);

if (isset($_POST['submit'], $_POST['command']) && $_POST['command'] != '')
{
	$command = $_POST['command'].PHP_EOL;
	fwrite($stdio, $command);
}
sleep(1);
$lines = '';

//var_dump($stdio);
/*while($line = fgets($stdio))
{
	flush();
	$lines .= str_replace(array(' ', '<', '>'), array('&nbsp;', '&lt;', '&gt;'), $line).'<br />';
}
fclose($stdio);*/

// collect returning data from command
stream_set_blocking($stdio, false);
while ($buf = fread($stdio, 4096))
{
	$lines .= $buf;
}
fclose($stdio);
$tpl->assign('test', $lines);

$tpl->draw('terminal');
?>