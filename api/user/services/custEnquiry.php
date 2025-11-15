<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
      require './../../api/common/connect.php';
    } else {
      require './../../api/common/connect2.php';
    }
    date_default_timezone_set('Asia/Kolkata');


  function addEnquiry(){ 
    $user=   $_REQUEST['user'];
    $email=  $_REQUEST['email'];
    $mobile= $_REQUEST['mobile'];
    $message=$_REQUEST['message'];
        // Insert Data
        $cols =  array("name","mobile", "email","message");
        $values =  array($user,$mobile,$email,$message);
        $table_name = "customer_enquiry";
        $insData = insert($cols, $values,$table_name);

        $array = array(
            'msg' => "We have received your message will respond soon Thank you !..",
            'data' => $_REQUEST
        );
        // if($_REQUEST['email']!=""){
        //   // Send Welcome Email to User
        //   $toEmail = $email;
        //   $subject = "Cosmeds - We have received your enquiry";
        //   $fromEmail = "info@cosmeds.in";
          
        //   $emailBody = <!doctype html>
        //                 <html lang="en">
        //                 <head>
        //                   <meta charset="utf-8">
        //                   <title>Cosmeds — Order Confirmation</title>
        //                   <meta name="viewport" content="width=device-width,initial-scale=1">
        //                 </head>
        //                 <body>
                          
        //                   <div class="small muted" style="text-align:center; padding:12px 0;  background-color:#0f4c81; color:#ffffff;">
        //                     We have received your enquiry at Cosmeds. Our team will get back to you shortly.
        //                   </div>

        //                   <div class="wrapper">
        //                     <div class="container">
        //                       <div class="inner">
        //                         <div class="header">
                                  
        //                           <h2>Cosmeds</h2>
        //                         </div>
        //                         <h1>We have received your enquiry</h1>
        //                         <p>Hi [Name],</p>
        //                         <p>Thank you for reaching out to Cosmeds. We have received your enquiry and our team will get back to you shortly.</p>
        //                         <p>If you have any further questions or need assistance, feel free to reply to this email or contact our support team.</p>
        //                         <p>Best regards,<br>The Cosmeds Team</p>
        //                       </div>
        //                       <div class="footer">
        //                         &copy; 2024 Cosmeds. All rights reserved.
        //                       </div>
        //                     </div>
        //                 </body>
        //                 </html>
        //                 ;
        //   //Replace
        //   $emailBody=str_replace("[Name]",$user,$emailBody);

        //   // Always set content-type when sending HTML email
        //   $headers = 'MIME-Version: 1.0' . "\r\n";
        //   $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
        //   $headers .= "BCC: $fromEmail". "\r\n";

        //   // More headers
        //   $headers .= 'From: <cosmeds-noreply@cosmeds.in>';
        
        //   //echo $mailHeaders;
        //   if (mail($toEmail,$subject,$emailBody,$headers)) {
        //       $message2 = 'Sent successfully';
        //       //echo $message2;
        //   }

        //   // if (mail("info@shuttleshop.in", $adminSub,$adminBody,$headers)) {
        //   //     $message = 'Sent successfully';
        //   // }
        // }
     

        http_response_code(200);
        echo json_encode($array);
    exit;
          
  }

  function addSubscribe(){
    $subEmail =$_REQUEST['subEmail'];
    
      // Insert Data
      $cols =  array("subEmail");
      $values =  array($_REQUEST['subEmail']);
      $table_name = "newsletter";
      $insData = insert($cols, $values,$table_name);

      $array = array(
          'msg' => "Thank you for connecting with us. We will notify you for our latest offers Thank you !..",
          'data' => $_REQUEST
      );
      http_response_code(200);
      echo json_encode($array);
  exit;
        
}
?>