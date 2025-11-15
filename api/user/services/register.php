<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function register($POST){
      //Check if email or mobile already exist with user_role 1(user)
      $email=$POST['email'];
      $mobile=$POST['mobile'];
      $query = "select * from users where (email='$email' or mobile='$mobile') and user_role=3";
      $query_res = select($query);

      // If user email or mobile already exist
      if($query_res!=null){
        if(count($query_res)){
          http_response_code(400);
          $array = array(
              'msg' => "User Email or Mobile already exist...",
              'data' => $POST
          );
          echo json_encode($array);
          exit;
        }
      }
      
      // Insert user record in users table
      $hash_password=password_hash($POST['password'],PASSWORD_DEFAULT);
      // Cart_id geberate
      $randomString = generateRandomString(7);

      $cols =  array("first_name", "last_name", "email","mobile","password","user_role","status","created_at","cart_id");
      $values =  array($POST['first_name'], $POST['last_name'], $POST['email'],$POST['mobile'],$hash_password,3,1,date('Y-m-d H:i:s'),$randomString);
      $table_name = "users";
      insert($cols, $values,$table_name);
      
      $_POST["user_role"]="User";

      //Send Email       
      $toEmail = $email;
      $subject = "Welcome to Shuttleshop";
      $adminSub = "New user registered";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=1";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $POST['first_name']." ".$POST['last_name'];
      $emailBody=str_replace("[Name]",$fullName,$emailBody);

      $getEmailtempQuery = "SELECT email_body from email_template where id=2";
      $getEmailtempData = select($getEmailtempQuery);
      $adminBody = $getEmailtempData['email_body'];
      //Replace
      $adminBody=str_replace("[user]",$fullName,$adminBody);
      $adminBody=str_replace("[user-email]",$toEmail,$adminBody);

      // Always set content-type when sending HTML email
      $headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

      // More headers
      $headers .= 'From: <shuttleshop-noreply@shuttleshop.in>' . "\r\n";
      // $headers .= 'Cc: pravin@wistar.in' . "\r\n";
                  
      //echo $mailHeaders;
      if (mail($toEmail,$subject,$emailBody,$headers)) {
          $message = 'Sent successfully';
      }

      if (mail("info@shuttleshop.in", $adminSub,$adminBody,$headers)) {
          $message = 'Sent successfully';
      }
      //Email ended


      $array = array(
          'msg' => "Registation Success..",
          'data' => $_POST
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    function generateRandomString($length = 10) {
      $bytes = random_bytes(ceil($length / 2));
      $randomString = substr(bin2hex($bytes), 0, $length);
   
      return $randomString;
   }
?>