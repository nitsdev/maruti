<?php
// Initialize the session - is required to check the login state.
session_start();
// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['google_loggedin'])) {
    header('Location: login.php');
    exit;
}
// Retrieve session variables
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $_SESSION['google_email'];
$google_name = $_SESSION['google_name'];
$google_picture = $_SESSION['google_picture'];
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Profile</title>

    <style>
      * {
        box-sizing: border-box;
        font-family: system-ui, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        font-size: 16px;
      }
      body {
        background-color: #ca6454;
        margin: 0;
      }
      .content {
        max-width: 400px;
        width: 100%;
        margin: 50px auto 15px auto;
        border-radius: 5px;
        background-color: #fff;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        padding: 25px;
      }
      .content h1 {
        text-align: center;
        font-size: 24px;
        font-weight: 500;
        margin: 0 0 15px 0;
        color: #3b4252;
      }
      .content h1 .icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #9196a5;
        margin-left: 5px;
        font-size: 14px;
      }
      .content h1 .icon svg {
        fill: #fff;
      }
      .content .login-txt {
        margin: 0;
        padding: 15px 0 25px 0;
        color: #3b4252;
      }
      .content .google-login-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        width: 100%;
        overflow: hidden;
        border-radius: 5px;
        background-color: #d6523e;
        cursor: pointer;
      }
      .content .google-login-btn .icon {
        display: inline-flex;
        height: 100%;
        padding: 15px 20px;
        align-items: center;
        justify-content: center;
        background-color: #cf412c;
        margin-right: 15px;
      }
      .content .google-login-btn .icon svg {
        fill: #fff;
      }
      .content .google-login-btn:hover {
        background-color: #d44a36;
      }
      .content .google-login-btn:hover .icon {
        background-color: #c63f2a;
      }
      .content .profile-picture {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 15px 0 25px 0;
      }
      .content .profile-picture img {
        width: 100%;
        max-width: 100px;
        border-radius: 50%;
      }
      .content .profile-details {
        display: flex;
        flex-flow: column;
        padding: 10px 0;
      }
      .content .profile-details > div {
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f1f2f5;
        margin-bottom: 15px;
        padding-bottom: 15px;
      }
      .content .profile-details > div .icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #9196a5;
        margin-right: 15px;
        font-size: 14px;
      }
      .content .profile-details > div .icon svg {
        fill: #fff;
      }
      .content .profile-details > div strong {
        display: block;
        font-size: 14px;
        font-weight: 500;
      }
      .content .profile-details > div:last-child {
        border-bottom: none;
      }
      .content .logout-btn {
        display: flex;
        align-items: center;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        width: 100%;
        overflow: hidden;
        border-radius: 5px;
        background-color: #db5d36;
        cursor: pointer;
      }
      .content .logout-btn .icon {
        display: inline-flex;
        height: 100%;
        padding: 15px 20px;
        align-items: center;
        justify-content: center;
        background-color: #d24e26;
        margin-right: 15px;
      }
      .content .logout-btn .icon svg {
        fill: #fff;
      }
      .content .logout-btn:hover {
        background-color: #d9562d;
      }
      .content .logout-btn:hover .icon {
        background-color: #c94b24;
      }
</style>
	</head>
	<body>

		<div class="content home">

			<div class="profile-picture">
                <img src="<?=$google_picture?>" alt="<?=$google_name?>" width="100" height="100">
            </div>

            <div class="profile-details">

                <div class="name">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
                    </div>
                    <div class="wrap">
                        <strong>Name</strong>
                        <span><?=$google_name?></span>
                    </div>
                </div>

                <div class="email">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
                    </div>
                    <div class="wrap">
                        <strong>Email</strong>
                        <span><?=$google_email?></span>
                    </div>
                </div>

            </div>

            <a href="logout.php" class="logout-btn">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
                </span>
                Logout
            </a>

		</div>

	</body>
</html>