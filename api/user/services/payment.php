<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //initiatePayment
    function initiatePayment($POST,$token){
      $feurl = $POST["feurl"];
      $user_id = $GLOBALS['jwt_token']->user_id;
      $cart_id =  $GLOBALS['jwt_token']->cart_id;
      $mobile = $GLOBALS['jwt_token']->mobile;

      //Get order details
      $getOrderQuery = "SELECT * from orders where user_id=$user_id and status=3 ORDER BY created_at DESC limit 1";
      $orderData = select($getOrderQuery);

      $bytes = random_bytes(7);
      $merchantTransactionId = bin2hex($bytes);

      $redLoc = "";
    
      $client_version = '1';
      $grant_type = 'client_credentials';

      if (str_contains($feurl, "localhost")) {
        $authURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";
        $client_id = $_ENV['PHONE_PE_CLIENT_ID_UAT'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET_UAT'];
        $payURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay";
      }else{
        $authURL = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
        $client_id = $_ENV['PHONE_PE_CLIENT_ID'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET'];
        $payURL = 'https://api.phonepe.com/apis/pg/checkout/v2/pay';
      }


      // URL-encoded post data
      $postFields = http_build_query([
          'client_id' => $client_id,
          'client_version' => $client_version,
          'client_secret' => $client_secret,
          'grant_type' => $grant_type
      ]);

      $ch = curl_init($authURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/x-www-form-urlencoded'
      ]);

      $response = curl_exec($ch);
      $token="";
      if (curl_errno($ch)) {
          echo 'Curl error: ' . curl_error($ch);
      } else {
          $token = json_decode($response, true)['access_token'];
      }

      curl_close($ch);

      if($token){
        $url = $payURL;
    
        // Replace with your actual O-Bearer token
        $authToken = 'O-Bearer '.$token;
    
        $autoId = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        // Request body
        $payload = [
            "merchantOrderId" => $orderData['order_id'],
            "amount" => (int)(number_format($orderData['amount'],2)*100),
            "expireAfter"=> 1200,
            "paymentFlow" => [
                "type" => "PG_CHECKOUT",
                "message" => "Payment message used for collect requests",
                "merchantUrls" => [
                    "redirectUrl" => $feurl."payment_success.php"
                ],
                "paymentModeConfig" => [
                    "enabledPaymentModes" => [
                        [
                            "type" => "UPI_INTENT"
                        ],
                        [
                          "type" => "UPI_COLLECT"
                        ],
                        [
                          "type" => "UPI_QR"
                        ],
                        [
                          "type" => "CARD",
                          "cardTypes" => [
                              "DEBIT_CARD",
                              "CREDIT_CARD"
                          ]
                        ]
                    ]
                ]
            ],
            "metaInfo"=> [
                "udf1"=> "<additional-information-1>",
                "udf2"=> "<additional-information-2>",
                "udf3"=> "<additional-information-3>",
                "udf4"=> "<additional-information-4>",
                "udf5"=> "<additional-information-5>"    
            ]
        ];
    
        // JSON encode payload
        $jsonPayload = json_encode($payload);
    
        // cURL setup
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: O-Bearer ' . $token
        ]);
        
    
        // Execute
        $response = curl_exec($ch);
    
        // Check for errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
          $redLoc = json_decode($response, true)['redirectUrl'];
          // print_r($response);
        }
    
        curl_close($ch);
      }



      $array = array(
        'msg' => "Initiated",
        'data' => $redLoc,
        'token' => $token,
        'merchantOrderId' => $orderData['order_id']
      );
      http_response_code(200);
      echo json_encode($array);
    }
      
    // checkPaymentStatus and do db save work
    function checkPaymentStatus($POST){
      $transactionId = $POST['transactionId'];
      $payType = $POST['payType'];
      $feurl = $POST['feurl'];
      $merchantOrderId = $POST['merchantOrderId'];

      $client_version = '1';
      $grant_type = 'client_credentials';

      $providerReferenceId="";
      if(isset($providerReferenceId))
        $providerReferenceId = $POST['providerReferenceId'];

      $orderId = "SHS-".explode("SHS-",$transactionId)[1];

      // Get user details by order id
      $orderQuery = "SELECT * from orders where order_id='$orderId'";
      $orderData = select($orderQuery);
      $orderAIId = $orderData['id'];
      $amount = $orderData['amount'];
      $currDateTime = date('Y-m-d H:i:s');


      if (str_contains($feurl, "localhost")) {
        $authURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";
        $client_id = $_ENV['PHONE_PE_CLIENT_ID_UAT'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET_UAT'];
        $statusURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/order/".$merchantOrderId."/status";
      }else{
        $authURL = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
        $client_id = $_ENV['PHONE_PE_CLIENT_ID'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET'];
        $statusURL = 'https://api.phonepe.com/apis/pg/checkout/v2/order/'.$merchantOrderId.'/status';
      }

      // URL-encoded post data
      $postFields = http_build_query([
          'client_id' => $client_id,
          'client_version' => $client_version,
          'client_secret' => $client_secret,
          'grant_type' => $grant_type
      ]);

      $ch = curl_init($authURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/x-www-form-urlencoded'
      ]);

      $response = curl_exec($ch);
      $token="";
      if (curl_errno($ch)) {
          echo 'Curl error: ' . curl_error($ch);
      } else {
          $token = json_decode($response, true)['access_token'];
      }

      curl_close($ch);
  

      if($payType == 1){
        $url = $statusURL;

        $headers = [
            'Content-Type: application/json',
            'Authorization: O-Bearer '.$token
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // echo $response;
        }
          
        $res = json_decode($response);
      }

      $resCode="";

      if($payType == 1){
        $resCode = $res->state;
        $transactionId = $res->orderId;
        // Payment pending
        if($resCode == 'PENDING'){
          //Update status on order and transaction table
          $updateOrderStatus = "UPDATE orders SET status=3,updated_at='$currDateTime',transaction_id='$transactionId' where id=$orderAIId and order_id='$orderId'";
          update($updateOrderStatus);
        }

        // Payment success
        else if($resCode == 'COMPLETED'){
          //Update status on order and transaction table
          $updateOrderStatus = "UPDATE orders SET status=1,updated_at='$currDateTime', transaction_id='$transactionId' where id=$orderAIId and order_id='$orderId'";
          update($updateOrderStatus);
        }

        // Payment failed
        else{
          //Update status on order and transaction table
          $updateOrderStatus = "UPDATE orders SET status=0,updated_at='$currDateTime',transaction_id='$transactionId' where id=$orderAIId and order_id='$orderId'";
          update($updateOrderStatus);
        }

        //Insert in transaction table
        $cols =  array("order_id","transactionId", "code","amount","merchantTransactionId","created_at","payment_details");
        $values =  array($orderId, $providerReferenceId, $resCode, $amount ,$transactionId, date('Y-m-d H:i:s'), json_encode($response));
        $table_name = "transactions";
        $insData = insert($cols, $values,$table_name);

      }else{
        // COD
        //Update status on order and transaction table
        $updateOrderStatus = "UPDATE orders SET status=1,updated_at='$currDateTime', transaction_id='$orderId' where id=$orderAIId and order_id='$orderId'";
        update($updateOrderStatus);

        $resCode = 'COMPLETED';

        //Insert in transaction table
        $cols =  array("order_id","transactionId", "code","amount","merchantTransactionId","created_at","payment_details");
        $values =  array($orderId, $providerReferenceId, $resCode, $amount ,"", date('Y-m-d H:i:s'), "");
        $table_name = "transactions";
        $insData = insert($cols, $values,$table_name);
      }

      // clear cart data
      if($resCode == 'COMPLETED' && isset($GLOBALS['jwt_token']->cart_id)){
        $cart_id = $GLOBALS['jwt_token']->cart_id;
        $clearCartQuery = "DELETE from carts where cart_id='$cart_id'";
        delete($clearCartQuery);
      }

      $array = array(
        'msg' => "Payment status",
        'code' => $resCode,
        'order_id' => $orderId
      );
      http_response_code(200);
      echo json_encode($array);

    }


    //reInitiatePayment
    function reInitiatePayment($POST,$token){
      $feurl = $POST["feurl"];
      $orderid = $POST["orderid"];
      $user_id = $GLOBALS['jwt_token']->user_id;
      $cart_id =  $GLOBALS['jwt_token']->cart_id;
      $mobile = $GLOBALS['jwt_token']->mobile;

      //Get order details
      $getOrderQuery = "SELECT * from orders where user_id=$user_id and order_id='$orderid'";
      $orderData = select($getOrderQuery);
              
      $bytes = random_bytes(7);
      $merchantTransactionId = bin2hex($bytes);
      $bytes = random_bytes(6);
      $merchantUserId = bin2hex($bytes);

      $redLoc = "";
    
      $client_version = '1';
      $grant_type = 'client_credentials';

      if (str_contains($feurl, "localhost")) {
        $authURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";
        $client_id = $_ENV['PHONE_PE_CLIENT_ID_UAT'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET_UAT'];
        $payURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2/pay";
      }else{
        $authURL = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
        $client_id = $_ENV['PHONE_PE_CLIENT_ID'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET'];
        $payURL = 'https://api.phonepe.com/apis/pg/checkout/v2/pay';
      }


      // URL-encoded post data
      $postFields = http_build_query([
          'client_id' => $client_id,
          'client_version' => $client_version,
          'client_secret' => $client_secret,
          'grant_type' => $grant_type
      ]);

      $ch = curl_init($authURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/x-www-form-urlencoded'
      ]);

      $response = curl_exec($ch);
      $token="";
      if (curl_errno($ch)) {
          echo 'Curl error: ' . curl_error($ch);
      } else {
          $token = json_decode($response, true)['access_token'];
      }

      curl_close($ch);

      if($token){
        $url = $payURL;
    
        // Replace with your actual O-Bearer token
        $authToken = 'O-Bearer '.$token;
    
        $autoId = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        // Request body
        $payload = [
            "merchantOrderId" => $merchantTransactionId.$orderData['order_id'],
            "amount" => (int)(number_format($orderData['amount'],2)*100),
            "expireAfter"=> 1200,
            "paymentFlow" => [
                "type" => "PG_CHECKOUT",
                "message" => "Payment message used for collect requests",
                "merchantUrls" => [
                    "redirectUrl" => $feurl."payment_success.php"
                ],
                "paymentModeConfig" => [
                    "enabledPaymentModes" => [
                        [
                            "type" => "UPI_INTENT"
                        ],
                        [
                          "type" => "UPI_COLLECT"
                        ],
                        [
                          "type" => "UPI_QR"
                        ],
                        [
                          "type" => "CARD",
                          "cardTypes" => [
                              "DEBIT_CARD",
                              "CREDIT_CARD"
                          ]
                        ]
                    ]
                ]
            ],
            "metaInfo"=> [
                "udf1"=> "<additional-information-1>",
                "udf2"=> "<additional-information-2>",
                "udf3"=> "<additional-information-3>",
                "udf4"=> "<additional-information-4>",
                "udf5"=> "<additional-information-5>"    
            ]
        ];
    
        // JSON encode payload
        $jsonPayload = json_encode($payload);
    
        // cURL setup
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: O-Bearer ' . $token
        ]);
        
    
        // Execute
        $response = curl_exec($ch);
    
        // Check for errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
          $redLoc = json_decode($response, true)['redirectUrl'];
          // print_r($response);
        }
    
        curl_close($ch);
      }


      $array = array(
        'msg' => "Initiated",
        'data' => $redLoc,
        'token' => $token,
        'merchantOrderId' => $merchantTransactionId.$orderData['order_id']
      );
      http_response_code(200);
      echo json_encode($array);
    }

    function refund($POST){
      $feurl = $POST["feurl"];
      $user_id = $GLOBALS['jwt_token']->user_id;
      $cart_id =  $GLOBALS['jwt_token']->cart_id;
      $mobile = $GLOBALS['jwt_token']->mobile;
      $order_id = $_POST['orderId'];

      $orderQuery = "SELECT * from orders where order_id='$order_id'";
      $orderData = select($orderQuery);
      
      $paymentQuery = "SELECT * from transactions where order_id='$order_id'";
      $paymentData = select($paymentQuery);

      // $user_id=$row_data['user_id'];
      $amount=$orderData['amount'];
      $originalTransactionId=$orderData['transaction_id'];
      
      $client_version = '1';
      $grant_type = 'client_credentials';
      
      if (str_contains($feurl, "localhost")) {
        $authURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token";
        $client_id = $_ENV['PHONE_PE_CLIENT_ID_UAT'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET_UAT'];
        $refundURL = "https://api-preprod.phonepe.com/apis/pg-sandbox/payments/v2/refund";
      }else{
        $authURL = 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token';
        $client_id = $_ENV['PHONE_PE_CLIENT_ID'];
        $client_secret = $_ENV['PHONE_PE_CLIENT_SECRET'];
        $refundURL = 'https://api.phonepe.com/apis/pg/payments/v2/refund';
      }

      // URL-encoded post data
      $postFields = http_build_query([
          'client_id' => $client_id,
          'client_version' => $client_version,
          'client_secret' => $client_secret,
          'grant_type' => $grant_type
      ]);

      $ch = curl_init($authURL);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/x-www-form-urlencoded'
      ]);

      $response = curl_exec($ch);
      $token="";
      if (curl_errno($ch)) {
          echo 'Curl error: ' . curl_error($ch);
      } else {
          $token = json_decode($response, true)['access_token'];
      }

      curl_close($ch);
  
      $response="";
      
      if($paymentData["merchantTransactionId"]){
        $url = $refundURL;

        $curl = curl_init();

        $payload = [
          "merchantRefundId" => "refund-".$order_id,
          "originalMerchantOrderId" => $order_id,
          "amount" => (int)(number_format($amount,2)*100)
        ];
  
        // JSON encode payload
        $jsonPayload = json_encode($payload);

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $jsonPayload,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: O-Bearer ' . $token
          ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }           

        return $response;

      }else{
        return "";
      }
    }
?>