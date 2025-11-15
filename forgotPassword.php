<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Maruti Studio | Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href="./../css/jquery-validation.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../user/style.css">
</head>
<style>
    .password-container{
      position: relative;
    }
    .password-container input[type="password"],
    .password-container input[type="text"]{
      box-sizing: border-box;
    }
    .fa-eye{
      position: absolute;
      top: 38%;
      right: 2%;
      cursor: pointer;
      color: blue;
    }
    .regLink{
        margin-left: 15%;
    }
    p{
      font-size: 22px;
    }
    
</style>
<body>


  <div class="container">
    <div class="col-md-6">
      <h2 class="m-3 mt-5"><b>Forgot Password</b></h2><br><br>
      <p class="m-3">Please enter your registerd email id for getting <b>OTP</b> to reset your password</p>
      <input type="email" class="login__input m-5" name="email" id="email" placeholder="User Email" />
      <button type="submit" class="button login__submit" id="getOtp">
          <span class="button__text">Get Otp</span>
          <i class="button__icon fas fa-chevron-right"></i>
        </button>
    </div>
  </div>
  
</body>
<?php
    include("./../common/appjs.php");
?>
</html>
