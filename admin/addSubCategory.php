<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Sub Category</title>
        <?php
            include('css.php')
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha382-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        
    </head>
    <body class="sb-nav-fixed">
    <?php
        include('assets/navbar.php')
    ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Sub Category</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add New Sub Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="Sub Category.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Categories</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Add New Sub Category
                            </div>
                            <div class="card-body">
                                <form class="addSubCategory" action="" name="addSubCategory" id="addSubCategory" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Select Category
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="category" id="category" class="form-select">
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2 l1cat">
                                                Select Level 1 Category
                                            </div>
                                            <div class="col-sm-4 l1cat">
                                                <select name="level1category" id="level1category" class="form-select">
                                                    <option value="">Select Level 1 Category</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-sm-2 l2cat">
                                                <span class="l2catname">Enter Leavel1 Category Name </span><span class="selCatName"></span> <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 l2cat">
                                                <input type="text" name="sub_category" id="sub_category" placeholder="Sub Category Name" class="form-control">
                                            </div>
                                        
                                            <input type="hidden" name="endurl" id="endurl" value="addSubCategory" />
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
    $.validator.addMethod("custValidate", function(value, element, param) {
        const flag= $("#category").val() == "" && $("#level1category").val() == "";
        console.log(flag);
        return flag;
    });

    
    const token = localStorage.getItem("token");

    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='addSubCategory']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                sub_category: "required",
            },
            // Specify validation error messages
            messages: {
                sub_category: "Please enter sub category",
                category: "Category Or Level1 Category should be selected",
                level1category: "Category Or Level1 Category should be selected"
            }
        });
    });
    
    $("#category,#level1category").change(function(){
        if($("#category").val() == "" && $("#level1category").val()==""){
            $("#category").prop('required',true);
            $("#level1category").prop('required',true);
        }else{
            $("#category").prop('required',false);
            $("#level1category").prop('required',false);
        }
        $("form[name='addSubCategory']").valid();
    });

    $("#level1category").change(function(){
        $(".l2catname").text("Enter Leavel2 Category Name");
    });

    $("#submit").click(function(e) {
        e.preventDefault();
        if($("#category").val() == "" && $("#level1category").val() == ""){
            $("#category").prop('required',true);
            $("#level1category").prop('required',true);
            $("form[name='addSubCategory']").valid();
        }
        
        if ($("form[name='addSubCategory']").valid()) {
            const dataPair = {};
            $("#submit").attr("disabled",true);

            $("#addSubCategory :input").each(function() {
                if ($(this).attr("name")) {
                    dataPair[$(this).attr("name")] = $(this).val();
                }
            });
            
            // AJAX Call
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: dataPair,
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#submit").attr("disabled",false);
                        location.replace("subcategory.php");
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
        $(".l1cat,.l2cat").css("display","none");

        // Get all active category
        // AJAX Call
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getAllCategory","status":1},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const items = result.data;
                var list = $("#category");
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

        $("#category").change(function(){
            // Get all active sub category
            // AJAX Call
            const categoryid = $(this).val();
            $(".l2catname").text("Enter Leavel1 Category Name");
            $(".selCatName").text(" For "+$("#category option:selected").text());
            $("#level1category").find('option:not(:first)').remove();
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
                    var list = $("#level1category");

                    if(items.length){
                        for(var i in items) {
                            list.append(new Option(items[i].sub_category_name, items[i].id));
                        }

                    }else{

                    }
                    
                    $(".l1cat,.l2cat").css("display","block");
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

</script>

</html>
