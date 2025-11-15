<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Edit Product</title>
        <?php
            include('css.php')
        ?>
        <style>
            .imgprod{
                width:150px;
                height:100px;
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
                        <h1 class="mt-4">Edit Product</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit / Update Product</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="Product.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Categories</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Edit / Update Product
                            </div>
                            <div class="card-body">
                                <form class="editProduct" action="" name="editProduct" id="editProduct" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Name <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="product_name" id="product_name" placeholder="" class="form-control">
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
                                                    <option value="0">Select Level 1 Category</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                Sub Category
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="sub_category_id" id="sub_category_id" class="form-select">
                                                    <option value="0">Select Sub Category</option>
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
                                                Selling Price (MRP Price) <span class="mandatory">*</span>
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
                                                Selling Inc GST <span class="mandatory">*</span>
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

                                        <div class="row inner-div" id="img_data">
                                            <div class="col-sm-2">
                                                <strong>Upload Product images</strong>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Image 1 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image1" class="product_image form-control">
                                                <span>
                                                    <a href="" id="product_image_data1" target="_blank">view</a>
                                                </span>
                                            </div>
                                            <div class="col-sm-2">
                                                Product Image 2 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image2" class="product_image form-control">
                                                <span>
                                                    <a href="" id="product_image_data2" target="_blank">view</a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Product Image 3 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image3" class="product_image form-control">
                                                <span>
                                                    <a href="" id="product_image_data3" target="_blank">view</a>
                                                </span>
                                            </div>
                                            <div class="col-sm-2">
                                                Product Image 4 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="product_image[]" id="product_image4" class="product_image form-control">
                                                <span>
                                                    <a href="" id="product_image_data4" target="_blank">view</a>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="endurl" id="endurl" value="editProduct" />
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
    let category_id = 0;
    let level1cat = 0;
    let subcat =0;
    let product_owner = 0;
    
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='editProduct']").validate({
            // Specify validation rules
            ignore: [],
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                product_name: "required",
                category_id: "required",
                level1category_id: "required",
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
                weight: "required"
            },
            // Specify validation error messages
            messages: {
                product_name: "Please Enter product name",
                category_id: "Please select category",
                level1category_id: "Please select level 1 category",
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
                weight: "Please enter product weight"
            }
        });
    });

    // AJAX Call
    const token = localStorage.getItem("token");
    var params = new URLSearchParams(window.location.search);
    const id=params.get('id');
    $("#id").val(id);

    

    $.ajax({
        url: `${base_url}/api/admin/routes.php`, //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {"endurl":"getUniqueProduct","id":id},
        headers: {
            'Authorization':`Bearer ${token}`
        },
        success: function(result) {
            const data = result.data;
            $("#product_name").val(data.product_name); // for getting data
            $("#description").val(data.description);
            $("#short_description").val(data.short_description);
            $("#category_id").val(data.category_id);
            $("#bullet1").val(data.bullet1);
            $("#bullet2").val(data.bullet2);
            $("#bullet3").val(data.bullet3);
            $("#price").val(data.price);
            $("#discount").val(data.discount);
            $("#stock").val(data.stock);
            $("#medicine_type").val(data.medicine_type);
            $("#length").val(data.length);
            $("#width").val(data.width);
            $("#height").val(data.height);
            $("#weight").val(data.weight);
            $("#hsn").val(data.hsn);
            $("#gst").val(data.gst);
            
            calGST();

            category_id = data.category_id;
            level1cat = data.level1category_id || 0;
            subcat = data.sub_category_id || 0;
            product_owner = data.product_owner;

            const image_data = result.image_data;
            for(let img_count = 0; img_count<image_data.length; img_count++){
                console.log(`#product_image_data${img_count+1}`)
                $(`#product_image_data${img_count+1}`). attr('href', "../"+image_data[img_count].url); 
            }
        },
        error: function(result) {
            toastr.error(result.responseJSON.msg, {
                timeOut: 5000
            });
            location.replace("./login.php");
        },
    });

    $("document").ready(function() {

        $("#category_id").change(async function(){
            // Get all active sub category
            // AJAX Call
            const categoryid = $(this).val();
            await loadLevel1Cat(1,categoryid);
            
        });
        $("#level1category_id").change(async function(){
            // Get all active sub category
            // AJAX Call
            const categoryid = $("#category_id").val();
            await loadSubCategory(1,categoryid);
        });

        async function loadLevel1Cat(action, categoryid){
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
                    
                    if(action===0){
                        $("#level1category_id").val(level1cat);
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
        }

        async function loadSubCategory(action, categoryid){
            $("#sub_category_id").find('option:not(:first)').remove();
            const level1catid = $("#level1category_id").val();

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

                    if(action===0){
                        $("#sub_category_id").val(subcat);
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
        }

        
        
        
        setTimeout(() => {
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
                success: async function(result) {
                    const items = result.data;
                    var list = $("#category_id");
                    for(var i in items) {
                        list.append(new Option(items[i].category_name, items[i].id));
                    }

                    $("#category_id").val(category_id);
                    await loadLevel1Cat(0,category_id);
                    setTimeout(() => {
                        loadSubCategory(0,category_id);
                    }, 500);
                    
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
        }, 200);

    }); 

    $("#submit").click(function(e) {
        e.preventDefault();
        if($("#category").val() == "" && $("#level1category").val() == ""){
            $("#category").prop('required',true);
            $("#level1category").prop('required',true);
            $("form[name='editProduct']").valid();
        }
        
        if ($("form[name='editProduct']").valid()) {

            var form = $('#editProduct')[0];
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

    $(".calGst").change(function(){
        calGST();
    });

    function calGST(){
        const price = $("#price").val() || 0;
        const discount = $("#discount").val() || 0;
        const gst = $("#gst").val() || 0;
        const priceAfterDiscount = price - ((price*discount)/100);
        const actSellingpriceIncGST = priceAfterDiscount + ((priceAfterDiscount*gst)/100);
        $(".inc_gst").text(actSellingpriceIncGST);
    }

</script>
