<?php
$tpl = new RainTPL;

if (isset($_GET['save']) && isset($_POST['submit']))
{
    if (($_POST['token'] != '' && strlen($_POST['token']) == 32) || (!isset($_POST['activate'])))
    {
        if (($set_config_notification = setConfigValue('config_notification', (isset($_POST['activate']) && $_POST['activate'] == 'checked' ? 'true' : 'false'))) !== 0)
			$tpl->msg('red', '', $error_code['0x0047'].$set_config_notification);
    }
    
    if (isset($_POST['token']))
    {
        if (($_POST['token'] != '' && strlen($_POST['token']) == 32) || (!isset($_POST['activate'])))
        {
            if (($set_config_notification_token = setConfigValue('config_notification_token', trim($_POST['token']))))
			        $tpl->msg('red', '', $error_code['0x0048'].$set_config_notification_token);
        }
        else
            $tpl->msg('red', '', $error_code['2x0015']);
    }
    
    if (isset($_POST['cb_picontrol']))
    {
        if (($set_config_notification_picontrol = setConfigValue('config_notification_picontrol', ($_POST['cb_picontrol'] == 'checked' ? 'true' : 'false'))) !== 0)
			$tpl->msg('red', '', $error_code['0x0049'].$set_config_notification_picontrol);
    }
    
    if (isset($_POST['cb_cpu_temp']))
    {
        if (($set_config_notification_cpu_temp = setConfigValue('config_notification_cpu_temp', ($_POST['cb_cpu_temp'] == 'checked' ? 'true' : 'false'))) !== 0)
			$tpl->msg('red', '', $error_code['0x0050'].$set_config_notification_cpu_temp);
    }
    
    if (isset($_POST['cb_memory']))
    {
        if (($set_config_notification_memory = setConfigValue('config_notification_memory', ($_POST['cb_memory'] == 'checked' ? 'true' : 'false'))) !== 0)
			$tpl->msg('red', '', $error_code['0x0051'].$set_config_notification_memory);
    }
}

$token = getConfigValue('config_notification_token');
$pushbullet_error = false;

if ($token != '')
{
    if (isset($_GET['test_push']))
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/pushes');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token, 'Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "note", "title": "Pi Control | Testpush", "body": "Dein Pi Control hat dir ein Testpush gesendet."}');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_exec($ch);
        curl_close($ch);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/users/me');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output_me = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($output_me['error']))
    {
        $tpl->msg('red', 'Pushbullet', $error_code['1x0009'].$output_me['error']['message']);
        $pushbullet_error = true;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.pushbullet.com/v2/devices');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output_devices = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($output_devices['error']) && $pushbullet_error === false)
    {
        $tpl->msg('red', 'Pushbullet', $error_code['1x0010'].$output_devices['error']['message']);
        $pushbullet_error = true;
    }
}

$tpl->assign('pushbullet_error', $pushbullet_error);
$tpl->assign('me', $output_me['name']);
$tpl->assign('devices', $output_devices['devices']);
$tpl->assign('config_notification', getConfigValue('config_notification'));
$tpl->assign('config_notification_token', $token);
$tpl->assign('config_notification_picontrol', getConfigValue('config_notification_picontrol'));
$tpl->assign('config_notification_cpu_temp', getConfigValue('config_notification_cpu_temp'));
$tpl->assign('config_notification_memory', getConfigValue('config_notification_memory'));

$tpl->draw('settings/notification');
?>