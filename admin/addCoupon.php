<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Coupon</title>
        <?php
            include('css.php')
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha382-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            .inner-div{
                padding-bottom: 10px;
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
                        <h1 class="mt-4">Coupon</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add New Coupon</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="coupons.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Coupons</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Add New Coupon
                            </div>
                            <div class="card-body">
                                <form class="addCoupon" action="" name="addCoupon" id="addCoupon" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row">
                                            <div class="col-sm-2 inner-div">
                                                Coupon Code <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="text" name="coupon" id="coupon" placeholder="Coupon Code" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Minimum Cart Amount <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                            <input type="number" name="minAmount" id="minAmount" placeholder="Minimum cart amount" class="form-control">
                                            </div>

                                            <div class="col-sm-2 inner-div">
                                                Coupon Valid From <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="date" name="validFrom" id="validFrom" placeholder="Coupon valid From" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Coupon Valid Till <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="date" name="validTill" id="validTill" placeholder="Coupon valid Till" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Coupon Description <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="text" name="couponDescription" id="couponDescription" placeholder="Coupon Code Description" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Discount type
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <select name="discountType" id="discountType" class="form-control">
                                                    <option value="">Select Discount Type</option>
                                                    <option value="1">Up To</option>
                                                    <option value="2">Flat</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Program type
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <select name="programId" id="programId" class="form-control">
                                                    <option value="">Select Program Type</option>
                                                    <option value="1">Grocery</option>
                                                    <option value="2">Food</option>
                                                    <option value="3">Fresh</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Discount % <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="number" name="discount_per" id="discount_per" placeholder="Discount %" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                Discount Per User <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="number" name="perUser" id="perUser" placeholder="Coupon Code Discount per user limit" class="form-control">
                                            </div>
                                            <div class="col-sm-2 inner-div">
                                                 Discount Per Month <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="number" name="perMonth" id="perMonth" placeholder="Coupon Code Discount per month limit" class="form-control">
                                            </div>
                                            <input type="hidden" name="endurl" id="endurl" value="add_coupon" />
                                        </div>
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
    </body>

    <script>
    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='addCoupon']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                coupon: "required",
                minAmount: "required",
                validFrom: "required",
                validTill: "required",
                couponStatus: "required",
                couponDescription: "required",
                discountType: "required",
                discount_per: "required",
                perUser: "required",
                perMonth: "required"
            },
            // Specify validation error messages
            messages: {
                coupon: "Please enter category name",
                minAmount: "Please enter category name",
                validFrom: "Please enter category name",
                validTill: "Please enter category name",
                couponStatus: "Please enter category name",
                couponDescription: "Please enter category name",
                discountType: "Please enter category name",
                discount_per: "Please enter category name",
                perUser: "Please enter category name",
                perMonth: "Please enter category name"
            },
                
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            // submitHandler: function(form) {
            //     form.submit();
            // }
        });
    });
    
    $("#submit").click(function(e) {
        e.preventDefault();
        
        if ($("form[name='addCoupon']").valid()) {
                var form = $('#addCoupon')[0];
                var formData = new FormData(form);

                const dataPair = {};
                $("#addCoupon :input").each(function() {
                    if ($(this).attr("name")) {
                        dataPair[$(this).attr("name")] = $(this).val();
                    }
                });

                const token = localStorage.getItem("token");

                $.ajax({
                    url: `${base_url}/api/admin/routes.php`,
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    headers: {
                        'Authorization':`Bearer ${token}`
                    },
                    success: function (result) {
                        toastr.success(JSON.parse(result).msg, {
                            timeOut: 5000
                        });
                        setTimeout(() => {
                            $("#submit").attr("disabled",false);
                            location.replace("./coupons.php");
                        }, 2000);
                    },
                    error: function(result) {
                        toastr.error(JSON.parse(result.responseText).msg, {
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
        }
    );

</script>

</html>
