<?php
include("include/connection.php");

if (isset($_POST['sign_up'])) {
    $name = mysqli_real_escape_string($con, $_POST['user_name']);
    $pass = mysqli_real_escape_string($con,$_POST['user_pass']);
    $email = mysqli_real_escape_string($con, $_POST['user_email']);
    $country = mysqli_real_escape_string($con, $_POST['user_country']);
    $gender = mysqli_real_escape_string($con, $_POST['user_gender']);
    $rand = rand(1, 2);

    if ($name == '') {
        echo "<script>alert('We cannot verify your name')</script>";
        exit();
    }

    if (strlen($pass) < 8) {
        echo "<script>alert('Password should be a minimum of 8 characters!')</script>";
        exit();
    }

    $check_email = "SELECT * FROM users WHERE user_email=?";
    $stmt = mysqli_prepare($con, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $check = mysqli_stmt_num_rows($stmt);

    if ($check > 0) {
        echo "<script>alert('Email already exists, please try again!')</script>";
        exit();
    }

    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    if ($rand == 1) {
        $profile_pic = "images/codingcafe1.png";
    } else if ($rand == 2) {
        $profile_pic = "images/codingcafe2.png";
    }

    $insert = "INSERT INTO users (user_name, user_pass, user_email, user_profile, user_country, user_gender) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insert);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $hashed_pass, $email, $profile_pic, $country, $gender);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Congratulations $name, your account has been created successfully')</script>";
        echo "<script>window.open('signin.php', '_self')</script>";
    } else {
        echo "<script>alert('Registration Failed, try again!!')</script>";
    }
}
?>
