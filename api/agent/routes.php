<?php
    // include('../healthcheck.php');
    include('./services/authService.php');
    include('./services/register.php');
    include('./services/product.php');
    include('./services/category.php');
    include('./services/seller.php');
    include('./services/subCategory.php');
    include('./services/address.php');    
    include('./services/orders.php');
    include('./services/settlement.php');

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $headers= apache_request_headers();

    $jwttoken="";
    $userRole = 4;
    $programId = 1;

    // Check if auth token is there in headers or not
    if($httpMethod == "POST"){
        if($_POST["endurl"]!="login" && $_POST["endurl"]!="register"){
            checkHeaders($headers,$userRole);
            $jwttoken = $headers['Authorization'];
        }
    }else if($httpMethod == "GET"){
        checkHeaders($headers,$userRole);
        $jwttoken = $headers['Authorization'];
    }

    // Check if existing token is valid or not
    if($jwttoken){
        // jwt.php
        validateToken(explode(" ", $jwttoken)[1]);
    }

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


    if($httpMethod == "GET"){
        if($_REQUEST["endurl"]=="getAllProducts"){
            //product.php
            getAllProducts();
        }
        else if($_REQUEST["endurl"]=="getSingleProduct"){
            //product.php
            getSingleProduct($_REQUEST);
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
        // else if($_POST["endurl"]=="addProduct"){
        //     //product.php
        //     addProduct($_POST);
        // }
        // else if($_POST["endurl"]=="editProduct"){
        //     //product.php
        //     editProduct($_POST);
        // }
        // else if($_POST["endurl"]=="getAllProducts"){
        //     //product.php
        //     getAllProducts($_POST);
        // }
        // else if($_POST["endurl"]=="importProduct"){
        //     //Call importProduct function present in product.php
        //     importProduct($_POST);
        // }
        // else if($_POST["endurl"]=="getSingleProduct"){
        //     //product.php
        //     getSingleProduct($_POST);
        // }
        // else if($_POST["endurl"]=="changeStatus"){
        //     //product.php
        //     changeStatus($_POST);
        // }
        // else if($_POST["endurl"]=="addCategory"){
        //     //Category.php
        //     addCategory($_POST);
        // }
        // else if($_POST["endurl"]=="addSubCategory"){
        //     //subCategory.php
        //     addSubCategory($_POST);
        // }
        // else if($_POST["endurl"]=="getCategory"){
        //     //Category.php
        //     getCategory($_POST);
        // }
        // else if($_POST["endurl"]=="getSubCategory"){
        //     //SubCategory.php
        //     getSubCategory($_POST);
        // }
        // else if($_POST["endurl"]=="getSellers"){
        //     //seller.php
        //     getSellers($_POST);
        // }
        // else if($_POST["endurl"]=="getCategoryBySeller"){
        //     //Category.php
        //     getCategoryBySeller($_POST);
        // }
        // else if($_POST["endurl"]=="getUniqueCategory"){
        //     //Category.php
        //     getUniqueCategory($_POST);
        // }
        // else if($_POST["endurl"]=="getUniqueSubCategory"){
        //     //subCategory.php
        //     getUniqueSubCategory($_POST);
        // }
        // else if($_POST["endurl"]=="editCategory"){
        //     //Category.php
        //     editCategory($_POST);
        // }
         else if($_POST["endurl"]=="getAddresses"){
             //address.php
             getAddresses($_POST);
         }
        // else if($_POST["endurl"]=="getOrders"){
        //     //orders.php
        //     getOrders($_POST);
        // }
        else if($_POST["endurl"]=="addAddresses"){
            //address.php
            addAddresses($_POST);
        }
        // else if($_POST["endurl"]=="addBankDetails"){
        //     //seller.php
        //     addBankDetails($_POST);
        // }
        // else if($_POST["endurl"]=="getBankDetails"){
        //     //seller.php
        //     getBankDetails($_POST);
        // }
        // else if($_POST["endurl"]=="editL1Category"){
        //     //subCategory.php
        //     editL1Category($_POST);
        // }
        // else if($_POST["endurl"]=="changeCatStatus"){
        //     //category.php
        //     changeCatStatus($_POST);
        // }
        // else if($_POST["endurl"]=="changeSubCategoryStatus"){
        //     //subCategory.php
        //     changeSubCategoryStatus($_POST);
        // }
        // else if($_POST["endurl"]=="getSettlement"){
        //     //Settlement.php
        //     getSettlement($_POST);
        // }
        // else if($_POST["endurl"]=="deleteCategory"){
        //     //category.php
        //     deleteCategory($_POST);
        // }

        else if($_POST["endurl"]=="getOrders"){
            //orders.php
            getOrders($_POST);
        }
        else if($_POST["endurl"]=="getFoodOrders"){
            //orders.php
            getFoodOrders($_POST);
        }
        else if($_POST["endurl"]=="changeOrderStatus"){
            //orders.php
            changeOrderStatus($_POST);
        }
        else if($_POST["endurl"]=="changeFoodOrderStatus"){
            //orders.php
            changeFoodOrderStatus($_POST);
        }
        else if($_POST["endurl"]=="getDeliveredOrders"){
            //orders.php
            getDeliveredOrders($_POST);
        }
        else if($_POST["endurl"]=="acceptOrder"){
            //orders.php
            acceptOrder($_POST);
        }
        else if($_POST["endurl"]=="getDeliveredFoodOrders"){
            //orders.php
            getDeliveredFoodOrders($_POST);
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
    
    

    function checkHeaders($headers,$userRole){
        if(!isset($headers['Authorization'])){
            $array = array(
                'msg' => UNAUTH,
            );
            http_response_code(NOT_FOUND_STATUS_CODE);
            echo json_encode($array);
            exit;
        }
    }
?>
