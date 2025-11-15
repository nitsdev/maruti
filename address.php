<?php
    include('header.php');
?>
<style>
    .tooltip-text {
        visibility: hidden;
        position: absolute;
        z-index: 1;
        width: 200px;
        color: #ffffffff;
        background-color: #404d58ff;
        padding: 7px;
    }

    .hover-text:hover .tooltip-text {
        visibility: visible;
    }

    .top {
        top: -40px;
        left: -50%;
    }

    .bottom {
        top: 25px;
        left: -50%;
    }

    .left {
        top: -8px;
        right: 120%;
    }

    .right {
        top: -8px;
        left: 120%;
    }

    .hover-text {
        position: relative;
        display: inline-block;
        /* margin: 40px; */
        text-align: center;
    }

    #myTabContent .active{
        background-color: white !important;
    }


</style>

        <div class="container mb-5">
            <!-- The Modal -->
            <div id="modalDialog" class="modal">
                <div class="modal-content animate-top">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Address</h5>
                        <button type="button" class="close">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="osahan-user text-center p-2">
                            <form class="editAddress" action="" name="editAddress" id="editAddress"> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_first_name" name="edit_first_name" placeholder="Enter First Name">
                                    </div>

                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_last_name" name="edit_last_name" placeholder="Enter Last Name">
                                    </div>

                                    <div class="form-group">
                                    <input type="email" class="form-control" id="edit_email" name="edit_email" placeholder="Enter Email">
                                    </div>
                                
                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_mobile" name="edit_mobile" placeholder="Enter Mobile Number" maxlength="10">
                                    </div>

                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_add1" name="edit_add1" placeholder="Address line 1">
                                    </div>

                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_add2" name="edit_add2" placeholder="Address line 2">
                                    </div>

                                    <div class="form-group">
                                    <select name="edit_country_id" id="edit_country_id" class="form-control">
                                        <option value="">Select Country</option>
                                    </select>
                                    </div>

                                    <div class="form-group">
                                    <select name="edit_state_id" id="edit_state_id" class="form-control">
                                        <option value="">Select State</option>
                                    </select>
                                    </div>

                                    <div class="form-group">
                                    <select name="edit_city_id" id="edit_city_id" class="form-control">
                                        <option value="">Select City</option>
                                    </select>
                                    </div>

                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_landmark" name="edit_landmark" placeholder="Enter Landmark">
                                    </div>

                                    <div class="form-group">
                                    <input type="text" class="form-control" id="edit_pincode" name="edit_pincode" placeholder="Enter Pincode" maxlength="6">
                                    </div>

                                    <input type="hidden" name="addId" id="addId" value="addId" />

                                    <input type="hidden" name="endurl" id="editaddendurl" value="editAddress" />

                                    <button type="button" id="editExistAddress" class="btn btn-primary ">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close">Close</button>
                    </div>
                </div>
            </div>

            <!-- Address Start -->
            <div class="container-fluid pt-5">
                <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Manage Addresse(s)</span></h2>
                <div class="row px-xl-5 pb-3 shopSection">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="osahan-account-page-left shadow-sm bg-white h-100">
                                <div class="border-bottom p-4">
                                <h4 class="font-weight-bold mt-0 mb-4">Add New Address</h4>
                                </div>
                                <div class="osahan-user text-center p-2">
                                <div class="row">
                                    <form class="addAddress" action="" name="addAddress" id="addAddress"> 
                                        <div class="col-md-12">
                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="email" class="form-control" id="addemail" name="email" placeholder="Enter Email">
                                            </div>
                                        
                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" maxlength="10">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="add1" name="add1" placeholder="Address line 1">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="add2" name="add2" placeholder="Address line 2">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <select name="country_id" id="country_id" class="form-control">
                                                <option value="">Select Country</option>
                                            </select>
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <select name="state_id" id="state_id" class="form-control">
                                                <option value="">Select State</option>
                                            </select>
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <select name="city_id" id="city_id" class="form-control">
                                                <option value="">Select City</option>
                                            </select>
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Enter Landmark">
                                            </div>

                                            <div class="form-group mt-2 mb-2">
                                            <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" maxlength="6">
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_shipping" name="is_shipping" value="1" checked>
                                                <label class="form-check-label">Default Shipping Address</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_billing" name="is_billing" value="1" checked>
                                                <label class="form-check-label">Default Billing Address</label>
                                            </div>

                                            <input type="hidden" name="endurl" id="addendurl" value="addAddress" />

                                            <button type="button" id="addNewAddress" class="btn btn-primary ">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="osahan-account-page-right shadow-sm bg-white p-4 h-100">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade  active show" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                                        <h4 class="font-weight-bold mt-0 mb-4">Manage Addresses</h4>
                                        <div class="row addSection">
                                            <!-- <div class="col-md-6">
                                                <div class="bg-white card addresses-item mb-4 border border-primary shadow">
                                                    <div class="gold-members p-4">
                                                        <div class="media">
                                                            <div class="mr-3"><i class="icofont-ui-home icofont-3x"></i></div>
                                                            <div class="media-body">
                                                                <p class="text-black">Osahan House, Jawaddi Kalan, Ludhiana, Punjab 141002, India
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="bg-white card addresses-item mb-4 shadow-sm">
                                                    <div class="gold-members p-4">
                                                        <div class="media">
                                                            <div class="mr-3"><i class="icofont-briefcase icofont-3x"></i></div>
                                                            <div class="media-body">
                                                                <p>NCC, Model Town Rd, Pritm Nagar, Model Town, Ludhiana, Punjab 141002, India
                                                                </p>
                                                                <p class="mb-0 text-black font-weight-bold"><a class="text-primary mr-3" data-toggle="modal" data-target="#add-address-modal" href="#"><i class="icofont-ui-edit"></i> EDIT</a> <a class="text-danger" data-toggle="modal" data-target="#delete-address-modal" href="#"><i class="icofont-ui-delete"></i> DELETE</a></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Address End -->

        </div>


    

<?php
    include('footer.php');
?>
  </body>
</html>
<script>


    // Get the modal
    var modal = $('#modalDialog');

    // Get the button that opens the modal
    var btn = $("#mbtn");

    // Get the <span> element that closes the modal
    var span = $(".close");

    $(document).ready(function(){    
        // When the user clicks on <span> (x), close the modal
        span.on('click', function() {
            modal.fadeOut();
        });
    });

    // When the user clicks anywhere outside of the modal, close it
    $('body').bind('click', function(e){
        if($(e.target).hasClass("modal")){
            modal.fadeOut();
        }
    });


    let categoryData = localStorage.getItem("category");
    if(!categoryData){
        setTimeout(() => {
            loadRestData();
        }, 2100);
    }else{
        loadRestData();
    }

    $('#mobile,#pincode').keypress(function(e) {  
      var arr = [];  
      var kk = e.which;  
     
      for (i = 48; i < 58; i++)  
          arr.push(i);  
     
      if (!(arr.indexOf(kk)>=0))  
          e.preventDefault();  
    });  
    
    let addressCount = 0;

    $('input[type="checkbox"]').change(function(){
        this.value = (Number(this.checked));
    });

    function loadRestData(){
        setTimeout(() => {
            $.ajax({
                url: `${base_url}/api/user/routes.php`, //the page containing php script
                type: "post", //request type,
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                dataType: 'json',
                data: {"endurl":"checkLogin"},
                success: function(result) {
                    getAddresses();
                },
                error: function(result) {
                    setTimeout(() => {
                        // location.replace("index.php");
                    }, 1000);
                }
            });
        }, 400);
    }

    function getAddresses(){

        setTimeout(() => {
             // AJAX Call
            $.ajax({
                url: `${base_url}/api/user/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: {"endurl":"getAddresses"},
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    addressCount = result.data.length;
                
                    if(!addressCount){
                        $("#is_billing,#is_shipping").prop("disabled", true);
                    }
                    $(".addSection").empty();
                    const addData = result.data;
                    for(let add=0; add<addData.length; add++){
                        const addHtml = `<div class="col-md-6">
                                    <div class="bg-white card addresses-item mb-4 ${+addData[add].is_billing || +addData[add].is_shipping ? 'border border-primary shadow' : 'shadow-sm'}">
                                        <div class="gold-members p-4">
                                            <div class="media">
                                                <div class="mr-3"><i class="icofont-ui-home icofont-3x"></i></div>
                                                <div class="media-body">
                                                    <p class="text-black">
                                                        ${addData[add].first_name} ${addData[add].last_name},
                                                        ${addData[add].add_line1} ${addData[add].add_line2}, ${addData[add].city_name}, ${addData[add].state_name}, ${addData[add].country_name},
                                                        Near ${addData[add].landmark}, ${addData[add].pincode} <br>
                                                        ${addData[add].email} ${addData[add].mobile}
                                                    </p>
                                                    <p class="mb-0 text-black font-weight-bold">
                                                        <a class="text-primary mr-3 editAdd" data-toggle="modal" data-address="${addData[add].id}">
                                                            <i class='fas fa-pencil-alt hover-text' style='font-size:15px;color:green'>
                                                                <span class="tooltip-text top">Edit</span>
                                                            </i>
                                                        </a> 
                                                        ${+addData[add].is_billing || +addData[add].is_shipping ? '' :
                                                        '<a class="text-danger mr-3 deleteAdd" data-toggle="modal" data-address="'+addData[add].id+'"><i class="fa fa-trash hover-text m-2" style="font-size:15px;color:red"><span class="tooltip-text top">Delete</span></i> </a>'}
                                                        ${+addData[add].is_billing ? '' : 
                                                            '<a class="text-success mr-3 makeDBilling" data-toggle="modal" data-address="'+addData[add].id+'" ><i class="fas fa-address-book hover-text m-2" style="font-size:15px;color:#56d556"><span class="tooltip-text top">Make this address as default billing</span></i></a>'}
                                                        ${+addData[add].is_shipping ? '' : 
                                                            '<a class="text-success mr-3 makeDShipping" data-toggle="modal" data-address="'+addData[add].id+'" ><i class="fas fa-address-book hover-text m-2" style="font-size:15px;color:#cd4cb6"><span class="tooltip-text top">Make this address as default shipping</span></i></a>'}
                                                        ${+addData[add].is_billing ?
                                                                '<a class="text-success mr-3" data-toggle="modal" data-address="'+addData[add].id+'" ><i class="fas fa-duotone fa-book hover-text m-2" style="font-size:15px;color:#56d556"><span class="tooltip-text top">Your default billing address</span></i></a>':''}
                                                        ${+addData[add].is_shipping ?
                                                            '<a class="text-success mr-3" data-toggle="modal" data-address="'+addData[add].id+'"><i class="fas fa-duotone fa-book hover-text m-2" style="font-size:15px;color:#cd4cb6"><span class="tooltip-text top">Your default shipping address</span></i></a>':''}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                        $(".addSection").append(addHtml);
                    }

                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                },
            });
        }, 800);
    }

    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='addAddress']").validate({
            // Specify validation rules
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required:true,
                    digits: true, 
                    minlength:10,
                    maxlength:10
                },
                add1: "required",
                add2: "required",
                country_id: "required",
                state_id: "required",
                city_id: "required",
                landmark: "required",
                pincode: {
                    required:true,
                    digits: true, 
                    minlength:6,
                    maxlength:6
                }
            },
            // Specify validation error messages
            messages: {
                first_name: "Please enter first name",
                last_name: "Please enter last name",
                email: "Please enter valid email",
                mobile: "Please enter 10 digit mobilenumber",
                add1: "Please enter address line1",
                add2: "Please enter address line2",
                country_id: "Please select country",
                state_id: "Please select state",
                city_id: "Please select city",
                landmark: "Please enter landmark",
                pincode: "Please select pincode"
            },
        });

        $("form[name='editAddress']").validate({
            // Specify validation rules
            rules: {
                edit_first_name: "required",
                edit_last_name: "required",
                edit_email: {
                    required: true,
                    email: true
                },
                edit_mobile: {
                    required:true,
                    digits: true, 
                    minlength:10,
                    maxlength:10
                },
                edit_add1: "required",
                edit_add2: "required",
                edit_country_id: "required",
                edit_state_id: "required",
                edit_city_id: "required",
                edit_landmark: "required",
                edit_pincode: {
                    required:true,
                    digits: true, 
                    minlength:6,
                    maxlength:6
                }
            },
            // Specify validation error messages
            messages: {
                edit_first_name: "Please enter first name",
                edit_last_name: "Please enter last name",
                edit_email: "Please enter valid email",
                edit_mobile: "Please enter 10 digit mobilenumber",
                edit_add1: "Please enter address line1",
                edit_add2: "Please enter address line2",
                edit_country_id: "Please select country",
                edit_state_id: "Please select state",
                edit_city_id: "Please select city",
                edit_landmark: "Please enter landmark",
                edit_pincode: "Please select pincode"
            },
        });
    });

    // Add address
    $("#addNewAddress").click(function(e) {
        e.preventDefault();
        if ($("form[name='addAddress']").valid()) {

            const dataPair = {};
            $("#addAddress :input").each(function() {
                if ($(this).attr("name")) {
                    dataPair[$(this).attr("name")] = $(this).val();
                }
            });

            // AJAX Call
            $.ajax({
                url: `${base_url}/api/user/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: dataPair,
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    $("#is_billing,#is_shipping").prop("disabled", false);
                    $('#addAddress')[0].reset();
                    getAddresses();
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#loginUser").attr("disabled",false);
                    }, 2000);
                },
            });
        }
    });

    //Get countries
    $.ajax({
        url: `${base_url}/api/user/routes.php`, 
        type: "post", 
        dataType: 'json',
        data: {"endurl":"getCountry"},
        success: function(result) {
            const items = result.data;
            var list = $("#country_id");
            var list_edit = $("#edit_country_id");
            for(var i in items) {
                list.append(new Option(items[i].name, items[i].id));
                list_edit.append(new Option(items[i].name, items[i].id));
            }

            $("#country_id").val(101);
            setTimeout(async() => {
                await getState(101);
            }, 500);
        },
        error: function(result) {
            toastr.error(result.responseJSON.msg, {
                timeOut: 5000
            });
        },
    });
    
    // Get states by country
    $("#country_id").change(async function(){
        $("#state_id").find('option:not(:first)').remove();
        $("#city_id").find('option:not(:first)').remove();

        const country_id = $("#country_id").val();
        await getState(country_id);
    });

    async function getState(country_id){
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            data: {"endurl":"getStateByCountry",country_id},
            success: function(result) {
                const items = result.data;
                var list = $("#state_id");
                for(var i in items) {
                    list.append(new Option(items[i].name, items[i].id));
                }

                $("#state_id").val(7);
                setTimeout(async () => {
                    await getCity(7);
                }, 500);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    // Get city by state
    $("#state_id").change(async function(){
        $("#city_id").find('option:not(:first)').remove();

        const state_id = $("#state_id").val();
        await getCity(state_id);
    });

    async function getCity(state_id){
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            data: {"endurl":"getCityByState",state_id},
            success: function(result) {
                const items = result.data;
                var list = $("#city_id");
                for(var i in items) {
                    list.append(new Option(items[i].name, items[i].id));
                }

                $("#city_id").val(2022);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    // For edit
    // Get states by country
    $("#edit_country_id").change(function(){
        const country_id = $("#edit_country_id").val();
        setEditState(country_id);
    });

    async function setEditState(country_id){
        $("#edit_state_id").find('option:not(:first)').remove();
        $("#edit_city_id").find('option:not(:first)').remove();
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            data: {"endurl":"getStateByCountry",country_id},
            success: function(result) {
                const items = result.data;
                var list = $("#edit_state_id");
                for(var i in items) {
                    list.append(new Option(items[i].name, items[i].id));
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    // Get city by state
    $("#edit_state_id").change(function(){
        const state_id = $("#edit_state_id").val();
        setEditCity(state_id);
    });

    async function setEditCity(state_id){
        $("#edit_city_id").find('option:not(:first)').remove();
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            data: {"endurl":"getCityByState",state_id},
            success: function(result) {
                const items = result.data;
                var list = $("#edit_city_id");
                for(var i in items) {
                    list.append(new Option(items[i].name, items[i].id));
                }
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    }

    // Make default billing
    $(document).on("click",".makeDBilling",function(){
        const addId = Number($(this).attr("data-address"));
        
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {"endurl":"makeDefaultBilling",addId},
            success: function(result) {
                getAddresses();
                toastr.success(result.msg, {
                    timeOut: 5000
                });
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    });

    // Make default shipping
    $(document).on("click",".makeDShipping",function(){
        const addId = Number($(this).attr("data-address"));
        
        $.ajax({
            url: `${base_url}/api/user/routes.php`, 
            type: "post", 
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {"endurl":"makeDefaultShipping",addId},
            success: function(result) {
                getAddresses();
                toastr.success(result.msg, {
                    timeOut: 5000
                });
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    });

    // Make default shipping
    $(document).on("click",".deleteAdd",function(){
        const addId = Number($(this).attr("data-address"));

        if (confirm("Are you sure want to delete this address?") == true) {
            $.ajax({
                url: `${base_url}/api/user/routes.php`, 
                type: "post", 
                dataType: 'json',
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                data: {"endurl":"deleteAddress",addId},
                success: function(result) {
                    getAddresses();
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                },
            });
        } else {
            
        }
    });

    $(document).on("click",".editAdd",function(){
        const addId = Number($(this).attr("data-address"));
        $("#addId").val(addId);

        // Get address by id;
         // AJAX Call
         $.ajax({
            url: `${base_url}/api/user/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getAddresseById",addId},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: async function(result) {
                addressData = result.data;

                const keys = Object.keys(addressData);

                keys.forEach((key, index) => {
                    $(`#edit_${key}`).val(addressData[key]);
                });

                $("#edit_add1").val(addressData['add_line1']);
                $("#edit_add2").val(addressData['add_line2']);

                await setEditState(addressData['country_id']);
                await setEditCity(addressData['state_id']);

                setTimeout(() => {
                    $("#edit_state_id").val(addressData['state_id']);
                    $("#edit_city_id").val(addressData['city_id']);
                }, 2000);
                
                setTimeout(() => {
                    modal.show();
                },1000);                
              
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
            },
        });
    });

    //Edit address
    $("#editExistAddress").click(function(e) {
        e.preventDefault();
        if ($("form[name='editAddress']").valid()) {

            const dataPair = {};
            $("#editAddress :input").each(function() {
                if ($(this).attr("name")) {
                    dataPair[$(this).attr("name")] = $(this).val();
                }
            });

            // AJAX Call
            $.ajax({
                url: `${base_url}/api/user/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: dataPair,
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                success: function(result) {
                    modal.fadeOut();
                    getAddresses();
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                },
            });
        }
    });
</script>