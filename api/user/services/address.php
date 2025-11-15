<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function addAddress($POST){
      $first_name = $POST['first_name'];
      $last_name = $POST['last_name'];
      $email = $POST['email'];
      $mobile = $POST['mobile'];
      $add_line1 = $POST['add1'];
      $add_line2 = $POST['add2'];
      $country_id = $POST['country_id'];
      $state_id = $POST['state_id'];
      $city_id = $POST['city_id'];
      $landmark = $POST['landmark'];
      $pincode = $POST['pincode'];
      $is_shipping = $POST['is_shipping'];
      $is_billing = $POST['is_billing'];
      $user_id=$GLOBALS['jwt_token']->user_id;
      
      if($is_shipping == 1){
        //Set others address is_shipping to 0
        $update_query = "update addresses set is_shipping=0 where user_id=$user_id";
        update($update_query);
      }

      if($is_billing == 1){
        //Set others address is_billing to 0
        $update_query = "update addresses set is_billing=0 where user_id=$user_id";
        update($update_query);
      }

      // Insert Data
      $cols =  array("first_name","last_name", "email","mobile","add_line1","add_line2","country_id" ,"state_id" ,"city_id" ,"landmark" ,"pincode" , "is_shipping","is_billing","user_id");
      $values =  array($first_name, $last_name, $email ,$mobile,$add_line1 ,$add_line2,$country_id,$state_id,$city_id,$landmark,$pincode,$is_shipping,$is_billing,$user_id);
      $table_name = "addresses";
      $insData = insert($cols, $values,$table_name);

      $array = array(
          'msg' => "Address Added..",
          'data' => $insData
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //getAddresses
    function getAddresses(){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $addressQuery = "select a.*,c.name as country_name,s.name as state_name,ct.name as city_name from addresses as a left join countries as c on c.id=a.country_id left join states as s on s.id=a.state_id left join cities as ct on ct.id=a.city_id where a.user_id=$user_id";
      $res = selectMultiple($addressQuery);

      $array = array(
        'msg' => "Address Fetched..",
        'data' => $res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //makeDefaultBilling
    function makeDefaultBilling($POST){
      $addId = $POST['addId'];
      $user_id=$GLOBALS['jwt_token']->user_id;
      //Set others address is_shipping to 0
      $update_query = "update addresses set is_billing=0 where user_id=$user_id and is_billing=1";
      update($update_query);

      $update_query = "update addresses set is_billing=1 where user_id=$user_id and id=$addId";
      update($update_query);

      $array = array(
        'msg' => "Address made as default billing..",
        'data' => ''
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //makeDefaultShipping
    function makeDefaultShipping($POST){
      $addId = $POST['addId'];
      $user_id=$GLOBALS['jwt_token']->user_id;
      //Set others address is_shipping to 0
      $update_query = "update addresses set is_shipping=0 where user_id=$user_id and is_shipping=1";
      update($update_query);

      $update_query = "update addresses set is_shipping=1 where user_id=$user_id and id=$addId";
      update($update_query);

      $array = array(
        'msg' => "Address made as default shipping..",
        'data' => ''
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //deleteAddress
    function deleteAddress($POST){
      $addId = $POST['addId'];
      $user_id=$GLOBALS['jwt_token']->user_id;
      
      $selectQuery = "select * from addresses where id=$addId and user_id=$user_id and (is_billing=1 or is_shipping=1)";
      $res = select($selectQuery);

      if($res){
        $array = array(
          'msg' => "You can not delete default billing or shipping address..",
          'data' => ''
        );
        http_response_code(500);
        echo json_encode($array);
      }else{
        $deleteQuery = "delete from addresses where id=$addId and user_id=$user_id";
        delete($deleteQuery);

        $array = array(
          'msg' => "Address deleted..",
          'data' => ''
        );
        http_response_code(200);
        echo json_encode($array);
      }
      
    }

    function getAddresseById($POST){
      $addId = $POST['addId'];
      $user_id=$GLOBALS['jwt_token']->user_id;

      $selectQuery = "select * from addresses where id=$addId and user_id=$user_id";
      $res = select($selectQuery);

      $array = array(
        'msg' => "Address fetched..",
        'data' => $res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    // Edit address
    function editAddress($POST){
      $first_name = $POST['edit_first_name'];
      $last_name = $POST['edit_last_name'];
      $email = $POST['edit_email'];
      $mobile = $POST['edit_mobile'];
      $add_line1 = $POST['edit_add1'];
      $add_line2 = $POST['edit_add2'];
      $country_id = $POST['edit_country_id'];
      $state_id = $POST['edit_state_id'];
      $city_id = $POST['edit_city_id'];
      $landmark = $POST['edit_landmark'];
      $pincode = $POST['edit_pincode'];
      $addId = $POST['addId'];
      $area_id = $POST['edit_area_id'] ?? 31527;
      $user_id=$GLOBALS['jwt_token']->user_id;
      
      // update Data
      $setData = "SET first_name='$first_name', last_name='$last_name', email='$email', mobile=$mobile, add_line1='$add_line1', add_line2='$add_line2', country_id=$country_id, state_id=$state_id, city_id=$city_id, landmark='$landmark', pincode=$pincode, area_id=$area_id";

      $update_query = "update addresses $setData where id='$addId' and user_id=$user_id";
      
      $query_res = update($update_query);

      $array = array(
          'msg' => "Address Updated..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>