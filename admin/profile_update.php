<?php
include 'includes/session.php';

if (isset($_GET['return'])) {
    $return = $_GET['return'];
} else {
    $return = 'home.php';
}

if (isset($_POST['updateprofile'])) {
    // Sanitize input data
    $contactNumber = trim($_POST['contactNumber']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $firstname = trim($_POST['fname']);
    $lastname = trim($_POST['lname']);
    $middleName = trim($_POST['middleName']);
    $photo = $_FILES['photo']['name'];
    $newPassword = trim($_POST['newPassword']);
    $confirmNewPassword = trim($_POST['confirmNewPassword']);

    // Fetch user details
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['admin']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Handle file upload
        if (!empty($photo)) {
            $target_dir = '../images/';
            $target_file = $target_dir . basename($photo);
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
            $filename = $photo;
        } else {
            $filename = $user['photo'];
        }

        // Handle password update only if a new password is provided
        if (!empty($newPassword)) {
            if ($newPassword === $confirmNewPassword) {
                $password = password_hash($newPassword, PASSWORD_DEFAULT);
            } else {
                $_SESSION['error'] = 'New passwords do not match.';
                header('location:' . $return);
                exit();
            }
        } else {
            $password = $user['password'];
        }

        // Update user details
        $update_sql = "UPDATE users SET 
            fname = ?, 
            mname = ?, 
            lname = ?, 
            contact = ?, 
            photo = ?, 
            username = ?, 
            email = ?, 
            password = ? 
            WHERE userid = ?";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "ssssssssi",
            $firstname,
            $middleName,
            $lastname,
            $contactNumber,
            $filename,
            $username,
            $email,
            $password,
            $user['userid']
        );

        if ($update_stmt->execute()) {
            $_SESSION['success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update profile.';
        }
    } else {
        $_SESSION['error'] = 'User not found.';
    }

    header('location:' . $return);
    exit();
}
?>
