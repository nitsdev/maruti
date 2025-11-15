<?php
  $env_file_path = realpath('../../.env');
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;

  require './../../vendor/autoload.php'; 
  Dotenv\Dotenv::createUnsafeImmutable('./../../')->load();

  $LOCAL_DB_USERNAME = getenv('LOCAL_DB_USERNAME');  
  $LOCAL_DB_PASSWORD = getenv('LOCAL_DB_PASSWORD');
  $LOCAL_DB_NAME = getenv('LOCAL_DB_NAME');
  $LOCAL_DB_HOST = getenv('LOCAL_DB_HOST') ?: 'localhost';

  $con=mysqli_connect($LOCAL_DB_HOST,$LOCAL_DB_USERNAME,$LOCAL_DB_PASSWORD,$LOCAL_DB_NAME);
?>