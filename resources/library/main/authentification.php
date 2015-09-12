<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['token']))
{
    if (isset($authentificationMsg))
        die($authentificationMsg);
    else
    {
        $referer = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        
        if ($referer != '')
            $referer = '&referer='.urlencode($referer);
        
        header('Location: ?i=login'.$referer);
        exit();
    }
}
?>