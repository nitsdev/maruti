<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');
    

    // get coupon
    function getCoupon($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="and status='$status'";
      }
      $query = "select * from coupons where program_id=1 $where";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Coupon Fetched..",
          'data' => $query_res
      );
      http_response_code(200);      
      echo json_encode($array);
    }
    

    //get unique coupon
    function getUniqueCoupon($POST){      
      $id = $POST['id'];
      $query = "select * from coupons where id=$id";
      $query_res = select($query);

      $array = array(
          'msg' => "Coupon Fetched..",
          'data' => $query_res
      );
      http_response_code(200);      
      echo json_encode($array);
    }

    function addCoupon($POST){
      // Validation
      $coupon = $POST['coupon'];
      $programId = $POST['programId'];
      $query = "select * from coupons where code = '$coupon' and program_id = $programId"; //issue
      $query_res = select($query);
      if($query_res){
        $array = array(
          'msg' => "Coupon already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
        // Insert Data
        $user_id=$GLOBALS['jwt_token']->user_id;
        $cols =  array("code","min_amt", "valid_from","valid_till","status","description","discount_type","program_id","discount_per","per_user","per_month");
        $values =  array($POST['coupon'],$POST['minAmount'],$POST['validFrom'],$POST['validTill'],1,$POST['couponDescription'],$POST['discountType'],$POST['programId'],$POST['discount_per'],$POST['perUser'],$POST['perMonth']);
        $table_name = "coupons";
        $insData = insert($cols, $values,$table_name);

        $array = array(
            'msg' => "Coupon Added Successfully..",
            'data' => $_POST
        );
        http_response_code(200);
        echo json_encode($array);
    exit;
      }      
    }

    // delete coupon
    function deleteCoupon($POST){
      $id = $POST['id'];
      $deleteCouponQuery = "DELETE from coupons where id = $id";
      delete($deleteCouponQuery);

      $array = array(
        'msg' => "Coupon Deleted.."
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
      
      
    }

    //  edit coupon
  function editCoupon($POST){
    $id = $POST['id'];
    // Validation
    $coupon = $POST['coupon_code'];
    $minAmount = $POST['minAmount'];
    $validFrom = $POST['validFrom'];
    $validTill = $POST['validTill'];
    $couponDescription = $POST['description'];
    $discountType = $POST['discount_type'];
    $discount_per = $POST['discount_percent'];    

    $query = "SELECT * from coupons where code='$coupon' and id != $id";
    $query_res = select($query);

    if($query_res){
      $array = array(
        'msg' => "Coupon code already exist..",
        'data' => $_POST
      );
      http_response_code(401);
      echo json_encode($array);
      exit;
    }else{
        // Update db
        $updateCatQuery = "UPDATE coupons SET code = '$coupon', min_amt=$minAmount , valid_from='$validFrom' , valid_till='$validTill' , description='$couponDescription' , discount_type='$discountType' , discount_per='$discount_per' where id=$id";
        update($updateCatQuery);

        $array = array(
          'msg' => "Coupon Updated Successfully..",
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
    }     

  }

  // get food coupon
    function getFoodCoupon($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where=" and status='$status' ";
      }
      $query = "select * from coupons where program_id=2 $where ";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Coupon Fetched..",
          'data' => $query_res
      );
      http_response_code(200);      
      echo json_encode($array);
    }
?>