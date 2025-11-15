<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function addToCart($POST,$flag){
      $product_id = $POST["productid"];
      $cart_id = $POST["cart_id"];

      $productCount=1;
      if(isset($POST["qty"])){
        $productCount = $POST["qty"];
      }

      $query = "select * from carts where product_id=$product_id and cart_id='$cart_id'";
      $query_res = select($query);

      // Get stock from product table
      $productQuery = "SELECT stock from products where id=$product_id";
      $stockData = select($productQuery);
      // Check stock before add update
      if(isset($query_res["quantity"])){
        $qty=$query_res["quantity"]+$productCount;
      }else{
        $qty=$productCount;
      }

      if($stockData['stock'] < $qty){   
        $array = array(
          'msg' => "Out of stock",
          'data' => $POST
        );
        http_response_code(500);
        echo json_encode($array);
        exit;
      }else if($qty > 12){   
        $array = array(
          'msg' => "You already have this item in your cart with maximum quantity of 12",
          'data' => $POST
        );
        http_response_code(500);
        echo json_encode($array);
        exit;
      }else{
        // Update cart else add new to cart
        if($query_res){
          $updateddate=date('Y-m-d H:i:s');
          $update_query = "update carts set quantity=$qty, updated_at='$updateddate' where product_id=$product_id and cart_id='$cart_id'";
          update($update_query);

        }else{
          $cols =  array("cart_id","product_id", "quantity","created_at");
          $values =  array($cart_id, $product_id, $qty ,date('Y-m-d H:i:s'));
          $table_name = "carts";
          $insData = insert($cols, $values,$table_name);
        }

        $query = "select * from carts where cart_id='$cart_id'";
        $query_res = selectMultiple($query);

        if($flag){
          $array = array(
              'msg' => "Product added to cart",
              'data' => $query_res
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
        }
      }
    }


    //getCartItem
    function getCartItem($POST){
      $cart_id=$GLOBALS['jwt_token']->cart_id;

      $table_name = "carts";
      $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and cart_id='$cart_id' GROUP BY p.id";
      $query_res = selectMultiple($query);

      $chargesQuery = "SELECT * from charges WHERE program_id=1";
      $charges = select($chargesQuery);
      
      $shipping_pin = $POST['shipping_pin'];

      $shippingFee = array();

      if($charges['delivery_charge_type'] == 2 && $shipping_pin != 0){
        foreach ($query_res as $p) {
          //Get origin shipping pincode
          // Get users pincode by owner id
          $productOwner = $p['product_owner'];
          if($productOwner==0){
            $productOwner = $p['added_by'];
          }
          $getProdOwner = "SELECT * from addresses where user_id=$productOwner";
          $getProdOwnerData = select($getProdOwner);
          $originPin = $getProdOwnerData["pincode"];

          $weight = $p['weight'];
          $quantity = $p['quantity'];

          $totalWeight = $weight*$quantity;
          
          // Calculate weight*quantity per unique item

          // Calculate Shipping fee API call
          $curl = curl_init();

          $url = 'https://track.delhivery.com/api/kinko/v1/invoice/charges/.json?md=S&ss=Delivered&d_pin='.$shipping_pin.'&o_pin='.$originPin.'&cgm='.$totalWeight.'&pt=Pre-paid&cod=0';
          // echo $url;

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

          array_push($shippingFee,json_decode($response)[0]);

        }
      }

      $array = array(
        'msg' => "",
        'data' => $query_res,
        'charges' => $charges,
        'shippingFee'=>$shippingFee
      );
      http_response_code(200);
      echo json_encode($array);
    }
    
    //deleteCartItem
    function deleteCartItem($POST){
      $product_id = $POST["product_id"];
      $cart_id=$GLOBALS['jwt_token']->cart_id;

      $delete_query = "delete from carts where cart_id='$cart_id' and product_id=$product_id";
      delete($delete_query);

      $table_name = "carts";
      $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and c.cart_id='$cart_id' GROUP BY p.id";
      $query_res = selectMultiple($query);

      $array = array(
        'msg' => "Product deleted from cart",
        'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }


    //updateCartItem
    function updateCartItem($POST){
      $product_id = $POST["product_id"];
      $action = $POST["action"];
      $qty = $POST["qty"];
      $cart_id=$GLOBALS['jwt_token']->cart_id;

      if($action != "bulk"){
        $getproductqty = "select * from carts where cart_id='$cart_id' and product_id=$product_id";
        $query_res = select($getproductqty);
        $qty =0;
        if($query_res){
          $qty = $query_res["quantity"];
        }else{
          $cols =  array("cart_id","product_id", "quantity","created_at");
          $values =  array($cart_id, $product_id, 0 ,date('Y-m-d H:i:s'));
          $table_name = "carts";
          $insData = insert($cols, $values,$table_name);
        }

        if($action == "mins"){
          $qty= $qty-1;
        }
        if($action == "plus"){
          $qty= $qty+1;
        }
      }

      // Get product stock
      $stockQuery = "SELECT stock,product_name from products where id = $product_id";
      $stockData = select($stockQuery);

      if($stockData['stock'] < $qty){
        $array = array(
          'msg' => "Only ".$stockData['stock']." Stock available for product- ".$stockData['product_name'],
          'data' => $POST
        );
        http_response_code(500);
        echo json_encode($array);
      }else{
        if($qty<1){
          $array = array(
            'msg' => "Minimum quantity should not be less then 1, You can remove this item from cart..",
            'data' => $POST
          );
          http_response_code(500);
          echo json_encode($array);
        }else{
          $update_query = "update carts set quantity = $qty where cart_id='$cart_id' and product_id=$product_id";
          $query_res = update($update_query);
  
          $table_name = "carts";
          $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and c.cart_id='$cart_id' GROUP BY p.id";
          $query_res = selectMultiple($query);
  
          $array = array(
            'msg' => "Cart updated successfully",
            'data' => $query_res
          );
          http_response_code(200);
          echo json_encode($array);
        }
      }
    }
?>