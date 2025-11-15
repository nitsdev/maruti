<?php

require_once '../vendor/autoload.php';

// session_start();

// init configuration
$clientID = '1089971882652-b44f4ul84cb72k0bhmlnad50947ev1l4.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'welcome.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  // now you can use this profile info to create account in your website and make user logged in.
}  else {
  echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}

// Connect to database
// $hostname = "localhost";
// $username = "root";
// $password = "";
// $database = "youtube-google-login";

// $conn = mysqli_connect($hostname, $username, $password, $database);