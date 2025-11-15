<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    // include('../healthcheck.php');
    include('./services/authService.php');
    include('./services/register.php');
    include('./services/category.php');
    include('./services/subCategory.php');
    include('./services/product.php');
    include('./services/cart.php');
    include('./services/common.php');
    include('./services/address.php');
    include('./services/coupon.php');
    include('./services/wishlist.php');
    include('./services/blog.php');
    include('./services/checkout.php');
    include('./services/payment.php');
    include('./services/orders.php');
    include('./services/user.php');
    include('./services/shiprocket.php');
    include('./services/custEnquiry.php');
    include('./services/delhivery.php');
    include('./services/shop.php');

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $headers= apache_request_headers();

    $jwttoken="";
    $userRole = 3;
    $programId = 1;

    if(isset($headers['Authorization'])){
        $jwttoken = $headers['Authorization'];
    }

    if($jwttoken){
        // jwt.php
        validateToken(explode(" ", $jwttoken)[1]);

        // Authorise user by role
        if($httpMethod == "POST"){
            if($_POST["endurl"]!="login" && $_POST["endurl"]!="register"){
                //jwt.php
                authenticateUser($GLOBALS['jwt_token'],$userRole,$programId);
            }
        }else if($httpMethod == "GET"){
            //jwt.php
            authenticateUser($GLOBALS['jwt_token'],$userRole,$programId);
        }
    }    

    if($httpMethod == "GET"){
        if($_REQUEST["endurl"]=="getAllProducts"){
            //product.php
            getAllProducts();
        }
        else if($_REQUEST["endurl"]=="getProductBySearch"){
            //product.php
            getProductBySearch($_REQUEST);
        }
        else if($_REQUEST["endurl"]=="getHomeImg"){
            //user.php
            getHomeImg($_REQUEST);
        }
        else if($_REQUEST["endurl"]=="getFeatureProduct"){
            //product.php
            getFeatureProduct($_REQUEST);
        }
        else if($_REQUEST["endurl"]=="subSubmit"){
            //custEnquiry.php
            subSubmit($_REQUEST);
        }
        else if($_REQUEST["endurl"]=="getProd"){
            //Call addEnquiry function present in product.php
            getProd($_REQUEST);
        }
        else if($_REQUEST["endurl"]=="getSingleProduct"){
            //product.php
            getSingleproduct($_REQUEST);
        } 
        else{
            $array = array(
                'msg' => "API Not Found..",
            );
            http_response_code(NOT_FOUND_STATUS_CODE);
            echo json_encode($array);
            exit;
        }
    }else if($httpMethod == "POST"){
        if($_POST["endurl"]=="login"){
            //Call Login function present in authService.php
            login($_POST);
        }
        else if($_POST["endurl"]=="register"){
           register($_POST);
        }
        else if($_POST["endurl"]=="checkLogin"){
            $array = array(
                'msg' => "Token is valid",
            );
            http_response_code(200);
            echo json_encode($array);
        }
        
        else if($_POST["endurl"]=="getCategory"){
            //Call getCategory function present in category.php
            getCategory($_POST);
        }
        else if($_POST["endurl"]=="getAllProducts"){
            //Call getAllProducts function present in product.php
            getAllProducts($_POST);
        } 
        else if($_POST["endurl"]=="getHomeImg"){
            //Call getHomeImg function present in user.php
            getHomeImg($_POST);
        }
        else if($_POST["endurl"]=="getProductBySearch"){
            //Call getProductBySearch function present in product.php
            getProductBySearch($_POST);
        }
        else if($_POST["endurl"]=="getBrandImg"){
            //Call getBrandImg function present in user.php
            getBrandImg($_POST);
        }
        else if($_POST["endurl"]=="getProductsByCat"){
            //Call getProductsByCat function present in product.php
            getProductsByCat($_POST);
        }
        else if($_POST["endurl"]=="addEnquiry"){
            //Call addEnquiry function present in custEnquiry.php
            addEnquiry($_POST);
        }
        else if($_POST["endurl"]=="addSubscribe"){
            //Call addSubscribe function present in custEnquiry.php
            addSubscribe($_POST);
        }
        else if($_POST["endurl"]=="addToCart"){
            //Call addToCart function present in cart.php
            addToCart($_POST,1);
        }
         else if($_POST["endurl"]=="addToWishlist"){
            //Call addToWishlist function present in cart.php
            addToWishlist($_POST,1);
        }
        else if($_POST["endurl"]=="getUser"){
            //Call getUser function present in user.php
            getUser($_POST);
        }
        else if($_POST["endurl"]=="editProfile"){
            //Call editProfile function present in user.php
            editProfile($_POST);
        }
        else if($_POST["endurl"]=="getOrders"){
            //Call getOrders function present in orders.php
            getOrders($_POST);
        }
        else if($_POST["endurl"]=="cancelOrder"){
            //Call cancelOrder function present in orders.php
            cancelOrder($_POST);
        }
        else if($_POST["endurl"]=="getAddresses"){
            //Call getAddresses function present in address.php
            getAddresses($_POST);
        }
        else if($_POST["endurl"]=="getCountry"){
            //Call getCountry function present in common.php
            getCountry($_POST);
        }
        else if($_POST["endurl"]=="getUniqueProduct"){
            //Call getUniqueProduct function present in product.php
            getUniqueProduct($_POST);
        }
        else if($_POST["endurl"]=="getStateByCountry"){
            //Call getStateByCountry function present in common.php
            getStateByCountry($_POST);
        }
        else if($_POST["endurl"]=="getCityByState"){
            //Call getCityByState function present in common.php
            getCityByState($_POST);
        }
        else if($_POST["endurl"]=="addAddress"){
            //Call addAddress function present in address.php
            addAddress($_POST);
        }

        else{
            $array = array(
                'msg' => "API Not Found..",
            );
            http_response_code(NOT_FOUND_STATUS_CODE);
            echo json_encode($array);
            exit;
        }
    }else{
        $array = array(
            'msg' => "Invalid HTTP method passed..",
        );
        http_response_code(NOT_FOUND_STATUS_CODE);
        echo json_encode($array);
        exit;
    }   
?>
