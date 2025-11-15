<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    


    // gett 50 products
    function getProduct($POST){
      $status=$POST['status'];
      $from=$POST['from']-1;
      $to=$POST['to'];
      $perpage=$POST['perpage'];
      $pId=$POST['pId'];
      $pName=$POST['pName'];

      $where="where 1=1 and";
      if($status!="*"){
        $where.=" p.status='$status' and";
      }
      if(isset($pId) && $pId){
        $where.=" p.id=$pId and";
      }
      if(isset($pName) && $pName){
        $where.=" (p.product_name like '%$pName%' or p.short_description like '%$pName%' or p.description like '%$pName%') and";
      }
      $table_name = "products";
      $query = "SELECT p.id AS product_id, p.product_name,p.medicine_type, p.stock, p.status,p.discount, p.description, p.price, c.category_name, sc1.sub_category_name AS level1cat, sc2.sub_category_name AS sub_category, u.first_name, u.last_name, ( SELECT pi.url FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.id ASC LIMIT 1 ) AS url FROM $table_name p LEFT JOIN category c ON c.id = p.category_id LEFT JOIN sub_category sc1 ON sc1.id = p.level1category_id LEFT JOIN sub_category sc2 ON sc2.id = p.sub_category_id LEFT JOIN users u ON u.id = p.added_by $where p.status!=3 ORDER BY p.id DESC LIMIT  $from, $perpage";
      
      $query_res = selectMultiple($query);

      $countQuery = "select count(p.id) as pcount from $table_name as p $where p.status!=3";
      
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

    // gett all products
    function getAllProduct($POST){
      $status=$POST['status'];
      $where="";
      if($status!="*"){
        $where="where p.status='$status'";
      }
      $table_name = "products";
      $query = "select p.*,pi.*,p.id as product_id,u.first_name,u.last_name,c.category_name,sc1.sub_category_name as level1cat, sc2.sub_category_name as sub_catgory from $table_name p left JOIN product_images pi on p.id=pi.product_id JOIN category c on c.id=p.category_id JOIN sub_category sc1 on sc1.id=p.level1category_id LEFT JOIN sub_category sc2 on sc2.id=p.sub_category_id LEFT JOIN users u ON u.id=p.added_by $where and p.status!=3 GROUP BY p.id DESC";
      $query_res = selectMultiple($query);
      $array = array(
        'msg' => "All products Details..",
        'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }


    function getProducts($POST){
      
      $table_name = "products";
      $query = "select p.*,pi.*,p.id as product_id,u.first_name,u.last_name,c.category_name,sc1.sub_category_name as level1cat, sc2.sub_category_name as sub_catgory from $table_name p left JOIN product_images pi on p.id=pi.product_id JOIN category c on c.id=p.category_id JOIN sub_category sc1 on sc1.id=p.level1category_id LEFT JOIN sub_category sc2 on sc2.id=p.sub_category_id LEFT JOIN users u ON u.id=p.added_by GROUP BY p.id DESC";
      $query_res = selectMultiple($query);
      $array = array(
        'msg' => "All products Details..",
        'data' => $query_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    // gett unique product
    function getUniqueProduct($POST){
      $id=$POST['id'];
      $table_name = "products";
      // $query = "select p.*,pi.*,p.id as product_id from $table_name p left join product_images pi on p.id=pi.product_id where p.id=$id";
      $product_query= "select * , id as product_id from products where id= $id ";
      $product_query_res = select($product_query);
      $product_image_query= "select * from product_images where product_id= $id ";
      $product_image_query_res = selectMultiple($product_image_query);
      $array = array(
        'msg' => "Product Details..",
        'data' => $product_query_res,
        'image_data' => $product_image_query_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }

    function changeStatus($POST){
      $status=$POST['status'];
      $id=$POST['id'];

      // If invalid status code passed
      if($status > 2){
        $array = array(
          'msg' => "Invalid status code passed..",
          'data' => $_POST
        );
        http_response_code(400);
        echo json_encode($array);
        exit;
      }

      // Update status
      $table_name = "products";
      $user_id=$GLOBALS['jwt_token']->user_id;
      $updated_at=date('Y-m-d H:i:s');
      $query = "UPDATE $table_name SET status = $status, updated_by=$user_id, updated_at='$updated_at' WHERE id = $id";
      update($query);

      $array = array(
          'msg' => "Product Status Updated Successfully..",
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

  //  Add product
  function addProduct($POST){
    // Validation
    $product_name = $POST['product_name'];
    $category_id = $POST['category_id'];
    $level1category_id = $POST['level1category_id'];
    $sub_category_id = $POST['sub_category_id'];
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
    $hsn=$POST['hsn'];
    $gst=$POST['gst']; 

    // $product_owner =4;
    // if($POST['product_owner'] != 'admin'){
    //   $product_owner = $POST['product_owner'];
    // }

    $query = "select * from products where product_name='$product_name'";
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

        $sku = $randomString = generateRandomString(5);

        // add to db
        $user_id=$GLOBALS['jwt_token']->user_id;
        $cols =  array("product_name","short_description", "description","sku","price","discount","length","width","height","weight","bullet1","bullet2","bullet3","stock","medicine_type","category_id","level1category_id","sub_category_id","product_owner","added_by","added_at","status","hsn","gst");
        $values =  array($product_name, $short_description, $description ,$sku ,$price ,$discount ,$length ,$width ,$height ,$weight, $bullet1, $bullet2, $bullet3, $stock, $category_id,$medicine_type ,$level1category_id ,$sub_category_id, $user_id, $user_id, date('Y-m-d H:i:s'),1,$hsn,$gst);
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

  //  edit product
  function editProduct($POST){
    // Validation
    $id = $POST['id'];
    $product_name = $POST['product_name'];
    $category_id = $POST['category_id'];
    $level1category_id = $POST['level1category_id'];
    $sub_category_id = $POST['sub_category_id'];
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
    $product_owner =4;
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
        $updateQuery = "update products SET product_name='$product_name', short_description='$short_description', description='$description', price=$price, discount=$discount, length=$length, width=$width, height=$height, weight=$weight, bullet1='$bullet1', bullet2='$bullet2', bullet3='$bullet3', stock=$stock, medicine_type=$medicine_type, category_id=$category_id, level1category_id=$level1category_id, sub_category_id=$sub_category_id, product_owner=$user_id, updated_by= $user_id, updated_at='$currdate',hsn='$hsn',gst=$gst where id = $id";
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

  // delete products
  // function deleteProduct($POST){
  //   $id = $POST['id'];

  //   //get all images
  //   $imgQuery = "select * from product_images where product_id = $id";
  //   $imgData = selectMultiple($imgQuery);
  //   $uploadpath = './../../upload/product/'; // upload directory

  //   //Upload image
  //   for($i=0; $i<count($imgData); $i++){
  //     //Delete image
  //     $filename = './../../'.$imgData[$i]['url'];
  //     if (file_exists($filename)) {
  //       unlink($filename);
  //     }
  //   }

  //   // Delete image
  //   $deleteImage = "delete from product_images where product_id = $id";
  //   delete($deleteImage);

  //   //Delete product
  //   $deleteProduct = "delete from products where id = $id";
  //   delete($deleteProduct);

  //   $array = array(
  //     'msg' => "Product Deleted.."
  //   );
  //   http_response_code(200);
  //   echo json_encode($array);
  //   exit;
  // }

  //changeProductStatus
  function changeProductStatus($POST){
    $id = $POST['id'];
    $status = $POST['status'];
    $resMsg = "";

    if($status == 0){
      $newStatus = 1;
      $resMsg = "Product Activated";
    }else if($status == 1){
      $newStatus = 2;
      $resMsg = "Product De-Activated";
    }else if($status == 2){
      $newStatus = 1;
      $resMsg = "Product Approved";
    }else{
      $newStatus = 0;
    }

    $statusQuery = "UPDATE products SET status = $newStatus where id=$id";
    update($statusQuery);

    $array = array(
      'msg' => $resMsg
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
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
            $values =  array($product_name, $short_description, $description ,$sku ,$price ,$discount ,$length ,$width ,$height ,$weight, $bullet1, $bullet2, $bullet3, $stock, $category_id ,$level1category_id ,$sub_category_id, $product_owner, $product_owner, $currdate,$hsn,$gst,1);
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

  function getRatings($POST){
    $pid = $POST['pid'];
    $query = "SELECT rs.*,u.first_name, u.last_name FROM `review_stars` as rs JOIN users as u ON u.id=rs.user_id WHERE product_id=$pid";
    $query_res = selectMultiple($query);

    $prodQuery = "SELECT * FROM `products` WHERE id=$pid";
    $prodQuery_res = select($prodQuery);

    $commentQuery = "SELECT * FROM `review_comments` WHERE product_id=$pid";
    $commentQuery_res = selectMultiple($commentQuery);
    $array = array(
      'msg' => "All ratings for product..",
      'data' => $query_res,
      'commentsData'=> $commentQuery_res,
      'prodData' => $prodQuery_res
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function deleteRating($POST){
    $id = $POST['id'];
    
    $deleteRatingQuery = "DELETE from review_stars where id = $id";
      delete($deleteRatingQuery);

      $array = array(
        'msg' => "Rating Deleted.."
      );
      http_response_code(200);
      echo json_encode($array);
      exit;      
      
    }

    function getComment($POST){
      $pid = $POST['pid'];  
      $prodQuery = "SELECT * FROM `products` WHERE id=$pid";
      $prodQuery_res = select($prodQuery);
  
      $commentQuery = "SELECT * FROM `review_comments` WHERE product_id=$pid";
      $commentQuery_res = selectMultiple($commentQuery);
      $array = array(
        'msg' => "All ratings for product..",
        'commentsData'=> $commentQuery_res,
        'prodData' => $prodQuery_res
      );
      http_response_code(200);
      echo json_encode($array);
      exit;
    }
  
    function deleteComment($POST){
      $id = $POST['id'];
      
      $deleteCommentQuery = "DELETE from review_comments where id = $id";
        delete($deleteCommentQuery);
  
        $array = array(
          'msg' => "Comment Deleted.."
        );
        http_response_code(200);
        echo json_encode($array);
        exit;      
        
      }

      // get food product

      function getFoodProduct($POST){
        $status=isset($POST['status'])?$POST['status']:"*";
        $from=$POST['from']-1;
        $to=$POST['to'];
        $perpage=$POST['perpage'];
        $prod_name=$POST['food_pName'];
        $user_id=$GLOBALS['jwt_token']->user_id;
        $where=" where 1=1";
        if($status!="*"){
          $where="where p.status='$status'";
        }
        if(isset($prod_name) && $prod_name){
          $prod=addslashes($prod_name);
        $where.=" and (p.product_name like '%$prod%' or p.short_desc like '%$prod%')";
      }

        $where.=" and 1=1";

        $table_name = "food_products";
        // $query="SELECT p.*, pi.*, p.id AS product_id, c.category_name, sc1.sub_category_name AS level1cat, sc2.sub_category_name AS sub_catgory, fpp.* FROM $table_name p LEFT JOIN food_product_images pi ON p.id = pi.product_id JOIN category c ON c.id = p.category JOIN sub_category sc1 ON sc1.id = p.sub_category LEFT JOIN sub_category sc2 ON sc2.id = p.sub_category LEFT JOIN food_product_prices fpp ON p.id = fpp.product_id $where AND p.status != 3 GROUP BY p.id ORDER BY p.id DESC LIMIT $from, $perpage";
        $query = "select p.*,pi.*,p.id as product_id,c.category_name,sc1.sub_category_name as level1cat, sc2.sub_category_name as sub_catgory from $table_name p left JOIN food_product_images pi on p.id=pi.product_id JOIN category c on c.id=p.category JOIN sub_category sc1 on sc1.id=p.sub_category LEFT JOIN sub_category sc2 on sc2.id=p.sub_category $where and p.status!=3 GROUP BY p.id DESC LIMIT $from, $perpage";
        // echo $query; die(); 
        $query_res = selectMultiple($query);

        $c=0;
        foreach($query_res as $product){
          $prodId = $product['product_id'];
          $addedBy = $product['created_by'];

          $getPriceQuery = "SELECT * from food_product_prices where product_id=$prodId";
          $prodPrice = selectMultiple($getPriceQuery);
          $query_res[$c]['prodPrice'] = $prodPrice;

          $userQuery = "SELECT * from users where id=$addedBy";
          $user = select($userQuery);
          $query_res[$c]['user'] = $user;

          $c++;
        }

        $table_name = "food_products";
        $countQuery = "select count(p.id) as pcount from $table_name as p $where and status!=3";
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

      //changeProductStatus
      function changeFoodProductStatus($POST){
        $id = $POST['id'];
        $status = $POST['status'];
        $resMsg = "";

        if($status == 0){
          $newStatus = 1;
          $resMsg = "Product Activated";
        }else if($status == 1){
          $newStatus = 2;
          $resMsg = "Product De-Activated";
        }else if($status == 2){
          $newStatus = 1;
          $resMsg = "Product Approved";
        }else{
          $newStatus = 0;
        }

        $statusQuery = "UPDATE food_products SET status = $newStatus where id=$id";
        update($statusQuery);

        $array = array(
          'msg' => $resMsg
        );
        http_response_code(200);
        echo json_encode($array);
        exit;
      }
?>