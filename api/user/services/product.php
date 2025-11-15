<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    require './../../api/common/common.php';

  //get all products
  function getAllProducts(){
    $table_name = "products";
    $query = "select p.*,pi.*,p.id as product_id from $table_name p left join product_images pi on p.id=pi.product_id where p.status=1 GROUP BY p.id ORDER BY p.added_at desc";
    $query_res = selectMultiple($query);

    for($prod=0; $prod< count($query_res); $prod++){
      $productId = $query_res[$prod]['product_id'];

      // All Star
      $starQuery = "select rs.*,users.first_name,last_name from review_stars as rs JOIN users ON users.id=rs.user_id where product_id=$productId and rs.status=1 ORDER BY rating_date DESC";
      $starData = selectMultiple($starQuery);

      // All Comment
      $commentQuery = "select * from review_comments where product_id=$productId and status=1";
      $commentData = selectMultiple($commentQuery);

      $query_res[$prod]['starData'] = $starData;
      $query_res[$prod]['commentData'] = $commentData;
    }


    // Most ordered products
    $getMostOrderedProductsQuery = "SELECT DISTINCT(count(*)) as count, p.id as product_id,p.*, pi.* FROM `delivered_products` as dp LEFT JOIN products as p ON p.id=dp.product_id LEFT JOIN product_images as pi ON p.id=pi.product_id where  p.status=1 group by dp.product_id order by count desc";
    $getMostOrderedProducts = selectMultiple($getMostOrderedProductsQuery);

    for($prod=0; $prod< count($getMostOrderedProducts); $prod++){
      $productId = $getMostOrderedProducts[$prod]['product_id'];

      // All Star
      $starQuery = "select rs.*,users.first_name,last_name from review_stars as rs JOIN users ON users.id=rs.user_id where product_id=$productId and rs.status=1 ORDER BY rating_date DESC";
      $starData = selectMultiple($starQuery);

      // All Comment
      $commentQuery = "select * from review_comments where product_id=$productId and status=1";
      $commentData = selectMultiple($commentQuery);

      $getMostOrderedProducts[$prod]['starData'] = $starData;
      $getMostOrderedProducts[$prod]['commentData'] = $commentData;
    }
    

    // featured products
    $feturedCatQuery = "SELECT * from category where is_featured=1 and is_deleted=1 and status=1";
    $feturedCatData = selectMultiple($feturedCatQuery);

    $fetureCatData = array();
    $count = 0;
    foreach ($feturedCatData as $feturedCat) {
      $catid = $feturedCat['id'];

      $prodQuery = "SELECT p.id as product_id,p.*, pi.* FROM products as p JOIN product_images as pi ON p.id=pi.product_id where p.category_id=$catid and p.status=1 GROUP BY p.id";
      $prodData = selectMultiple($prodQuery);

      for($prod=0; $prod< count($prodData); $prod++){
        $productId = $prodData[$prod]['product_id'];
  
        // All Star
        $starQuery = "select rs.*,users.first_name,last_name from review_stars as rs JOIN users ON users.id=rs.user_id where product_id=$productId and rs.status=1 ORDER BY rating_date DESC";
        $starData = selectMultiple($starQuery);
  
        // All Comment
        $commentQuery = "select * from review_comments where product_id=$productId and status=1";
        $commentData = selectMultiple($commentQuery);
  
        $prodData[$prod]['starData'] = $starData;
        $prodData[$prod]['commentData'] = $commentData;
      }      

      $fetureCatData[$count]['feturedCat'] = $feturedCat;
      $fetureCatData[$count]['prodData'] = $prodData;

      $count++;
    }

    $array = array(
      'msg' => "All products Details..",
      'data' => $query_res,
      'mostOrderedProducts' => $getMostOrderedProducts,
      'feturedCatData' => $fetureCatData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  //get all products by search
  function getProductBySearch($REQUEST){
    $searchItem = $REQUEST["s"];

    $searchArray = explode(" ",$searchItem);

    $searchQuery = "";
    for($search=0; $search<count($searchArray); $search++){
      $orQuery = " or ";

      $lastOrQuery="";
      if($search < count($searchArray)-1){
        $lastOrQuery = $orQuery;
      }

      $searchQuery .= "p.product_name like '%$searchArray[$search]%' $orQuery";
      $searchQuery .= "p.short_description like '%$searchArray[$search]%' $orQuery";
      $searchQuery .= "p.description like '%$searchArray[$search]%' $orQuery";
      $searchQuery .= "p.bullet1 like '%$searchArray[$search]%' $orQuery";
      $searchQuery .= "p.bullet2 like '%$searchArray[$search]%' $orQuery";
      $searchQuery .= "p.bullet3 like '%$searchArray[$search]%' $lastOrQuery";

    }

    $whereCond = " and $searchQuery ";
    $table_name = "products";
    $query = "select p.*,pi.*,p.id as product_id from $table_name p left join product_images pi on p.id=pi.product_id where p.status=1 $whereCond GROUP BY p.id";
    $query_res = selectMultiple($query);
    $array = array(
      'msg' => "All products Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  // Get single product details
  function getSingleproduct($REQUEST){
    $table_name = "products";
    $id = $REQUEST['id'];
    if(isset($GLOBALS['jwt_token']->user_id))
      $user_id = $GLOBALS['jwt_token']->user_id;

    $query = "select * from $table_name where status=1 and id=$id";
    $query_res = select($query);

     //Check if product id is valid 
     checkProductExist($query_res,$REQUEST);

    $query = "select * from product_images where product_id=$id";
    $image_res = selectMultiple($query);

    // All Star
    $starQuery = "select rs.*,users.first_name,last_name from review_stars as rs JOIN users ON users.id=rs.user_id where product_id=$id and rs.status=1 ORDER BY rating_date DESC";
    $starData = selectMultiple($starQuery);

    // All Comment
    $commentQuery = "select * from review_comments where product_id=$id and status=1";
    $commentData = selectMultiple($commentQuery);

    $userStarData="";
    $userCommentData="";
    if(isset($GLOBALS['jwt_token']->user_id)){
      // User Star
      $starQuery = "select * from review_stars where user_id=$user_id and product_id=$id";
      $userStarData = select($starQuery);

      // User Comment
      $commentQuery = "select * from review_comments where user_id=$user_id and product_id=$id";
      $userCommentData = select($commentQuery);
    }

    $array = array(
      'msg' => "Product Details..",
      'data' => $query_res,
      'images' => $image_res,
      'all_review_star' => $starData,
      'all_review_comment' => $commentData,
      'review_star' => $userStarData,
      'review_comment' => $userCommentData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function getShopData($POST){
    $cat_type = $POST['cat_type'];
    $id = $POST['id'];

    $andQuery="";
    if($cat_type==1){
      $andQuery=" and category_id=$id";
    }
    if($cat_type==2){
      $andQuery=" and level1category_id=$id";
    }
    if($cat_type==3){
      $andQuery=" and sub_category_id=$id";
    }

    $table_name = "products";
    $query = "select p.*,pi.*,p.id as product_id from $table_name p left join product_images pi on p.id=pi.product_id where p.status=1 $andQuery GROUP BY p.id ORDER BY p.added_at desc";
    $query_res = selectMultiple($query);

    for($prod=0; $prod< count($query_res); $prod++){
      $productId = $query_res[$prod]['product_id'];

      // All Star
      $starQuery = "select rs.*,users.first_name,last_name from review_stars as rs JOIN users ON users.id=rs.user_id where product_id=$productId and rs.status=1 ORDER BY rating_date DESC";
      $starData = selectMultiple($starQuery);

      // All Comment
      $commentQuery = "select * from review_comments where product_id=$productId and status=1";
      $commentData = selectMultiple($commentQuery);

      $query_res[$prod]['starData'] = $starData;
      $query_res[$prod]['commentData'] = $commentData;
    }

    $array = array(
      'msg' => "Products Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  //get product for contact form
  function getProd($REQUEST){
    $table_name = "products";

    $query = "select * from $table_name where status=1";
    $query_res = selectMultiple($query);
    $array = array(
      'msg' => "Product Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function getFeatureProduct(){
    $table_name = "products";
    $query = "select p.*,pi.*,p.id as product_id from $table_name p left join product_images pi on p.id=pi.product_id where p.status=1 GROUP BY p.id ORDER BY RAND() LIMIT 7";
    $query_res = selectMultiple($query);

    $array = array(
      'msg' => "All products Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  // addReview
  function addReview($POST){
    $user_id = $GLOBALS['jwt_token']->user_id;
    $pid = $POST["pid"];
    $rating = $POST["rating"];
    $comment = $POST["comment"];

    // Star
    $cols =  array("user_id","product_id","rating","rating_date");
    $values =  array($user_id, $pid, $rating, date('Y-m-d H:i:s'));
    $table_name = "review_stars";
    $insData = insert($cols, $values,$table_name);

    // Comment
    $cols =  array("user_id","product_id","comment","comment_date");
    $values =  array($user_id, $pid, $comment, date('Y-m-d H:i:s'));
    $table_name = "review_comments";
    $insData = insert($cols, $values,$table_name);

    $array = array(
      'msg' => "Review Submitted..",
      'data' => $POST
    );
    http_response_code(200);
    echo json_encode($array);
    exit;

  }

  // get product by category
  function getProductsByCat($POST){
    $id = $POST["id"];
    $table_name = "products";
    $query = "SELECT p.*, pi.*, p.id AS product_id, c.category_name FROM $table_name p LEFT JOIN product_images pi ON p.id = pi.product_id JOIN category c ON c.id = p.category_id WHERE p.status = 1 AND p.category_id = $id GROUP BY p.id ORDER BY RAND() LIMIT 7";
    $query_res = selectMultiple($query);

    $array = array(
      'msg' => "All products Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function getUniqueProduct($POST){
    $id = $POST["id"];
    $table_name = "products";
    $query = "SELECT p.*, pi.*, p.id AS product_id, c.category_name FROM $table_name p LEFT JOIN product_images pi ON p.id = pi.product_id JOIN category c ON c.id = p.category_id WHERE p.status = 1 AND p.id = $id GROUP BY p.id ";
    // echo $query; die();
    $query_res = select($query);

    $array = array(
      'msg' => "Unique products Details..",
      'data' => $query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }
?>