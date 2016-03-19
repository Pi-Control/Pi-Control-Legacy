<?php
define('PICONTROL', true);

(include_once realpath(dirname(__FILE__)).'/../../init.php') or die('Fehler beim Laden!');
(include_once LIBRARY_PATH.'main/tpl.class.php') or die('Fehler beim Laden!');
(include_once LIBRARY_PATH.'main/main.function.php') or die('Fehler beim Laden!');

$host = '127.0.0.1';
$port = (isset($_GET['port'])) ? $_GET['port'] : 0;
$null = NULL;

$tpl = new PiTpl;
$tpl->setTpl($tpl);

$termial = getConfig('terminal:port_'.$port, array());

if (is_array($termial) && $port > 9000 && $port < 9006 && ((empty($termial) || $termial['pid'] == getmypid())))
	setConfig('terminal:port_'.$port.'.pid', getmypid());
else
	exit();

set_time_limit(0);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 0, $port);
socket_listen($socket);

$clients = array($socket);
$ssh = NULL;
$stdio = NULL;
$lastLine = '';

while (true)
{
	$changed = $clients;
	
	socket_select($changed, $null, $null, 0, 10);
	
	if (in_array($socket, $changed))
	{
		foreach ($clients as $client)
			@socket_write($client, 'ping', strlen('ping'));
		
		$socket_new = socket_accept($socket);
		$clients[] = $socket_new;
		
		$header = socket_read($socket_new, 1024);
		perform_handshaking($header, $socket_new, $host, $port);
		
		socket_getpeername($socket_new, $ip);
		
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
		
		$response = mask(json_encode(array('type'=>'system', 'message'=> 'Verbunden')));
		send_message($response);
		$response_text = mask(json_encode(array('type' => 'console', 'message' => $lastLine)));
		send_message($response_text);
	}
	
	foreach ($changed as $changed_socket)
	{
		if (@socket_recv($changed_socket, $buf, 1024, 0) === 0)
		{
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
		}
		else
		{
			$received_text = unmask($buf);
			$tst_msg = json_decode($received_text);
			
			if (isset($tst_msg->message))
			{
				$user_message = $tst_msg->message;
				
				if ($user_message == '^PI')
				{
					setConfig('terminal:'.getmypid().'.time', date('d.m.Y H:i:s'));
					setConfig('terminal:'.getmypid().'.ip', $ip);
					setConfig('terminal:'.getmypid().'.reason', 'Kommando');
					removeConfig('terminal:port_'.$port);
					socket_close($socket);
					exec('kill -9 '.getmypid());
					break 2;
				}
				
				if ($user_message != '^C')
					$ssh->write($user_message."\n");
				else
					$ssh->write(chr(3));
			}
		}
	}
	
	if (count($clients) == 2 && $ssh === NULL)
	{
		if (($ssh = $tpl->getSSHResource()) === false)
			exit('Login Failed');
		
		$ssh->setTimeout(1);
		stream_set_timeout($ssh->fsock, 999999);
	}
	
	if ($ssh !== NULL)
	{
		if ($output = $ssh->read())
		{
			$ansi = new File_ANSI();
			$ansi->appendString($output);
			
			$lastLine = $ansi->getScreen();
			$response_text = mask(json_encode(array('type' => 'console', 'message' => $ansi->getScreen())));
			send_message($response_text);
			
			$response = mask(json_encode(array('type'=>'system', 'message'=> 'Verbunden')));
			send_message($response);
		}
	}
}

socket_close($socket);

function send_message($msg)
{
	global $clients;
	
	foreach ($clients as $client)
		@socket_write($client, $msg, strlen($msg));
	
	return true;
}

function unmask($text)
{
	$length = ord($text[1]) & 127;
	if ($length == 126)
	{
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif ($length == 127)
	{
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else
	{
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i)
		$text .= $data[$i] ^ $masks[$i%4];
	
	return $text;
}

function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if ($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif ($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif ($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

function perform_handshaking($receved_header, $client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if (preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			$headers[$matches[1]] = $matches[2];
	}
	
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}

exit();
?>