<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Newly arrived Brands </title>
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
                        <h1 class="mt-4">Newly arrived Brands</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Newly arrived Brands</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Newly arrived Brands
                            </div>
                            <div class="card-body">
                                <form class="homeImg" action="" name="homeImg" id="homeImg" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 1  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg1" id="homeImg1" class="form-control">
                                                <input type="text" name="text1" id="text1" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                        </div>
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 2  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg2" id="homeImg2" class="form-control">
                                                <input type="text" name="text2" id="text2" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                        </div>
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 3  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg3" id="homeImg3" class="form-control">
                                                <input type="text" name="text3" id="text3" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                        </div>
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 4  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg4" id="homeImg4" class="form-control">
                                                <input type="text" name="text4" id="text4" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                        </div>
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 5  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg5" id="homeImg5" class="form-control">
                                                <input type="text" name="text5" id="text5" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                        </div>
                                        <div class="row inner-div mb-3">
                                            <div class="col-sm-3 mb-3">
                                            Brand Image 6  <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-7 mb-3">
                                                <input type="file" name="homeImg6" id="homeImg6" class="form-control">
                                                <input type="text" name="text6" id="text6" class="form-control mt-2" placeholder="Enter Brand Name">
                                            </div>
                                            
                                            <input type="hidden" name="endurl" id="endurl" value="updateBrandImage" />
                                        </div>
                                        <div class="button-section">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary" id="submit">Update</button>
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
        $("form[name='homeImg']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                homeImg1: "required",
                homeImg2: "required",
                homeImg3: "required",
                homeImg4: "required",
                homeImg5: "required",
                homeImg6: "required"
            },
            // Specify validation error messages
            messages: {
                homeImg1: "Please select Slider Image 1",
                homeImg2: "Please select Slider Image 2",
                homeImg1: "Please select Slider Image 3",
                homeImg1: "Please select Slider Image 4",
                homeImg1: "Please select Slider Image 5",
                homeImg3: "Please select Slider Image 6"
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
        // image format validation 
        if ($("form[name='homeImg']").valid()) {
            var property = document.getElementById('homeImg1').files[0];
            var image_name = property.name;
            var image_extension = image_name.split('.').pop().toLowerCase();
        
            if (jQuery.inArray(image_extension, ['jpg', 'jpeg', 'png','webp']) == -1) {
                toastr.error("Only jpg, jpeg and png file formate is allowed", {
                    timeOut: 5000
                });
            }else{
                $("#submit").attr("disabled",true);

                var form = $('#homeImg')[0];
                var formData = new FormData(form);

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
                            location.replace("./brands.php");
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
