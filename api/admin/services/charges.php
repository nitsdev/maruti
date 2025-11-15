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

    //addCharges
    function addCharges($POST){
      $buyer_gst = $POST["buyer_gst"];
      $delivery_charge_type = $POST["delivery_charge_type"];
      $delivery_charge = $POST["delivery_charge"];
      $id = $POST["id"];

      if(isset($id) && $id == 0 ){
        $cols =  array("buyer_gst","delivery_charge_type", "delivery_charge");
        $values =  array($buyer_gst,$delivery_charge_type,$delivery_charge);
        $table_name = "charges";
        $insData = insert($cols, $values,$table_name);

        $array = array(
          'msg' => "Charges Added Successfully",
          'data' => $POST,
        );
        http_response_code(200);
        echo json_encode($array);
      }else{
        $updateQuery = "UPDATE charges SET buyer_gst=$buyer_gst, delivery_charge_type=$delivery_charge_type, delivery_charge=$delivery_charge where id=$id";
        update($updateQuery);

        $array = array(
          'msg' => "Charges Updated Successfully",
          'data' => $POST,
        );
        http_response_code(200);
        echo json_encode($array);
      }
    }

    function getCharges($POST){
      $getCharges = "SELECT * from charges";
      $chargesData = selectMultiple($getCharges);

      $array = array(
        'msg' => "Charges Fetched",
        'data' => $POST,
      );
      http_response_code(200);
      echo json_encode($chargesData);
    }
?>