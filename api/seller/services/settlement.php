<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    


    // add address
    function getSettlement($POST){
      $order_id = $POST['order_id'];
      $product_id = $POST['product_id'];

      $settlementQuery = "SELECT s.*,pro.*,pro.id as prod_id, s.gst as sell_gst, s.discount as discountamt from settlement as s JOIN products as pro on pro.id=s.product_id where s.order_id='$order_id' and s.product_id=$product_id";
      $settlementData = select($settlementQuery);

      $array = array(
        'msg' => "Settlement Fetched..",
        'data' => $settlementData
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getCharges(){
      $getChargeQuery = "SELECT * from charges where program_id=1";
      $chargeData = select($getChargeQuery);

      $array = array(
        'msg' => "Charges Fetched..",
        'data' => $chargeData
      );
      http_response_code(200);
      echo json_encode($array);

    }
?>