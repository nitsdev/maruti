<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    date_default_timezone_set('Asia/Kolkata'); 

    //Get user details
    function getUser(){
        $user_id=$GLOBALS['jwt_token']->user_id;
        $query = "SELECT id,first_name,last_name,email,mobile FROM `users` WHERE id= '$user_id'";
        $query_res = select($query);

        $array = array(
        'msg' => "User details fetched.",
        'data' => $query_res
        );
        http_response_code(200);
        echo json_encode($array);
    }

    //edit profile
    function editProfile($POST){
        $id = $POST['id'];
        $first_name = $POST['first_name'];
        $last_name = $POST['last_name'];
        
        $updateCatQuery = "UPDATE users SET first_name = '$first_name', last_name='$last_name' where id=$id";
        update($updateCatQuery);

        $array = array(
          'msg' => "Details Updated Successfully..",
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;    
      }

      function sendOtp($POST){
        $email = $POST['email'];
        $currTime = date('Y-m-d H:i:s');

        //Generate 6 digit otp
        $otp = str_pad(mt_rand(1, 999999),6,0,STR_PAD_LEFT);

        //Check if user exist
        $checkEmailExistQuery = "SELECT id,token_send_count,updated_at from users where email='$email' and user_role=3";
        $checkEmailExist = select($checkEmailExistQuery);

        $updatedAtFromTable = $checkEmailExist['updated_at'];
        $duration='+5 minutes';
        $add5min = date('Y-m-d H:i:s', strtotime($duration, strtotime($updatedAtFromTable)));

        if(strtotime($currTime) > strtotime($add5min)){
          $token_send_count = 1;
        }else{
          $token_send_count = $checkEmailExist['token_send_count']+1;
        }

        if($token_send_count < 4){
          if(isset($checkEmailExist['id'])){
            $user_id = $checkEmailExist['id'];            
            $updateOTP = "UPDATE users SET fp_token='$otp',updated_at='$currTime',token_send_count=$token_send_count where id=$user_id";
            update($updateOTP);
          }

          // OTP send on Email
          sendOTPToUser($email,$otp);

        }else{
          $array = array(
            'msg' => "You have reached the otp limit, try after 5 mins",
            'data' => $POST
          );
          http_response_code(500);
          echo json_encode($array);
          exit;  
        }

        $resendLeft = 3-$token_send_count;
        $array = array(
          'msg' => "OTP send successfully, If you are registered with us you will get an otp in your registered email..",
          'resendLeft'=>$resendLeft,
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;  
      }

      function sendOTPToUser($email,$otp){
        // Get user email
        $getEmailQuery = "SELECT first_name,last_name FROM `users` where email = '$email'";
        $getEmailData = select($getEmailQuery);
  
        $loggedInUser = $getEmailData['first_name']." ".$getEmailData['last_name'];
        //Send Email       
        $toEmail = $email;
        $subject = "Maruti Studio | OTP for forgot password";
        // $adminSub = "Order return has been initiated On Shuttleshop";
        
        $getEmailtempQuery = "SELECT email_body from email_template where id=10";
        $getEmailtempData = select($getEmailtempQuery);
        $emailBody = $getEmailtempData['email_body'];
        //Replace
        $fullName = $loggedInUser;
        $emailBody=str_replace("[Name]",$fullName,$emailBody);
        $emailBody=str_replace("[OTP]",$otp,$emailBody);
  
        // Always set content-type when sending HTML email
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
  
        // More headers
        $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>';
       
        //echo $mailHeaders;
        if (mail($toEmail,$subject,$emailBody,$headers)) {
            $message2 = 'Sent successfully';
            //echo $message2;
        }
  
        // if (mail("info@shuttleshop.in", $adminSub,$adminBody,$headers)) {
        //     $message = 'Sent successfully';
        // }
        //Email ended
      }


      function resetPassword($POST){
        $email = $POST['email'];
        $otp = $POST['otp'];
        $password = $POST['password'];
        $retry_password = $POST['retry_password'];

        $currTime = date('Y-m-d H:i:s');

        if($password != $retry_password){
          $array = array(
            'msg' => "Password and confirm password should match..",
            'data' => $POST
          );
          http_response_code(500);
          echo json_encode($array);
          exit;    
        }else{
          //Check if otp match with record
          $getByOTPQuery = "SELECT id,email,updated_at FROM `users` WHERE fp_token= '$otp'";
          $getByOTPRes = select($getByOTPQuery);

          if(isset($getByOTPRes['id'])){
            //check if otp exipred or not
            $updatedAtFromTable = $getByOTPRes['updated_at'];
            $duration='+5 minutes';
            $add5min = date('Y-m-d H:i:s', strtotime($duration, strtotime($updatedAtFromTable)));

            if(strtotime($currTime) > strtotime($add5min)){
              $array = array(
                'msg' => "OTP is invalid or expired..",
                'data' => $POST
              );
              http_response_code(500);
              echo json_encode($array);
              exit;    
            }else{
              //If otp not expired and everthing is fine update new password
              $user_id = $getByOTPRes['id'];
              $hash_password=password_hash($password,PASSWORD_DEFAULT);
              $updatePassword = "UPDATE users SET password = '$hash_password', updated_at='$currTime' where id=$user_id and email='$email'";
              update($updatePassword);

              $array = array(
                'msg' => "Your password has been reset successfully !!",
                'data' => $POST
              );
              http_response_code(200);
              echo json_encode($array);
              exit;  
            }
          }else{
            $array = array(
              'msg' => "OTP is invalid or expired..",
              'data' => $POST
            );
            http_response_code(500);
            echo json_encode($array);
            exit;    
          }
        }
      }



    //Get home image 
    function getHomeImg(){
      $query = "SELECT id, product_name FROM `products` WHERE status= 1";
      $query_res = selectMultiple($query);

      $offerquery = "SELECT * FROM `offerimg` WHERE id= 1";
      $offerquery_res = select($offerquery);

      $array = array(
      'msg' => "Home page images fetched.",
      'data' => $query_res,
      'offerdata' => $offerquery_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getBrandImg(){
      $query = "SELECT * FROM `brands` WHERE id= 1";
      $query_res = select($query);

      $array = array(
      'msg' => "Home page images fetched.",
      'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>