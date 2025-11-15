<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function addToWishlist($POST){
      $product_id = $POST["productid"];
      $cart_id = $POST["cart_id"];
      $user_id=$GLOBALS['jwt_token']->user_id;
      $cart_id_t=$GLOBALS['jwt_token']->cart_id;

      $query = "select * from wishlist where product_id=$product_id and user_id='$user_id'";
      $query_res = select($query);

      if(!$query_res){
        if($cart_id == $cart_id_t){
          $cols =  array("user_id","product_id");
          $values =  array($user_id, $product_id);
          $table_name = "wishlist";
          $insData = insert($cols, $values,$table_name);
        }
      }


      // // Update cart else add new to cart
      // if($query_res){
      //   $qty=$query_res["quantity"]+1;
      //   $updateddate=date('Y-m-d H:i:s');
      //   $update_query = "update carts set quantity=$qty, updated_at='$updateddate' where product_id=$product_id and cart_id='$cart_id'";
      //   update($update_query);

      // }else{
      //   $cols =  array("cart_id","product_id", "quantity","created_at");
      //   $values =  array($cart_id, $product_id, 1 ,date('Y-m-d H:i:s'));
      //   $table_name = "carts";
      //   $insData = insert($cols, $values,$table_name);
      // }

      $query = "select * from wishlist where user_id='$user_id'";
      $query_res = selectMultiple($query);
      
      $array = array(
          'msg' => "Product added to wishlist",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //getWishlistItem
    function getWishlistItem($POST){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $query = "select *,p.id as product_id from wishlist as w left join products as p on p.id=w.product_id left join product_images pi on p.id=pi.product_id where p.status=1 and user_id=$user_id GROUP BY p.id";
      $query_res = selectMultiple($query);
      
      $array = array(
          'msg' => "fetched wishlist",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //deleteWishlistItem
    function deleteWishlistItem($POST,$flag){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $product_id = $POST['product_id'];

      $removeProduct = "delete from wishlist where user_id=$user_id and product_id=$product_id";
      delete($removeProduct);

      $query = "select *,p.id as product_id from wishlist as w left join products as p on p.id=w.product_id where user_id='$user_id'";
      $query_res = selectMultiple($query);
      
      if($flag){
        $array = array(
            'msg' => "Product removed from wishlist",
            'data' => $query_res
        );
        http_response_code(200);
        echo json_encode($array);
      }
    }

    //move to cart
    function moveToCart($POST){ 
      $user_id=$GLOBALS['jwt_token']->user_id;
      $cart_id=$GLOBALS['jwt_token']->cart_id;

      $table_name = "carts";
      $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and cart_id='$cart_id' GROUP BY p.id";
      $cart_query_res = selectMultiple($query);

      $query = "select *,p.id as product_id from wishlist as w left join products as p on p.id=w.product_id where user_id='$user_id'";
      $wishlist_query_res = selectMultiple($query);

      $array = array(
          'msg' => "Product moved to cart",
          'cart_data' => $cart_query_res,
          'wishlist_data' => $wishlist_query_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    // //getCartItem
    // function getCartItem(){
    //   $cart_id=$GLOBALS['jwt_token']->cart_id;

    //   $table_name = "carts";
    //   $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and cart_id='$cart_id' GROUP BY p.id";
    //   $query_res = selectMultiple($query);

    //   $array = array(
    //     'msg' => "",
    //     'data' => $query_res
    //   );
    //   http_response_code(200);
    //   echo json_encode($array);
    // }
    
    // //deleteCartItem
    // function deleteCartItem($POST){
    //   $product_id = $POST["product_id"];
    //   $cart_id=$GLOBALS['jwt_token']->cart_id;

    //   $delete_query = "delete from carts where cart_id='$cart_id' and product_id=$product_id";
    //   delete($delete_query);

    //   $table_name = "carts";
    //   $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and c.cart_id='$cart_id' GROUP BY p.id";
    //   $query_res = selectMultiple($query);

    //   $array = array(
    //     'msg' => "Product deleted from cart",
    //     'data' => $query_res
    //   );
    //   http_response_code(200);
    //   echo json_encode($array);
    // }


    // //updateCartItem
    // function updateCartItem($POST){
    //   $product_id = $POST["product_id"];
    //   $action = $POST["action"];
    //   $qty = $POST["qty"];
    //   $cart_id=$GLOBALS['jwt_token']->cart_id;

    //   if($action != "bulk"){
    //     $getproductqty = "select * from carts where cart_id='$cart_id' and product_id=$product_id";
    //     $query_res = select($getproductqty);
    //     $qty = $query_res["quantity"];

    //     if($action == "mins"){
    //       $qty= $qty-1;
    //     }
    //     if($action == "plus"){
    //       $qty= $qty+1;
    //     }
    //   }

    //   if($qty<1){
    //     $array = array(
    //       'msg' => "Minimum quantity should not be less then 1",
    //       'data' => $POST
    //     );
    //     http_response_code(500);
    //     echo json_encode($array);
    //   }else{
    //     $update_query = "update carts set quantity = $qty where cart_id='$cart_id' and product_id=$product_id";
    //     $query_res = update($update_query);

    //     $table_name = "carts";
    //     $query = "select c.quantity,p.*,pi.*,p.id as product_id from $table_name c left join products p on c.product_id=p.id left join product_images pi on p.id=pi.product_id where p.status=1 and c.cart_id='$cart_id' GROUP BY p.id";
    //     $query_res = selectMultiple($query);

    //     $array = array(
    //       'msg' => "Cart updated successfully",
    //       'data' => $query_res
    //     );
    //     http_response_code(200);
    //     echo json_encode($array);
    //   }
    // }
?>