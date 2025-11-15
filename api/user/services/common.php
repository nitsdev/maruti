<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function getCountry(){
      $query = "select * from countries";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Country Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getStateByCountry($POST){
      $country_id = $POST["country_id"];
      $query = "select * from states where country_id=$country_id";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "State Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getCityByState($POST){
      $state_id = $POST["state_id"];
      $query = "select * from cities where state_id=$state_id";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "City Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>