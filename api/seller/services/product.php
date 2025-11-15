<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    require './../../api/common/common.php';

    function addProduct($POST){
      //Check if email or mobile already exist with user_role 1(user)
      $product_name=$POST['product_name'];
      $short_description=$POST['short_description'];
      $description=$POST['description'];
      $length=$POST['length'];
      $width=$POST['width'];
      $height=$POST['height'];
      $weight=$POST['weight'];
      $bullet1=$POST['bullet1'];
      $bullet2=$POST['bullet2'];
      $bullet3=$POST['bullet3'];
      $stock=$POST['stock'];
      $medicine_type=$POST['medicine_type'];
      $category_id=$POST['category_id'];
      $price=$POST['price'];
      $discount=$POST['discount'];
      $hsn=$POST['hsn'];
      $gst=$POST['gst'];      

      $level1category_id="";
      if(isset($POST['level1category_id'])){
        $level1category_id=$POST['level1category_id'];
      }
      $sub_category_id="";
      if(isset($POST['sub_category_id'])){
        $sub_category_id = $POST['sub_category_id'];
      }
      $sku = $randomString = generateRandomString(5);

      //check if address is added or not
      $user_id=$GLOBALS['jwt_token']->user_id;
      $addressQuery="select count(*) as count from addresses where user_id=$user_id";
      $addressRes = select($addressQuery);

      //check if bank details is added or not
      $bankDetailsQuery="select count(*) as count from seller_bank_details where user_id=$user_id";
      $bankDetailsRes = select($bankDetailsQuery);

      if($addressRes['count']==0){
        $array = array(
          'msg' => "Please add pickup address before adding product ",
          'data' => $POST
        );
        http_response_code(400);
        echo json_encode($array);
        exit;
      }else if($bankDetailsRes['count']==0){
        $array = array(
          'msg' => "Please add bank details before adding product ",
          'data' => $POST
        );
        http_response_code(400);
        echo json_encode($array);
        exit;
      }
      else{
        //need to validate by product name for cat id level1 and sub prod id
        $query = "select count(*) as count from products where product_name='$product_name' and category_id=$category_id";
        if($level1category_id){
          $query.=" and level1category_id=$level1category_id";
        }
        if($sub_category_id){
          $query.=" and sub_category_id=$sub_category_id";
        }

        $query_res = select($query);
        if($query_res['count']){
          $array = array(
            'msg' => "Product already exist with ".$product_name,
            'data' => $POST
          );
          http_response_code(400);
          echo json_encode($array);
          exit;
        }

        // Validate cat id and level id and sub id exist
        $missingflag=false;
        $field="";

        $query = "select count(*) as count from category where id=$category_id and status=1 and is_deleted=1";
        $query_res = select($query);
        if(!$query_res['count']){
          $missingflag=true;
          $field="Category";
        }

        if($level1category_id){
          $query = "select count(*) as count from sub_category where id=$level1category_id and category_id=$category_id and level=1 and status=1 and is_deleted=1";
          $query_res = select($query);
          if(!$query_res['count']){
            $missingflag=true;
            $field="Category and Level 1 Category";
          }
        }

        if($sub_category_id){
          $query = "select count(*) as count from sub_category where id=$sub_category_id  and category_id=$category_id and sub_category_id=$level1category_id and level=2 and status=1 and is_deleted=1";
          $query_res = select($query);
          if(!$query_res['count']){
            $missingflag=true;
            $field="Category, Level 1 Category and Sub Category";
          }
        }

        if($missingflag){
          $array = array(
            'msg' => $field." not matching..",
            'data' => $POST
          );
          http_response_code(400);
          echo json_encode($array);
          exit;
        }

      //File upload
      $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
      $uploadpath = './../../upload/product/'; // upload directory
      $imageArray = array();
      $imageTempArray = array();
      $finalImageArry=array();
      $imageExtension=array();

      if(count($_FILES['product_image']['name']))
      {
        for($imgCount=0; $imgCount<count($_FILES['product_image']['name']);$imgCount++){
          $img = $_FILES['product_image']['name'][$imgCount];
          $tmp = $_FILES['product_image']['tmp_name'][$imgCount];
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
                'msg' => "Invalid file formate passed..",
                'data' => $_POST
            );
            http_response_code(401);
            echo json_encode($array);
            exit;
          }
        }      


        $cols =  array("product_name", "short_description","description","length","width","height","weight","bullet1","bullet2","bullet3","stock","medicine_type","category_id","level1category_id","sub_category_id","product_owner","added_by","added_at","sku","status","price","discount","hsn","gst");
        $values =  array($product_name,$short_description,$description,$length,$width,$height,$weight,$bullet1,$bullet2,$bullet3,$stock,$medicine_type,$category_id,$level1category_id,$sub_category_id,$GLOBALS['jwt_token']->user_id,$GLOBALS['jwt_token']->user_id,date('Y-m-d H:i:s'),$sku,0,$price,$discount,$hsn,$gst);
        $table_name = "products";
        $insData = insert($cols, $values,$table_name);

        //Upload image
        for($i=0; $i<count($imageArray); $i++){
          $path = $uploadpath.strtolower($finalImageArry[$i].".".$imageExtension[$i]); 
          move_uploaded_file($imageTempArray[$i],$path);

          $cols =  array("product_id","name","extension","type","url","is_image");
          $values =  array($insData,$imageArray[$i],$imageExtension[$i], $imageExtension[$i] ,"upload/product/".$finalImageArry[$i].".".$imageExtension[$i],1);
          $table_name = "product_images";
          insert($cols, $values,$table_name);
        }

        $array = array(
            'msg' => "Product Added Successfully..",
            'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
      }
    }
  }

  function generateRandomString($length = 10) {
      $bytes = random_bytes(ceil($length / 2));
      $randomString = substr(bin2hex($bytes), 0, $length);
   
      return $randomString;
   }

  //  Change status only if product status is approved by admin
  function changeStatus($POST){
    $status=$POST['status'];
    $id=$POST['id'];
    $table_name = "products";

    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "select status,product_name,added_by,product_owner from $table_name where id=$id";
    $query_res = select($query);
    //Below functions are in common.php
    //Check if product id is valid 
    checkProductExist($query_res,$POST);

    //Seller can not update other sellers product
    checkSellerHaveAccessProduct($query_res,$POST,$user_id);    

    // Seller can only make status either 1 or 2 or 3 what if seller pass status as 0
    checkProductStatusCode($status, $POST);

    // If product status is 0 means not activated by admin then seller can not chage the status of product
    if($query_res['status'] == 0){
      $array = array(
        'msg' => "Product need to be approved by admin first, until you can not change the status of product- id:".$id." ".$query_res['product_name'],
        'data' => $POST
      );
      http_response_code(400);
      echo json_encode($array);
      exit;
    }

    if($status==1){
      $status=2;
    }else if($status==2){
      $status=1;
    }
    
    // Update product details
    $table_name = "products";
    $updated_at=date('Y-m-d H:i:s');
    $query = "UPDATE $table_name SET status = $status, updated_by=$user_id, updated_at='$updated_at' WHERE id = $id";
    update($query);

    $array = array(
        'msg' => "Product Status Updated Successfully..",
        'data' => $POST
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }


  //get all products
  function getAllProducts($POST){
    $status=$POST['status'];
    $from=$POST['from']-1;
    $to=$POST['to'];
    $perpage=$POST['perpage'];
    
    $user_id=$GLOBALS['jwt_token']->user_id;
    $where=" where 1=1";
    if($status!="*"){
      $where="where p.status='$status'";
    }
    $table_name = "products";
    $query = "SELECT 
                  p.*, 
                  pi.*, 
                  p.id AS product_id, 
                  c.category_name, 
                  sc1.sub_category_name AS level1cat, 
                  sc2.sub_category_name AS sub_category, 
                  u.first_name, 
                  u.last_name
              FROM 
                  products p
              LEFT JOIN 
                  product_images pi 
                  ON p.id = pi.product_id
              JOIN 
                  category c 
                  ON c.id = p.category_id
              JOIN 
                  sub_category sc1 
                  ON sc1.id = p.level1category_id
              LEFT JOIN 
                  sub_category sc2 
                  ON sc2.id = p.sub_category_id
              LEFT JOIN 
                  users u 
                  ON u.id = p.product_owner
              WHERE 
                  p.status != 3 
                  AND p.product_owner = 10
              GROUP BY 
                  p.id
              ORDER BY 
                  p.id DESC
              LIMIT 0, 10
              ";
    // $query = "select p.*,pi.*,p.id as product_id,c.category_name,sc1.sub_category_name as level1cat, sc2.sub_category_name as sub_catgory from $table_name p left JOIN product_images pi on p.id=pi.product_id JOIN category c on c.id=p.category_id JOIN sub_category sc1 on sc1.id=p.level1category_id LEFT JOIN sub_category sc2 on sc2.id=p.sub_category_id $where and product_owner=$user_id and p.status!=3 GROUP BY p.id DESC LIMIT $from, $perpage";
    // echo $query;
    $query_res = selectMultiple($query);

    $table_name = "products";
    $countQuery = "select count(p.id) as pcount from $table_name as p $where and status!=3 and product_owner=$user_id";
    $countData = select($countQuery);

    $array = array(
      'msg' => "All products Details..",
      'data' => $query_res,
      'countData' => $countData
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  // Get single product details
  function getSingleProduct($POST){
    $table_name = "products";
    $id = $POST['id'];
    $user_id=$GLOBALS['jwt_token']->user_id;

    $query = "select * from $table_name where id=$id and product_owner=$user_id";
    $query_res = select($query);

    $product_image_query= "select * from product_images where product_id= $id ";
    $product_image_query_res = selectMultiple($product_image_query);

    //Check if product id is valid 
    checkProductExist($query_res,$POST);

    //Seller can not see other sellers product
    //checkSellerHaveAccessProduct($query_res,$POST,$user_id);
    
    $array = array(
      'msg' => "Product Details..",
      'data' => $query_res,
      'image_data' => $product_image_query_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  //  edit product
  function editProduct($POST){
    // Validation
    $id = $POST['id'];
    $product_name = $POST['product_name'];
    $category_id = $POST['category_id'];
    $level1category_id = $POST['level1category_id'];
    if(isset($POST['sub_category_id']) && $POST['sub_category_id'])
      $sub_category_id = $POST['sub_category_id'];
    else
      $sub_category_id=0;
    $description = $POST['description'];
    $short_description = $POST['short_description'];
    $bullet1 = $POST['bullet1'];
    $bullet2 = $POST['bullet2'];
    $bullet3 = $POST['bullet3'];
    $price = $POST['price'];
    $discount = $POST['discount'];
    $stock = $POST['stock'];
    $medicine_type = $POST['medicine_type'];
    $length = $POST['length'];
    $width = $POST['width'];
    $height = $POST['height'];
    $weight = $POST['weight'];
    if(isset($POST['hsn']) && $POST['hsn'])
      $hsn=$POST['hsn'];
    else
      $hsn="";
    $gst=$POST['gst']; 

    $query = "select * from products where product_name='$product_name' and id != $id";
    $query_res = select($query);
    if($query_res){
      $array = array(
        'msg' => "Product name already exist..",
        'data' => $_POST
      );
      http_response_code(401);
      echo json_encode($array);
      exit;
    }else{
      //File upload
      $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
      $uploadpath = './../../upload/product/'; // upload directory
      $imageArray = array();
      $imageTempArray = array();
      $finalImageArry=array();
      $imageExtension=array();

      if(count($_FILES['product_image']['name']))
      {
        for($imgCount=0; $imgCount<count($_FILES['product_image']['name']);$imgCount++){
          $img = $_FILES['product_image']['name'][$imgCount];
          $tmp = $_FILES['product_image']['tmp_name'][$imgCount];

          if($img){
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
                  'msg' => "Invalid file formate passed..",
                  'data' => $_POST
              );
              http_response_code(401);
              echo json_encode($array);
              exit;
            }
          }else{
            array_push($imageArray,'');
            array_push($finalImageArry,'');
            array_push($imageTempArray,'');
            array_push($imageExtension,'');
          }
        }      

        // Update on db
        $user_id=$GLOBALS['jwt_token']->user_id;
        $currdate = date('Y-m-d H:i:s');
        $updateQuery = "update products SET product_name='$product_name', short_description='$short_description', description='$description', price=$price, discount=$discount, length=$length, width=width, height=$height, weight=weight, bullet1='$bullet1', bullet2='$bullet2', bullet3='$bullet3', stock=$stock, medicine_type=$medicine_type, category_id=$category_id, level1category_id=$level1category_id, sub_category_id=$sub_category_id, product_owner=$user_id, status='0', updated_by= $user_id, updated_at='$currdate',hsn='$hsn',gst=$gst where id = $id and product_owner=$user_id";
        update($updateQuery);  
     
        //get all images
        $imgQuery = "select * from product_images where product_id = $id";
        $imgData = selectMultiple($imgQuery);

        //Upload image
        for($i=0; $i<count($imageArray); $i++){
          if($imageArray[$i]){
            $path = $uploadpath.strtolower($finalImageArry[$i].".".$imageExtension[$i]); 
            move_uploaded_file($imageTempArray[$i],$path);

            //Delete old image
            $filename = './../../'.$imgData[$i]['url'];
            if (file_exists($filename)) {
              unlink($filename);
            }

            //Update new data
            $url="upload/product/".$finalImageArry[$i].".".$imageExtension[$i];
            $imgId= $imgData[$i]['id'];
            $updateImageQuery = "update product_images set name = '$imageArray[$i]', extension	= '$imageExtension[$i]', type='$imageExtension[$i]', url='$url' where id =$imgId";
            update($updateImageQuery);
          }
        }

        $array = array(
          'msg' => "Product Updated Successfully..",
          'data' => $POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;

      }else{
        $array = array(
            'msg' => "Product images are mandatory",
            'data' => $_POST
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }
    }      
  }


  // import products in .csv format   
  function importProduct($POST){
    //File upload
    $valid_extensions = array('csv'); // valid extensions
    $uploadpath = './../../upload/product/'; // upload directory
    $imageArray = array();
    $imageTempArray = array();
    $finalImageArry=array();
    $imageExtension=array();

    if(count($_FILES['importProductsFile']['name']))
    {
      for($imgCount=0; $imgCount<count($_FILES['importProductsFile']['name']);$imgCount++){
        $img = $_FILES['importProductsFile']['name'][$imgCount];
        $tmp = $_FILES['importProductsFile']['tmp_name'][$imgCount];
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
              'msg' => "Invalid file formate passed..",
              'data' => $_POST
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
      }
    }      
    // Allowed mime types
    $fileMimes = [
      'text/x-comma-separated-values',
      'text/comma-separated-values',
      'application/octet-stream',
      'application/vnd.ms-excel',
      'application/x-csv',
      'text/x-csv',
      'text/csv',
      'application/csv',
      'application/excel',
      'application/vnd.msexcel',
      'text/plain',
    ];

    // Validate whether selected file is a CSV file
    if (
        !empty( $_FILES['importProductsFile']['name'][0]) &&
        in_array($_FILES['importProductsFile']['type'][0], $fileMimes)
      ) {
      // Open uploaded CSV file with read-only mode
      $csvFile = fopen($_FILES['importProductsFile']['tmp_name'][0], 'r');

      $newcsvFile = fopen($_FILES['importProductsFile']['tmp_name'][0], 'r');
      // Skip the first line
      fgetcsv($csvFile);
      fgetcsv($newcsvFile);

      $csvProducts="";

      $rowno =0;
      while (($getData = fgetcsv($newcsvFile, 10000, ',')) !== false) {
        $rowno++;
        // Get row data
        $product_name = addslashes($getData[0]);
        $short_description = addslashes($getData[1]);
        $description = $getData[2];
        $price = $getData[4];
        $discount = $getData[5];
        $length = $getData[6];
        $width = $getData[7];
        $height = $getData[8];
        $weight = $getData[9];
        $bullet1 = $getData[10];
        $bullet2 = $getData[11];
        $bullet3 = $getData[12];
        $stock = $getData[13];
        $category_id = $getData[14];
        $level1category_id = $getData[15];
        $sub_category_id = $getData[16];
        $gst = $getData[18];

        $csvProducts = $csvProducts.","."'$product_name'";

        if(!$product_name){
          $array = array(
            'msg' => "Product name is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$short_description){
          $array = array(
            'msg' => "Short Description is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$description){
          $array = array(
            'msg' => "Description is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$price){
          $array = array(
            'msg' => "Price is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$discount){
          $array = array(
            'msg' => "Discount is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$length){
          $array = array(
            'msg' => "Length is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$width){
          $array = array(
            'msg' => "Width is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$height){
          $array = array(
            'msg' => "Height is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$weight){
          $array = array(
            'msg' => "Weight is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$bullet1){
          $array = array(
            'msg' => "Bullet 1 is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$bullet2){
          $array = array(
            'msg' => "Bullet 2 is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$bullet3){
          $array = array(
            'msg' => "Bullet 3 is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$stock){
          $array = array(
            'msg' => "Stock is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$category_id){
          $array = array(
            'msg' => "Category Id is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$level1category_id){
          $array = array(
            'msg' => "Level 1 Category Id is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }
        if(!$gst){
          $array = array(
            'msg' => "GST % is missing for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }

        // Check if category exist
        $getCatQuery = "SELECT count(*) as catcount from category where id=$category_id";
        $catExist = select($getCatQuery);

        if($catExist['catcount'] == 0){
          $array = array(
            'msg' => "Category id is not valid for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }

        // Check if level 1 category exist
        $getSubCatQuery = "SELECT count(*) as subcatcount from sub_category where id=$level1category_id and level=1";
        $subCatExist = select($getSubCatQuery);

        if($subCatExist['subcatcount'] == 0){
          $array = array(
            'msg' => "Level 1 Sub Category id is not valid for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }

        $getSubCatQuery = "SELECT count(*) as subcatcount from sub_category where id=$level1category_id and level=1 and category_id=$category_id";
        $subCatExist = select($getSubCatQuery);

        if($subCatExist['subcatcount'] == 0){
          $array = array(
            'msg' => "Level 1 Sub Category id Not associated with category id $category_id for row no ".$rowno
          );
          http_response_code(401);
          echo json_encode($array);
          exit;
        }


      }
      $csvProducts = substr($csvProducts, 1);

      //Check if product name already exist
      $checkProducQuery = "SELECT product_name from products where product_name IN ($csvProducts)";
      $chckProddutData = selectMultiple($checkProducQuery);
      
      if(count($chckProddutData) != 0){
        $array = array(
          'msg' => "Some products are already exist",
          'data' => $chckProddutData
        );
        http_response_code(401);
        echo json_encode($array);
        exit;
      }
      else{
        // Parse data from CSV file line by line

        while (($getData = fgetcsv($csvFile, 10000, ',')) !== false) {
            // Get row data
            $product_owner=$GLOBALS['jwt_token']->user_id;
            $currdate = date('Y-m-d H:i:s');

            $product_name = addslashes($getData[0]);
            $short_description = addslashes($getData[1]);
            $description = $getData[2];
            $sku = $randomString = generateRandomString(5);
            $price = $getData[4];
            $discount = $getData[5];
            $length = $getData[6];
            $width = $getData[7];
            $height = $getData[8];
            $weight = $getData[9];
            $bullet1 = $getData[10];
            $bullet2 = $getData[11];
            $bullet3 = $getData[12];
            $stock = $getData[13];
            $category_id = $getData[14];
            $level1category_id = $getData[15];
            $sub_category_id = $getData[16];
            $hsn = $getData[17];
            $gst = $getData[18];
            $productImage1 = $getData[19];
            $productImage2 = $getData[20];
            $productImage3 = $getData[21];
            $productImage4 = $getData[22];
            $productVideo1 = $getData[23];

            // add to db
            $cols =  array("product_name","short_description", "description","sku","price","discount","length","width","height","weight","bullet1","bullet2","bullet3","stock","category_id","level1category_id","sub_category_id","product_owner","added_by","added_at","hsn","gst","status");
            $values =  array($product_name, $short_description, $description ,$sku ,$price ,$discount ,$length ,$width ,$height ,$weight, $bullet1, $bullet2, $bullet3, $stock, $category_id ,$level1category_id ,$sub_category_id, $product_owner, $product_owner, $currdate,$hsn,$gst,0);
            $table_name = "products";
            $insData = insert($cols, $values,$table_name);
      

          //Upload image
            for($i=1; $i<=4; $i++){
              $imgVar = "productImage".$i;
              $cols =  array("product_id","name","extension","type","url","is_image");
              $values =  array($insData,"0","0", "0" ,$$imgVar,1);
              $table_name = "product_images";
              insert($cols, $values,$table_name);
            }

            // Video
            $cols =  array("product_id","name","extension","type","url","is_image");
            $values =  array($insData,"0","0", "0" ,$productVideo1,0);
            $table_name = "product_images";
            insert($cols, $values,$table_name);
            
        }
        // Close opened CSV file
        fclose($csvFile);
        } 

        $array = array(
            'msg' => "Product imported...",
            'data' => $_POST
        );
        http_response_code(200);
        echo json_encode($array);
        exit;

      }

  }
?>