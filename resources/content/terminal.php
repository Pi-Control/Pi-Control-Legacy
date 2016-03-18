<?php
if (!defined('PICONTROL')) exit();

$tpl->setHeaderTitle('Terminal');

$tpl->getSSHResource(1);

$tpl->draw('terminal');
?>