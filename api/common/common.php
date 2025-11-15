<?php
//Check if product id is exist in system
  function checkProductExist($query_res,$POST){
    if(!$query_res){
      $array = array(
        'msg' => "Product does not exist in system or product is inactive..",
        'data' => $POST
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    }
  }

  //Check seller can update product
  function checkSellerHaveAccessProduct($query_res,$POST,$user_id){
    if($query_res['shop_id'] != $user_id){
      $array = array(
        'msg' => "You do not access to view product..",
        'data' => $POST
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    }
  }

?>