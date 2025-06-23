<?php
session_start();
include 'includes/conn.php';

// Check if "Remember Me" cookie is set
if (isset($_COOKIE['remember_username'])) {
    $remembered_username = $_COOKIE['remember_username'];
} else {
    $remembered_username = '';
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND user_role = 1";
    $query = $conn->query($sql);

    $date_login = date('Y-m-d H:i:s'); // Current date and time for logs

    if ($query->num_rows < 1) {
        // Log failed login due to incorrect username
        $status = 'failed - username not found';
        $insertLogSql = "INSERT INTO userlog (userid, date_login, status) VALUES (NULL, '$date_login', '$status')";
        $conn->query($insertLogSql);

        $_SESSION['error'] = 'Cannot find account with the username';
    } else {
        $row = $query->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = $row['userid'];
            $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($username) . '! You have successfully logged in.';

            // Handle "Remember Me"
            if (isset($_POST['remember'])) {
                setcookie('remember_username', $username, time() + (86400 * 30), "/"); // Expires in 30 days
            } else {
                setcookie('remember_username', '', time() - 3600, "/"); // Delete cookie
            }

            // Log successful login
            $userid = $row['userid'];
            $status = 'online';
            $insertLogSql = "INSERT INTO userlog (userid, date_login, status) VALUES ('$userid', '$date_login', '$status')";
            $conn->query($insertLogSql);

            // Redirect to home.php
            header('location: home.php');
            exit();
        } else {
            // Log failed login due to incorrect password
            $userid = $row['userid']; // Log user ID to know whose password failed
            $status = 'failed - incorrect password';
            $insertLogSql = "INSERT INTO userlog (userid, date_login, status) VALUES ('$userid', '$date_login', '$status')";
            $conn->query($insertLogSql);

            $_SESSION['error'] = 'Incorrect password';
        }
    }
} else {
    $_SESSION['error'] = 'Input admin credentials first';
}

header('location: index.php');
?>
