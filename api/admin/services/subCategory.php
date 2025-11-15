<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    //add subcategory
    function addSubCategory($POST){
      // Validation
      $catId = $POST['category'];
      $level1category = $POST['level1category'];
      $sub_category = $POST['sub_category'];

      $query = "select * from sub_category where sub_category_name='$sub_category'";
      $query_res = select($query);
      if($query_res){
        $array = array(
          'msg' => "Sub Category already exist..",
          'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }else{
          // Insert Data
          $level =1;
          if($level1category){
            $level =2;
          }
          
          $user_id=$GLOBALS['jwt_token']->user_id;
          $cols =  array("sub_category_name","category_id","sub_category_id","level","created_by","created_at","status");
          $values =  array($sub_category, $catId, $level1category, $level, $user_id ,date('Y-m-d H:i:s'),1);
          $table_name = "sub_category";
          $insData = insert($cols, $values,$table_name);

          $array = array(
              'msg' => "Sub Category Added Successfully..",
              'data' => $_POST
          );
          http_response_code(200);
          echo json_encode($array);
          exit;
      }      
    }

    //get subcategory
    function getSubCategory($POST){
      $status = $POST['status'];

      if(isset($POST['level']))
        $level = $POST['level'];
      else  
        $level = "";

      if(isset($POST['categoryid']))
        $categoryid = $POST['categoryid'];
      else  
        $categoryid = "";

      if(isset($POST['level1catid']))
        $level1catid = $POST['level1catid'];
      else  
        $level1catid = "";

      $query = "select sc.*,c.category_name,u.first_name,u.last_name from sub_category as sc left join category as c on c.id=sc.category_id left join users as u on u.id=sc.created_by where sc.is_deleted=1";
      if($level){
        $query .= " and sc.level=$level";
      }
      if($categoryid){
        $query .= " and sc.category_id=$categoryid";
      }
      if($level1catid){
        $query .= " and sc.sub_category_id=$level1catid";
      }
      if($status!='*'){
        $query .= " and sc.status='$status'";
      }

      $query_res = selectMultiple($query);

      // Adding l1 in result array
      if($level == 2){
        $count=0;
        foreach($query_res as $res){
          $l1catid = $res['sub_category_id'];
          
          $l1Query = "SELECT * from sub_category where id=$l1catid and is_deleted=1 ";
          if($status!='*'){
            $l1Query .= " and status='$status'";
          }
          $l1Res = select($l1Query);

          $query_res[$count]['l1CatName'] = $l1Res['sub_category_name'];

          $count++;
        }
      }

      $array = array(
          'msg' => "Sub Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    // delete subcategory
    function deleteSubcategory($POST){
      $id = $POST['id'];
      $selectSubCatQuery = "SELECT * from sub_category where id = $id";
      $subCatData = select($selectSubCatQuery);

      $flag = false;

      if($subCatData['level']==1){
        //Get if this L1 cat having any non deleted L2 sub category
        $getL2SubCatQuery = "SELECT * from sub_category where sub_category_id = $id and is_deleted=1";
        $subCatData = selectMultiple($getL2SubCatQuery);
        if(isset($subCatData) && count($subCatData)){
          $flag = true;
          $array = array(
            'msg' => "You cant delete this L1 sub category, as this sub category having (Active/Inactive/Approval Pending) L2 sub categories"
          );
          http_response_code(400);
          echo json_encode($array);
          exit;
        }
      }

      if($flag == false){
        $deleteCouponQuery = "UPDATE sub_category SET is_deleted=0 where id = $id";
        delete($deleteCouponQuery);

        $array = array(
          'msg' => "Sub Category Deleted.."
        );
        http_response_code(200);
        echo json_encode($array);
        exit;   
      }  
      
    }

    // Get sub cat by id
    function getUniqueSubCategory($POST){
      $id = $POST['id'];
      $query = "SELECT * FROM `sub_category` WHERE id=$id; ";
      
      $query_res = select($query);

      $array = array(
          'msg' => "Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //editL1Category
    function editL1Category($POST){
      $id = $POST['id'];
      $level1category = $POST['level1category'];

      $queryL1Update = "UPDATE `sub_category` SET sub_category_name='$level1category' WHERE id=$id; ";
      update($queryL1Update);

      $array = array(
          'msg' => "L1 Category Updated..",
          'data' => $POST
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //editL2Category
    function editL2Category($POST){
      $id = $POST['id'];
      $level1category = $POST['level1category'];
      $sub_category = $POST['sub_category'];

      $queryL1Update = "UPDATE `sub_category` SET sub_category_name='$sub_category' WHERE id=$id; ";
      update($queryL1Update);

      $array = array(
          'msg' => "L2 Category Updated..",
          'data' => $POST
      );
      http_response_code(200);
      echo json_encode($array);
    }

    //changeCategoryStatus
    function changeSubCategoryStatus($POST){
      $id = $POST['id'];
      $status = $POST['status'];
      $resMsg = "";

      $subCatQuery = "SELECT * from sub_category where id=$id";
      $subCatData = select($subCatQuery);

      $catId = $subCatData['category_id'];
      $subCatId = $subCatData['sub_category_id'];

      $flag = false;
      $msg="";

      if($subCatData['level']==1){
        // When activating check if parent is active or not
        if($status==0){
          // Get cat status before activating
          $catStatusQuery = "SELECT * from category where id=$catId";
          $catData = select($catStatusQuery);
          $catStatus = $catData['status'];

          if($catStatus==0 || $catStatus==2){
            $msg="You can not activate this L1 sub category as parent (category) is not active";
            $flag = true;
          }
        // When deactivating check if any active childen is there
        }else{
          $getActiveL2SubCat = "SELECT count(*) as count from sub_category where sub_category_id = $id and status=1";
          $getActiveL2SubCatData = select($getActiveL2SubCat);
          if($getActiveL2SubCatData['count']>1){
            $msg="You can not decactivate this L1 sub category as its having active L2 sub category";
            $flag = true;
          }
        }
      }else if($subCatData['level']==2){
        if($status==1){
          //Check if parent sub_cat is inactive
          $salSubCatStatusL1 = "SELECT * from sub_category where id=$subCatId";
          $salSubCatStatusL1Data = select($salSubCatStatusL1);
          if($salSubCatStatusL1Data['status']==0 || $salSubCatStatusL1Data['status']==2){
            $msg="You can not activate this L2 sub category as parent (L1 sub category) is not active";
            $flag = true;
          }
        }
      }

      if($flag == true){
        $array = array(
          'msg' => $msg
        );
        http_response_code(400);
        echo json_encode($array);
        exit;
      }else{
        if($status == 0){
          $newStatus = 1;
          $resMsg = "Sub Category Activated";
        }else if($status == 1){
          $newStatus = 0;
          $resMsg = "Sub Category De-Activated";
        }else if($status == 2){
          $newStatus = 1;
          $resMsg = "Sub Category Approved";
        }else{
          $newStatus = 0;
        }

        $statusQuery = "UPDATE sub_category SET status = $newStatus where id=$id";
        update($statusQuery);

        $array = array(
          'msg' => $resMsg
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
      }
    }

    
    //get foodsubcategory
    function getFoodSubCategory($POST){
      $status = $POST['status'];

      if(isset($POST['level']))
        $level = $POST['level'];
      else  
        $level = "";

      if(isset($POST['categoryid']))
        $categoryid = $POST['categoryid'];
      else  
        $categoryid = "";

      if(isset($POST['level1catid']))
        $level1catid = $POST['level1catid'];
      else  
        $level1catid = "";

      $query = "select sc.*,c.category_name,u.first_name,u.last_name from sub_category as sc left join category as c on c.id=sc.category_id left join users as u on u.id=sc.created_by where c.pgm_type=2 and sc.is_deleted=1";
      if($level){
        $query .= " and sc.level=$level";
      }
      if($categoryid){
        $query .= " and sc.category_id=$categoryid";
      }
      if($level1catid){
        $query .= " and sc.sub_category_id=$level1catid";
      }
      if($status!='*'){
        $query .= " and sc.status='$status'";
      }

      $query_res = selectMultiple($query);

      // Adding l1 in result array
      if($level == 2){
        $count=0;
        foreach($query_res as $res){
          $l1catid = $res['sub_category_id'];
          
          $l1Query = "SELECT * from sub_category where id=$l1catid and is_deleted=1 ";
          if($status!='*'){
            $l1Query .= " and status='$status'";
          }
          $l1Res = select($l1Query);

          $query_res[$count]['l1CatName'] = $l1Res['sub_category_name'];

          $count++;
        }
      }

      $array = array(
          'msg' => "Sub Category Fetched..",
          'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
    }
?>