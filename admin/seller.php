<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Sellers Details</title>
        <?php
            include('css.php')
        ?>
        <style>
            .filterSection {
                padding-top: 10px !important;
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
                        <h1 class="mt-4">Sellers Details</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sellers Details</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                    <!-- <a href="#"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Pending Approval</b></p></a> -->

                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Sellers List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-1"></div> 
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="sellerStatus" id="sellerStatus" class="form-select">
                                        <option value="*">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                        <option value="2" selected>Approval Pending</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="card-body text-center">
                                <table id="datatablesSimple" class="text-center">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Created At</th>
                                            <th>Docs</th>
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
                                            <td></td>
                                        </tr>                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        
                                
                    </div>
                    <!-- <input type="hidden" name="deleteCategoryId" value="" id="deleteCategoryId"> -->
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
    function getSeller(status){
        $('#datatablesSimple tbody').empty();
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getSellers","status":status},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

            for(let sellers = 0; sellers<data.length; sellers++){
                    const seller_id = data[sellers].id;
                    const docs = result.docs.filter(doc => doc.seller_id == seller_id);
                    let docHtml = "";
                    for(let doc=0; doc<docs.length;doc++){
                        docHtml+=`<a href='../${docs[doc].url}' target='_blank'>View</a><br>`;
                    }
                        let enableDisableAction = "";
                        let approvalButton = "";
                        if(data[sellers].status == 0 ||  data[sellers].status == 1)
                            enableDisableAction = `<a href=""><i class="fa-solid fa-rotate-right m-2 actionEnDisApp" data-id="${data[sellers].id}" data-status="${data[sellers].status}" title="Change Seller Status" style="color:orange"></i></a>`;
                        if(data[sellers].status == 2)
                            approvalButton = `<a href=""><i class="fa fa-check-square m-2 actionEnDisApp" data-id="${data[sellers].id}" data-status="${data[sellers].status}" title="Approve Seller" style="color:green"></i></a>`;

                    const sellersHtml = `<tr>
                                            <td>${data[sellers].id}</td>
                                            <td>${data[sellers].first_name} ${data[sellers].last_name}</td>
                                            <td>${data[sellers].email}</td>
                                            <td>${data[sellers].mobile}</td>
                                            <td>${data[sellers].created_at}</td>
                                            <td>${docHtml}</td>
                                            <td>${data[sellers].status == 1 ? "Active" : data[sellers].status == 2 ? "Approval Pending" :"Inactive"}</td>
                                            <td class="text-center">
                                                ${enableDisableAction}
                                                ${approvalButton}
                                            </td>
                                        </tr>`; 
                    
                    $('#datatablesSimple').find('tbody').append(sellersHtml);
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

    $(document).on("click",".actionEnDisApp",function(e){
        e.preventDefault();
        const id = $(this).attr("data-id");
        const status = $(this).attr("data-status");
       
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"changeSellerStatus","id":id,status},
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
    })

    $("#sellerStatus").change(function(){
        const sellerStatus = $("#sellerStatus").val();
        getSeller(sellerStatus);
    });

    $(document).ready(function(){
        getSeller("*");
    });

</script>