<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');


    //get orders by user id
  function getOrders(){
    $user_id=$GLOBALS['jwt_token']->user_id;
    $query = "select * from orders where status in (1,5,6) order by created_at DESC";  //Need to get only success orders
    $query_res = selectMultiple($query);

    // print_r($query_res);
    $sellerOrdArr = [];

    foreach ($query_res as $ord_pro) {
      $product_json = json_decode($ord_pro['product_json']);
      $order_id = $ord_pro['order_id'];
      $trans_id = $ord_pro['transaction_id'];
      // print_r($product_json);
      $orderedPro = [];

      if(isset($ord_pro['waybills']))
        $wayBills = explode(",",$ord_pro['waybills']);
      else
        $wayBills="";

      $count=0;
      foreach ($product_json as $pro) {
        if($pro->product_owner == $user_id){
          $orderedPro[$count]['product_json'] = $pro;
          array_push($sellerOrdArr,$ord_pro);
          $sellerOrdArr[count($sellerOrdArr)-1]['product_json']=$pro;
          if($wayBills)
            $sellerOrdArr[count($sellerOrdArr)-1]['waybill'] = $wayBills[$count];
          else  
            $sellerOrdArr[count($sellerOrdArr)-1]['waybill'] = "";

          $product_id = $pro->id;

          //Get delhivery label
          if($wayBills)
            $waybillno = $wayBills[$count];
          else
            $waybillno = "0000";
          
          $getLabelQuery = "SELECT * from delhivery_labels where waybill=$waybillno";
          $getLabelData = select($getLabelQuery);

          $pdf="";
          if(isset($getLabelData['label_pdf'])){
            $pdf=$getLabelData['label_pdf'];
          }

          $sellerOrdArr[count($sellerOrdArr)-1]['pdf'] = $pdf;

          //Get order delivered data
          $deliveredQuery = "SELECT * from delivered_products where order_id='$order_id' and product_id=$product_id";
          $deliveredData = select($deliveredQuery);

          $sellerOrdArr[count($sellerOrdArr)-1]['deliveredData'] = $deliveredData;

          // Get Delivery Status For COD
          $delStatus='';
          if($order_id == $trans_id){
            $delStatusQuery = "SELECT * from delivery_history where order_id='$order_id' and prod_id=$product_id order by created_at DESC limit 1";
            $delStatus = select($delStatusQuery);

            $sellerOrdArr[count($sellerOrdArr)-1]['delStatus'] = $delStatus;
          }

          $count++;
        }
      }
    }
    $array = array(
      'msg' => "All orders Details..",
      'data' => $sellerOrdArr
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }

  function changeOrderStatus($POST){
    $orderId = $POST['orderId'];
    $statusId = $POST['statusId'];
    $prodId = $POST['prodId'];

    $getOrderHistory = "SELECT sub_order_id from delivery_history where order_id = '$orderId' and prod_id=$prodId";
    $histData = select($getOrderHistory);
    $subOrderId = $histData['sub_order_id'];

    $user_id=$GLOBALS['jwt_token']->user_id;
    $cols =  array("order_id", "sub_order_id", "prod_id", "status", "comment", "created_by");
    $values =  array($orderId, $subOrderId, $prodId, $statusId, "", $user_id);
    $table_name = "delivery_history";
    insert($cols, $values,$table_name);

    $array = array(
      'msg' => "Status Changed Successfully.",
      'data' => $POST
    );
    http_response_code(200);
    echo json_encode($array);
    exit;
  }


?>