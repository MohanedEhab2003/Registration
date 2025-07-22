<?php
require_once 'Session.php';

$session = new Session();
$session->destroy();

header('Location: login.php');
exit;
?>