<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Category</title>
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
                        <h1 class="mt-4">Category</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit/Update Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="category.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Categories</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Edit/Update Category
                            </div>
                            <div class="card-body">
                                <form class="editCategory" action="" name="editCategory" id="editCategory" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Category Name <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="category" id="category_name" placeholder="Category Name" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Category Image
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="category_image" id="category_image" class="form-control">
                                                <a id="catImg" href ="" target="_blank">View</a>
                                            </div>
                                            <input type="hidden" name="endurl" id="endurl" value="editCategory" />
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
    const token = localStorage.getItem("token");
    var params = new URLSearchParams(window.location.search);
    const id=params.get('id');
    $("#id").val(id);

    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='editCategory']").validate({
            // Specify validation rules
            rules: {
                category: "required"
            },
            // Specify validation error messages
            messages: {
                category: "Please enter category name"
            },
        });
    });

    // Get unique category by id
    // AJAX Call
    $.ajax({
        url: `${base_url}/api/admin/routes.php`, //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {"endurl":"getUniqueCategory","id":id},
        headers: {
            'Authorization':`Bearer ${token}`
        },
        success: function(result) {
            const data = result.data;
            $("#category_name").val(data.category_name);
            $(`#catImg`). attr('href', "../upload/category/"+data.category_image);
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

    $("#submit").click(function(e) {
        e.preventDefault();
                
        if ($("form[name='editCategory']").valid()) {
            let isValid = false;
            // Validate image extension if exist
            if(document.getElementById('category_image').files[0]){
                var property = document.getElementById('category_image').files[0];
                var image_name = property.name;
                var image_extension = image_name.split('.').pop().toLowerCase();
            
                if (jQuery.inArray(image_extension, ['jpg', 'jpeg', 'png']) == -1) {
                    toastr.error("Only jpg, jpeg and png file formate is allowed", {
                        timeOut: 5000
                    });
                }else{
                    // valid image so should be call edit category
                    isValid = true;
                }
            }else{
                // No image so should be call edit category
                isValid = true;
            }

            if(isValid === true){
                var form = $('#editCategory')[0];
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
                            location.replace("category.php");
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
        }
    }); 
</script>
