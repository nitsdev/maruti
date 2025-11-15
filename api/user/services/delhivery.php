<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //checkDeliveryAvailable
    function checkDeliveryAvailable($POST){
      $pin = $POST['pin'];
      $payType = $POST['payType'];

      // if($payType == 1){
      //   $curl = curl_init();

      //   curl_setopt_array($curl, array(
      //     CURLOPT_URL => 'https://track.delhivery.com/c/api/pin-codes/json/?filter_codes='.$pin,
      //     CURLOPT_RETURNTRANSFER => true,
      //     CURLOPT_ENCODING => '',
      //     CURLOPT_MAXREDIRS => 10,
      //     CURLOPT_TIMEOUT => 0,
      //     CURLOPT_FOLLOWLOCATION => true,
      //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //     CURLOPT_CUSTOMREQUEST => 'GET',
      //     CURLOPT_HTTPHEADER => array(
      //       'Content-Type: application/json',
      //       'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
      //     ),
      //   ));

      //   $response = curl_exec($curl);

      //   curl_close($curl);

      //   $array = array(
      //     'msg' => "Fetched..",
      //     'data' => json_decode($response)
      //   );
      // }else{
      //   if($pin == 496118){
      //     $array = array(
      //       'msg' => "Fetched..",
      //       'data' => 1
      //     );
      //   }else{
      //     $array = array(
      //       'msg' => "Fetched..",
      //       'data' => 0
      //     );
      //   }
      //}

      $isDelPincodeQuery = "SELECT * FROM is_delivered_pincode WHERE pincode='$pin'";
      $isDelPincode = select($isDelPincodeQuery);

      if(!isset($isDelPincode['shop']) || $isDelPincode['shop'] == 0){
        $array = array(
          'msg' => "Fetched..",
          'data' => 0
        );
      }else{
        $array = array(
          'msg' => "Fetched..",
          'data' => 1
        );
      }

      
      http_response_code(200);
      echo json_encode($array);
    }
?>