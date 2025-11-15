<?php
  $env_file_path = realpath('../../.env');
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;

  require './../../vendor/autoload.php';
  Dotenv\Dotenv::createUnsafeImmutable('./../../')->load();

  // Try getenv() for better compatibility
  $SERVER_DB_USERNAME = getenv('SERVER_DB_USERNAME');  
  $SERVER_DB_PASSWORD = getenv('SERVER_DB_PASSWORD');
  $SERVER_DB_NAME = getenv('SERVER_DB_NAME');

  $con=mysqli_connect('localhost',$SERVER_DB_USERNAME,$SERVER_DB_PASSWORD,$SERVER_DB_NAME);
?>