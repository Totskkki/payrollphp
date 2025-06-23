<?php
include 'includes/session.php';
include '../timezone.php';
session_start();
session_destroy();

$userid = $_SESSION['admin'];
$date_logout = date('Y-m-d H:i:s');

$logoutSql = "UPDATE userlog SET date_logout = '$date_logout', status = 1 WHERE userid = '$userid' AND status = 0 ORDER BY id DESC LIMIT 1";
$conn->query($logoutSql);

session_destroy();
header('location: index.php');
exit();
