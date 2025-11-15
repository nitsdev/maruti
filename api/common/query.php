<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $GLOBALS['g_con'] = $con;

  function insert($cols, $values,$table_name){
    $conn = $GLOBALS['g_con'];

    $insert_query = "insert into ".$table_name;

    // Columns
    $columns = "(";
    $count=1;
    foreach ($cols as $c) {
      if($count != count($cols)){
        $columns .= $c.",";
        $count++;
      }else{
        $columns .= $c;
      }
    }
    $columns .= ")";

    $insert_query .= $columns. " values ";

    // Values
    $val = "(";
    $count=1;
    foreach ($values as $v) {
      if($count != count($values)){
        if(is_numeric($v)){
          $val .= $v.",";
        }else{
          $val .= "'".$v."',";
        }
        $count++;
      }else{
        if(is_numeric($v)){
          $val .= $v;
        }else{
          $val .= "'".$v."'";
        }
      }
    }
    $val .= ")";

    $insert_query .= $val;
    // echo $insert_query;
    $sql_execute=mysqli_query($conn,$insert_query);
    $last_id = mysqli_insert_id($conn);
    return $last_id;
  }


  function select($select_query){
    $conn = $GLOBALS['g_con'];
    $sql_execute=mysqli_query($conn,$select_query,MYSQLI_USE_RESULT);
    $row = mysqli_fetch_assoc($sql_execute);
    return $row;
  }

  function selectMultiple($select_query){
    $conn = $GLOBALS['g_con'];
    $sql_execute=mysqli_query($conn,$select_query);
    $data=array(); 
    while($result=mysqli_fetch_assoc($sql_execute)) {
        $data[]=$result; 
    }

    return $data;
  }

  function update($update_query){
    $conn = $GLOBALS['g_con'];
    $sql_execute=mysqli_query($conn,$update_query);
    return $sql_execute;
  }

  function delete($delete_query){
    $conn = $GLOBALS['g_con'];
    $sql_execute=mysqli_query($conn,$delete_query);
    return $sql_execute;
  }
?>