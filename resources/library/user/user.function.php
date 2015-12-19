<?php
function loggedInUsers(&$item, $key, $array)
{
	$item['username'] = (isset($array['user_'.$item['username']])) ? $array['user_'.$item['username']]['username'] : $item['username'];
	$item['keep_logged_in'] = (isset($item['keep_logged_in']) && $item['keep_logged_in'] == 'true') ? true : false;
	$item['current_online'] = (substr($key, 6) == $_COOKIE['_pi_control_login']) ? true : false;
}
?>