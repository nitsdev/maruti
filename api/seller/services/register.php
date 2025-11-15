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
      $userType =  GROCERY_USER_TYPE;
      $query = "select * from users where (email='$email' or mobile='$mobile') and user_role=2 and user_type=$userType";
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
      $cols =  array("first_name", "last_name", "email","mobile","password","user_role","status","created_at","user_type");
      $values =  array($POST['first_name'], $POST['last_name'], $POST['email'],$POST['mobile'],$hash_password,2,2,date('Y-m-d H:i:s'),$userType);
      $table_name = "users";
      $insData = insert($cols, $values,$table_name);

      $isValidated = false;
      $uploadedImgs = [];

      //File upload
      $valid_extensions = array('pdf'); // valid extensions
      $uploadpath = './../../upload/seller_ids/'; // upload directory
      $imageArray = array();
      $imageTempArray = array();
      $finalImageArry=array();
      $imageExtension=array();

      // Id proof
      if(count($_FILES['idproof']['name']))
      {
        for($imgCount=0; $imgCount<count($_FILES['idproof']['name']);$imgCount++){
          $img = $_FILES['idproof']['name'][$imgCount];
          $tmp = $_FILES['idproof']['tmp_name'][$imgCount];
          // get uploaded file's extension
          $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
          // can upload same image using rand function
          $final_image = rand(1000,1000000).time();

          array_push($finalImageArry,$final_image);
          array_push($imageExtension,$ext);

          // check's valid format
          if(in_array($ext, $valid_extensions)) 
          { 
            array_push($imageArray,$img);
            array_push($imageTempArray,$tmp);
          }else{
            deleteData($insData,$uploadedImgs);
            $array = array(
                'msg' => "Invalid file formate passed for seller ID..",
                'data' => $_POST
            );
            http_response_code(401);
            echo json_encode($array);
            exit;
          }
        }      

        //Upload image
        for($i=0; $i<count($imageArray); $i++){
          $path = $uploadpath.strtolower($finalImageArry[$i].".".$imageExtension[$i]); 
          move_uploaded_file($imageTempArray[$i],$path);

          array_push($uploadedImgs,$path);

          $cols =  array("seller_id","name","extension","type","url");
          $values =  array($insData,$imageArray[$i],$imageExtension[$i], $imageExtension[$i] ,"upload/seller_ids/".$finalImageArry[$i].".".$imageExtension[$i]);
          $table_name = "seller_ids";
          insert($cols, $values,$table_name);
        }
        $isValidated = true;
        
      }else{
        deleteData($insData,$uploadedImgs);

        $isValidated = false;
        $array = array(
            'msg' => "Please upload ID proof..",
            'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }

      // Shop Image

      //File upload
      $valid_extensions = array('png','jpg','jpeg'); // valid extensions
      $uploadpath = './../../upload/seller_ids/'; // upload directory
      $imageArray = array();
      $imageTempArray = array();
      $finalImageArry=array();
      $imageExtension=array();

      if(count($_FILES['shopimage']['name']))
      {
        for($imgCount=0; $imgCount<count($_FILES['shopimage']['name']);$imgCount++){
          $img = $_FILES['shopimage']['name'][$imgCount];
          $tmp = $_FILES['shopimage']['tmp_name'][$imgCount];
          // get uploaded file's extension
          $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
          // can upload same image using rand function
          $final_image = rand(1000,1000000).time();

          array_push($finalImageArry,$final_image);
          array_push($imageExtension,$ext);

          // check's valid format
          if(in_array($ext, $valid_extensions)) 
          { 
            array_push($imageArray,$img);
            array_push($imageTempArray,$tmp);
          }else{
            deleteData($insData,$uploadedImgs);
            $array = array(
                'msg' => "Invalid file formate passed for shop image only jpg, png, jpeg allowed..",
                'data' => $_POST
            );
            http_response_code(401);
            echo json_encode($array);
            exit;
          }
        }      

        //Upload image
        for($i=0; $i<count($imageArray); $i++){
          $path = $uploadpath.strtolower($finalImageArry[$i].".".$imageExtension[$i]); 
          move_uploaded_file($imageTempArray[$i],$path);

          array_push($uploadedImgs,$path);

          $cols =  array("seller_id","name","extension","type","url");
          $values =  array($insData,$imageArray[$i],$imageExtension[$i], $imageExtension[$i] ,"upload/seller_ids/".$finalImageArry[$i].".".$imageExtension[$i]);
          $table_name = "seller_ids";
          insert($cols, $values,$table_name);
        }
        $isValidated = true;
        
      }else{
        deleteData($insData,$uploadedImgs);
        
        $isValidated = false;
        $array = array(
            'msg' => "Please upload shop image..",
            'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }

      if($isValidated == true){
        //Send Welcome Email
        sendWelcomeEmail($POST['first_name'],$POST['last_name'],$POST['email']);

        
        $_POST["user_role"]="Seller";
        $array = array(
            'msg' => "Registation Success..",
            'data' => $_POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
      }
    }

    function deleteData($insData,$uploadedImgs){
      // Delete data from users if insertion failed
      $deleteUserQuery = "DELETE from users where id=$insData";
      delete($deleteUserQuery);

      // Delete data from seller_ids if insertion failed
      $deleteIDsQuery = "DELETE from seller_ids where seller_id=$insData";
      delete($deleteIDsQuery);

      for($img=0; $img<count($uploadedImgs); $img++){
        if(unlink($uploadedImgs[$img]))
        {
            
        }
      }
    }

    function sendWelcomeEmail($first_name,$last_name,$email){
      //Send Email       
      $toEmail = $email;
      $subject = "Maruti Studio | Welcome to Shuttleshop";
      
      $getEmailtempQuery = "SELECT email_body from email_template where id=8";
      $getEmailtempData = select($getEmailtempQuery);
      $emailBody = $getEmailtempData['email_body'];
      //Replace
      $fullName = $first_name.' '.$last_name;
      $emailBody=str_replace("[Name]",$fullName,$emailBody);

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
?>