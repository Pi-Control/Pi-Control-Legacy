<?php
if (!defined('PICONTROL')) exit();

(include_once LIBRARY_PATH.'main/rpi.function.php') or die('Error: 0x0010');
(include_once LIBRARY_PATH.'cache/cache.class.php') or die('Error: 0x0011');
$tpl->setHeaderTitle(_t('Benutzer/Gruppen'));

$users = new Cache('users', 'rpi_getAllUsers');

$users->load();

$tpl->assign('all_users', $users->getContent());
$tpl->assign('users_cache_hint', $users->displayHint());

$tpl->draw('users_groups');
?>