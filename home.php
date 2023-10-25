<!DOCTYPE html>
<?php
session_start();
include("include/connection.php");
if (!isset($_SESSION['user_email'])) {
    header("Location: signin.php");
    exit();
}
else{
    $user_name = $_SESSION['user_email'];

    // Define $unread status (you might have a different logic for this)
    $unread = "unread";
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/home.css">
        <title>MY CHAT - HOME</title>
    </head>
    <body>
    <div class="container main-section">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12 left-sidebar">
                <div class="input-group searchbox">
                    <div class="input-group-btn">
                        <center><a href="include/find_friends.php"><button class="btn btn-default search-icon" name="search_user" type="submit">Add new user</button></a></center>
                    </div>
                </div>
                <div class="left-chat">
                    <ul>
                        <?php include("include/get_users_data.php"); ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 right-sidebar">
                <div class="row">
                    <!-- Getting the user information who is logged in -->
                    <?php
                    $user = $_SESSION['user_email'];
                    $get_user = "select * from users where user_email='$user'";                                                               
                    $run_user = mysqli_query($con, $get_user);
                    $row = mysqli_fetch_array($run_user);
                    $user_id = $row['user_id'];
                    $user_name = $row['user_name'];
                    ?>
                    <!-- Getting the user data on which user clicked -->
                    <?php
                    if (isset($_GET['user_name'])) {
                        global $con;
                        $get_username = $_GET['user_name'];
                        $get_user = "select * from users where user_name='$get_username'";
                        $run_user = mysqli_query($con, $get_user);
                        $row_user = mysqli_fetch_array($run_user);
                        $username = $row_user['user_name'];
                        $user_profile_image = $row_user['user_profile'];
                    }
                    
                    // Rest of your code...
                    
                    
$total_messages = "SELECT * FROM users_chat WHERE (sender_username='$user_name' AND receiver_username='$username') OR (receiver_username='$user_name' AND sender_username='$username')";
$run_messages = mysqli_query($con, $total_messages);

if (!$run_messages) {
    die('Error in SQL query: ' . mysqli_error($con));
}

$total = mysqli_num_rows($run_messages);

                    ?>
                    <div class="col-md-12 right-header">
                        <div class="right-header-img">
                            <img src=<?php echo "$user_profile_image"; ?>>
                        </div>
                        <div class="right-header-detail">
                            <form method="post">
                                <p><?php echo $username; ?></p>
                                <span><?php echo $total; ?> messages</span>&nbsp &nbsp
                                <button name="logout" class="btn btn-danger">Logout</button>
                            </form>
                            <?php
                            if (isset($_POST['logout'])) {
                                $update_msg = mysqli_query($con, "UPDATE users SET log_in='Offline' WHERE user_name='$user_name'");
                                header("Location: logout.php");
                                exit();
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div id="scrolling_to_bottom" class="col-md-12 right-header-contentChat">
                        <?php
                        $update_msg = mysqli_query($con, "UPDATE users_chat SET msg_status='read' WHERE sender_username='$username' AND receiver_username='$user_name'");
                        $sel_msg = "select * from users_chat where (sender_username='$user_name' AND receiver_username='$username') OR (receiver_username='$user_name' AND sender_username='$user_name') ORDER BY msg_date ASC";
                        $run_msg = mysqli_query($con, $sel_msg);
                        
                        while ($row = mysqli_fetch_array($run_msg)) {
                            $sender_username = $row['sender_username'];
                            $receiver_username = $row['receiver_username'];
                            $msg_content = $row['msg_content'];
                            $msg_date = $row['msg_date'];
                        ?>
                            <ul>
                                <?php
                                if ($user_name == $sender_username AND $username == $receiver_username) {
                                    echo "<li>
                                            <div class='rightside-right-chat'>
                                                <span>$username<small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                          </li>";
                                } else if ($user_name == $receiver_username AND $username == $sender_username) {
                                    echo "<li>
                                            <div class='rightside-left-chat'>
                                                <span>$username<small>$msg_date</small></span>
                                                <p>$msg_content</p>
                                            </div>
                                          </li>";
                                }
                                ?>
                            </ul>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 right-chat-textbox">
                        <form method="post">
                            <input autocomplete="off" type="text" name="msg_content" placeholder="Write your message......">
                            <button class="btn" name="submit"><i class="fa fa-telegram" aria-hidden="true"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                    </div>
        <?php
        if (isset($_POST['submit'])) {
            $msg = htmlentities($_POST['msg_content']);
            if (empty($msg)) {
                echo "<div class='alert alert-danger'><strong><center>Message was unable to send.</center></strong></div>";
            } elseif (strlen($msg) > 100) {
                echo "<div class='alert alert-danger'><strong><center>Message is too long. Use only 100 characters</center></strong></div>";
            } else {
                $insert = "INSERT INTO users_chat (sender_username, receiver_username, msg_content, msg_status, msg_date) VALUES (?, ?, ?, ?, NOW())";
                $stmt = mysqli_prepare($con, $insert);
        
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssss", $user_name, $username, $msg, $unread);
                    $result = mysqli_stmt_execute($stmt);
        
                    if ($result) {
                        echo "Message inserted successfully";
                    } else {
                        echo "Error: " . mysqli_stmt_error($stmt);
                    }
        
                    mysqli_stmt_close($stmt);
                }
            }
        }    
        ?>
<script>
    $('#scrolling_to_bottom').animate({
        scrollTop:$('#scrolling_to_bottom').get(0).scrollHeight},1000);

</script>  
<script type="text/javascript">
$(document).ready(function(){
    var height=$(window).height();
    $(.'left-chat').css('height',(height-92)+'px');
    $('.left-header-contentChat').css('height',(height-163)+'px');
});
    </body>
    </html>
<?php
}
?>
