<?php
    // include('../healthcheck.php');
    include('./services/authService.php');
    include('./services/product.php');
    include('./services/category.php');
    include('./services/subCategory.php');
    include('./services/coupon.php');    
    include('./services/custEnquiry.php');
    include('./services/charges.php');  
    include('./services/users.php');   
    include('./services/agent.php');       
    include('./services/homeImg.php');        
    include('./services/seller.php'); 

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $headers= apache_request_headers();

    $jwttoken="";
    $userRole = 1;
    $programId = 1;

    if(isset($headers['Authorization'])){
        $jwttoken = $headers['Authorization'];
    }
    
    if($_POST["endurl"]!="login"){
        if(!isset($headers['Authorization'])){
                $array = array(
                'msg' => UNAUTH,
            );
            http_response_code(NOT_FOUND_STATUS_CODE);
            echo json_encode($array);
            exit;
        }
    }

    if($jwttoken){
        // jwt.php
        validateToken(explode(" ", $jwttoken)[1]);
    }
    //Authorise user by role
    if($httpMethod == "POST" && $_POST["endurl"]!="login"){
        //jwt.php
        authenticateUser($GLOBALS['jwt_token'],$userRole,$programId);
    }

    if($httpMethod == "GET"){
        if($_REQUEST["endurl"]==""){
           
        }
        else{
            $array = array(
                'msg' => "API Not Found..",
            );
            http_response_code(NOT_FOUND_STATUS_CODE);
            echo json_encode($_POST);
            exit;
        }
    }else if($httpMethod == "POST"){
        if($_POST["endurl"]=="login"){
            //Call Login function present in authService.php
            login($_POST);
        }
        else if($_POST["endurl"]=="userDetails"){
            $array = array(
                'msg' => "User Details..",
                'data' => array(
                    'test'=>"test"
                )
            );
            http_response_code(200);
            echo json_encode($array);
            exit;
        }
        else if($_POST["endurl"]=="changeStatus"){
            //Call changeStatus function present in product.php
            changeStatus($_POST);
        }
        else if($_POST["endurl"]=="add_category"){
            //Call add_category function present in category.php
            addCategory($_POST);
        }
        else if($_POST["endurl"]=="getCategory"){
            //Call getCategory function present in category.php
            getCategory($_POST);
        }
        else if($_POST["endurl"]=="getUniqueCategory"){
            //Call getUniqueCategory function present in category.php
            getUniqueCategory($_POST);
        }
        else if($_POST["endurl"]=="editCategory"){
            //Call editCategory function present in category.php
            editCategory($_POST);
        }
        else if($_POST["endurl"]=="deleteCategory"){
            //Call deleteCategory function present in category.php
            deleteCategory($_POST);
        }
        else if($_POST["endurl"]=="changeCategoryStatus"){
            //Call changeCategoryStatus function present in category.php
            changeCategoryStatus($_POST);
        }
        else if($_POST["endurl"]=="checkedFeaturedStatus"){
            //Call checkedFeaturedStatus function present in category.php
            checkedFeaturedStatus($_POST);
        }
        else if($_POST["endurl"]=="getCoupon"){
            //Call getCoupon function present in category.php
            getCoupon($_POST);
        }
        else if($_POST["endurl"]=="getUniqueCoupon"){
            //Call getUniqueCoupon function present in category.php
            getUniqueCoupon($_POST);
        }
        else if($_POST["endurl"]=="editCoupon"){
            //Call editCoupon function present in category.php
            editCoupon($_POST);
        }
        else if($_POST["endurl"]=="deleteCoupon"){
            //Call deleteCoupon function present in category.php
            deleteCoupon($_POST);
        }
        else if($_POST["endurl"]=="getSubCategory"){
            //Call getSubCategory function present in subcategory.php
            getSubCategory($_POST);
        }
        else if($_POST["endurl"]=="deleteSubcategory"){
            //Call getSubCategory function present in subcategory.php
            deleteSubcategory($_POST);
        }
        else if($_POST["endurl"]=="changeSubCategoryStatus"){
            //Call changeSubCategoryStatus function present in subcategory.php
            changeSubCategoryStatus($_POST);
        }
        else if($_POST["endurl"]=="addSubCategory"){
            //Call addSubCategory function present in subcategory.php
            addSubCategory($_POST);
        }
        else if($_POST["endurl"]=="getAllCategory"){
            //Call getAllCategory function present in subcategory.php
            getAllCategory($_POST);
        }
        else if($_POST["endurl"]=="getUniqueSubCategory"){
            //Call getUniqueSubCategory function present in subcategory.php
            getUniqueSubCategory($_POST);
        }
        else if($_POST["endurl"]=="editL1Category"){
            //Call editL1Category function present in subcategory.php
            editL1Category($_POST);
        }
        else if($_POST["endurl"]=="addProduct"){
            //Call addProduct function present in product.php
            addProduct($_POST);
        }
        else if($_POST["endurl"]=="editProduct"){
            //Call editProduct function present in product.php
            editProduct($_POST);
        }
        else if($_POST["endurl"]=="getProduct"){
            //Call getProduct function present in product.php
            getProduct($_POST);
        }
        else if($_POST["endurl"]=="changeProductStatus"){
            //Call changeProductStatus function present in product.php
            changeProductStatus($_POST);
        }
        else if($_POST["endurl"]=="getUniqueProduct"){
            //Call getUniqueProduct function present in product.php
            getUniqueProduct($_POST);
        }
        else if($_POST["endurl"]=="getUsers"){
            //Call getUsers function present in users.php
            getUsers($_POST);
        }
        else if($_POST["endurl"]=="changeUserStatus"){
            //Call changeUserStatus function present in users.php
            changeUserStatus($_POST);
        }
        else if($_POST["endurl"]=="getAgents"){
            //Call getAgents function present in agent.php
            getAgents($_POST);
        }
        else if($_POST["endurl"]=="changeAgentStatus"){
            //Call changeAgentStatus function present in agent.php
            changeAgentStatus($_POST);
        }
        else if($_POST["endurl"]=="addCharges"){
            //Call addCharges function present in charges.php
            addCharges($_POST);
        }
        else if($_POST["endurl"]=="getCharges"){
            //Call getCharges function present in charges.php
            getCharges($_POST);
        }
        else if($_POST["endurl"]=="updateHomeImage"){
            //Call homeImg function present in homeImg.php
            updateHomeImage($_POST);
        }
        else if($_POST["endurl"]=="updateofferImage"){
            //Call homeImg function present in homeImg.php
            updateofferImage($_POST);
        }
        else if($_POST["endurl"]=="updateBrandImage"){
            //Call homeImg function present in homeImg.php
            updateBrandImage($_POST);
        }
        else if($_POST["endurl"]=="getSellers"){
            //Call getSellers function present in seller.php
            getSellers($_POST);
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
