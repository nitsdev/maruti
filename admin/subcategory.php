<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Level 1 Category</title>
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
                        <h1 class="mt-4">Sub Category</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Sub Category</li>
                        </ol>
                        <div class="card mb-4 buttons-top fixbtn">
                                    <!-- <a href="#"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Pending Approval</b></p></a> -->

                                    <a href="addSubCategory.php"><p class="border border-2 mr-5 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Sub Category</b></p></a>                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Level 1 Category List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-1"></div> 
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="subCatStatus" id="subCatStatus" class="form-select">
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
                                            <th>SN</th>
                                            <th>Level 1 Category ID</th>
                                            <th>Category</th>
                                            <th>Level 1 Category</th>
                                            <th>Created by</th>
                                            <th>Created At</th>
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
                    <input type="hidden" name="deleteSubcategoryId" value="" id="deleteSubcategoryId">
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

    function getSubCategory(status){
        $('#datatablesSimple tbody').empty();
        
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getSubCategory","level":1,"status":status},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

                for(let l1cat = 0; l1cat<data.length; l1cat++){
                    let enableDisableAction = "";
                    let approvalButton = "";
                    if(data[l1cat].status == 0 ||  data[l1cat].status == 1)
                        enableDisableAction = `<a href=""><i class="fa-solid fa-rotate-right m-2 actionEnDisApp" data-id="${data[l1cat].id}" data-status="${data[l1cat].status}" title="Change Category Status" style="color:orange"></i></a>`;
                    if(data[l1cat].status == 2)
                        approvalButton = `<a href=""><i class="fa fa-check-square m-2 actionEnDisApp" data-id="${data[l1cat].id}" data-status="${data[l1cat].status}" title="Approve Category" style="color:green"></i></a>`;
                        

                    const l1catHtml = `<tr>
                            <td>${l1cat+1}</td>
                            <td>${data[l1cat].id}</td>                                        
                            <td>${data[l1cat].category_name}</td>
                            <td>${data[l1cat].sub_category_name}</td>
                            <td>${data[l1cat].first_name} ${data[l1cat].last_name}</td>
                            <td>${data[l1cat].created_at}</td>
                            <td>${data[l1cat].status == 0 ? "Inactive" : data[l1cat].status == 1 ? "Active" :"Approval Pending"}</td>
                            <td>
                                <a href="editsubcategory.php?id=${data[l1cat].id}"><i class="fa-regular fa-pen-to-square m-2" title="Edit"></i></a> 
                                ${enableDisableAction}
                                ${approvalButton}
                            </td>
                        </tr>`;
                    
                    $('#datatablesSimple').find('tbody').append(l1catHtml);
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

    $(document).on("click",".actionEnDisApp",function(e){
        e.preventDefault();
        const id = $(this).attr("data-id");
        const status = $(this).attr("data-status");
       
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"changeSubCategoryStatus","id":id,status},
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

    $("#subCatStatus").change(function(){
        const subCatStatus = $("#subCatStatus").val();
        getSubCategory(subCatStatus);
    });

    $(document).ready(function(){
        getSubCategory("*");
    });

</script>


