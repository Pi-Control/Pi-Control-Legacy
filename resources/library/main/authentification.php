<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['token']))
{
    if (isset($authentificationMsg))
        die($authentificationMsg);
    else
    {
        header('Location: ?i=login');
        exit();
    }
}
?>