<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Users Details</title>
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
                        <h1 class="mt-4">User Details</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users Details</li>
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Users List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-1"></div> 
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="userStatus" id="userStatus" class="form-select">
                                        <option value="*">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>S.N.</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
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
    function getUser(status){
        $('#datatablesSimple tbody').empty();
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getUsers",user_role:'3',"status":status},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;

            for(let users = 0; users<data.length; users++){
                        let enableDisableAction = "";
                        let approvalButton = "";
                        if(data[users].status == 0 ||  data[users].status == 1)
                            enableDisableAction = `<a href=""><i class="fa-solid fa-rotate-right m-2 actionEnDisApp" data-id="${data[users].id}" data-status="${data[users].status}" title="Change User Status" style="color:orange"></i></a>`;
                        
                    const usersHtml = `<tr>
                                            <td>${data[users].id}</td>
                                            <td>${data[users].first_name} ${data[users].last_name}</td>
                                            <td>${data[users].email}</td>
                                            <td>${data[users].mobile}</td>
                                            <td>${data[users].created_at}</td>
                                            <td>${data[users].status == 1 ? "Active" : "Inactive"}</td>
                                            <td>
                                                ${enableDisableAction}
                                            </td>
                                        </tr>`; 
                    
                    $('#datatablesSimple').find('tbody').append(usersHtml);
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
            data: {"endurl":"changeUserStatus","id":id,status},
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

    $("#userStatus").change(function(){
        const userStatus = $("#userStatus").val();
        getUser(userStatus);
    });

    $(document).ready(function(){
        getUser("*");
    });
</script>
