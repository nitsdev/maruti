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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha382-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        
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
                            <li class="breadcrumb-item active">Add New Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="category.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View Categories</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Add New Category
                            </div>
                            <div class="card-body">
                                <form class="addCategory" action="" name="addCategory" id="addCategory" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                Category Name <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" name="category" id="category" placeholder="Category Name" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Category Image <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="file" name="category_image" id="category_image" class="form-control">
                                            </div>
                                            
                                            <input type="hidden" name="endurl" id="endurl" value="add_category" />
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
        $("form[name='addCategory']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                category: "required",
                category_image: "required"
            },
            // Specify validation error messages
            messages: {
                category: "Please enter category name",
                category_image: "Please select category image"
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
        
        if ($("form[name='addCategory']").valid()) {
            var property = document.getElementById('category_image').files[0];
            var image_name = property.name;
            var image_extension = image_name.split('.').pop().toLowerCase();
        
            if (jQuery.inArray(image_extension, ['jpg', 'jpeg', 'png']) == -1) {
                toastr.error("Only jpg, jpeg and png file formate is allowed", {
                    timeOut: 5000
                });
            }else{
                $("#submit").attr("disabled",true);

                var form = $('#addCategory')[0];
                var formData = new FormData(form);

                const dataPair = {};
                $("#addCategory :input").each(function() {
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
                            location.replace("./category.php");
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
    });

</script>

</html>
