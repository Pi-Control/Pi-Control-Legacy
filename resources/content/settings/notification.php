<?php
$tpl = new RainTPL;

if (isset($_GET['send_note']))
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer VQY6SLkyg5LL2DeMFFuftItuSC9mOlIm', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "note", "title": "Pi Control | Temperaturüberschreitung", "body": "Dein Pi Control meldet eine erhöhte Temperatur von 25°C."}');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $output = json_decode(curl_exec($ch), true);
    curl_close($ch);
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/users/me');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer VQY6SLkyg5LL2DeMFFuftItuSC9mOlIm'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output_me = json_decode(curl_exec($ch), true);
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/devices');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer VQY6SLkyg5LL2DeMFFuftItuSC9mOlIm'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output_devices = json_decode(curl_exec($ch), true);
curl_close($ch);

$tpl->assign('me', $output_me['name']);
$tpl->assign('devices', $output_devices['devices']);

$tpl->draw('settings/notification');
?>