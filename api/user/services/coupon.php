<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');
    
    function getCoupon(){
      $todayDate = date('Y-m-d');
      $query = "SELECT * from coupons where status='1' and valid_from <= '$todayDate' and valid_till >= '$todayDate' and is_visible_ui=1 and program_id=1";
      
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Coupon Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit();
    }

    //checkCoupon
    function checkCoupon($POST){
      $coupon_code = $POST['coupon_code'];
      $subtotal = $POST['subtotal'];
      $currTime = date('Y-m-d');

      $coupon_query = "select * from coupons where code='$coupon_code' and status=1 and valid_from <= '$currTime' and valid_till >='$currTime' and program_id=1";
      $user_id=$GLOBALS['jwt_token']->user_id;
      $res = select($coupon_query);
      $valid = 0;

      if(!$res){
        $array = array(
          'msg' => "Coupon code is expired or not valid..",
          'data' => $res
        );
        http_response_code(300);
        echo json_encode($array);
      }else{
        $minamt=$res['min_amt'];
        $per_user=$res['per_user'];
        $per_month=$res['per_month'];
        $coupon_user_id=$res['user_id'];

        // Get already applied coupn count for this user and above applied coupon code
        $where = "";

        $date = getdate();
        $month = $date['mon'];

        if($per_month){
          $where .= " and Month(created_at) = $month"; 
        }
        $appliedCouponQuery = "SELECT * from coupon_applied where coupon_code='$coupon_code' and program_id=1 and user_id='$user_id'".$where;
        $appliedCoupon = selectMultiple($appliedCouponQuery);

        // Check per month validation
        if($per_month){
          if(count($appliedCoupon) >= $per_month){
            $valid = 0;
            $array = array(
              'msg' => "You already applied this coupon..",
              'data' => '',
              'isClear' => 1
            );
            http_response_code(500);
            echo json_encode($array);
            exit();
          }
        }

        // Check per user validation
        if(!$per_month && $per_user){
          if(count($appliedCoupon) >= $per_user){
            $valid = 0;
            $array = array(
              'msg' => "You already applied this coupon..",
              'data' => '',
              'isClear' => 1
            );
            http_response_code(500);
            echo json_encode($array);
            exit();
          }
        }

        if(isset($coupon_user_id)){
          if($coupon_user_id != $user_id){
            $valid = 0;
            $array = array(
              'msg' => "Coupon is invalid",
              'data' => '',
              'isClear' => 1
            );
            http_response_code(500);
            echo json_encode($array);
            exit();
          }
        }
        
        if($minamt>$subtotal){
          $array = array(
            'msg' => "Cart minimum amount should be $minamt",
            'data' => $res,
            'isClear' => 0
          );
          http_response_code(500);
          echo json_encode($array);
          exit();
        }
        

        if($res['discount_type']==1){
          $coupon_id=$res['id'];
          //Get discount for user;
          $todatDate = date('Y-m-d');
          $user_discount = "select dis_per from user_discount where user_id=$user_id and applied_date='$todatDate' and coupon_id=$coupon_id and program_id=1";
          $user_discount_res = select($user_discount);

          if($user_discount_res){
            $discount = $user_discount_res['dis_per'];
          }else{
            $disCut= 0;
            if($res['discount_per'] <= 2){
              $disCut = 0.3;
            }else if($res['discount_per'] <= 5 && $res['discount_per'] > 2){
              $disCut = 0.7;
            }
            else if($res['discount_per'] <= 10 && $res['discount_per'] > 5){
              $disCut = 1.2;
            }else{
              $disCut = 2.5;
            }

            $max_discount = $res['discount_per']-$disCut;
            $min_discount = 0.2;
            $scale = pow(10, 2);
            $discount = mt_rand($max_discount * $scale, $max_discount * $scale) / $scale;

            // Delete data for user
            $delete_query = "delete from user_discount where user_id=$user_id and coupon_id=$coupon_id and program_id=1";
            delete($delete_query);
            // Insert new
            $cols = array('user_id','coupon_id','dis_per','applied_date','program_id');
            $values = array($user_id,$coupon_id,$discount,date('Y-m-d'),1);
            $table_name = "user_discount";
            $insData = insert($cols, $values, $table_name);
          }

        }else{
          $discount = $res['discount_per'];
        }
        

        $array = array(
          'msg' => "Coupon Fetched..",
          'data' => $res,
          'discount'=>$discount
        );
        http_response_code(200);
        echo json_encode($array);
        exit();
      }
    }      
?>