<?php

$env_file_path = realpath('../../.env');
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require './../../vendor/autoload.php';
include('const.php');


Dotenv\Dotenv::createUnsafeImmutable('./../../')->load();

$GLOBALS['jwt_token'] = "";

function generateToken($loginData){
    // echo DB_NAME;
    $secret_Key = $_ENV['SECRET_KEY'];
    $date = new DateTimeImmutable();
    if($loginData['user_role'] == 3){
        $expire_at = $date->modify('+360 minutes')->getTimestamp(); // Add 6h for user
    }
    else{
        $expire_at = $date->modify('+120 minutes')->getTimestamp(); // Add 2h
    }
    $domainName = $_ENV['SITE_NAME'];

    $request_data = [
        'iat' => $date->getTimestamp(), // Issued at: time when the token was generated
        'iss' => $domainName, // Issuer
        'nbf' => $date->getTimestamp(), // Not before
        'exp' => $expire_at, // Expire
        'email' => $loginData['email'], // Email
        'userName' => $loginData['first_name']." ".$loginData['last_name'],
        'user_id' => $loginData['id'],
        'mobile' => $loginData['mobile'],
        'user_role' => $loginData['user_role'],
        'cart_id' => $loginData['cart_id'],
    ];

    $jwttoken = JWT::encode($request_data, $secret_Key, 'HS512');
    return $jwttoken;
}
// generateToken();

function validateToken($jwttoken){
    $secret_Key = $_ENV['SECRET_KEY'];
    try {
        $token = JWT::decode($jwttoken, new Key($secret_Key, 'HS512'));

        $GLOBALS['jwt_token'] = $token;

        $now = new DateTimeImmutable();
        $serverName = $_ENV['SITE_NAME'];
        if (
            $token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp()
        ) {
            $array = array(
                'msg' => UNAUTH,
            );
            http_response_code(401);
            echo json_encode($array);
            exit;
        } else {
            return 'auth';
        }
    }catch (Exception $e){
        if($e->getMessage()=="Wrong number of segments"){
            $array = array(
                'msg' => INVALID_TOKEN,
            );            
        }
        if($e->getMessage()=="Expired token"){
            $array = array(
                'msg' => TOKEN_EXPIRED,
            );            
        }
        if($e->getMessage()=="Signature verification failed"){
            $array = array(
                'msg' => TOKEN_EXPIRED,
            );            
        }
        http_response_code(500);
        echo json_encode($array);
        exit;
    }
}

function authenticateUser($jwt_obj,$role_id,$programId){
    if($jwt_obj->user_role != $role_id || $jwt_obj->iss != $_ENV['SITE_NAME']){
        $array = array(
            'msg' => UNAUTH,
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
    }
}
?>


<?php 
    // echo $_ENV['SECRETKEY'];
?>
