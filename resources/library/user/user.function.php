<?php
if (!defined('PICONTROL')) exit();

function loggedInUsers(&$item, $key, $array)
{
	$item['username'] = (isset($array['user_'.$item['username']])) ? $array['user_'.$item['username']]['username'] : $item['username'];
	$item['remember_me'] = (isset($item['remember_me']) && $item['remember_me'] == 'true') ? true : false;
	$item['current_online'] = (substr($key, 6) == $_COOKIE['_pi-control_login']) ? true : false;
}
?>