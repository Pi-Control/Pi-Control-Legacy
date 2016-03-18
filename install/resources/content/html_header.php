<?php
if (!defined('PICONTROL')) exit();

$tpl = new PiTpl;
$tpl->assign('title', (isset($data['title']) && $data['title'] != '') ? $data['title'] : 'Pi Control');

if (isset($_GET['s']) && $_GET['s'] == 'install' || !isset($_GET['s']))
	$picontrolUpdate = checkUpdate();

$referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

if ($referer != '')
	$referer = '&referer='.urlencode($referer);

$tpl->assign('updatePicontrol', isset($picontrolUpdate) ? $picontrolUpdate : '');
$tpl->assign('javascript_req_url', urlencode($_SERVER['REQUEST_URI']));
$tpl->assign('referer', $referer);
$tpl->assign('langUrl', (isset($_GET['lang']) && $_GET['lang'] != '') ? '&amp;lang='.$_GET['lang'] : '');

$tpl->draw('html_header');
?>