<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Import Products</title>
        <?php
            include('css.php')
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha382-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <style>
            .inner-div{
                padding-bottom: 10px;
            }
            .clr{
                color: red;
            }
            .alreadyexistproducts{
                display:none;
                color:red;
            }

            /* li::before{
                content: "➡️";
            } */
        </style>
    </head>
    <body class="sb-nav-fixed">
    <?php
        include('assets/navbar.php')
    ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Import Products</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Import Products in Bulk</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                <a href="addProduct.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Single Product</b></p></a>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Import Products
                            </div>
                            <div class="card-body">
                                <form class="importProducts" method="POST" name="importProducts" id="importProducts" enctype="multipart/form-data">
                                    <div class="container-head">
                                        <div class="row">
                                            <div class="col-sm-2 inner-div">
                                                 Select file for bulk upload <span class="mandatory">*</span>
                                            </div>
                                            <div class="col-sm-4 inner-div">
                                                <input type="file" name="importProductsFile[]" id="importProductsFile" placeholder="Upload file in Excel Format" class="form-control">
                                                <h5 class="mt-2 clr">Only .csv file type is supported</h5>
                                            </div>
                                            
                                            <input type="hidden" name="endurl" id="endurl" value="importProduct" />
                                        </div>
                                        <div class="button-section">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-primary" id="submit" name="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="alreadyexistproducts">
                                <ul class='alreadyexistproductsul'>

                                </ul>
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
        $("form[name='importProducts']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                importProductsFile: "required"
            },
            // Specify validation error messages
            messages: {
                importProductsFile: "Please select excel file to upload products"
            },
            
        });
    });
    


    $("#submit").click(function(e) {
        e.preventDefault();
        const token = localStorage.getItem("token");
                
        if ($("form[name='importProducts']").valid()) {

            var form = $('#importProducts')[0];
            var formData = new FormData(form);

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
                    

                    const data = result.responseJSON.data;
                    let htmlData = "";
                    $(".alreadyexistproductsul").html(htmlData);
                    for(let i = 0; i< data.length; i++){
                        htmlData+=`<li>${data[i].product_name}</li>`;
                    }
                    console.log(htmlData)
                    $(".alreadyexistproductsul").html(htmlData);
                    $(".alreadyexistproducts").css("display","flex");
                    $(".alreadyexistproducts").css("justify-content","center");
                },
            });
        }
    });
</script>


</html>
