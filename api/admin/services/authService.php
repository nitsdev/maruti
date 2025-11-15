
<?php
    include('./../../api/common/jwt.php');
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
        require './../../api/common/connect.php';
    } else {
        require './../../api/common/connect2.php';
    }
    require './../../api/common/query.php';

    function login($POST){
        // Check id and password
        $email=$POST['email'];
        $password=$POST['password'];
        $query = "select * from users where email='$email' and user_role=1";
        $query_res = select($query);

        $flag=false;
        if($query_res != null){
            $flag=true;
        }

        if($flag==true){
            if(password_verify($password,$query_res['password'])){
                $flag=true;
            }else{
                $flag=false;
            }
        }

        if($flag==false){
            http_response_code(400);
            $array = array(
                'msg' => "Invalid Email or Password...",
                'data' => $POST
            );
            echo json_encode($array);
            exit;
        }

        // jwt.php
        $token = generateToken($query_res);
        $array = array(
            'msg' => "Login Success..",
            'token' => $token
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
    }
?>