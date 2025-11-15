
<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

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
        $query = "select * from users where (email='$email' or mobile='$email') and user_role=3";
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

        if($query_res['status'] == 0){
            http_response_code(400);
            $array = array(
                'msg' => "You are deactivated, contact admin for further support...",
                'data' => $POST
            );
            echo json_encode($array);
            exit;
        }

        // Get card data 
        $cart_id = $query_res['cart_id'];
        $table_name = "carts";
        $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and c.cart_id='$cart_id' GROUP BY p.id";
        $cartData = selectMultiple($query);

        $user_id = $query_res["id"];
        $wishlistQuery = "select * from wishlist where user_id=$user_id";
        $wishlistData = selectMultiple($wishlistQuery);
        // jwt.php
        $token = generateToken($query_res);
        $array = array(
            'msg' => "Login Success..",
            'token' => $token,
            'cartData'=> $cartData,
            'wishlistData'=> $wishlistData
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
    }
?>