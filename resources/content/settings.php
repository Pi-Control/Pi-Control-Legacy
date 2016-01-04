<?php
(include_once LIBRARY_PATH.'cron/cron.class.php') or die($error_code['0x0006']);
(include_once LIBRARY_PATH.'cache/cache.class.php') or die($error_code['0x0007']);
$tpl->setHeaderTitle(_t('Einstellungen'));

switch ((isset($_GET['do']) ? $_GET['do'] : ''))
{
	case 'overview':
		include_once CONTENT_PATH.'/settings/overview.php';
			break;
	case 'pi_control':
		include_once CONTENT_PATH.'/settings/pi_control.php';
			break;
	case 'update':
		include_once CONTENT_PATH.'/settings/update.php';
			break;
	case 'plugins':
		include_once CONTENT_PATH.'/settings/plugins.php';
			break;
	case 'trouble-shooting':
		include_once CONTENT_PATH.'/settings/trouble-shooting.php';
			break;
	case 'statistic':
		include_once CONTENT_PATH.'/settings/statistic.php';
			break;
	case 'notification':
		include_once CONTENT_PATH.'/settings/notification.php';
			break;
	case 'user':
		include_once CONTENT_PATH.'/settings/user.php';
			break;
	case 'cache':
		include_once CONTENT_PATH.'/settings/cache.php';
			break;
	default:
		$tpl->draw('settings');
}
?>