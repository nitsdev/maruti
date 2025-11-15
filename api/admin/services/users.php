<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');
    

    // get users
    function getUsers($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="and status='$status'";
      }
      $query = "select * from users where user_role=3 $where order by created_at DESC";

      $usersData = selectMultiple($query);

      $array = array(
          'msg' => "Users Details fetched Successfully..",
          'data' => $usersData
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    //changeUserStatus
   function changeUserStatus($POST){
    $id = $POST['id'];
    $status = $POST['status'];
    $resMsg = "";

    if($status == 0){
      $newStatus = 1;
      $resMsg = "User Activated";
    }else if($status == 1){
      $newStatus = 0;
      $resMsg = "User De-Activated";
    }else{
      $newStatus = 0;
    }

    $currDate = date('Y-m-d H:i:s');
    $statusQuery = "UPDATE users SET status = $newStatus, updated_at = '$currDate' where id=$id";
    update($statusQuery);

    $array = array(
      'msg' => $resMsg
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
   }
?>