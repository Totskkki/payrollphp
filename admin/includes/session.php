<?php
session_start();
include 'includes/conn.php';

if (!isset($_SESSION['admin']) || trim($_SESSION['admin']) == '') {
    header('location: index.php');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// $sql = "SELECT * FROM users WHERE userid = '".$_SESSION['admin']."'";
// $query = $conn->query($sql);
// $user = $query->fetch_assoc();
$sql = "SELECT u.*, addr.*
  
FROM users u
LEFT JOIN address addr ON addr.userid = u.userid
WHERE u.userid = '".$_SESSION['admin']."'";

$query = $conn->query($sql);
$user = $query->fetch_assoc();
