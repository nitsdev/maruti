<?php
    include('header.php');
?>
<style>
        .seeCoupon{
            margin-left: 20px;
        }

        .coupon {
            border: 5px dotted #bbb;
            width: 80%;
            border-radius: 15px;
            margin: 0 auto;
            max-width: 600px;
        }

        .container {
            padding: 2px 16px;
            background-color: #f1f1f1;
        }

        .promo {
            background: #ccc;
            padding: 3px;
        }

        .expire {
            color: red;
        }

        .modal-content{
            width: 100% !important;
        }

        .errorMsg{
            color:red;
        }

        .applyCoupon{
            z-index:0 !important;
        }

        .rowBetween{
            padding-bottom:15px;
        }

        .bill_ship{
            margin-top: 6%;
            padding: 3%;
        }

        td a{
            color:black !important;
        }
        .filterSec{
          padding-bottom:10px;
        }
        .filterButtonDiv{
            top: 20px;
        }

        /* Timeline CSS */
        ul.timeline {
            list-style-type: none;
            position: relative;
        }
        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }
        ul.timeline > li {
            margin: 20px 0;
            padding-left: 20px;
        }
        ul.timeline > li:before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border-radius: 50%;
            border: 3px solid #22c0e8;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }
        .modal-dialog {
            max-width: 90% !important;
        }
    </style>

        <div class="container mb-5">
            <!-- Cart Start -->
            <div class="container-fluid">
                <div class="row px-xl-5">
                    <div class="col-lg-12 table-responsive mb-5">
                        <div class="filterSec">
                          Filter
                          <div class="row">
                            <div class="col-sm-3">
                                From Date:
                                <input type="date" name="from_date" id="from_date" class="form-control" placeholder="Order From Date">
                            </div>
                            <div class="col-sm-3">
                                To Date:
                                <input type="date" name="to_date" id="to_date" class="form-control" placeholder="Order To Date">
                            </div>
                            <div class="col-sm-3 filterButtonDiv">
                                <button type='button' class='btn btn-info filter mt-4' id='filter'>Filter</button>
                            </div>
                          </div>
                        </div>
                        <table class="table table-light table-borderless table-hover text-center mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Order Id</th>
                                    <th>Transaction Id</th>
                                    <th>Order Amount</th>
                                    <th>Quantity</th>
                                    <th>Ordered At</th>
                                    <th style="width:150px">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle orderData">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Cart End -->
            
        </div>


    

<?php
    include('footer.php');
?>
  </body>
</html>
<script>
    $(document).ready(function(){ 
        const token = localStorage.getItem("token");

        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#to_date").val(today);

        var date = new Date();
        var day = now.getTime() - (3*10 * 24 * 60 * 60 * 1000*3); //90days
        date.setTime(day);
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var dayago = date.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#from_date").val(dayago);

        setTimeout(() => {
            getOrders();
        }, 1500);
        
    });

    $("#filter").click(function(){
        getOrders()
    });

    function getOrders(){
        $(".orderData").html("");

        const token = localStorage.getItem("token");
        const payType = localStorage.getItem("payType");
        //Get orders   
        $.ajax({
            url: `${base_url}/api/user/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"getOrders",
                "from_date":$("#from_date").val(),
                "to_date":$("#to_date").val()
            },
            success: function(result) {
                const data = result.data;
                for(let ord = 0; ord < data.length; ord++){
                    let status = "";
                    let action = "";

                    let created_at = new Date(data[ord].created_at);
                    let delivered_at = new Date(data[ord].delivery_date);
                    let currDate = new Date();
                    let diff = (currDate - created_at)/1000/60/60/24;
                    let delivery_diff = (currDate - delivered_at)/1000/60/60/24;
                    let deliveredProdCount = data[ord].deliveredProdCount;
                    let product_json = JSON.parse(data[ord].product_json);

                    if(data[ord].status == 0){
                        status = "<div class='text-danger'>Failed</div>";
                        if(diff < 2)
                            action = "<button type='button' class='btn btn-info btnClass retryPay' data-orderid="+data[ord].order_id+">Retry Payment</button>";
                    }
                    else if(data[ord].status == 1){
                        if(deliveredProdCount == 0)
                            status = "<div class='text-success'>Success </div>";
                        else
                            status = "<div class='text-success'>Delivery In Transite </div>";
                        // Add condition until pickup not done or until not deivered
                        if(data[ord].is_delivered == 1 && data[ord].is_returned == 0){
                        // Add and condition - return allow only for 7 days after delivery
                            if(delivery_diff <= 7 ){
                                // action += "<button type='button' class='btn btn-warning btnClass returnOrder mr-2 mt-2' data-orderid="+data[ord].order_id+">Return</button>";
                            }
                            status = "<div class='text-success'>Delivered</div>";
                        } else if(data[ord].is_returned == 1){
                            status = "<div class='text-success'>Returned </div>";

                            if(payType == 1){
                                if(data[ord].refund_status == 0){
                                    action += " <div class='text-muted'>Refund Initiated</div>";
                                }else{
                                    status += " <div class='text-muted'>and Refunded</div>";
                                }
                            }
                        }
                        else{
                            if(deliveredProdCount == 0)
                            action = "<button type='button' class='btn btn-danger btnClass cancelOrder mr-2 mt-2' data-orderid="+data[ord].order_id+" >Cancel Order</button>";
                        }
                        action += "<button type='button' class='btn btn-primary btnClass trackOrder mr-2 mt-2' data-orderid="+data[ord].order_id+" data-toggle='modal' data-target='#myModal'>View / Track Order</button>";
                        // action += "<button type='button' class='btn btn-primary btnClass viewInvoice mt-2' data-orderid="+data[ord].order_id+">View Invoice</button>";
                    }
                    else if(data[ord].status == 2){
                        status = "<div class='text-info'>Payment Failed, Awaiting re-payment</div>";
                        if(diff < 2)
                            action = "<button type='button' class='btn btn-info btnClass retryPay' data-orderid="+data[ord].order_id+">Retry Payment</button>";
                    }
                    else if(data[ord].status == 3){
                        status = "<div class='text-warning'>Payment Status Pending</div>";
                    }
                    else if(data[ord].status == 4){
                        status = "<div class='text-danger'>Order Cancelled </div>";
                        if(payType == 1){
                            if(data[ord].refund_status == 0){
                                action = " <div class='text-muted'>Refund Initiated</div>";
                            }else{
                                status += " <div class='text-muted'>and Refunded</div>";
                            }
                        }
                        // action += "<button type='button' class='btn btn-primary btnClass viewInvoice mt-2' data-orderid="+data[ord].order_id+">View Invoice</button>";
                    }
                    else if(data[ord].status == 5){
                        status = "<div class='text-muted'>Return Requested</div>";
                        action += "<button type='button' class='btn btn-primary btnClass trackOrder mr-2 mt-2' data-orderid="+data[ord].order_id+" data-toggle='modal' data-target='#myModal'>View / Track Order</button>";
                        // action += "<button type='button' class='btn btn-primary btnClass viewInvoice mt-2' data-orderid="+data[ord].order_id+">View Invoice</button>";
                    }
                    else if(data[ord].status == 6){
                        status = "<div class='text-muted'>Returned</div>";
                        if(data[ord].refund_status == 0){
                            action = "<a href=''><i class='fa-solid fa-credit-card m-2 refund mr-2 mt-2' data-orderid="+data[ord].order_id+" title='Refund' style='color:green'></i></a>";
                        }else{
                            status += " <div class='text-muted'>and Refunded</div>";
                        }
                        // action += "<button type='button' class='btn btn-primary btnClass viewInvoice mt-2' data-orderid="+data[ord].order_id+">View Invoice</button>";
                    }

                    const htmlData = `<tr>
                            <td>${data[ord].order_id}</td>
                            <td>${data[ord].transaction_id}</td>
                            <td>${data[ord].amount}</td>
                            <td>${data[ord].product_quantity}</td>
                            <td>${data[ord].created_at}</td>
                            <td>${status}</td>
                            <td>${action}</td>
                        </tr>`;

                    $(".orderData").append(htmlData);
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./user/login.php");
            },
        });
    }

    $(document).on('click','.retryPay',function(){
        const orderid = $(this).attr("data-orderid");
       
        const token = localStorage.getItem("token");
        let feurl="";
        if (!window.location.host.includes("localhost")) {
            feurl = "https://cosmeds.in/";
        } else{
            feurl = "http://localhost/cosmeds/";
        }
        setCookie("token",token);
        localStorage.setItem("payType",1);
        localStorage.setItem("order_id",orderid);
        $.ajax({
          url: `${base_url}/api/user/routes.php`, //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          headers: {
              'Authorization':`Bearer ${token}`
          },
          data: {
              "endurl":"reInitiatePayment",feurl,orderid
          },
          success: function(result) {
            localStorage.setItem("merchantOrderId",result.merchantOrderId);
            location.replace(result.data);
          },
          error: function(result) {
            location.replace("./cart.php");
          },
      });
  });
    

  function setCookie(name,value) {
    var d = new Date();
    d.setTime(d.getTime() + (60*2*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
  }

  $(document).on("click",".cancelOrder",function(){
    const orderId = $(this).attr("data-orderid");

    let feurl="";
    if (!window.location.host.includes("localhost")) {
        feurl = "https://cosmeds.in/";
    } else{
        feurl = "http://localhost/cosmeds/";
    }

    if (confirm("Are you sure want to cancel this order?") == true) {
        const token = localStorage.getItem("token");
        const payType = localStorage.getItem("payType");
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {"endurl":"cancelOrder",orderId,feurl,payType},
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
                setTimeout(() => {
                     window.location.reload();
                }, 1000);
                
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    } else {
        
    }
  });

  $(document).on("click",".trackOrder",function(){
    const orderId = $(this).attr("data-orderid");
    localStorage.setItem("t_orderId",orderId);
    const url = "track.php";
    window.location = url;
  });

  $(document).on("click",".returnOrder",function(){
    const orderId = $(this).attr("data-orderid");
    const token = localStorage.getItem("token");

    if (confirm("Are you sure want to return this order?") == true) {
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {"endurl":"returnOrder",orderId},
            success: function(result) {
                window.location.reload();
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }
  });

  $(document).on("click",".viewInvoice",function(){
    const orderId = $(this).attr("data-orderid");
    localStorage.setItem("order_id",orderId);
    window.location = "invoice.php";
  });
</script>