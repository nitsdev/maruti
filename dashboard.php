<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Seller Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        
        <!-- Bootstrap CSS & JS (required for Toggle styles) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">       
        <!-- Bootstrap Toggle CSS & JS -->
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
        <style>
            .breadcrum{
                display: flex;
                justify-content: space-between;
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <?php 
            include('assets/navbar.php');
        ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <div class='breadcrum'>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                            <ol class="breadcrumb mb-4">
                                <input type="checkbox" id="deliveryToggle"
                                    data-toggle="toggle" 
                                    data-on="Open" 
                                    data-off="Close">
                            </ol>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
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
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script> -->
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <!-- <script src="js/scripts.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>


<!-- <script>
    $(function () {
        $('#deliveryToggle').bootstrapToggle(); // Initialize toggle
    });
    const token = localStorage.getItem("token");

    $(document).ready(()=>{
        $.ajax({
            url: `${base_url}/api/foodSeller/routes.php`,
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"getShopStatus"
            },
            success: function(result) {
                if(result.status == 1){
                    $('#deliveryToggle').bootstrapToggle('on');
                }else{
                    $('#deliveryToggle').bootstrapToggle('off');
                }
            },
            error: function(result) {
                location.replace("login.php");
            }
        });
    });

    $('#deliveryToggle').change(function (e) {
        var isChecked = $(this).prop('checked') ? 1 : 0;

        $.ajax({
            url: `${base_url}/api/foodSeller/routes.php`,
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"changeOpenCloseStatus",isChecked
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
            },
            error: function(result) {
                location.replace("login.php");
            }
        });
    });
</script> -->


