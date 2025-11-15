<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    


    // add address
    function addAddresses($POST){
      $firstName = $POST['firstName'];
      $lastName = $POST['lastName'];
      $shopName = isset($POST['shopName']) ? $POST['shopName'] : "";
      $email = $POST['email'];
      $mobile = $POST['mobile'];
      $add_line1 = $POST['addLine1'];
      $add_line2 = $POST['addLine2'];
      $country_id = $POST['country'];
      $state_id = $POST['state'];
      $city_id = $POST['city'];
      $landmark = $POST['landmark'];
      $pincode = $POST['pincode'];
      $whname = $POST['firstName']."".$POST['mobile'];
      $whname = str_replace(" ","",$whname);
      $is_shipping = 1;
      $is_billing = 1;
      $user_id=$GLOBALS['jwt_token']->user_id;
      $address = $add_line1.",".$add_line2;
      // $country = $POST['country_name'];
      // $state = $POST['state_name'];
      // $city = $POST['city_name'];


      $id=0;
      if(isset($POST['id']) && $POST['id']){
        $id = $POST['id'];
      }

      $selectBywhQuery = "select * from addresses where wh_name = '$whname'";
      $whNameData = select($selectBywhQuery);

      //Add warehouse in delhivery

      if($id == 0){
        $url ='https://track.delhivery.com/api/backend/clientwarehouse/create/';
      }else{
        $url ='https://track.delhivery.com/api/backend/clientwarehouse/edit/';
      }
      // $curl = curl_init();

      // $inputData = '{
      //   "name": "'.$whname.'",
      //   "email": "'.$email.'",
      //   "phone": "'.$mobile.'",
      //   "address": "'.$address.'",
      //   "city": "'.$city.'",
      //   "country": "'.$country.'",
      //   "pin": "'.$pincode.'",
      //   "return_address": "'.$address.'",
      //   "return_pin": "'.$pincode.'",
      //   "return_city": "'.$city.'",
      //   "return_state": "'.$state.'",
      //   "return_country": "'.$country.'"
      // }';

      
      // curl_setopt_array($curl, array(
      //   CURLOPT_URL => $url,
      //   CURLOPT_RETURNTRANSFER => true,
      //   CURLOPT_ENCODING => '',
      //   CURLOPT_MAXREDIRS => 10,
      //   CURLOPT_TIMEOUT => 0,
      //   CURLOPT_FOLLOWLOCATION => true,
      //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //   CURLOPT_CUSTOMREQUEST => 'POST',
      //   CURLOPT_POSTFIELDS =>$inputData,
      //   CURLOPT_HTTPHEADER => array(
      //     'Content-Type: application/json',
      //     'Accept: application/json',
      //     'Authorization: Token 441d3229ab31ac8b3518e0d94fed6dcca746db3c-1'
      //   ),
      // ));
      
      // $response = curl_exec($curl);
      
      // curl_close($curl);

      if(!$id){
        // Insert Data
        $cols =  array("first_name","last_name", "shop_name","email","mobile","add_line1","add_line2","country_id" ,"state_id" ,"city_id" ,"landmark" ,"pincode" , "is_shipping","is_billing","user_id","wh_name");
        $values =  array($firstName, $lastName, $shopName, $email ,$mobile,$add_line1 ,$add_line2,$country_id,$state_id,$city_id,$landmark,$pincode,$is_shipping,$is_billing,$user_id,$whname);
        $table_name = "addresses";
        $insData = $user_id;
        insert($cols, $values,$table_name);

        $uploadedImgs = [];

        // User Image

        //File upload
        $valid_extensions = array('png','jpg','jpeg'); // valid extensions
        $uploadpath = './../../upload/agent_ids/'; // upload directory
        $imageArray = array();
        $imageTempArray = array();
        $finalImageArry=array();
        $imageExtension=array();

        if(count($_FILES['sellerimage']['name']))
        {
          for($imgCount=0; $imgCount<count($_FILES['sellerimage']['name']);$imgCount++){
            $img = $_FILES['sellerimage']['name'][$imgCount];
            $tmp = $_FILES['sellerimage']['tmp_name'][$imgCount];
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
              $array = array(
                  'msg' => "Invalid file formate passed for image only jpg, png, jpeg allowed..",
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

            $cols =  array("agent_id","name","extension","type","url");
            $values =  array($insData,$imageArray[$i],$imageExtension[$i], $imageExtension[$i] ,"upload/agent_ids/".$finalImageArry[$i].".".$imageExtension[$i]);
            $table_name = "agent_ids";
            insert($cols, $values,$table_name);
          }
          $isValidated = true;
          
        }else{          
          $isValidated = false;
          $array = array(
              'msg' => "Please upload image..",
              'data' => $_POST
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }

        $array = array(
            'msg' => "Address Added successfully..",
            'data' => $insData
        );
        http_response_code(200);
        echo json_encode($array);
      }else{
          $user_id=$GLOBALS['jwt_token']->user_id;
          $currdate = date('Y-m-d H:i:s');
          $updateAddress = "UPDATE addresses SET first_name='$firstName',last_name='$lastName', shop_name='$shopName', email='$email',mobile='$mobile',add_line1='$add_line1',add_line2='$add_line2',country_id=$country_id ,state_id=$state_id ,city_id=$city_id ,landmark='$landmark' ,pincode='$pincode', wh_name='$whname' where id=$id and user_id=$user_id";
          update($updateAddress);

          $array = array(
            'msg' => "Address Updated successfully..",
            'data' => $POST
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
      }
    }

    //getAddresses
    function getAddresses(){
      $user_id=$GLOBALS['jwt_token']->user_id;
      $addressQuery = "select a.*,c.name as country_name,s.name as state_name,ct.name as city_name, a.id as addId from addresses as a 
          left join countries as c on c.id=a.country_id left join states as s on s.id=a.state_id left join cities as ct on ct.id=a.city_id where a.user_id=$user_id";
      $res = select($addressQuery);

      $user_id=$GLOBALS['jwt_token']->user_id;
      // Get Image
      $getImageQuery  = "SELECT * from agent_ids WHERE agent_id=$user_id";
      $img = select($getImageQuery);

      $array = array(
        'msg' => "Address Fetched..",
        'data' => $res,
        'img' => $img
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>