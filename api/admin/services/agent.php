<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    


    // get sellers
    function getAgents($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="and status='$status'";
      }
      $query = "select * from users where user_role=4 $where order by created_at DESC";
      $sellerData = selectMultiple($query);

      $getDocsQuery = "SELECT * from seller_ids";
      $getDocsData = selectmultiple($getDocsQuery);

      $array = array(
          'msg' => "Agent Details Successfully..",
          'data' => $sellerData,
          'docs' => $getDocsData
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }



    //changeSellersStatus
    function changeAgentStatus($POST){
      $currDate = date('Y-m-d H:i:s');

      $id = $POST['id'];
      $status = $POST['status'];
      $resMsg = "";
  
      if($status == 0){
        $newStatus = 1;
        $resMsg = "Agent Activated";
        // sendActivationEmail($id);
      }else if($status == 1){
        $newStatus = 0;
        $resMsg = "Agent De-Activated";
      }else if($status == 2){
        $newStatus = 1;
        $resMsg = "Agent Approved";
      }else{
        $newStatus = 0;
        $resMsg = "Something went wrong";
      }
  
      $statusQuery = "UPDATE users SET status = $newStatus,updated_at = '$currDate' where id=$id";
      update($statusQuery);
  
      $array = array(
        'msg' => $resMsg
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

?>