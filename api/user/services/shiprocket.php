<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    function createShipRocket($POST){
      $order_id = $POST['order_id'];
      $payType = $POST['payType'];
      
      $orderQuery = "SELECT * from orders where order_id='$order_id'";
      $orderData = select($orderQuery);

      $productJson = $orderData['product_json'];
      $shippingJson = $orderData['shipping_json'];
      $billingJson = $orderData['billing_json'];
      $cartJson = $orderData['cart_json'];
      $coupnJson = json_decode($orderData['coupon_json'],true);

      // Add into coupon_applied table -
      if(isset($coupnJson)){
        $created_at=date('Y-m-d H:i:s');
        $user_id=$GLOBALS['jwt_token']->user_id;
        $cols =  array("coupon_id", "coupon_code", "user_id", "discount_amt", "created_at");
        $values =  array($coupnJson['id'], $coupnJson['code'], $user_id, $orderData['coupon_discount'], $created_at);
        $table_name = "coupon_applied";
        insert($cols, $values,$table_name);
      }

      //Get shippingdata 
      $shippingJson = json_decode($shippingJson,true);
      $name = $shippingJson[0]['first_name']." ".$shippingJson[0]['last_name'];
      $pin = $shippingJson[0]['pincode'];
      $add = $shippingJson[0]['add_line1'].",".$shippingJson[0]['add_line2'].",landmark-".$shippingJson[0]['landmark'];
      $city_id = $shippingJson[0]['city_id'];
      $state_id = $shippingJson[0]['state_id'];
      $country_id = $shippingJson[0]['country_id'];
      $phone = $shippingJson[0]['mobile'];

      $country = getCountryById($country_id);
      $state = getStateById($state_id);
      $city = getCityById($city_id);

      $waybillArray = "";
      $count=0;
      $pcount=1;
      foreach (json_decode($productJson,true) as $pro) {
        $count++;
        // Generate waybill
        $waybill = generateWayBill();

        if($count==1){
          $waybillArray = $waybill;
        }else{
          $waybillArray = $waybillArray.",".$waybill;
        }

        // Taking product owner / pickup details
        $productOwner = $pro['product_owner'];
        $pro_id = $pro['id'];

        if($productOwner==0){
          $productOwner = $pro['added_by'];
        }
        $quantity = 0;
        foreach (json_decode($cartJson,true) as $cart) {
          if( $pro['id'] == $cart["product_id"]){
            $quantity = $cart["quantity"];
          }
        }

        $getProdOwner = "SELECT * from addresses where user_id=$productOwner";
        $getProdOwnerData = select($getProdOwner);
        $seller_name = $getProdOwnerData['first_name']." ".$getProdOwnerData['last_name'];
        $seller_pin = $getProdOwnerData['pincode'];
        $seller_add = $getProdOwnerData['add_line1'].",".$getProdOwnerData['add_line2'].",landmark-".$getProdOwnerData['landmark'];
        $seller_city_id = $getProdOwnerData['city_id'];
        $seller_state_id = $getProdOwnerData['state_id'];
        $seller_country_id = $getProdOwnerData['country_id'];
        $seller_phone = $getProdOwnerData['mobile'];
        $sellerWHName = $getProdOwnerData['wh_name'];
        $seller_country = getCountryById($seller_country_id);
        $seller_state = getStateById($seller_state_id);
        $seller_city = getCityById($seller_city_id);


        if($payType == 1){
          // Creating delhivery order
          $curl = curl_init();

          $bodyData= 'format=json&data={
            "shipments": [
              {
                "name":"'.$name.'" ,
                "add": "'.$add.'",
                "pin": "'.$pin.'",
                "city": "'.$city.'",
                "state": "'.$state.'",
                "country": "'.$country.'",
                "phone": "'.$phone.'",
                "order": "'.$order_id.'",
                "payment_mode": "Prepaid",
                "return_pin": "'.$seller_pin.'",
                "return_city": "'.$seller_city.'",
                "return_phone": "'.$seller_phone.'",
                "return_add": "'.$seller_add.'",
                "return_state": "'.$seller_state.'",
                "return_country": "'.$seller_country.'",
                "products_desc": "'.$pro['short_description'].'",
                "hsn_code": "",
                "cod_amount": "0",
                "order_date": "'.$orderData['created_at'].'",
                "total_amount": "'.$pro['price']-($pro['price']*$pro['discount']/100)+(($orderData['delivery_fee']/$pro['product_quantity'])*$quantity)-(($orderData['coupon_discount']/$pro['product_quantity'])*$quantity).'",
                "seller_add": "'.$seller_add.'",
                "seller_name": "'.$sellerWHName.'",
                "seller_pin": "'.$seller_pin.'",
                "seller_inv": "",
                "quantity": "'.$quantity.'",
                "waybill": '.$waybill.',
                "shipment_width": "'.$pro['width']*$quantity.'",
                "shipment_height": "'.$pro['height']*$quantity.'",
                "shipment_length": "'.$pro['length']*$quantity.'",
                "weight": "'.$pro['weight']*$quantity.'",
                "seller_gst_tin": "",
                "shipping_mode": "Surface",
                "address_type": ""
              }
            ],
            "pickup_location": {
              "name": "'.$sellerWHName.'",
              "add": "'.$seller_add.'",
              "city": "'.$seller_city.'",
              "pin_code": '.$seller_pin.',
              "country": "'.$seller_country.'",
              "phone": "'.$seller_phone.'"
            }
          }';

          // echo $bodyData;
          // die();

          // curl_setopt_array($curl, array(
          //   CURLOPT_URL => 'https://track.delhivery.com/api/cmu/create.json',
          //   CURLOPT_RETURNTRANSFER => true,
          //   CURLOPT_ENCODING => '',
          //   CURLOPT_MAXREDIRS => 10,
          //   CURLOPT_TIMEOUT => 0,
          //   CURLOPT_FOLLOWLOCATION => true,
          //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          //   CURLOPT_CUSTOMREQUEST => 'POST',
          //   CURLOPT_POSTFIELDS =>$bodyData,
          //   CURLOPT_HTTPHEADER => array(
          //     'Content-Type: application/json',
          //     'Accept: application/json',
          //     'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
          //   ),
          // ));

          $response = curl_exec($curl);

          // print_r(json_decode($response));

          curl_close($curl);
        }

        // COD
        if($payType == 2){
          // $created_at=date('Y-m-d H:i:s');
          $sub_order_id = $order_id."_".$pcount;
          $cols =  array("order_id", "sub_order_id", "prod_id", "status", "comment", "created_by");
          $values =  array($order_id, $sub_order_id, $pro_id, 1, "", 4);
          $table_name = "delivery_history";
          insert($cols, $values,$table_name);
        }
        
        $pcount++;

      }
      
      $updateWaybill = "UPDATE orders SET waybills = '$waybillArray' where order_id = '$order_id'";
      update($updateWaybill);

      //Reduce stock
      foreach (json_decode($cartJson,true) as $cart) {
        $pro_id = $cart["product_id"];
        $quantity = $cart["quantity"];
        //Get product stock
        $productStockQuery = "SELECT stock from products where id = $pro_id";
        $productStockData = select($productStockQuery);
        $existingStock = $productStockData['stock'];
        $stockAfterOrder = $existingStock - $quantity;

        // Update new stock to product table
        $updateStock = "UPDATE products SET stock = $stockAfterOrder where id = $pro_id";
        update($updateStock);
      }

      $array = array(
        'msg' => "Order Created",
        'data' => ""
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function generateWayBill(){

      $curl = curl_init();
      
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://track.delhivery.com/waybill/api/bulk/json/?count=1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);      

      return $response;
    }

    function getCountryById($country_id){
      // Get country
      $countryQuery = "SELECT name from countries where id=$country_id";
      $countryName = select($countryQuery);
      $country = $countryName['name'];
      return $country;
    }


    function getStateById($state_id){
      // Get state
      $stateQuery = "SELECT name from states where id=$state_id";
      $stateName = select($stateQuery);
      $state = $stateName['name'];
      return $state;
    }

    function getCityById($city_id){
      // Get city
      $cityQuery = "SELECT name from cities where id=$city_id";
      $cityName = select($cityQuery);
      $city = $cityName['name'];
      return $city;
    }

    // Track delhivery order
    function trackOrder($POST){
      $orderId = $POST['orderId'];
      $prodcount = $POST['prodcount'];
      $waybill = $POST['waybill'];

      $orderQuery = "SELECT * from orders where order_id='$orderId'";
      $orderData = select($orderQuery);

      $transId = $orderData['transaction_id'];
      $resData = null;
      $subOrderId = "{$orderId}_{$prodcount}";
      $payType = null;
      
      if($transId == $orderId){
        $delHistQuery = "SELECT * from delivery_history where order_id='$orderId' and sub_order_id='$subOrderId' ORDER BY created_at DESC";
        $resData = selectMultiple($delHistQuery);
        $payType = 2;
      }else{
        $url = 'https://track.delhivery.com/api/v1/packages/json/?waybill='.$waybill.'&ref_ids='.$orderId;

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $resData = json_decode($response);
        $payType=2;
      }
      

      $array = array(
        'msg' => "Fetched",
        'data' => $resData,
        'payType'=>$payType
      );
      http_response_code(200);
      echo json_encode($array);


    }

    function returnOrder($POST){
      $order_id = $POST['orderId'];
      
      $orderQuery = "SELECT * from orders where order_id='$order_id'";
      $orderData = select($orderQuery);

      $productJson = $orderData['product_json'];
      $shippingJson = $orderData['shipping_json'];
      $billingJson = $orderData['billing_json'];
      $cartJson = $orderData['cart_json'];

      //Get shippingdata 
      $shippingJson = json_decode($shippingJson,true);
      $name = $shippingJson[0]['first_name']." ".$shippingJson[0]['last_name'];
      $pin = $shippingJson[0]['pincode'];
      $add = $shippingJson[0]['add_line1'].",".$shippingJson[0]['add_line2'].",landmark-".$shippingJson[0]['landmark'];
      $city_id = $shippingJson[0]['city_id'];
      $state_id = $shippingJson[0]['state_id'];
      $country_id = $shippingJson[0]['country_id'];
      $phone = $shippingJson[0]['mobile'];

      $country = getCountryById($country_id);
      $state = getStateById($state_id);
      $city = getCityById($city_id);

      $waybillArray = "";
      $count=0;

      foreach (json_decode($productJson,true) as $pro) {
        $count++;
        
        // Generate waybill
        $waybill = generateWayBill();

        if($count==1){
          $waybillArray = $waybill;
        }else{
          $waybillArray = $waybillArray.",".$waybill;
        }

        // Taking product owner / pickup details
        $productOwner = $pro['product_owner'];
        if($productOwner==0){
          $productOwner = $pro['added_by'];
        }
        $quantity = 0;
        foreach (json_decode($cartJson,true) as $cart) {
          if( $pro['id'] == $cart["product_id"]){
            $quantity = $cart["quantity"];
          }
        }

        $getProdOwner = "SELECT * from addresses where user_id=$productOwner";
        $getProdOwnerData = select($getProdOwner);
        $seller_name = $getProdOwnerData['first_name']." ".$getProdOwnerData['last_name'];
        $seller_pin = $getProdOwnerData['pincode'];
        $seller_add = $getProdOwnerData['add_line1'].",".$getProdOwnerData['add_line2'].",landmark-".$getProdOwnerData['landmark'];
        $seller_city_id = $getProdOwnerData['city_id'];
        $seller_state_id = $getProdOwnerData['state_id'];
        $seller_country_id = $getProdOwnerData['country_id'];
        $seller_phone = $getProdOwnerData['mobile'];
        $sellerWHName = $getProdOwnerData['wh_name'];
        $seller_country = getCountryById($seller_country_id);
        $seller_state = getStateById($seller_state_id);
        $seller_city = getCityById($seller_city_id);


        // Creating delhivery order
        $curl = curl_init();

        $bodyData= 'format=json&data={
          "shipments": [
            {
              "name":"'.$name.'" ,
              "add": "'.$add.'",
              "pin": "'.$pin.'",
              "city": "'.$city.'",
              "state": "'.$state.'",
              "country": "'.$country.'",
              "phone": "'.$phone.'",
              "order": "'.$order_id.'",
              "payment_mode": "Pickup",
              "return_pin": "'.$seller_pin.'",
              "return_city": "'.$seller_city.'",
              "return_phone": "'.$seller_phone.'",
              "return_add": "'.$seller_add.'",
              "return_state": "'.$seller_state.'",
              "return_country": "'.$seller_country.'",
              "products_desc": "'.$pro['short_description'].'",
              "hsn_code": "",
              "cod_amount": "0",
              "order_date": "'.$orderData['created_at'].'",
              "total_amount": "'.$pro['price']-($pro['price']*$pro['discount']/100)+($orderData['delivery_fee']/$orderData['product_quantity']).'",
              "seller_add": "'.$seller_add.'",
              "seller_name": "'.$sellerWHName.'",
              "seller_pin": "'.$seller_pin.'",
              "seller_inv": "",
              "quantity": "'.$quantity.'",
              "waybill": '.$waybill.',
              "shipment_width": "'.$pro['width']*$quantity.'",
              "shipment_height": "'.$pro['height']*$quantity.'",
              "shipment_length": "'.$pro['length']*$quantity.'",
              "weight": "'.$pro['weight']*$quantity.'",
              "seller_gst_tin": "",
              "shipping_mode": "Surface",
              "address_type": ""
            }
          ],
          "pickup_location": {
            "name": "'.$sellerWHName.'",
            "add": "'.$seller_add.'",
            "city": "'.$seller_city.'",
            "pin_code": '.$seller_pin.',
            "country": "'.$seller_country.'",
            "phone": "'.$seller_phone.'"
          }
        }';

        // echo $bodyData;
        // die();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://track.delhivery.com/api/cmu/create.json',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$bodyData,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

      }

      $user_id=$GLOBALS['jwt_token']->user_id;
      $updated_at=date('Y-m-d H:i:s');
      $updateQuery = "UPDATE orders SET order_returned_by = '$user_id', updated_at = '$updated_at', is_returned=1, status=5 where order_id = '$order_id'";
      update($updateQuery);

      $cols =  array("order_id", "waybills", "created_at");
      $values =  array($order_id, $waybillArray, $updated_at);
      $table_name = "return_orders";
      insert($cols, $values,$table_name);

      sendOrderReturnRequestEmail($order_id);
      
      $array = array(
        'msg' => "Order Returned",
        'data' => ""
      );
      http_response_code(200);
      echo json_encode($array);
      
    }


    function sendOrderReturnRequestEmail($order_id){
      $user_id=$GLOBALS['jwt_token']->user_id;

      // Get user email
      $getEmailQuery = "SELECT email,first_name,last_name FROM `users` where id = $user_id";
      $getEmailData = select($getEmailQuery);

      $loggedInUser = $getEmailData['first_name']." ".$getEmailData['last_name'];
      //Send Email       
      $toEmail = $getEmailData['email'];
      $subject = "Maruti Studio | Order Return Initiated";
      $adminSub = "Order return has been initiated On Shuttleshop";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=5";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $loggedInUser;
      $emailBody=str_replace("[Name]",$fullName,$emailBody);
      $emailBody=str_replace("[OrderId]",$order_id,$emailBody);

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
?>