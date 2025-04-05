<?php
require_once './services/DatabaseConnection.php';
require_once './services/User.php';

$user = new User();
$user->logout();
header("Location: index.php");
exit;
