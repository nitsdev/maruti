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
            $values =  array($catName, strtolower($final_image), $user_id ,date('Y-m-d H:i:s'),1);
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

    // get all category
    function getCategory($POST){
      $status = $POST['status'];
      $where="1=1";
      if($status!="*"){
        $where="c.status='$status'";
      }
      $query = "select c.id as category_id,c.*,u.first_name,u.last_name from category as c left join users as u on c.created_by=u.id where $where and c.is_deleted = 1";
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //get category by id
    function getUniqueCategory($POST){
      $id = $POST['id'];
      $query = "SELECT * FROM `category` WHERE id=$id; ";
      
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
    // Validation
    $category_name = $POST['category'];
    $category_image = $_FILES['category_image'];

    $query = "SELECT * from category where category_name='$category_name' and id != $id and is_deleted=1";
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
        $categoryQuery = "SELECT * from category where id = $id";
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

        $updateCatQuery = "UPDATE category SET category_name = '$category_name'".$imageUpdateQuery." where id=$id";
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

  //changeCategoryStatus
  function changeCategoryStatus($POST){
    $id = $POST['id'];
    $status = $POST['status'];
    
    //Get sub cat by category those are active
    $getActiveSubCat = "Select * from sub_category where category_id = $id and status=1";
    $activeSubCat = selectMultiple($getActiveSubCat);

    // When deactive check if category having active L1 sub category
    if(count($activeSubCat) && $status==1){
      $array = array(
        'msg' => "You cant deactivate this category, as this category having active L1 sub categories"
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    }else{
      $resMsg = "";

      if($status == 0){
        $newStatus = 1;
        $resMsg = "Category Activated";
      }else if($status == 1){
        $newStatus = 0;
        $resMsg = "Category De-Activated";
      }else if($status == 2){
        $newStatus = 1;
        $resMsg = "Category Approved";
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
  }

  //checkedFeaturedStatus
  function checkedFeaturedStatus($POST){
    $catId = $POST["catid"];
    $getCatQuery = "SELECT * from category where id=$catId";
    $getCatData = select($getCatQuery);

    $catFeatureStatus = 0;
    $msg = "Category removed from featured section..";
    if($getCatData["is_featured"] == 0){
      $catFeatureStatus = 1;
      $msg = "Category added to featured section..";
    }

    $updateQuery = "UPDATE category SET is_featured=$catFeatureStatus where id=$catId";
    update($updateQuery);

    $array = array(
      'msg' => $msg,
      'data' => $POST
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

    // get all category
    function getAllCategory($POST){
      $status = $POST['status'];
      $where="";
      if($status!="*"){
        $where="c.status='$status'";
      }
      $query = "select c.id as category_id,c.*,u.first_name,u.last_name from category as c left join users as u on c.created_by=u.id where $where and is_deleted=1";
      
      $query_res = selectMultiple($query);

      $array = array(
          'msg' => "Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>