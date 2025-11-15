<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');


  //get orders by user id
  function getOrders(){
    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "SELECT * FROM orders as ord WHERE ord.status = 1 and  is_delivered != 1 ORDER BY created_at desc limit 50";
    $orderData = selectMultiple($query);
    
    $count=0;

    foreach ($orderData as $ord) {
      $ownerData = [];
      $imgData = [];
      $deliveryHist = [];

      $product_json = json_decode($ord['product_json']);
      $orderid = $ord['order_id'];

      $innercount=1;
      foreach ($product_json as $pro) {
        $ownerId = $pro->product_owner;
        $prodId = $pro->id;
        $sub_ord_id = $orderid."_".$innercount;

        $getProductOwner = "SELECT * from addresses where user_id = $ownerId";
        $owner = select($getProductOwner);

        $getImages = "SELECT * from product_images where product_id = $prodId";
        $img = selectMultiple($getImages);

        $getDeliveryHistory = "SELECT * from delivery_history where order_id = '$orderid' and sub_order_id='$sub_ord_id' ORDER BY created_at DESC";
        $deliveryHistory = selectMultiple($getDeliveryHistory);

        array_push($ownerData,$owner);
        array_push($imgData,$img);
        array_push($deliveryHist,$deliveryHistory);

        $innercount++;
      }

      $orderData[$count]['ownerData'] = $ownerData;
      $orderData[$count]['imgData'] = $imgData;
      $orderData[$count]['deliveryData'] = $deliveryHist;

      $count++;
    }

    $array = array(
      'msg' => "Orders fetched.",
      'data' => $orderData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
    
  }

  function changeOrderStatus($POST){
    $orderId = $POST['orderId'];
    $subOrderId = $POST['subOrderId'];
    $statusId = $POST['statusId'];
    $comment = $POST['comment'];
    $prodId = $POST['prodId'];

    $created_at=date('Y-m-d H:i:s');
    $user_id=$GLOBALS['jwt_token']->user_id;
    $cols =  array("order_id", "sub_order_id", "prod_id", "status", "comment", "created_by");
    $values =  array($orderId, $subOrderId, $prodId, $statusId, $comment, $user_id);
    $table_name = "delivery_history";
    insert($cols, $values,$table_name);

    if($statusId == 6){
      // Total Item count for order
      $totalUniqueItemQuery = "SELECT id from delivery_history where status = 1 and order_id='$orderId'";
      $totalUniqueItem = selectMultiple($totalUniqueItemQuery);

      //Get all delivery hist which have status 6
      $getStatusOfDelivered = "SELECT id from delivery_history where status = 6 and order_id='$orderId'";
      $getStatusOfDeliveredData = selectMultiple($getStatusOfDelivered);

      if(count($totalUniqueItem) == count($getStatusOfDeliveredData)){
        // Update is_delived in order table
        $updateIsDelivered = "UPDATE orders set is_delivered=1, delivery_date='$created_at' where order_id='$orderId'";
        update($updateIsDelivered);
      }

      $cols =  array("order_id", "product_id");
      $values =  array($orderId, $prodId);
      $table_name = "delivered_products";
      insert($cols, $values,$table_name);
    }

    $array = array(
      'msg' => "Status Changed Successfully.",
      'data' => $POST
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  //Get delivered product
  function getDeliveredOrders(){
    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "SELECT * FROM orders as ord WHERE ord.status = 1 and is_delivered = 1 ORDER BY created_at desc limit 50";
    $orderData = selectMultiple($query);
    
    $count=0;

    foreach ($orderData as $ord) {
      $ownerData = [];
      $imgData = [];
      $deliveryHist = [];

      $product_json = json_decode($ord['product_json']);
      $orderid = $ord['order_id'];

      $innercount=1;
      foreach ($product_json as $pro) {
        $ownerId = $pro->product_owner;
        $prodId = $pro->id;
        $sub_ord_id = $orderid."_".$innercount;

        $getProductOwner = "SELECT * from addresses where user_id = $ownerId";
        $owner = select($getProductOwner);

        $getImages = "SELECT * from product_images where product_id = $prodId";
        $img = selectMultiple($getImages);

        $getDeliveryHistory = "SELECT * from delivery_history where order_id = '$orderid' and sub_order_id='$sub_ord_id' ORDER BY created_at DESC";
        $deliveryHistory = selectMultiple($getDeliveryHistory);

        array_push($ownerData,$owner);
        array_push($imgData,$img);
        array_push($deliveryHist,$deliveryHistory);

        $innercount++;
      }

      $orderData[$count]['ownerData'] = $ownerData;
      $orderData[$count]['imgData'] = $imgData;
      $orderData[$count]['deliveryData'] = $deliveryHist;

      $count++;
    }

    $array = array(
      'msg' => "Orders fetched.",
      'data' => $orderData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
    
  }

   //get orders details for food
  function getFoodOrders(){
    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "SELECT * FROM food_orders WHERE status = 1 and  is_delivered != 1 
        and (delivery_agent_id = $user_id or delivery_agent_id IS NULL or delivery_agent_id = '') 
        ORDER BY created_at desc limit 50";

    $orderData = selectMultiple($query);
    
    $returnOrderData = [];

    // print_r($query_res);
    $cnt = 0;

    foreach ($orderData as $ord) {
      $order_id = $ord['order_id'];
      $product_json = json_decode($ord['product_json'],true);
      $sellerId = $product_json[0]['shop_id'];
      $delivery_agent_id = $ord['delivery_agent_id'];

      // Check if order accepted by seller
      $delStatusQuery = "SELECT * from delivery_history where status =1 and order_id='$order_id' order by created_at DESC";
      $delStatus = select($delStatusQuery);      

      //Check if agent pin and seller pincode same
      $agentDataQuery = "SELECT * from addresses where user_id='$user_id'";
      $agentData = select($agentDataQuery);

      $sellerDataQuery = "SELECT * from addresses where user_id='$sellerId'";
      $sellerData = select($sellerDataQuery);
      
      
      if(isset($delStatus) && $delStatus['status'] && ($sellerData['pincode'] == $agentData['pincode'])){

        $returnOrderData[$cnt] = $ord;  
        
        // Check if order accepted by seller
        $delStatusQuery = "SELECT * from delivery_history where order_id='$order_id' order by created_at DESC";
        $delStatus = select($delStatusQuery);      

        $returnOrderData[$cnt]['delStatus'] = $delStatus; 
        $returnOrderData[$cnt]['agentData'] = $agentData; 
        $returnOrderData[$cnt]['sellerData'] = $sellerData; 
        $cnt++;

      }

    }

    $array = array(
      'msg' => "Orders fetched.",
      'data' => $returnOrderData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;    
  }

  // acceptOrder
  function acceptOrder($POST){
    $orderId = $POST['orderId'];

    // Check if already accepted
    $getOrderDataQuery = "SELECT delivery_agent_id, status from food_orders WHERE order_id = '$orderId'";
    $orderData = select($getOrderDataQuery);

    if(isset($orderData) && $orderData['delivery_agent_id']){
      $array = array(
        'msg' => "Someone already accepted this order for delivery."
      );
      http_response_code(501);
      echo json_encode($array);
      exit;
    }else if(isset($orderData) && $orderData['status'] !=1){
      $array = array(
        'msg' => "Orders cancelled, You cannot accept this order."
      );
      http_response_code(501);
      echo json_encode($array);
      exit;
    }else{
      $user_id=$GLOBALS['jwt_token']->user_id;
      $updateDelAgentIdQuery = "UPDATE food_orders SET delivery_agent_id=$user_id WHERE order_id = '$orderId'";
      update($updateDelAgentIdQuery);

      $array = array(
        'msg' => "Orders assign to you, please reach restaurant on time and pickup the order for delivery..",
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }
  }

  // Change order status 
  function changeFoodOrderStatus($POST){
    $orderId = $POST['orderId'];
    $ordStatus = $POST['ordStatus'];

    $user_id=$GLOBALS['jwt_token']->user_id;
    $cols =  array("order_id", "sub_order_id", "prod_id", "status", "comment", "created_by");
    $values =  array($orderId, "", "", $ordStatus, "", $user_id);
    $table_name = "delivery_history";
    insert($cols, $values,$table_name);

    $query = "SELECT * FROM food_orders as ord WHERE ord.status = 1 and order_id = '$orderId'";
    $ord = select($query);

    $product_json = json_decode($ord['product_json'],true);
    $sellerId = $product_json[0]['shop_id'];
    $shippingJson = json_decode($ord['shipping_json'],true);
    $userEmail =  $shippingJson[0]['email'];

    // Get seller email
    $sellerEmailQuery = "SELECT * FROM addresses WHERE user_id = $sellerId";
    $sellerEmail = select($sellerEmailQuery);
    $shop_name = $sellerEmail['shop_name'];
    $user_name = $shippingJson[0]['first_name']." ".$shippingJson[0]['last_name'];

    if($ordStatus == 4){
      // is_delivered
      $currTime = date('Y-m-d H:i:s');

      $updateDeliveryStatusQuery = "UPDATE food_orders SET is_delivered=1, delivery_date='$currTime' WHERE order_id = '$orderId'";
      update($updateDeliveryStatusQuery);

      //Send Order Delivered Email to Seller/ User/ Admin      
      sendDeliveryEmail($sellerEmail['email'], $orderId);

      sendDelveryEmailToUser($userEmail, $orderId, $shop_name, $user_name);
    }

    $array = array(
      'msg' => "Order Status Changed..",
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function sendDeliveryEmail($email, $orderId){
    //Send Email       
    $toEmail = $email;
    $subject = "Shuttleshop Food | Order Delivered";
    $adminSub = "Order Delivered";
    
    $getEmailtempQuery = "SELECT email_body from email_template where id=16";
    $getEmailtempData = select($getEmailtempQuery);
    $emailBody = $getEmailtempData['email_body'];
    //Replace
    $emailBody=str_replace("[OrderId]",$orderId,$emailBody);

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
    //Email ended
  }

  function sendDelveryEmailToUser($email, $orderId, $shop_name, $user_name){
    //Send Email       
    $toEmail = $email;
    $subject = "Shuttleshop Food | Order Delivered";
    $adminSub = "Order Id ". $orderId  ." delivered on time";
    
    $getEmailtempQuery = "SELECT email_body from email_template where id=17";
    $getEmailtempData = select($getEmailtempQuery);
    $emailBody = $getEmailtempData['email_body'];
    //Replace
    $emailBody=str_replace("[UserName]",$user_name,$emailBody);
    $emailBody=str_replace("[shopName]",$shop_name,$emailBody);
    $emailBody=str_replace("[OrderId]",$orderId,$emailBody);

    // Always set content-type when sending HTML email
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

    $fromEmail = "info@shuttleshop.in";
    // More headers
    $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>'. "\r\n";
    $headers .= "Reply-To: $fromEmail". "\r\n";

                
    //echo $mailHeaders;
    if (mail($toEmail,$subject,$emailBody,$headers)) {
        $message2 = 'Sent successfully';
        //echo $message2;
    }
    //Email ended
  }


  //Get delivered food product
  function getDeliveredFoodOrders(){
    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "SELECT * FROM food_orders WHERE status = 1 and  is_delivered = 1 
        and delivery_agent_id = $user_id
        ORDER BY created_at desc limit 50";

    $orderData = selectMultiple($query);
    
    $returnOrderData = [];

    // print_r($query_res);
    $cnt = 0;

    foreach ($orderData as $ord) {
      $order_id = $ord['order_id'];
      $product_json = json_decode($ord['product_json'],true);
      $sellerId = $product_json[0]['shop_id'];
      $delivery_agent_id = $ord['delivery_agent_id'];

      // Check if order accepted by seller
      $delStatusQuery = "SELECT * from delivery_history where status =1 and order_id='$order_id' order by created_at DESC";
      $delStatus = select($delStatusQuery);      

      //Check if agent pin and seller pincode same
      $agentDataQuery = "SELECT * from addresses where user_id='$user_id'";
      $agentData = select($agentDataQuery);

      $sellerDataQuery = "SELECT * from addresses where user_id='$sellerId'";
      $sellerData = select($sellerDataQuery);
      
      
      if(isset($delStatus) && $delStatus['status'] && ($sellerData['pincode'] == $agentData['pincode'])){

        $returnOrderData[$cnt] = $ord;  
        
        // Check if order accepted by seller
        $delStatusQuery = "SELECT * from delivery_history where order_id='$order_id' order by created_at DESC";
        $delStatus = select($delStatusQuery);      

        $returnOrderData[$cnt]['delStatus'] = $delStatus; 
        $returnOrderData[$cnt]['agentData'] = $agentData; 
        $returnOrderData[$cnt]['sellerData'] = $sellerData; 
        $cnt++;

      }

    }

    $array = array(
      'msg' => "Orders fetched.",
      'data' => $returnOrderData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;    
  }
?>