<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

  
    function getSubCategory($POST){
      $status = $POST['status'];
      
      if(isset($POST['level']))
        $level = $POST['level'];
      else  
        $level = "";

      if(isset($POST['categoryid']))
        $categoryid = $POST['categoryid'];
      else  
        $categoryid = "";

      $query = "select * from sub_category where status='$status' and is_deleted=1 and pgm_type=1";
      if($level){
        $query .= " and level=$level";
      }
      if($categoryid){
        $query .= " and category_id=$categoryid";
      }

      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Sub Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>