<?php
session_start();
include("include/connection.php");

if (isset($_POST['sign_in'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['pass'];

    $select_user = "SELECT user_email, user_pass FROM users WHERE user_email = ?";
    $stmt = mysqli_prepare($con, $select_user);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $stored_password = $row['user_pass'];

        if (password_verify($password, $stored_password)) {
            // Password is correct
            $_SESSION['user_email'] = $email;
            $update_msg = mysqli_query($con, "UPDATE users SET log_in='Online' WHERE user_email='$email'");
            $user = $_SESSION['user_email'];
            $get_user = "SELECT user_name FROM users WHERE user_email = ?";
            $stmt = mysqli_prepare($con, $get_user);
            mysqli_stmt_bind_param($stmt, "s", $user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);
            $user_name = $row['user_name'];

            echo "<script>window.open('home.php?user_name=$user_name','_self')</script>";
        } else {
            echo "<div class='alert alert-danger'>
                    <strong>Check your email and password.</strong>
                  </div>";
        }
    } else {
        echo "<div class='alert alert-danger'>
                <strong>Check your email and password.</strong>
              </div>";
    }
}
?>
