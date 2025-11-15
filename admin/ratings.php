<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Product Ratings</title>
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
                        <h1 class="mt-4">Product Ratings</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Ratings</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                    <!-- <a href="#"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Pending Approval</b></p></a> -->

                                    <!-- <a href="addCoupon.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Coupon</b></p></a> -->
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Product Ratings
                            </div>
                            
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product </th>
                                            <th>Rating(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
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

    
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getProducts"},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

                for(let prod = 0; prod<data.length; prod++){
                    const prodHtml = `<tr>
                                            <td>${data[prod].product_id}</td>
                                            <td><img class="imgFix" src="../${data[prod]?.url}" alt="${data[prod].product_name}"><br>${data[prod].product_name}</td>
                                            <td><a href="viewRatings.php?pid=${data[prod].product_id}">View Ratings</a></td>
                                        </tr>`; 
                    
                    $('#datatablesSimple').find('tbody').append(prodHtml);
                }
            },
            error: function(result) {
                console.log (result);
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });
    

</script>