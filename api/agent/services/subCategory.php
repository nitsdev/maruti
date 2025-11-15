<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');


    // get subcategory
      function getSubCategory($POST){
        $status = $POST['status'];
        $user_id=$GLOBALS['jwt_token']->user_id;
        $query="";

        if(isset($POST['level']))
          $level = $POST['level'];
        else  
          $level = "";

        if(isset($POST['flag']))
          $flag = $POST['flag'];
        else  
          $flag = "";
  
        if(isset($POST['categoryid']))
          $categoryid = $POST['categoryid'];
        else  
          $categoryid = "";
  
        if(isset($POST['level1catid']))
          $level1catid = $POST['level1catid'];
        else  
          $level1catid = "";
  
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

        if($flag == '*'){
          $allActiveSubCat = "SELECT * from sub_category as sc where 1=1 $query";
          $query_res = selectMultiple($allActiveSubCat);
        }else{
          $subCatQuery = "select sc.*,c.category_name,u.first_name,u.last_name from sub_category as sc left join category as c on c.id=sc.category_id left join users as u on u.id=sc.created_by where 1=1 and sc.is_deleted=1 $query";   
          $query_res = selectMultiple($subCatQuery);
    
          // Adding l1 in result array
          if($level == 2){
            $count=0;
            foreach($query_res as $res){
              $l1catid = $res['sub_category_id'];
              
              $l1Query = "SELECT * from sub_category where id=$l1catid and is_deleted=1";
              $l1Res = select($l1Query);
              $query_res[$count]['l1CatName'] = $l1Res['sub_category_name'];
    
              $count++;
            }
          }
        }
  
        $array = array(
            'msg' => "Sub Category Fetched..",
            'data' => $query_res,
            'user_id'=>$user_id
        );
        http_response_code(200);
        echo json_encode($array);
      }

      // add subcategory
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
            $values =  array($sub_category, $catId, $level1category, $level, $user_id ,date('Y-m-d H:i:s'),2);
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


  function getUniqueSubCategory($POST){
    $status = $POST['status'];
    $id = $POST['id'];
    $user_id=$GLOBALS['jwt_token']->user_id;

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

    $query = "select sc.*,c.category_name,u.first_name,u.last_name from sub_category as sc left join category as c on c.id=sc.category_id left join users as u on u.id=sc.created_by where 1=1 and sc.id=$id and sc.created_by=$user_id and sc.is_deleted=1";
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
    
    $query_res = select($query);

    // Adding l1 in result array
    if($level == 2){
      $count=0;
      foreach($query_res as $res){
        $l1catid = $res['sub_category_id'];
        
        $l1Query = "SELECT * from sub_category where id=$l1catid and is_deleted=1";
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
      if($salSubCatStatusL1Data['status']==0){
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

?>