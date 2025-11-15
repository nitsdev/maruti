<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    //get sellers
    function getSellers($POST){
      $status=$POST['status'];

      // If invalid status code passed
      if($status > 2){
        $array = array(
          'msg' => "Invalid status code passed..",
          'data' => $_POST
        );
        http_response_code(400);
        echo json_encode($array);
        exit;
      }

      $query = "select * from users where user_role=2";
      
      if($status){
        $query.=" and status=$status";
      }

      $sellerData = selectMultiple($query);

      $array = array(
          'msg' => "Seller Details Successfully..",
          'data' => $sellerData
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }


    function addBankDetails($POST){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $gst=$POST['gstNumber'];
      $name=$POST['name'];      
      $acc_number=$POST['accNumber'];      
      $upi_id=$POST['upiId'];      
      $ifsc_number=$POST['ifscNumber'];
      $branch=$POST['branch'];
      

      $id=0;
      if(isset($POST['id']) && $POST['id']){
        $id = $POST['id'];
      }
      if($id == 0){
        // Insert Data
        $user_id=$GLOBALS['jwt_token']->user_id;
        $cols =  array("user_id", "gst_number","acc_name","acc_number","upi_id","ifsc_number","branch");
        $values =  array($user_id,$POST['gstNumber'],$POST['name'],$POST['accNumber'],$POST['upiId'],$POST['ifscNumber'],$POST['branch']);
        $table_name = "seller_bank_details";
        $insData = insert($cols, $values,$table_name);

        $array = array(
            'msg' => "Bank Details added Successfully..",
            'data' => $insData
        );
        http_response_code(200);
        echo json_encode($array);
      }else{
        $updateBankDetails = "UPDATE seller_bank_details SET gst_number='$gst',acc_name='$name', acc_number='$acc_number',upi_id='$upi_id',ifsc_number='$ifsc_number',branch='$branch' where id=$id and user_id=$user_id";
        update($updateBankDetails);

        $array = array(
          'msg' => "Bank Details Updated successfully..",
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
      }
          
    }


    //getBankDetails
    function getBankDetails(){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $bankDetailsQuery = "SELECT * FROM `seller_bank_details` WHERE user_id=$user_id";
      $res = select($bankDetailsQuery);

      $array = array(
        'msg' => "Bank details Fetched..",
        'data' => $res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>