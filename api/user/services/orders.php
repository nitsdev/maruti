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


    //Get orders by user id
    function getOrders($POST){
      $from_date = $POST["from_date"];
      $to_date = $POST["to_date"];

      $user_id=$GLOBALS['jwt_token']->user_id;
      $query = "SELECT * FROM `orders` WHERE user_id= '$user_id' and DATE(created_at) >= '$from_date' and DATE(created_at) <= '$to_date' ORDER BY created_at desc";
      $query_res = selectMultiple($query);

      $count =0;
      foreach($query_res as $ord){
        $order_id = $ord['order_id'];
        $deleveredProductQuery = "SELECT * from delivered_products where order_id= '$order_id'";
        $deleveredProductQueryRes = selectMultiple($deleveredProductQuery);

        $query_res[$count]['deliveredProdCount'] = count($deleveredProductQueryRes);

        $count++;
      }

      

      $array = array(
        'msg' => "Orders fetched.",
        'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //cancelOrder
    function cancelOrder($POST){
      $orderId = $POST["orderId"];

      $paymentQuery = "SELECT * from transactions where order_id='$orderId'";
      $paymentData = select($paymentQuery);

      if($paymentData["merchantTransactionId"]){
        $payType = 1;
      }else{
        $payType = 0;
      }

      $selectWayBillQuery = "SELECT waybills from orders where order_id='$orderId'";
      $wayBillData = select($selectWayBillQuery);
      
      $wayBills = isset($wayBillData) && $wayBillData['waybills'] ? explode(",",$wayBillData['waybills']) : "";

      if($payType == 1){
        // foreach($wayBills as $wayb){
        //   $curl = curl_init();

        //   $data = '{"waybill":'.$wayb.',"cancellation":true}';

        //   curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://track.delhivery.com/api/p/edit',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS =>$data,
        //     CURLOPT_HTTPHEADER => array(
        //       'Content-Type: application/json',
        //       'Accept: application/json',
        //       'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
        //     ),
        //   ));

        //   $response = curl_exec($curl);

        //   curl_close($curl);
        // }
      }

      //Cancel order in table

      $user_id=$GLOBALS['jwt_token']->user_id;
      $updated_at=date('Y-m-d H:i:s');
      $updateStatusQuery = "UPDATE orders SET status=4,order_cancelled_by = '$user_id', updated_at = '$updated_at' where order_id='$orderId'";
      update($updateStatusQuery);

      if($payType == 1){
        //Refund
        $refundStatus = refund($POST);

        if(json_decode($refundStatus)->state == 'PENDING'){
          $refundid = json_decode($refundStatus)->refundId;
          $refundUpdate = "UPDATE orders set refund_status=1, refunded_trans_id = '$refundid' where order_id='$orderId'";
          update($refundUpdate);

          $user_id=$GLOBALS['jwt_token']->user_id;
          $currDateTime = date('Y-m-d H:i:s');

          $refundUpdate = "UPDATE transactions set refund_status=1, refund_id = '$refundid', refunded_by=$user_id, refunded_at='$currDateTime', refund_details='$refundStatus' where order_id='$orderId'";
          update($refundUpdate);
        }
      }

      sendOrderCancelEmail($orderId);

      $array = array(
        'msg' => "Orders cancelled.",
        'data' => ""
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getOrderById($POST){
      $order_id=$POST['order_id'];
      if(isset($POST['owner'])){
        $owner=$POST['owner'];
        if($owner == 0){
          $owner = ADMIN_ID;
        }
      }
      
      $query = "SELECT * FROM `orders` WHERE order_id= '$order_id'";
      $query_res = select($query);

      $deleveredProductQuery = "SELECT * from delivered_products where order_id= '$order_id'";
      $deleveredProductQueryRes = selectMultiple($deleveredProductQuery);

      $sellerDetails="";
      if(isset($POST['owner'])){
        // Get seller details
        $getSellerByIdQuery = "SELECT addr.*,c.name as country_name,s.name as state_name,ct.name as city_name FROM addresses as addr LEFT JOIN countries as c ON c.id=addr.country_id LEFT JOIN states as s ON s.id=addr.state_id LEFT JOIN cities as ct ON ct.id=addr.city_id where user_id=$owner";
        // echo $getSellerByIdQuery;
        $sellerDetails = select($getSellerByIdQuery);
      }

      $array = array(
        'msg' => "Orders fetched.",
        'data' => $query_res,
        'sellerDetails'=>$sellerDetails,
        'deleveredProductQueryRes'=>$deleveredProductQueryRes
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function sendOrderSuccessEmail($POST){
      $order_id = $POST['order_id'];
      $user_id=$GLOBALS['jwt_token']->user_id;

      // Get user email
      $getEmailQuery = "SELECT email FROM `users` where id = $user_id";
      $getEmailData = select($getEmailQuery);

      $loggedInUser = $POST['loggedInUser'];
      //Send Email       
      $toEmail = $getEmailData['email'];
      $subject = "Maruti Studio | Order Placed";
      $adminSub = "New Order On Shuttleshop";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=3";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $loggedInUser;
      $emailBody=str_replace("[Name]",$fullName,$emailBody);
      $emailBody=str_replace("[OrderId]",$order_id,$emailBody);
      $emailBody=str_replace("[invoicelink]","https://shuttleshop.in/order.php",$emailBody);

      // $getEmailtempQuery = "SELECT email_body from email_template where id=2";
      // $getEmailtempData = select($getEmailtempQuery);
      // $adminBody = $getEmailtempData['email_body'];
      // //Replace
      // $adminBody=str_replace("[user]",$fullName,$adminBody);
      // $adminBody=str_replace("[user-email]",$toEmail,$adminBody);

      // Always set content-type when sending HTML email
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

      $fromEmail = "info@shuttleshop.in";
      // More headers
      $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>'. "\r\n";
      $headers .= "Reply-To: $fromEmail". "\r\n";
      $headers .= "BCC: $fromEmail". "\r\n";

                  
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

    function sendOrderCancelEmail($order_id){
      $user_id=$GLOBALS['jwt_token']->user_id;

      // Get user email
      $getEmailQuery = "SELECT email,first_name,last_name FROM `users` where id = $user_id";
      $getEmailData = select($getEmailQuery);

      $loggedInUser = $getEmailData['first_name']." ".$getEmailData['last_name'];
      //Send Email       
      $toEmail = $getEmailData['email'];
      $subject = "Maruti Studio | Order Cancelled";
      $adminSub = "Order has been cancelled On Shuttleshop";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=4";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $loggedInUser;
      $emailBody=str_replace("[Name]",$fullName,$emailBody);
      $emailBody=str_replace("[OrderId]",$order_id,$emailBody);

      // Always set content-type when sending HTML email
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

       $fromEmail = "info@shuttleshop.in";
      // More headers
      $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>';
      $headers .= "Reply-To: $fromEmail\r\n";
      $headers .= "BCC: $fromEmail\r\n";
     
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
?>