<?php
$tpl->setHeaderTitle(_t('Einstellungen zum Benutzer'));

function arraySort($array, $on, $order = SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();
	
    if (count($array) > 0)
	{
        foreach ($array as $k => $v)
		{
            if (is_array($v))
			{
                foreach ($v as $k2 => $v2)
				{
                    if ($k2 == $on)
                        $sortable_array[$k] = $v2;
                }
            }
			else
                $sortable_array[$k] = $v;
        }

        switch ($order)
		{
            case SORT_ASC:
            asort($sortable_array);
            	break;
            case SORT_DESC:
            arsort($sortable_array);
            	break;
        }

        foreach ($sortable_array as $k => $v)
            $new_array[$k] = $array[$k];
    }

    return $new_array;
}

function loggedInUsers(&$item, $key, $array)
{
	$item['username'] = (isset($array['user_'.$item['username']])) ? $array['user_'.$item['username']]['username'] : $item['username'];
	$item['keep_logged_in'] = (isset($item['keep_logged_in']) && $item['keep_logged_in'] == 'true') ? true : false;
}

$allUsers = getConfig('user');
$loggedInUsers = getConfig('login');

array_walk($loggedInUsers, 'loggedInUsers', $allUsers);

$loggedInUsers = array_sort($loggedInUsers, 'created', SORT_DESC);

$tpl->assign('allUsers', $allUsers);
$tpl->assign('loggedInUsers', $loggedInUsers);

if (isset($_GET['logout']) && $_GET['logout'] != '' && strlen($_GET['logout']) == 16)
{
	removeConfig('login:token_'.$_GET['logout']);
	header('Location: ?s=settings&do=user&msg=0');
}

if (isset($_GET['msg']))
{
	switch ($_GET['msg'])
	{
		case 0:
		$tpl->msg('success', '', 'Der Benutzer wurde erfolgreich abgemeldet.');
			break;
	}
}

$tpl->draw('settings/user');
?>