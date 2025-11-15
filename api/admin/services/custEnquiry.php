<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');
    
//getEnquiry

function getEnquiry($POST){
    $status = $POST['status'];
    $where="";
    if($status!="*"){
      $where="where status='$status'";
    }
    $query = "select * from customer_enquiry $where";
    $query_res = selectMultiple($query);

    $array = array(
        'msg' => "Enquiry Fetched..",
        'data' => $query_res
    );
    http_response_code(200);      
    echo json_encode($array);
}

// deleteEnquiry
function deleteEnquiry($POST){
    $id = $POST['id'];
    $deleteEnquiryQuery = "DELETE from customer_enquiry where id = $id";
    delete($deleteEnquiryQuery);

    $array = array(
      'msg' => "Enquiry Deleted.."
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
       
}

function getSubEmail($POST){
  $query = "select * from newsletter";
  $query_res = selectMultiple($query);

  $array = array(
      'msg' => "Subscribed user Emails Fetched..",
      'data' => $query_res
  );
  http_response_code(200);      
  echo json_encode($array);
}

function getDashboardDetail($POST){
  $sucOrdquery = "select COUNT(id) as total_success from orders where status=1";
  $sucOrdquery_res = select($sucOrdquery);

  $failOrdquery = "select COUNT(id) as total_failed from orders where status=2";
  $failOrdquery_res = select($failOrdquery);

  $sucFoodOrdquery = "select COUNT(id) as total_success from food_orders where status=1";
  $sucFoodOrdquery_res = select($sucFoodOrdquery);

  $failFoodOrdquery = "select COUNT(id) as total_failed from food_orders where status=2";
  $failFoodOrdquery_res = select($failFoodOrdquery);

  $sellerQuery = "select COUNT(id) as total_seller from users where user_role=2";
  $sellerQuery_res = select($sellerQuery);

  $userQuery = "select COUNT(id) as total_user from users where user_role=3";
  $userQuery_res = select($userQuery);

  $array = array(
      'msg' => "Fetched..",
      'data' => $sucOrdquery_res,
      'failData' => $failOrdquery_res,
      'foodData' => $sucFoodOrdquery_res,
      'failFoodData' => $failFoodOrdquery_res,
      'seller' => $sellerQuery_res,
      'user' => $userQuery_res
  );
  http_response_code(200);      
  echo json_encode($array);
}

?>