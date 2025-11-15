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

    //validateBeforePay
    function validateBeforePay($POST){
      $user_id = $GLOBALS['jwt_token']->user_id;
      $cart_id =  $GLOBALS['jwt_token']->cart_id;
      $coupon_code = $POST["coupon_code"];
      $coupon_discount = $POST["coupon_discount"];
      $shipping_address = $POST["shipping_address"];
      $billing_address = $POST["billing_address"];
      $shipping_fee = $POST["shipping_fee"];
      $grandtotal = $POST["grandtotal"];
      $minCartAmt = 149;
      $minFreeDelAmt = 699;

      if(($grandtotal-$shipping_fee) < $minCartAmt){
        $array = array(
          'msg' => "Min Cart Anount Should be more than or equal to -".$minCartAmt
        );
        http_response_code(500);
        echo json_encode($array);
        
      }else{
        $bytes = random_bytes(2);
        $prefixOrderId = bin2hex($bytes);
        $suffixOrderId = time();
        $order_id = "SHS-".$prefixOrderId."T".$suffixOrderId;

        // Total amount
        $totalCartValue = 0;
        // $cartProductQuery = "SELECT * from products where id IN (select product_id from carts where cart_id='$cart_id')";
        // $cardProductData = selectMultiple($cartProductQuery);
        // for($prod = 0; $prod < count($cardProductData); $prod++){
        //   $totalCartValue = $totalCartValue+$cardProductData[$prod][];
        // }

        $isValid = 0;
        $product_quantity = 0;

        //*********** */ Check if stock is available
        // Get cart details that might have inactive or deleted product also
        $cartProductQuery = "SELECT * from products where id IN (select product_id from carts where cart_id='$cart_id')";
        $cardProductData = selectMultiple($cartProductQuery);

        // Remove inactive products from cart
        for($prod = 0; $prod < count($cardProductData); $prod++){
          $cartProductId = $cardProductData[$prod]['id'];

          $checkIfInactive = "SELECT count(*) as count from products where status != 1 and id=$cartProductId";
          $checkIfInactiveData = select($checkIfInactive);
          if($checkIfInactiveData['count']>0){
            $deleteInactiveProductFromCart = "DELETE from carts where product_id=$cartProductId and cart_id='$cart_id'";
            delete($deleteInactiveProductFromCart);
          }
        }

        // Get cart details without inactive or deleted product also
        $cartProductQuery = "SELECT * from products where id IN (select product_id from carts where cart_id='$cart_id')";
        $cardProductData = selectMultiple($cartProductQuery);
        
        $chargesQuery = "SELECT * from charges WHERE program_id=1";
        $charges = select($chargesQuery);

        for($prod = 0; $prod < count($cardProductData); $prod++){
          $cartProductId = $cardProductData[$prod]['id'];
          $cartQuery = "SELECT * from carts where product_id=$cartProductId and cart_id='$cart_id'";
          $cardData = select($cartQuery);

          if($cardProductData[$prod]['stock'] < $cardData['quantity']){
            $isValid++;
            if($cardProductData[$prod]['stock'] > 0){
              $errorMsg = "Only ".$cardProductData[$prod]['stock']." stock available for product - ".$cardProductData[$prod]['product_name'];
            }else{
              $errorMsg = "Stock not available for product - ".$cardProductData[$prod]['product_name'];
            }
            $array = array(
              'msg' => $errorMsg,
              'data' => 0
            );
            http_response_code(500);
            echo json_encode($array);
          }else{
            $product_quantity = $product_quantity+$cardData['quantity'];
            $discount = ($cardProductData[$prod]['price']*$cardProductData[$prod]['discount'])/100;
            $priceAfterDiscount = $cardProductData[$prod]['price'] - $discount;
            $gst = (($priceAfterDiscount*$cardProductData[$prod]['gst'])/100);
            $priveAfterGST = ($priceAfterDiscount+$gst);
            $totalCartValue = $totalCartValue + ($priveAfterGST * $cardData['quantity']);
          }
        } 

        $delivery_charge=0;
        if($totalCartValue >= $minFreeDelAmt){
          $delivery_charge=0;
        }else{
          if($charges['delivery_charge_type']==1){
            $delivery_charge=0;
          }else if($charges['delivery_charge_type']==2){
            $delivery_charge=$shipping_fee;
          }else if($charges['delivery_charge_type']==3){
            $delivery_charge=count($cardProductData)*$charges['delivery_charge'];
          }else if($charges['delivery_charge_type']==4){
            $delivery_charge=$charges['delivery_charge'];
          }
        }
        
        if($isValid==0){
          // Get coupon value and reduce from totalCartValue
          $couponQuery = "SELECT * from coupons where code='$coupon_code'";
          $couponData = select($couponQuery);

          // Cart details
          $cartQuery = "SELECT * from carts where cart_id='$cart_id'";
          $cartData = selectMultiple($cartQuery);

          $totalCartValue = $totalCartValue - $coupon_discount + $delivery_charge;
          $gst = ($totalCartValue*18)/100;

          $shippingQuery = "SELECT * from addresses where id=$shipping_address";
          $shippingData = selectMultiple($shippingQuery);

          $billingQuery = "SELECT * from addresses where id=$billing_address";
          $billingData = selectMultiple($billingQuery);

          $product_json = json_encode($cardProductData);
          $coupon_json = json_encode($couponData);
          $cart_json = json_encode($cartData);
          $shipping_json = json_encode($shippingData);
          $billing_json = json_encode($billingData);

          // Update status to 0 - failed if status is 3 - pending
          // $updateStatusQuery = "UPDATE orders SET status=0 where status=3";
          // update($updateStatusQuery);

          // Insert into orders table
          $cols = array('order_id','user_id','amount','gst','delivery_fee','product_quantity','billing_address_id','shipping_address_id','coupon','coupon_discount','status','created_at','product_json','coupon_json','cart_json','shipping_json','billing_json');
          $values = array($order_id,$user_id,$totalCartValue,$gst,$delivery_charge,$product_quantity,$billing_address,$shipping_address,$coupon_code,$coupon_discount,3,date('Y-m-d H:i:s'),$product_json,$coupon_json,$cart_json,$shipping_json,$billing_json);
          $table_name = "orders";
          $insData = insert($cols, $values, $table_name);

          $array = array(
            'msg' => "Validated",
            'data' => 1,
            'order_id'=> $order_id
          );
          http_response_code(200);
          echo json_encode($array);
        }
      }
    }
?>