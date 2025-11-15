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
        <style>
            .filterSection {
                padding-top: 10px !important;
            }
            .catImg{
                max-width:100px;
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
                        <h1 class="mt-4">Category</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                    <!-- <a href="#"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Pending Approval</b></p></a> -->

                                    <a href="addCat.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Category</b></p></a>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Category List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-1"></div> 
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="catStatus" id="catStatus" class="form-select">
                                        <option value="*">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                        <option value="2">Approval Pending</option>
                                    </select>
                                </div> 
                                <!-- <div class="col-sm-2"></div> 
                                <div class="col-sm-2">
                                    <strong>Category Name</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <input type="text" name="filterCatName" id="filterCatName" value="" placeholder="Filter By Category Name">
                                </div>   -->
                            </div>
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>Category Id</th>
                                            <th>Category Name</th>
                                            <th>Created By</th>
                                            <th>Created At</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                            <th><small>Featured <br>Category</small></th>
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
                    <input type="hidden" name="deleteCategoryId" value="" id="deleteCategoryId">
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
   
    function getCategory(status){
        $('#datatablesSimple tbody').empty();

        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getCategory","status":status},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

                for(let cat = 0; cat<data.length; cat++){
                    let enableDisableAction = "";
                    let approvalButton = "";
                    if(data[cat].status == 0 ||  data[cat].status == 1)
                        enableDisableAction = `<a href=""><i class="fa-solid fa-rotate-right m-2 actionEnDisApp" data-id="${data[cat].id}" data-status="${data[cat].status}" title="Change Category Status" style="color:orange"></i></a>`;
                    if(data[cat].status == 2)
                        approvalButton = `<a href=""><i class="fa fa-check-square m-2 actionEnDisApp" data-id="${data[cat].id}" data-status="${data[cat].status}" title="Approve Category" style="color:green"></i></a>`;
                    
                    let isChecked = data[cat].is_featured==1 ? "checked" : "";

                    const catHtml = `<tr>
                                            <td>${cat+1}</td>
                                            <td>${data[cat].category_id}</td>
                                            <td><b>${data[cat].category_name}</b><br><img class="catImg" src="../upload/category/${data[cat].category_image}" alt="${data[cat].category_name} | Cosmeds"></td>
                                            <td>${data[cat].first_name} ${data[cat].last_name}</td>
                                            <td>${data[cat].created_at}</td>
                                            <td>${data[cat].status == 0 ? "Inactive" : data[cat].status == 1 ? "Active" :"Approval Pending"}</td>
                                            <td>
                                                <a href="editCategory.php?id=${data[cat].category_id}"><i class="fa-regular fa-pen-to-square m-2" title="Edit"></i></a> 
                                                <i class="fa-solid fa-trash m-2" onClick=deleteConfirm(${data[cat].category_id},event) title="Delete" style="color:red"></i> 
                                                ${enableDisableAction}
                                                ${approvalButton}
                                            </td>
                                            <td><input type="checkbox" ${isChecked} data-catid=${data[cat].category_id} class="isCheckedFeatured"></td>
                                        </tr>`;
                    
                    $('#datatablesSimple').find('tbody').append(catHtml);
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });
    }

    function deleteConfirm(id,e){
        e.preventDefault();
        $("#deleteCategoryId").val(id);
        const deleteFlag = confirm(`Are you sure want to delete Category id - ${id}`);
        if (deleteFlag == true) {
            deleteCategory(id);
        }
    }

    function deleteCategory(id){
        $("#deleteCategoryId").val(0);
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"deleteCategory","id":id},
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

    $("#catStatus").change(function(){
        const catStatus = $("#catStatus").val();
        getCategory(catStatus);
    });

    $(document).ready(function(){
        getCategory("*");
    });

    $(document).on("click",".actionEnDisApp",function(e){
        e.preventDefault();
        const id = $(this).attr("data-id");
        const status = $(this).attr("data-status");
       
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"changeCategoryStatus","id":id,status},
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
    });

    $(document).on("click",".isCheckedFeatured",function(){
        const catid = $(this).attr("data-catId");
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"checkedFeaturedStatus","catid":catid},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 2000
                });
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });

    });

</script>

