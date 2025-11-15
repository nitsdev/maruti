<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');
    

    function insertHomeImg($POST){
            
            $homeImg1 = $_FILES['homeImg1']['name'];
            $homeImg2 = $_FILES['homeImg2']['name'];
            $homeImg3 = $_FILES['homeImg3']['name'];

            $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
            $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];
            $temp_homeImg3 = $_FILES['homeImg3']['tmp_name'];

            move_uploaded_file($temp_homeImg1, "./../../upload/homeImg/$homeImg1");
            move_uploaded_file($temp_homeImg2, "./../../upload/homeImg/$homeImg2");
            move_uploaded_file($temp_homeImg3, "./../../upload/homeImg/$homeImg3");
        
            $cols =  array("homeImg1","homeImg2","homeImg3");
            $values =  array("$homeImg1","$homeImg2","$homeImg3");
            $table_name = "homeimg";
            insert($cols, $values,$table_name);

            $array = array(
              'msg' => "Homepage Images Added Successfully..",
              'data' => $POST
            );
            http_response_code(200);
            echo json_encode($array);
            exit;


    }
    
    function addHomeImage($POST){
            
      $homeImg1 = $_FILES['homeImg1']['name'];
      $homeImg2 = $_FILES['homeImg2']['name'];
      $homeImg3 = $_FILES['homeImg3']['name'];
      $homeImg4 = $_FILES['homeImg4']['name'];

      $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
      $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];
      $temp_homeImg3 = $_FILES['homeImg3']['tmp_name'];
      $temp_homeImg4 = $_FILES['homeImg4']['tmp_name'];

      move_uploaded_file($temp_homeImg1, "./../../upload/offerImg/$homeImg1");
      move_uploaded_file($temp_homeImg2, "./../../upload/offerImg/$homeImg2");
        move_uploaded_file($temp_homeImg3, "./../../upload/offerImg/$homeImg3");
        move_uploaded_file($temp_homeImg4, "./../../upload/offerImg/$homeImg4");
  
      $cols =  array("homeImg1","homeImg2","homeImg3","homeImg4");
      $values =  array("$homeImg1","$homeImg2","$homeImg3","$homeImg4");
      $table_name = "offerimg";
      insert($cols, $values,$table_name);

      $array = array(
        'msg' => "Homepage Offer Images Added Successfully..",
        'data' => $POST
      );
      http_response_code(200);
      echo json_encode($array);
      exit;


}
    
    
    function updateHomeImage($POST){
      // Validation
      $homeImg1 = $_FILES['homeImg1']['name'];
      $homeImg2 = $_FILES['homeImg2']['name'];
      $homeImg3 = $_FILES['homeImg3']['name'];
  
      $query = "SELECT * from homeimg where homeImg1='$homeImg1' and id != 2";
      $query_res = select($query);
  
      if($query_res){
        $array = array(
          'msg' => "Image already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
          // Update db
          $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
          $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];
          $temp_homeImg3 = $_FILES['homeImg3']['tmp_name'];

          move_uploaded_file($temp_homeImg1, "./../../upload/homeImg/$homeImg1");
          move_uploaded_file($temp_homeImg2, "./../../upload/homeImg/$homeImg2");
          move_uploaded_file($temp_homeImg3, "./../../upload/homeImg/$homeImg3");
          $updateHomeImgQuery = "UPDATE homeimg SET homeImg1 = '$homeImg1', homeImg2='$homeImg2' , homeImg3='$homeImg3' where id=1";
          update($updateHomeImgQuery);
  
          $array = array(
            'msg' => "Home Images Updated Successfully..",
            'data' => $POST
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
      }     
  
    }
    
    function updateofferImage($POST){
      // Validation
      $homeImg1 = $_FILES['homeImg1']['name'];
      $homeImg2 = $_FILES['homeImg2']['name'];
      $heading1 = $POST['heading1'];
      $heading2 = $POST['heading2'];        
      $textDesc1 = $POST['textDesc1'];        
      $textDesc2 = $POST['textDesc2'];        
      $discount = $POST['discount'];

      $query = "SELECT * from offerimg where homeImg1='$homeImg1' and id != 1";
      $query_res = select($query);
  
      if($query_res){
        $array = array(
          'msg' => "Offer Image already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
          // Update db
          $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
          $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];

          move_uploaded_file($temp_homeImg1, "./../../upload/offerImg/$homeImg1");
          move_uploaded_file($temp_homeImg2, "./../../upload/offerImg/$homeImg2");
          $updateOfferImgQuery = "UPDATE offerimg SET homeImg1 = '$homeImg1', homeImg2='$homeImg2' , discount='$discount', heading1='$heading1', heading2='$heading2', textDesc1='$textDesc1', textDesc2='$textDesc2' where id=1";
          update($updateOfferImgQuery);
  
          $array = array(
            'msg' => "Offer Images Updated Successfully..",
            'data' => $POST
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
      }     
  
    }


    function updateBrandImage($POST){
      // Validation
      $homeImg1 = $_FILES['homeImg1']['name'];
      $homeImg2 = $_FILES['homeImg2']['name'];
      $homeImg3 = $_FILES['homeImg3']['name'];
      $homeImg4 = $_FILES['homeImg4']['name'];
      $homeImg5 = $_FILES['homeImg5']['name'];
      $homeImg6 = $_FILES['homeImg6']['name'];  
      $text1 = $POST['text1'];
      $text2 = $POST['text2'];
      $text3 = $POST['text3'];
      $text4 = $POST['text4'];
      $text5 = $POST['text5'];
      $text6 = $POST['text6'];
  
      $query = "SELECT * from brands where brand_one='$homeImg1' and id != 1";
      $query_res = select($query);
  
      if($query_res){
        $array = array(
          'msg' => "Brand Image already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
          // Update db
          $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
          $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];
          $temp_homeImg3 = $_FILES['homeImg3']['tmp_name'];
          $temp_homeImg4 = $_FILES['homeImg4']['tmp_name'];
          $temp_homeImg5 = $_FILES['homeImg5']['tmp_name'];
          $temp_homeImg6 = $_FILES['homeImg6']['tmp_name'];

          move_uploaded_file($temp_homeImg1, "./../../upload/brands/$homeImg1");
          move_uploaded_file($temp_homeImg2, "./../../upload/brands/$homeImg2");
          move_uploaded_file($temp_homeImg3, "./../../upload/brands/$homeImg3");
          move_uploaded_file($temp_homeImg4, "./../../upload/brands/$homeImg4");
          move_uploaded_file($temp_homeImg5, "./../../upload/brands/$homeImg5");
          move_uploaded_file($temp_homeImg6, "./../../upload/brands/$homeImg6");
          $updateOfferImgQuery = "UPDATE brands SET brand_one = '$homeImg1', brand_two='$homeImg2' , brand_three='$homeImg3', brand_four='$homeImg4', brand_five='$homeImg5', brand_six='$homeImg6', text1='$text1', text2='$text2', text3='$text3', text4='$text4', text5='$text5', text6='$text6' where id=1";
          update($updateOfferImgQuery);
  
          $array = array(
            'msg' => "Brands Images Updated Successfully..",
            'data' => $POST
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
      }     
  
    }

function addbrandImage($POST){
        
  $homeImg1 = $_FILES['homeImg1']['name'];
  $homeImg2 = $_FILES['homeImg2']['name'];
  $homeImg3 = $_FILES['homeImg3']['name'];
  $homeImg4 = $_FILES['homeImg4']['name'];
  $homeImg5 = $_FILES['homeImg5']['name'];
  $homeImg6 = $_FILES['homeImg6']['name'];
  $text1 = $POST['text1'];
  $text2 = $POST['text2'];
  $text3 = $POST['text3'];
  $text4 = $POST['text4'];
  $text5 = $POST['text5'];
  $text6 = $POST['text6'];
  $temp_homeImg1 = $_FILES['homeImg1']['tmp_name'];
  $temp_homeImg2 = $_FILES['homeImg2']['tmp_name'];
  $temp_homeImg3 = $_FILES['homeImg3']['tmp_name'];
  $temp_homeImg4 = $_FILES['homeImg4']['tmp_name'];
  $temp_homeImg5 = $_FILES['homeImg5']['tmp_name'];
  $temp_homeImg6 = $_FILES['homeImg6']['tmp_name'];

  move_uploaded_file($temp_homeImg1, "./../../upload/brands/$homeImg1");
  move_uploaded_file($temp_homeImg2, "./../../upload/brands/$homeImg2");
  move_uploaded_file($temp_homeImg3, "./../../upload/brands/$homeImg3");
  move_uploaded_file($temp_homeImg4, "./../../upload/brands/$homeImg4");
  move_uploaded_file($temp_homeImg5, "./../../upload/brands/$homeImg5");
  move_uploaded_file($temp_homeImg6, "./../../upload/brands/$homeImg6");

  $cols =  array("brand_one","brand_two","brand_three","brand_four","brand_five","brand_six","text1","text2","text3","text4","text5","text6");
  $values =  array("$homeImg1","$homeImg2","$homeImg3","$homeImg4","$homeImg5","$homeImg6","$text1","$text2","$text3","$text4","$text5","$text6");
  $table_name = "brands";
  insert($cols, $values,$table_name);

  $array = array(
    'msg' => "Homepage Offer Images Added Successfully..",
    'data' => $POST
  );
  http_response_code(200);
  echo json_encode($array);
  exit;
}
    
?>