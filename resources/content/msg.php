<?php
$tpl = new PiTpl;
$tpl->assign('type', $data['type']);
$tpl->assign('title', $data['title']);
$tpl->assign('msg', $data['msg']);
$tpl->assign('cancelable', $data['cancelable']);

$tpl->draw('msg');
?>