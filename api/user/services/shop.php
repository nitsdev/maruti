<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function GetAllShops($POST){
      $flag = $POST['flag'];

      $shopData =[];

      if($flag == 3){
        $getShopDataQuery = "SELECT add_line1, shop_name, id, user_id FROM addresses where shop_name != '' ORDER BY RAND() LIMIT 10";
        $getShopData = selectMultiple($getShopDataQuery);
        $adminShopData = "SELECT add_line1, shop_name, id, user_id FROM addresses where user_id=4";
        $adminShopData = selectMultiple($adminShopData);
      }else{
        $getShopDataQuery = "SELECT add_line1, shop_name, id, user_id FROM addresses where shop_name != ''";
        $getShopData = selectMultiple($getShopDataQuery);
        $adminShopData = "SELECT add_line1, shop_name, id, user_id FROM addresses where user_id=4";
        $adminShopData = selectMultiple($adminShopData);
      }

      // Get seller shop img
      $getShopImageQuery = "SELECT url,seller_id,extension from seller_ids where extension!='pdf' and seller_id IN (SELECT id from users where user_role=2)";
      $shopImgData = selectMultiple($getShopImageQuery);

      $array = array(
        'msg' => "Shop Fetched..",
        'data' => $getShopData,
        'adminShopData' => $adminShopData,
        'shopImgData' => $shopImgData
      );
      http_response_code(200);
      echo json_encode($array);
    }


    function getProductsBySeller($POST){
      $sellerId = $POST['sellerId'];

      $getShopDataQuery = "SELECT add_line1, shop_name, id, user_id FROM addresses where user_id = $sellerId";
      $getShopData = selectMultiple($getShopDataQuery);


      $getProductsQuery = "SELECT *,prod.id as prod_id FROM products prod where product_owner = $sellerId and status=1";
      $productData = selectMultiple($getProductsQuery);

      $c=0;
      foreach($productData as $product){
        $prodId = $product['prod_id'];
        $getImgQuery = "SELECT url from product_images where product_id=$prodId limit 1";
        $prodImgData = select($getImgQuery);
        $productData[$c]['img'] = $prodImgData;
        $c++;
      }

      // Get cart data for user
      $cartData = [];
      if(isset($GLOBALS['jwt_token']) && ISSET($GLOBALS['jwt_token']->cart_id)){
        $cart_id=$GLOBALS['jwt_token']->cart_id;
        $cartQuery = "SELECT * FROM carts where cart_id = '$cart_id'";
        $cartData = selectMultiple($cartQuery);
      }

      // Seller Ids
      $shopImageQuery = "SELECT url from seller_ids where seller_id=$sellerId and extension != 'pdf'";
      $shopImage = select($shopImageQuery);

      $array = array(
        'msg' => "Shop Fetched..",
        'data' => $getShopData,
        'products' => $productData,
        'cartData' => $cartData,
        'shopImage' => $shopImage
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>