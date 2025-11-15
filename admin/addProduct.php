<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Product</title>
        <?php
            include('css.php')
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha382-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            .inner-div{
                padding-bottom:10px;
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
                        <h1 class="mt-4">Product</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add New Product</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="product.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Product</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Add New Product
                            </div>
                            <div class="card-body">
                                <form class="addProduct" action="" name="addProduct" id="addProduct" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Name <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="product_name" id="product_name" placeholder="Product Name" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Category <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="category_id" id="category_id" class="form-select">
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Level 1 Category <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="level1category_id" id="level1category_id" class="form-select">
                                                    <option value="">Select Level 1 Category</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                Sub Category
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="sub_category_id" id="sub_category_id" class="form-select">
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Description <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-10">
                                                <textarea name="description" id="description" placeholder="Description" cols="100" rows="2" class="description"></textarea>
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Short Description <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="short_description" id="short_description" placeholder="Short Description" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Bullet 1 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="bullet1" id="bullet1"  placeholder="Bullet 1" class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Bullet 2 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="bullet2" id="bullet2"  placeholder="Bullet 2" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Bullet 3 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="bullet3" id="bullet3"  placeholder="Bullet 3" class="form-control"/>
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Selling Price (MRP Price)<span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="price" id="price"  placeholder="Selling Price" class="form-control calGst"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Discount <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="discount" id="discount"  placeholder="Discount in %" class="form-control calGst"/>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                HSN Number
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="hsn" id="hsn"  placeholder="HSN Number" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                GST % <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <!-- <select name="gst" id="gst" class="form-select calGst">
                                                    <option value="0">0%</option>
                                                    <option value="5">5%</option>
                                                    <option value="12">12%</option>
                                                    <option value="18">18%</option>
                                                    <option value="28">28%</option>
                                                </select> -->
                                                <input type="number" name="gst" id="gst"  placeholder="GST in %" class="form-control calGst"/>
                                            </div>
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Stock <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="stock" id="stock"  placeholder="Stock" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Selling Inc. GST <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <span class='inc_gst'></span>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Medicine Type <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="medicine_type" id="medicine_type" class="form-select">
                                                    <option value="1">No Prescription</option>
                                                    <option value="2">RX</option>
                                                    <option value="3">nRX</option>
                                                </select>
                                                <!-- <input type="number" name="medicine_type" id="medicine_type"  placeholder="Stock" class="form-control"/> -->
                                            </div>
                                            
                                        </div>

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Length <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="length" id="length"  placeholder="Product Length" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Product Width <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="width" id="width"  placeholder="Product Width" class="form-control"/>
                                            </div>
                                        </div>


                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Height <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="height" id="height"  placeholder="Product Height" class="form-control"/>
                                            </div>
                                            <div class="col-sm-2">
                                                Product Weight <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" name="weight" id="weight"  placeholder="Product Weight" class="form-control"/>
                                            </div>
                                        </div>

                                        <!-- <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Owner <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="product_owner" id="product_owner" class="form-select">
                                                    <option value="">Select Product Owner</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 text-danger">
                                                Note: If you are adding product behalf of any seller please select seller from dropdown else select as admin.
                                            </div>
                                        </div> -->

                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                <strong>Upload Product images</strong>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Image 1 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image1" class="product_image form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Product Image 2 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image2" class="product_image form-control">
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Image 3 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image3" class="product_image form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Product Image 4 <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image4" class="product_image form-control">
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="endurl" id="endurl" value="addProduct" />
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

    <script>
    
    const token = localStorage.getItem("token");

    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='addProduct']").validate({
            // Specify validation rules
            ignore: [],
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                product_name: "required",
                category_id: "required",
                // sub_category_id: "required",
                description: "required",
                short_description: "required",
                bullet1: "required",
                bullet2: "required",
                bullet3: "required",
                price: "required",
                discount: "required",
                stock: "required",
                length: "required",
                width: "required",
                height: "required",
                weight: "required",
                'product_image[]': "required"
            },
            // Specify validation error messages
            messages: {
                product_name: "Please Enter product name",
                category_id: "Please select category",
                // sub_category_id: "Please select sub category",
                description: "Please enter description",
                short_description: "Please enter short description",
                bullet1: "Please enter bullet1",
                bullet2: "Please enter bullet2",
                bullet3: "Please enter bullet3",
                price: "Please enter selling price",
                discount: "Please enter discount %",
                stock: "Please enter stock",
                length: "Please enter product length",
                width: "Please enter product width",
                height: "Please enter product height",
                weight: "Please enter product weight",
                'product_image[]':"Please select product image"
            }
        });
    });
    
    // $("#category_id,#level1category_id").change(function(){
    //     if($("#category").val() == "" && $("#level1category").val()==""){
    //         $("#category").prop('required',true);
    //         $("#level1category").prop('required',true);
    //     }else{
    //         $("#category").prop('required',false);
    //         $("#level1category").prop('required',false);
    //     }
    // })

    $("#submit").click(function(e) {
        e.preventDefault();
        if($("#category").val() == "" && $("#level1category").val() == ""){
            $("#category").prop('required',true);
            $("#level1category").prop('required',true);
            $("form[name='addProduct']").valid();
        }
        
        if ($("form[name='addProduct']").valid()) {

            var form = $('#addProduct')[0];
            var formData = new FormData(form);


            // const dataPair = {};
            $("#submit").attr("disabled",true);

            // $("#addProduct :input").each(function() {
            //     if ($(this).attr("name")) {
            //         dataPair[$(this).attr("name")] = $(this).val();
            //     }
            // });
            
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
                        location.replace("product.php");
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

    $("document").ready(function() {

        // Get all active category
        // AJAX Call
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getCategory","status":1},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const items = result.data;
                var list = $("#category_id");
                for(var i in items) {
                    list.append(new Option(items[i].category_name, items[i].id));
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });

                if(result.status == 500){
                    setTimeout(() => {
                        location.replace("login.php");
                    }, 2000);
                }
            }
        });

        $("#category_id").change(function(){
            // Get all active sub category
            // AJAX Call
            const categoryid = $(this).val();
            $("#level1category_id").find('option:not(:first)').remove();
            $("#sub_category_id").find('option:not(:first)').remove();
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {"endurl":"getSubCategory","status":1,"level":1,"categoryid":categoryid},
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    const items = result.data;
                    var list = $("#level1category_id");
                    for(var i in items) {
                        list.append(new Option(items[i].sub_category_name, items[i].id));
                    }
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });

                    if(result.status == 500){
                        setTimeout(() => {
                            location.replace("login.php");
                        }, 2000);
                    }
                }
            });
        });

        $("#level1category_id").change(function(){
            // Get all active sub category
            // AJAX Call
            const categoryid = $("#category_id").val();
            const level1catid = $("#level1category_id").val();
            $("#sub_category_id").find('option:not(:first)').remove();

            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {"endurl":"getSubCategory","status":1,"level":2,"categoryid":categoryid,"level1catid":level1catid},
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    const items = result.data;
                    var list = $("#sub_category_id");
                    for(var i in items) {
                        list.append(new Option(items[i].sub_category_name, items[i].id));
                    }
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });

                    if(result.status == 500){
                        setTimeout(() => {
                            location.replace("login.php");
                        }, 2000);
                    }
                }
            });
        });


    });  

    $(".calGst").change(function(){
        const price = $("#price").val() || 0;
        const discount = $("#discount").val() || 0;
        const gst = $("#gst").val() || 0;
        const priceAfterDiscount = price - ((price*discount)/100);
        const actSellingpriceIncGST = priceAfterDiscount + ((priceAfterDiscount*gst)/100);
        $(".inc_gst").text(actSellingpriceIncGST);
    });
    
</script>

</html>
