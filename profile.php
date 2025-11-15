<?php
    include('header.php');
?>

        <div class="container mb-5">
            <h2 class="text-center">My Account</h2>
        
            <div class="col-md-11 m-auto row d-flex">
                <div class="card-body">
                    <form class="editProfile" action="" method="post" name="editProfile" id="editProfile" enctype="multipart/form-data">
                        <div class="container-head">
                            <div class="row inner-div">
                                <div class="col-md-2 pb-2">
                                    <b>First Name</b> 
                                </div>
                                <div class="col-sm-4 pb-3">
                                    <input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control">
                                </div>
                                <div class="col-md-2 pb-2">
                                    <b>Last Name</b>  
                                </div>
                                <div class="col-sm-4 pb-3">
                                    <input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control">
                                </div>
                            </div>
                            
                            <div class="row inner-div">
                                <div class="col-md-2 pb-2">
                                    <b>E-mail Id </b>
                                </div>
                                <div class="col-sm-10 pb-3">
                                <input type="email" name="email" id="email" placeholder="example@abc.com" class="form-control"  title="Email can't be updated" disabled>
                                </div>
                            </div>

                            <div class="row inner-div">
                                <div class="col-md-2 pb-2">
                                    <b>Mobile Number</b> 
                                </div>
                                <div class="col-sm-4 pb-3">
                                    <input type="number" name="number" id="number" placeholder="mobile number" class="form-control"  title="Mobile number can't be updated" disabled>
                                </div>
                            </div>
                            <input type="hidden" name="endurl" id="endurl" value="editProfile" />
                            <input type="hidden" name="id" id="id" value="" />
                            
                            <div class="col-sm-12 text-center mt-2" style="display:flex;justify-content:space-around;">
                                    <button type="submit" class="btn btn-primary" id="submit">Edit Details</button>
                                  <a href="address.php"> <button type="button" class="btn btn-primary ml-5 pl-2" id="address">My Address</button></a>
                                  <a href="resetPassword.php"> <button type="button" class="btn btn-primary ml-5 pl-2" id="resetPassword">Reset Password</button></a>
                            </div>
                            
                        </div>
                    </form>
                </div>

            </div>
            
        </div>

<?php
    include('footer.php');
?>
  </body>
</html>
<script>
    // AJAX Call
    const token = localStorage.getItem("token");
    
    // Wait for the DOM to be ready
    $(function() {
        $("form[name='editProfile']").validate({
            rules: {
                first_name: "required",
                last_name: "required"
            },
            // Specify validation error messages
            messages: {
                first_name: "First Name is required",
                last_name: "last Name is required"
            }
        });
    });


    setTimeout(() => {
        $.ajax({
            url: `${base_url}/api/user/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getUser"},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const data = result.data;
                    $("#first_name").val(data.first_name);
                    $("#last_name").val(data.last_name);
                    $("#email").val(data.email);
                    $("#number").val(data.mobile);
                    $("#id").val(data.id);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("user/login.php");
            },
        });
    }, 200);


    
    $("#submit").click(function(e) {
        e.preventDefault();
                
        if ($("form[name='editProfile']").valid()) {
            var form = $('#editProfile')[0];
            var formData = new FormData(form);

            // const dataPair = {};
            $("#submit").attr("disabled",true);
            
            // AJAX Call
            $.ajax({
                url: `${base_url}/api/user/routes.php`, //the page containing php script
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
                    }, 2000);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#submit").attr("disabled",false);
                        if(result.status == 500){
                            location.replace("user/login.php");
                        }
                    }, 2000);
                },
            });
        }
    });
</script>
