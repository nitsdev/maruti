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
                            <li class="breadcrumb-item active">Edit Level1 Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="Sub Category.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Categories</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Edit Level1 Category
                            </div>
                            <div class="card-body">
                                <form class="editL1Category" action="" name="editL1Category" id="editL1Category" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Select Category
                                            </div>
                                            <div class="col-sm-4">
                                                <select name="category" id="category" class="form-select" disabled>
                                                    <option value="">Select Category</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                Select Level 1 Category  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="level1category" id="level1category" class="form-control">
                                            </div>
                                        
                                            <input type="hidden" name="endurl" id="endurl" value="editL1Category" />
                                            <input type="hidden" name="id" id="id" value="" />
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
</html>

<script>
    // AJAX Call
    const token = localStorage.getItem("token");
    var params = new URLSearchParams(window.location.search);
    const id=params.get('id');
    $("#id").val(id);

    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='editL1Category']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                category: "required",
                level1category: "required"
            },
            // Specify validation error messages
            messages: {
                category: "Category Should be selected",
                level1category: "Level1 Category should not be empty"
            }
        });
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


        setTimeout(() => {
            // Get L1 cat by id
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {"endurl":"getUniqueSubCategory","id":id},
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    const data = result.data;
                    $("#category").val(data.category_id);
                    $("#level1category").val(data.sub_category_name);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    location.replace("./login.php");
                },
            });
        }, 200);
    });

    $("#submit").click(function(e) {
        e.preventDefault();
        if ($("form[name='editL1Category']").valid()) {
            const dataPair = {};
            $("#submit").attr("disabled",true);

            $("#editL1Category :input").each(function() {
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

</script>
