<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    


    // get sellers
    function getSellers($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="and status='$status'";
      }
      $query = "select * from users where user_role=2 $where and user_type=1 order by created_at DESC";
      $sellerData = selectMultiple($query);

       $getDocsQuery = "SELECT * from seller_ids";
       $getDocsData = selectmultiple($getDocsQuery);

      $array = array(
          'msg' => "Seller Details Successfully..",
          'data' => $sellerData,
          'docs' => $getDocsData
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    //changeSellersStatus
    function changeSellerStatus($POST){
      $currDate = date('Y-m-d H:i:s');

      $id = $POST['id'];
      $status = $POST['status'];
      $resMsg = "";
  
      if($status == 0){
        $newStatus = 1;
        $resMsg = "Seller Activated";
        sendActivationEmail($id);
      }else if($status == 1){
        $newStatus = 0;
        $resMsg = "Seller De-Activated";
      }else if($status == 2){
        $newStatus = 1;
        $resMsg = "Seller Approved";
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

     function sendActivationEmail($seller_id){
      //get seller details
      $getDetailsQuery = "SELECT first_name,last_name,email from users where id=$seller_id";
      $sellerData = select($getDetailsQuery);

      //Send Email       
      $toEmail = $sellerData['email'];
      $subject = "Maruti Studio | Account Activated";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=9";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $sellerData['first_name'].' '.$sellerData['last_name'];
      $emailBody=str_replace("[Name]",$fullName,$emailBody);

      // Always set content-type when sending HTML email
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

      // More headers
      $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>';
     
      //echo $mailHeaders;
      if (mail($toEmail,$subject,$emailBody,$headers)) {
          $message2 = 'Sent successfully';
          //echo $message2;
      }

      // if (mail("info@shuttleshop.in", $adminSub,$adminBody,$headers)) {
      //     $message = 'Sent successfully';
      // }
      //Email ended
    }

    // get foodsellers
    function getFoodSellers($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="and status='$status'";
      }
      $query = "select * from users where user_role=2 $where and user_type=2 order by created_at DESC";
      $sellerData = selectMultiple($query);

       $getDocsQuery = "SELECT * from seller_ids";
       $getDocsData = selectmultiple($getDocsQuery);

      $array = array(
          'msg' => "Seller Details Successfully..",
          'data' => $sellerData,
          'docs' => $getDocsData
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }
?>