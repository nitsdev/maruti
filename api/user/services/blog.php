<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    

    function addBlog($POST){
      $blog_title = $POST["blog_title"];
      $description = $POST["description"];
      $description = str_replace("'", '', $POST["description"]);;
      $ethenic_group1 = $POST["ethenic_group1"];
      $ethenic_group2 = $POST["ethenic_group2"];
      $ethenic_group3 = $POST["ethenic_group3"];
      $user_id=$GLOBALS['jwt_token']->user_id;
      $blog_slug = str_replace(str_split('\\/:*?"<>|+-'), ' ', $blog_title);
      $blog_slug = str_replace(' ', '-', $blog_slug);

      if(!is_numeric($ethenic_group1)){
        $cols =  array("name");
        $values =  array($ethenic_group1);
        $table_name = "ethenic_group1";
        $ethenic_group1 = insert($cols, $values,$table_name);
      }
      if(!is_numeric($ethenic_group2)){
        $cols =  array("name");
        $values =  array($ethenic_group2);
        $table_name = "ethenic_group2";
        $ethenic_group2 = insert($cols, $values,$table_name);
      }
      if(!is_numeric($ethenic_group3)){
        $cols =  array("name");
        $values =  array($ethenic_group3);
        $table_name = "ethenic_group3";
        $ethenic_group3 = insert($cols, $values,$table_name);
      }

      $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions
      $uploadpath = './../../upload/blogImage/'; // upload directory
      $imageArray = array();
      $imageTempArray = array();
      $finalImageArry=array();
      $imageExtension=array();
      // print_r($_FILES['file']);
      if($_FILES['file']['name'][0])
      {
        for($imgCount=0; $imgCount<count($_FILES['file']['name']);$imgCount++){
          $img = $_FILES['file']['name'][$imgCount];
          $tmp = $_FILES['file']['tmp_name'][$imgCount];
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

      $cols =  array("blog_title","description","ethenic_group1","ethenic_group2","ethenic_group3","created_at","created_by","blog_slug");
      $values =  array($blog_title,$description,$ethenic_group1,$ethenic_group2,$ethenic_group3,date('Y-m-d H:i:s'),$user_id,$blog_slug);
      $table_name = "blog";
      $insData = insert($cols, $values,$table_name);

       //Upload image
       for($i=0; $i<count($imageArray); $i++){
        $path = $uploadpath.strtolower($finalImageArry[$i]); 
        move_uploaded_file($imageTempArray[$i],$path);

        $cols =  array("blog_id","name","extension","type","url","is_image");
        $values =  array($insData,$imageArray[$i],$imageExtension[$i], $imageExtension[$i] ,"upload/blogImage/".$finalImageArry[$i],1);
        $table_name = "blog_images";
        insert($cols, $values,$table_name);
      }

      $array = array(
          'msg' => "Blog added successfully..",
          'data' => ""
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function getBlog(){

      $blogQuery = "select b.*,u.first_name,u.last_name,eg1.name as ethenic_group1_name,eg2.name as ethenic_group2_name,eg3.name as ethenic_group3_name from blog as b join users as u on u.id=b.created_by join ethenic_group1 as eg1 on eg1.id=b.ethenic_group1 join ethenic_group2 as eg2 on eg2.id=b.ethenic_group2 join ethenic_group3 as eg3 on eg3.id=b.ethenic_group3 order by created_at desc";
      $data = selectMultiple($blogQuery);

      for($blg=0; $blg<count($data); $blg++){
        //get Image
        $blogId = $data[$blg]['id'];
        $blogimgquery = "select * from blog_images where blog_id=$blogId";
        $blogimg = selectMultiple($blogimgquery);

        $data[$blg]['img']=$blogimg;

        // Get blog
        $commentCountQuery = "SELECT count(id) as cnt from blog_comment where blog_id=$blogId";
        $commentCount = select($commentCountQuery);
        $data[$blg]['commentCount']=$commentCount['cnt'];
      }

      $array = array(
        'msg' => "Blog fetched successfully..",
        'data' => $data
      );
      http_response_code(200);
      echo json_encode($array);
    }


    function getEthenicGroups(){
      $group1Query = "select * from ethenic_group1";
      $group1 = selectMultiple($group1Query);

      $group2Query = "select * from ethenic_group2";
      $group2 = selectMultiple($group2Query);

      $group3Query = "select * from ethenic_group3";
      $group3 = selectMultiple($group3Query);

      $array = array(
        'msg' => "Group fetched successfully..",
        'group1' => $group1,
        'group2' => $group2,
        'group3' => $group3

      );
      http_response_code(200);
      echo json_encode($array);
    }

    //getBlogImages
    function getBlogImages($POST){
      $id =  $POST["id"];

      $blogimgquery = "select * from blog_images where blog_id=$id";
      $blogimg = selectMultiple($blogimgquery);

      $array = array(
        'msg' => "Blog Images fetched successfully..",
        'blogimg' => $blogimg

      );
      http_response_code(200);
      echo json_encode($array);
    }

    //getBlogById
    function getBlogById($POST){
      $slug =  $POST["slug"];

      $blogQuery = "select b.*,u.first_name,u.last_name,eg1.name as ethenic_group1_name,eg2.name as ethenic_group2_name,eg3.name as ethenic_group3_name from blog as b join users as u on u.id=b.created_by join ethenic_group1 as eg1 on eg1.id=b.ethenic_group1 join ethenic_group2 as eg2 on eg2.id=b.ethenic_group2 join ethenic_group3 as eg3 on eg3.id=b.ethenic_group3 where b.blog_slug='$slug'";
      // "select * from blog where id=$id";
      $blogData = selectMultiple($blogQuery);

      $array = array(
        'msg' => "Blog Data fetched successfully..",
        'data' => $blogData

      );
      http_response_code(200);
      echo json_encode($array);

    }

    //addComment
    function addComment($POST){
      $blog_id = $POST['blog_id'];
      $comment_id = $POST['comment_id'];
      $comment = $POST['comment'];
      $user_id=$GLOBALS['jwt_token']->user_id;
      
      $cols =  array("blog_id","comment_id","comment","created_at","created_by");
      $values =  array($blog_id,$comment_id,$comment,date('Y-m-d H:i:s'),$user_id);
      $table_name = "blog_comment";
      $insData = insert($cols, $values,$table_name);

      $latestCommentQuery = "select bc.*,u.first_name,u.last_name from blog_comment as bc join users as u ON bc.created_by=u.id ORDER BY id DESC LIMIT 1";
      $latestComment = select($latestCommentQuery);

      $array = array(
        'msg' => "Blog Comment Added successfully..",
        'data' => $latestComment

      );
      http_response_code(200);
      echo json_encode($array);
    }

    //getCommentsById
    function getCommentsById($POST){
      $blog_id = $POST['blog_id'];
      $comment_id = $POST['comment_id'];

      $commentQuery = "select bc.*,u.first_name,u.last_name from blog_comment as bc join users as u ON bc.created_by=u.id where blog_id=$blog_id and comment_id=$comment_id ORDER BY id DESC";
      $commentData = selectMultiple($commentQuery);

      $array = array(
        'msg' => "Blog Comment Fetched..",
        'data' => $commentData

      );
      http_response_code(200);
      echo json_encode($array);
    }

?>