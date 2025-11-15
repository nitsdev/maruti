<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Edit Coupon</title>
        <?php
            include('css.php')
        ?>
        <style>
            .imgprod{
                width:150px;
                height:100px;
            }
            .inner-div{
                padding-bottom:20px;
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
                        <h1 class="mt-4">Edit Coupon</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit / Update Coupon</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="coupons.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Coupons</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Edit / Update Coupon
                            </div>
                            <div class="card-body">
                                <form class="editCoupon" action="" name="editCoupon" id="editCoupon" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Coupon Code <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="coupon_code" id="coupon_code" placeholder="Enter Coupon Code" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Minimum Cart Amount <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="minAmount" id="minAmount" placeholder="Enter Min Cart amount" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Description <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea name="description" id="description" placeholder="Enter Coupon Description" cols="90" rows="2" class="description"></textarea>
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Discount % <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="discount_percent" id="discount_percent" placeholder="Discount Percent" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Discount Type <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="discount_type" id="discount_type" class="form-control">
                                                    <option value="">Select Discount Type</option>
                                                    <option value="1">Up To</option>
                                                    <option value="2">Flat</option>
                                                </select>    
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Valid From <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="date" name="validFrom" id="validFrom"  placeholder="Coupon valid from" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Valid till <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="date" name="validTill" id="validTill"  placeholder="Coupon valid till" class="form-control"/>
                                            </div>
                                        </div>

                                        <input type="hidden" name="endurl" id="endurl" value="editCoupon" />
                                        <input type="hidden" name="id" id="id" value="" />
                                        <div class="button-section">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php
                include('assets/footer.php')
                ?>
            </div>
        </div>
        <?php
            include('js.php')
        ?>

        <script type="text/javascript" src="./js/jquery-custom-validation.js"></script>

    </body>
</html>
<script>
    
    // AJAX Call
    const token = localStorage.getItem("token");
    var params = new URLSearchParams(window.location.search);
    const id=params.get('id');
    $("#id").val(id);

    $.ajax({
        url: `${base_url}/api/admin/routes.php`, //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {"endurl":"getUniqueCoupon","id":id},
        headers: {
            'Authorization':`Bearer ${token}`
        },
        success: function(result) {
            const data = result.data;
            $("#coupon_code").val(data.code);
            $("#minAmount").val(data.min_amt);
            $("#description").val(data.description);
            $("#discount_percent").val(data.discount_per);
            $("#discount_type").val(data.discount_type);
            $("#validFrom").val(data.valid_from);
            $("#validTill").val(data.valid_till);

        },
        error: function(result) {
            toastr.error(result.responseJSON.msg, {
                timeOut: 5000
            });
            location.replace("./login.php");
        },
    });
    
    $("#submit").click(function(e) {
        e.preventDefault();
                
        if ($("form[name='editCoupon']").valid()) {
            var form = $('#editCoupon')[0];
            var formData = new FormData(form);

            // const dataPair = {};
            $("#submit").attr("disabled",true);
            
            // AJAX Call
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#submit").attr("disabled",false);
                        location.replace("coupons.php");
                    }, 2000);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#submit").attr("disabled",false);
                        if(result.status == 500){
                            location.replace("login.php");
                        }
                    }, 2000);
                },
            });
        }
    });
</script>
