<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admin | Maruti Studio | Orders</title>
        <?php
            include('css.php')
        ?>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            span img{
                width: 80px !important;
                height: 55px !important;
            }
            .vegImg {
                max-width: 40px;
                max-height: 20px;
            }

            .dish{
                display: flex;
                justify-content: space-evenly;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
    <?php
        include('assets/navbar.php')
    ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Placed <b>ORDERS</b></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                <button type="button" class="btn btn-primary addAmt">Add Amount on Phonepe</button>
                            </div>
                        </div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="grocery-tab" data-bs-toggle="tab" data-bs-target="#grocery" type="button" role="tab">
                                    Grocery
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="food-tab" data-bs-toggle="tab" data-bs-target="#food" type="button" role="tab">
                                    Food
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="fresh-tab" data-bs-toggle="tab" data-bs-target="#fresh" type="button" role="tab">
                                    Fresh
                                </button>
                            </li>
                        </ul>
                        <!-- Tab content -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Grocery -->
                            <div class="tab-pane fade show active" id="grocery" role="tabpanel">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Grocery Orders
                                    </div>
                                    <div class="card-header">
                                        <input type="text" id="noOfRec" value="100">
                                    </div>
                                    <div class="card-body">
                                        <table id="datatablesSimple">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Order ID</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                    <th>User Name</th>
                                                    <th>Delivery Fee</th>
                                                    <th>Coupon</th>
                                                    <th>Discount</th>
                                                    <th>Ordered At</th>
                                                    <th>Payment Status</th>
                                                    <th>Order Status</th>
                                                    <th>Delivery Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- food tab  -->
                            <div class="tab-pane fade" id="food" role="tabpanel">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Food Orders
                                    </div>
                                    <div class="card-header">
                                        <input type="text" id="noOfRecFood" value="100">
                                    </div>
                                    <div class="card-body">
                                        <table id="datatablesSimple2">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Order ID</th>
                                                    <th>Products</th>
                                                    <th>Resto</th>
                                                    <th>Amount</th>
                                                    <th>User Name</th>
                                                    <th>Delivery Fee</th>
                                                    <th>Coupon</th>
                                                    <th>Discount</th>
                                                    <th>Ordered At</th>
                                                    <th>Payment Status</th>
                                                    <th>Order Status</th>
                                                    <th>Delivery Agent</th>
                                                    <th>Delivery Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                             <!-- Fresh tab  -->
                            <div class="tab-pane fade" id="fresh" role="tabpanel">
                                <p>This is the Contact tab content.</p>
                            </div>

                        </div>
                    </div>
                </main>
                <?php
                include('assets/footer.php')
                ?>
            </div>
        </div>

        <div class="bs-example"> 
            <!-- Modal HTML -->
            <div id="addAmtModal" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add amount on phonepe (Un-Setteled Amount)</h5>
                            <button type="button" class="close closeModal" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-1"></div>
                                <div class="col-sm-5">Enter amount to add on phonepe</div>
                                <div class="col-sm-5"><input type="number" style="text-align:center" id="phonepeAmount"></div>
                                <div class="col-sm-1"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-9"></div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-success addAmtSubmit" data-dismiss="modal">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <?php
            include('js.php')
        ?>
    </body>
</html>
<script>
    const token = localStorage.getItem("token");
    let feurl="";
    if (!window.location.host.includes("localhost")) {
        feurl = "https://shuttleshop.in/";
    } else{
        feurl = "http://localhost/shuttleshop/";
    }

    // $.ajax({
    //     url: `${base_url}/api/admin/routes.php`, //the page containing php script
    //     type: "post", //request type,
    //     dataType: 'json',
    //     data: {"endurl":"getOrders"},
    //     headers: {
    //         'Authorization':`Bearer ${token}`
    //     },
    //     success: function(result) {
    //         const data = result.data;

    //         for(let orders = 0; orders<data.length; orders++){
    //             let status = "";
    //             let action = "";

    //             let created_at = new Date(data[orders].created_at);
    //             let delivered_at = new Date(data[orders].delivery_date);
    //             let currDate = new Date();
    //             let diff = (currDate - created_at)/1000/60/60/24;
    //             let delivery_diff = (currDate - delivered_at)/1000/60/60/24;

    //             if(data[orders].status == 0){
    //                 status = "<div class='text-danger'>Failed</div>";
    //             }
    //             else if(data[orders].status == 1){
    //                 status = "<div class='text-success'>Success </div>";
    //                 // Add condition until pickup not done or until not deivered
    //                 if(data[orders].is_delivered == 1){
    //                 // Add and condition - return allow only for 7 days after delivery
    //                     status = "<div class='text-success'>Delivered</div>";
    //                 }
    //                 else{
    //                     action = "<a href=''><i class='fa-solid fa-cancel m-2 cancelOrder' data-orderid="+data[orders].order_id+" title='Cancel Order' style='color:red'></i></a>";
    //                 }
    //             }
    //             else if(data[orders].status == 2){
    //                 status = "<div class='text-info'>Payment Failed, Awaiting re-payment</div>";
    //             }
    //             else if(data[orders].status == 3){
    //                 status = "<div class='text-warning'>Payment Status Pending</div>";
    //             }
    //             else if(data[orders].status == 4){
    //                 status = "<div class='text-danger'>Order Cancelled </div>";
    //                 if(data[orders].refund_status == 0){
    //                     action = "<a href=''><i class='fa-solid fa-credit-card m-2 refund' data-orderid="+data[orders].order_id+" title='Refund' style='color:green'></i></a>";
    //                 }else{
    //                     status += " <div class='text-muted'>and Refunded</div>";
    //                 }
    //             }
    //             else if(data[orders].status == 5){
    //                 status = "<div class='text-muted'>Return Requested</div>";
    //             }
    //             else if(data[orders].status == 6){
    //                 status = "<div class='text-muted'>Returned</div>";
    //                 if(data[orders].refund_status == 0){
    //                     action = "<a href=''><i class='fa-solid fa-credit-card m-2 refund' data-orderid="+data[orders].order_id+" title='Refund' style='color:green'></i></a>";
    //                 }else{
    //                     status += " <div class='text-muted'>and Refunded</div>";
    //                 }
    //             }

    //             if(data[orders]?.delStatus?.status == 1){
    //                 action += `<i class="fa-solid fa-dispatch m-2 dispatch" data-order_id=${data[orders].order_id} data-product_id=${product_id} title="Ready For Dispatch" style="color:red"></i>`;
    //                 orderCSS = "style='color:red'";
    //             }

    //             const ordersHtml = `<tr>
    //                     <td>${data[orders].id}</td>
    //                     <td>${data[orders].order_id}</td>
    //                     <td>${data[orders].product_quantity}</td>
    //                     <td>${data[orders].amount}</td>
    //                     <td>${data[orders].first_name} ${data[orders].last_name}</td>
    //                     <td>${data[orders].delivery_fee}</td>
    //                     <td>${data[orders].created_at}</td>
    //                     <td>${status}</td>
    //                     <td>
    //                         <a href=""><i class="fa-regular fa-eye m-2 viewOrder" data-orderid='${data[orders].order_id}' title="View Order Details"></i></a>
    //                         ${action}                           
    //                     </td>
    //                 </tr>`;
                
    //             $('#datatablesSimple').find('tbody').append(ordersHtml);
    //         }
    //     },
    //     error: function(result) {
    //         toastr.error(result.responseJSON.msg, {
    //             timeOut: 5000
    //         });
    //         location.replace("./login.php");
    //     },
    // });

    function loadData(){
        const noOfRec =  $("#noOfRec").val();

        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getOrders",noOfRec},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                $('#datatablesSimple tbody').empty();
                const data = result.data;

                const statusArray = [
                                        {"status":1, "color":"#ffc107", "name":"Preparing Package","ddAction":[]},
                                        {"status":2, "color":"#ffc107", "name":"Ready For dispatch","ddAction":[{"status":3,"value":"Dispatched"}]},
                                        {"status":3, "color":"#17a2b8", "name":"Dispatched","ddAction":[{"status":4,"value":"Out For Delivery"}]},
                                        {"status":4, "color":"#007bff", "name":"Out For Delivery","ddAction":[{"status":5,"value":"Delivery Failed Re-attempt Scheduled"},{"status":6,"value":"Delivered"}]},
                                        {"status":5, "color":"#dc3545", "name":"Delivery Failed, Re-attempt Scheduled","ddAction":[{"status":4,"value":"Out For Delivery"}]},
                                        {"status":6, "color":"#28a745", "name":"Delivered"}
                                    ]

                for(let orders = 0; orders<data.length; orders++){

                    const productjson = data[orders].product_json;
                    const product_id = data[orders].product_json.id;
                    const product_name = data[orders].product_json.product_name;
                    const quantity = JSON.parse(data[orders].cart_json).find(obj => obj.product_id === product_id).quantity;
                    const order_id = data[orders].order_id;
                    const trans_id = data[orders].transaction_id;
                    // console.log(product_id,quantity)

                    // let cart_amt = data[orders].amount-data[orders].delivery_fee;

                    let amount = (productjson.price-(productjson.price*productjson.discount/100))*quantity;

                    let action = "";
                    if(data[orders].is_delivered==1){
                        action = `<a href="settlement.php?order_id=${data[orders].order_id}&product_id=${product_id}"><i class="fa-solid fa-coins m-2" title="View Settlement" style="color:orange"></i></a>`;
                    }

                    let orderCSS = "";
                   
                    if(productjson.product_owner == 4 || productjson.product_owner == 0){
                        orderCSS = "style='color:red'";
                    }
                    if((!data[orders]?.delStatus?.status && data[orders].status==1)){
                        action += `<i class="fa-solid fa-dispatch m-2 dispatch" data-order_id=${data[orders].order_id} data-product_id=${product_id} title="Ready For Dispatch" style="color:red"></i>`;
                    }

                    if(data[orders]?.status == 1){
                        action += `<i class="fa-solid fa-cancel m-2 cancelOrder" data-order_id=${data[orders].order_id} data-paytype=${data[orders].ship_rocket_status} title="Cancel Order" style="color:red"></i>`;
                    }
                    
                    let orderStatus = "";
                    let statusColor = "";
                  
                    if(data[orders].deliveredData?.product_id == product_id){
                        orderStatus = "Delivered";
                        statusColor = "#28a745";
                    }
                    if(data[orders].is_returned==1){
                        orderStatus = "Returned";
                        statusColor = "#dc3545";
                    }
                    
                    let statusCSS = `style=color:${statusColor}`;

                    let delStatusVal = "";
                    let delStatusCSS = "";
                    // if(order_id == trans_id){
                    let delStatus = data[orders].delStatus?.status;
                    let selStatusData = statusArray.filter(d=>d.status == delStatus);
                    delStatusVal = selStatusData[0]?.name || "NA";
                    delStatusCSS = `style=color:${selStatusData[0]?.color}`;
                    // }

                    if(orderStatus == ""){
                        if(data[orders].status==0){
                            orderStatus = "Failed";
                        }else if(data[orders].status==1){
                            orderStatus = "Success";
                        }else if(data[orders].status==2){
                            orderStatus = "Failed";
                        }else if(data[orders].status==3){
                            orderStatus = "Pending";
                        }else if(data[orders].status==4){
                            orderStatus = "Cancelled";
                            statusColor = "style=color:red";
                        }else if(data[orders].status==5){
                            orderStatus = "Return Requested";
                        }else if(data[orders].status==6){
                            orderStatus = "Returned";
                        }
                    }

                    const delStatusValue = data[orders].status==1 ? delStatusVal : '';
                    
                    const ordersHtml = `<tr>
                            <td>${data[orders].id}</td>
                            <td ${orderCSS}>${data[orders].order_id}</td>
                            <td><a href='editProduct.php?id=${data[orders].product_json.id}' target='_blank'>${product_name}</a></td>
                            <td>${quantity}</td>
                            <td>${amount}</td>
                            <td>${data[orders].first_name}</td>
                            <td>${data[orders].delivery_fee}</td>
                            <td>${data[orders].coupon}</td>
                            <td>${data[orders].coupon_discount}</td>
                            <td>${data[orders].created_at}</td>
                            <td>${data[orders].transaction_id == 0 ? "Failed" : [1,4,5,6].includes(+data[orders].status) ? "Success" :"Awaiting"}</td>
                            <td ${statusColor}>${orderStatus}</td>
                            <td ${delStatusCSS}>${delStatusValue}</td>
                            <td>
                                <a href=""><i class="fa-regular fa-eye m-2" title="View"></i></a>
                                ${action}
                            </td>
                        </tr>`;
                    
                    $('#datatablesSimple').find('tbody').append(ordersHtml);
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });

        // Food
        setTimeout(() => {
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {"endurl":"getFoodOrders",noOfRec},
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    $('#datatablesSimple2 tbody').empty();
                    const data = result.data;

                    const statusArray = [
                                {"status":1, "color":"#ffc107", "name":"Preparing Food","ddAction":[]},
                                {"status":2, "color":"#ffc107", "name":"Packed","ddAction":[{"status":3,"value":"Dispatched"}]},
                                {"status":3, "color":"#17a2b8", "name":"Out For Delivery","ddAction":[{"status":4,"value":"Out For Delivery"}]},
                                {"status":5, "color":"#007bff", "name":"Cancelled","ddAction":[]},
                                {"status":4, "color":"#28a745", "name":"Delivered"}
                            ];

                    let cnt=1;
                    data.forEach((ord)=>{

                        let delStatusVal = "";
                        let delStatusCSS = "";
                        let orderStatus = "";
                        let statusColor = "";

                        let delStatus = ord.delStatus?.status;
                        let selStatusData = statusArray.filter(d=>d.status == delStatus);
                        delStatusVal = selStatusData[0]?.name || "NA";
                        delStatusCSS = `style=color:${selStatusData[0]?.color}`;

                        if(ord.status==0){
                            orderStatus = "Failed";
                            statusColor = "style=color:#bfb04f";
                        }else if(ord.status==1){
                            orderStatus = "Success";
                            statusColor = "style=color:green";
                        }else if(ord.status==2){
                            orderStatus = "Failed";
                            statusColor = "style=color:red";
                        }else if(ord.status==3){
                            orderStatus = "Pending";
                            statusColor = "style=color:#bfb04f";
                        }else if(ord.status==4){
                            orderStatus = "Cancelled";
                            statusColor = "style=color:red";
                        }

                        action = "";
                        if(!ord?.delStatus?.status && ord.status==1){
                            action += `<i class="fa-solid fa-dispatch m-2 acceptFoodOrder" data-order_id=${ord.order_id} title="Accept For Prepare Food" style="color:red"></i>`;
                        }

                        if(ord?.status == 1){
                            action += `<i class="fa-solid fa-cancel m-2 cancelFoodOrder" data-order_id=${ord.order_id} title="Cancel Order" style="color:red"></i>`;
                        }
                        

                        const delStatusValue = ord.status==1 ? delStatusVal : '';

                        const shipping_data = JSON.parse(ord.shipping_json);
                        const cart_data = JSON.parse(ord.cart_json);
                        const product_data = JSON.parse(ord.product_json);

                        let dishesData = "";

                        cart_data.forEach((cart)=>{
                            const product_id = cart.product_id;
                            const price_type = cart.price_type;
                            const quantity = cart.quantity;

                            const productData = product_data.filter((prod)=> prod.product_id == product_id && prod.half_full == price_type);

                            const prodName = productData[0].product_name;
                            const veg_nonveg = productData[0].veg_nonveg;

                            dishesData+= `<div class='dish'>
                                <div>${veg_nonveg == 1 ? '<img src="../img/veg.png" class="vegImg" alt="">' : '<img src="../img/nonveg.png" class="vegImg" alt="">'}</div>
                                <div>${prodName}</div>
                                <div>(${price_type == 1 ? "Half" : "Full"})</div> x <div>${quantity}</div>
                            </div><br>`;
                        });

                        const ordersHtml = `<tr>
                            <td>${cnt}</td>
                            <td>${ord.order_id}</td>
                            <td>${dishesData}</td>
                            <td>${ord?.seller?.first_name || ''} ${ord?.seller?.last_name || ''}<br>
                                ${ord?.seller?.email || ''} ${ord?.seller?.mobile || ''} <br>
                                ${ord?.seller?.add_line1 || ''} ${ord?.seller?.add_line2 || ''}
                                ${ord?.seller?.landmark || ''} ${ord?.seller?.pincode || ''}
                            </td>
                            <td>${ord.amount}</td>
                            <td>${ord.first_name} ${ord.last_name}<br><br>
                                ${shipping_data[0]?.first_name} ${shipping_data[0]?.last_name}<br>
                                ${shipping_data[0]?.email} ${shipping_data[0]?.mobile} <br>
                                ${shipping_data[0]?.add_line1} ${shipping_data[0]?.add_line2}
                                ${shipping_data[0]?.landmark} ${shipping_data[0]?.pincode}
                            </td>
                            <td>${ord.delivery_fee}</td>
                            <td>${ord.coupon || ""}</td>
                            <td>${ord.coupon_discount || 0}</td>
                            <td>${ord.created_at}</td>
                            <td>${ord.transaction_id == 0 ? "Failed" : [1,4,5,6].includes(+ord.status) ? "Success" :"Awaiting"}</td>
                            <td ${statusColor}>${orderStatus}</td>
                             <td>${ord?.agent?.first_name || ''} ${ord?.agent?.last_name || ''}<br>
                                ${ord?.agent?.email || ''} ${ord?.agent?.mobile || ''} <br>
                                ${ord?.agent?.add_line1 || ''} ${ord?.agent?.add_line2 || ''}
                                ${ord?.agent?.landmark || ''} ${ord?.agent?.pincode || ''}
                            </td>
                            <td ${delStatusCSS}>${delStatusValue}</td>
                            <td>
                                ${action}
                            </td>
                        </tr>`;
                    
                        $('#datatablesSimple2').find('tbody').append(ordersHtml);

                        cnt++;

                    });

                    
                    
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    location.replace("./login.php");
                },
            }); 
        }, 1000);
        
    }

    loadData();

    $("#noOfRec").change(function(){
        loadData();
    });

    $(document).on('click','.dispatch',function(){
        const orderId = $(this).attr("data-order_id");
        const prodId = $(this).attr("data-product_id");

        const token = localStorage.getItem("token");
        $.ajax({
            url: `${base_url}/api/admin/routes.php`,
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl": "changeOrderStatus",
                orderId,
                statusId: 2,
                prodId,
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            }
        });
    });


    // On add amount button
    $(document).on("click",".addAmt",function(){
        $("#addAmtModal").modal('show');
    });

    $(document).on("click",".addAmtSubmit",function(){
        const amount = $("#phonepeAmount").val();
        if(amount)
            initiatePayment(amount);
        else
            alert("Please enter amount");
    });

    function initiatePayment(amount){
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"initiatePayment",
                amount,
                feurl
            },
            success: function(result) {
                window.location.replace(result.data);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    $(document).on('click','.refund',function(e){
        e.preventDefault();
        const orderid =  $(this).attr("data-orderid");
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"refund",
                orderid,
                feurl
            },
            success: function(result) {
                const d = new Date();
                let day = d.getDay()

                if(day==6 || day == 0){
                    toastr.error("Refund might not initiated on weekends and public holidays", {
                        timeOut: 5000
                    });
                }else{
                    // window.location.replace(result.data);
                    if(result.data.message=="Payment Failed"){
                        msg="";
                        if(result.data.data.responseCode == "EXCESS_REFUND_AMOUNT"){
                            msg="Refund Already Initiated or Excess refund amount for this order, Add amount on phonepe and try again";
                        }
                        else if(result.data.data.responseCode == "BF_034"){
                            msg="Add amount on phonepe and try again";
                        }else{
                            msg=result.data.data.responseCode;
                        }
                        toastr.error(msg, {
                            timeOut: 5000
                        });
                    }else{
                        toastr.success("Amount refund initiated", {
                            timeOut: 5000
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2500);
                    }
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    });


    $(document).on('click','.cancelOrder',function(e){
        e.preventDefault();
        const orderId =  $(this).attr("data-order_id");
        const payType =  $(this).attr("data-paytype");
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"cancelOrder",
                orderId,
                payType
            },
            success: function(result) {
                toastr.success("Amount refund initiated", {
                    timeOut: 5000
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2500);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    });

    // Food
    $(document).on('click','.cancelFoodOrder',function(e){
        e.preventDefault();
        let userConfirmed = confirm("Are you sure you want to cancel this order?");

        const orderId =  $(this).attr("data-order_id");

        if(userConfirmed){
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                data: {
                    "endurl":"cancelFoodOrder",
                    orderId
                },
                success: function(result) {
                    toastr.success("Amount refund initiated", {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                },
            });
        }
    });

    $(document).on('click','.acceptFoodOrder',function(e){
        e.preventDefault();
        
        let userConfirmed = confirm("Are you sure you want to accept this order behalf of seller?");

        const orderId =  $(this).attr("data-order_id");

        if(userConfirmed){
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                data: {
                    "endurl":"acceptFoodOrder",
                    orderId
                },
                success: function(result) {
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 2500);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                },
            });
        }
    });
</script>