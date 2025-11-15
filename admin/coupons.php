<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Coupon</title>
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
                        <h1 class="mt-4">Coupon</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Coupons</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                    <!-- <a href="#"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Pending Approval</b></p></a> -->

                                    <a href="addCoupon.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Coupon</b></p></a>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Coupon List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-1"></div> 
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="couponStatus" id="couponStatus" class="form-select">
                                        <option value="*">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                        <!-- <option value="2">Approval Pending</option> -->
                                    </select>
                                </div> 
                            </div>
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Coupons</th>
                                            <th>Valid From</th>
                                            <th>Valid Till</th>
                                            <th>Discount %</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="deleteCouponId" value="" id="deleteCouponId">
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

    function getCoupon(status){
        $('#datatablesSimple tbody').empty();
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getCoupon","status":status},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

                for(let coupon = 0; coupon<data.length; coupon++){
                    const couponHtml = `<tr>
                                            <td>${data[coupon].id}</td>
                                            <td>${data[coupon].code}</td>
                                            <td>${data[coupon].valid_from}</td>
                                            <td>${data[coupon].valid_till}</td>
                                            <td>${data[coupon].discount_per}</td>
                                            <td>${data[coupon].status == 1 ? "Active" : "Inactive"}</td>
                                            <td>
                                                <a href="editCoupon.php?id=${data[coupon].id}"><i class="fa-regular fa-pen-to-square m-2" title="Edit"></i></a> 
                                                <a href=""><i class="fa-solid fa-trash m-2" onClick=deleteConfirm(${data[coupon].id},event) title="Delete" style="color:red"></i></a>
                                            </td>
                                        </tr>`; 
                    
                    $('#datatablesSimple').find('tbody').append(couponHtml);
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
    }

    function deleteConfirm(id,e){
        e.preventDefault();
        $("#deleteCouponId").val(id);
        const deleteFlag = confirm(`Are you sure want to delete Coupon id - ${id}`);
        if (deleteFlag == true) {
            deleteCoupon(id);
        }
    }

    function deleteCoupon(id){
        $("#deleteCouponId").val(0);
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"deleteCoupon","id":id},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    $("#couponStatus").change(function(){
        const couponStatus = $("#couponStatus").val();
        getCoupon(couponStatus);
    });

    $(document).ready(function(){
        getCoupon("*");
    });

</script>


