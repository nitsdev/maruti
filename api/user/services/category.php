<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function getCategory($POST){
      $status = $POST['status'];
      $query = "select * from category where status='$status' and is_deleted=1";
      $query_res = selectMultiple($query);

      for($cat=0; $cat<count($query_res);$cat++){
        $cat_id = $query_res[$cat]['id'];

        $prodCountQuery = "select count(id) as prod_count from products where category_id=$cat_id";
        $prodCount = select($prodCountQuery);

        $query_res[$cat]['count'] = $prodCount['prod_count'];

      }

      $array = array(
          'msg' => "Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>