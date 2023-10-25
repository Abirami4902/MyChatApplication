<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Courgette|Pacifico:400,700"rel="stylesheet">
  <link rel="stylesheet"href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title>Create new account</title>
    <link rel="stylesheet" type="text/css"href="css/signup.css">
</head>
<body>
    <div class="signup-form">
        <form action="" method="post">
           <div class="form-header">
                <h2>Sign Up</h2>     
                <p>Fill out this form and start chatting!!</p>       
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control"name="user_name"placeholder="Enter your name" autocomplete="off"required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control"name="user_pass"placeholder="Passwaord" autocomplete="off"required>
          </div>
          <div class="form-group">
            <label>E-mail Address</label>
            <input type="email" class="form-control"name="user_email"placeholder="someone@site.com" autocomplete="off"required>
          </div>
          <div class="form-group">
            <label>Country</label>
    <select class="form-control" name="user_country" required>
        <option disabled="">Select a Country</option>
        <option>Pakistan</option>
        <option>UK</option>
        <option>United States of America</option>
        <option>India</option>
        <option>France</option>
        <option>Bangladesh</option>
    </select>
          </div>
          <div class="form-group">
            <label>Gender</label>
        <select class="form-control" name="user_gender" required>
        <option disabled="">Select your gender</option>
        <option>Male</option>
        <option>Female</option>
        <option>others</option>
    </select>
          </div>
          <div class="form-group">
            <label calss="checkbox-inline">
                <input type="checkbox" required> I accept the <a href="#">Terms of Use</a>&amp; <a href="#">Privacy and policy</a>
            </label>
          </div>
          <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block btn-lg" name="sign_up">Sign Up</button>
          </div>
        <?php include("signup_user.php");?>


</form>
<div class="text-center small" style="color:674288;">Already have an account <a href="signin.php">Signin here</a></div>

    </div>
</body>
</html>