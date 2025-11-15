<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');


    // add category
    function addCategory($POST){
      // Validation
      $catName = $POST['category'];
      $query = "select * from category where category_name='$catName' and is_deleted=1";
      $query_res = select($query);
      if($query_res){
        $array = array(
          'msg' => "Category already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
        //File upload
        $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
        $path = './../../upload/category/'; // upload directory
        if($_FILES['category_image'])
        {
          $img = $_FILES['category_image']['name'];
          $tmp = $_FILES['category_image']['tmp_name'];
          // get uploaded file's extension
          $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
          // can upload same image using rand function
          $final_image = rand(1000,1000000).$img;
          // check's valid format
          if(in_array($ext, $valid_extensions)) 
          { 
            // Upload Image
            $path = $path.strtolower($final_image); 
            move_uploaded_file($tmp,$path);

            // Insert Data
            $user_id=$GLOBALS['jwt_token']->user_id;
            $cols =  array("category_name","category_image", "created_by","created_at","status");
            $values =  array($POST['category'], strtolower($final_image), $user_id ,date('Y-m-d H:i:s'),2);
            $table_name = "category";
            $insData = insert($cols, $values,$table_name);

            $array = array(
                'msg' => "Category Added Successfully..",
                'data' => $_POST
            );
            http_response_code(200);
            echo json_encode($array);
            exit;
          }else{
            $array = array(
                'msg' => "Invalid file formate passed..",
                'data' => $_POST
            );
            http_response_code(401);
            echo json_encode($array);
            exit;
          }
        }
      }      
    }

    //get category
    function getCategory($POST){
        $status = $POST['status'];
        // $id = $POST['id'];
        $user_id=$GLOBALS['jwt_token']->user_id;
        $where="";
        if($status!="*"){
          $where="and c.status=$status";
        }
        $whereid="";
        // if($id!="*"){
        //   $whereid="and c.id=$id";
        // }
        $query = "select c.id as category_id,c.*,u.first_name,u.last_name from category as c left join users as u on c.created_by=u.id where 1=1 $where $whereid and is_deleted=1";
        
        $query_res = selectMultiple($query);
  
        $array = array(
            'msg' => "Category Fetched..",
            'data' => $query_res
        );
        http_response_code(200);
        echo json_encode($array);
      }


      //get category by seller
      function getCategoryBySeller($POST){
        $status = $POST['status'];
        $user_id=$GLOBALS['jwt_token']->user_id;
        $where="";
        if($status!="*"){
          $where="and c.status='$status'";
        }
        $query = "select c.id as category_id,c.*,u.first_name,u.last_name from category as c left join users as u on c.created_by=u.id where 1=1 $where and is_deleted=1";
        
        $query_res = selectMultiple($query);
  
        $array = array(
            'msg' => "Category Fetched..",
            'data' => $query_res,
            'user_id'=>$user_id
        );
        http_response_code(200);
        echo json_encode($array);
      }

      //get unique category
      function getUniqueCategory($POST){
        $id = $POST['id'];
        $user_id=$GLOBALS['jwt_token']->user_id;
        $query = "select c.id as category_id,c.*,u.first_name,u.last_name from category as c left join users as u on c.created_by=u.id where 1=1 and created_by=$user_id and c.id=$id ";
        $query_res = select($query);
  
        $array = array(
            'msg' => "Category Fetched..",
            'data' => $query_res
        );
        http_response_code(200);
        echo json_encode($array);
      }

      //  edit category
  function editCategory($POST){
    $id = $POST['id'];
    $user_id=$GLOBALS['jwt_token']->user_id;
    // Validation
    $category_name = $POST['category'];
    $category_image = $_FILES['category_image'];

    $query = "SELECT * from category where category_name='$category_name' and id != $id and created_by=$user_id";
    $query_res = select($query);

    if($query_res){
      $array = array(
        'msg' => "Category name already exist..",
        'data' => $_POST
      );
      http_response_code(401);
      echo json_encode($array);
      exit;
    }else{
        $imageExist = false;

        // Category details by id
        $categoryQuery = "SELECT * from category where id = $id and created_by=$user_id";
        $categoryData = select($categoryQuery);

        $final_image = '';

        if($category_image['name']){
          $imageExist = true;

          //File upload // upload new image
          $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
          $path = './../../upload/category/'; // upload directory
          if($_FILES['category_image'])
          {
            $img = $_FILES['category_image']['name'];
            $tmp = $_FILES['category_image']['tmp_name'];
            $final_image = strtolower(rand(1000,1000000).$img);

            // get uploaded file's extension
            $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            // can upload same image using rand function
            // check's valid format
            if(in_array($ext, $valid_extensions)) 
            { 
              // Upload Image
              $path = $path.strtolower($final_image); 
              move_uploaded_file($tmp,$path);
            }else{
              $array = array(
                  'msg' => "Invalid file formate passed..",
                  'data' => $_POST
              );
              http_response_code(401);
              echo json_encode($array);
              exit;
            }
          }

          // Delete old data
          $filename = './../../upload/category/'.$categoryData['category_image'];
          if (file_exists($filename)) {
            unlink($filename);
          }

        }

        // Update db
        $imageUpdateQuery = "";
        if($imageExist){
          $imageUpdateQuery = " ,category_image='$final_image'";
        }

        $updateCatQuery = "UPDATE category SET category_name = '$category_name'".$imageUpdateQuery." where id=$id and created_by=$user_id";
        update($updateCatQuery);

        $array = array(
          'msg' => "Category Updated Successfully..",
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
    }     

  }

  
  function changeCatStatus($POST){
    $id = $POST['id'];
    $status = $POST['status'];
    $resMsg = "";

    if($status == 0){
      $newStatus = 1;
      $resMsg = "Category Activated";
    }else if($status == 1){
      $newStatus = 0;
      $resMsg = "Category De-Activated";
    }else{
      $newStatus = 0;
    }

    $statusQuery = "UPDATE category SET status = $newStatus where id=$id";
    update($statusQuery);

    $array = array(
      'msg' => $resMsg
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
   }

  // delete category
  function deleteCategory($POST){
    $id = $POST['id'];

    //Get sub cat by category those are active
    $getActiveSubCat = "Select * from sub_category where category_id = $id and is_deleted=1 ";
    $activeSubCat = selectMultiple($getActiveSubCat);
    
    // get product count associated with category id
    $productCountQuery = "select count(id) as pc from products where category_id = $id";
    $productCount = select($productCountQuery);

    if($productCount['pc']!=0){
      $array = array(
          'msg' => "Category can't be deleted as this category is associated with some products.",
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    } // When deactive check if category having active L1 sub category
    else if(count($activeSubCat)){
      $array = array(
        'msg' => "You cant delete this category, as this category having (Active/Inactive/Approval Pending) L1 sub categories"
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    }else{ 
      // Update is_deleted to 0
      $deleteQuery = "UPDATE category SET is_deleted=0 where id = $id";
      update($deleteQuery);

      $array = array(
        'msg' => "Category Deleted.."
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }
   }

?>