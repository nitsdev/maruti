<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shuttleshop | Charges</title>
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
                        <h1 class="mt-4">Standard Charges</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Standard Charges</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Standard Charges
                            </div>
                            <div class="card-body">
                                <form class="addCharges" action="" name="addCharges" id="addCharges" enctype="multipart/form-data">
                                    <div class="container-head">
                                        
                                        <div><strong>Buyers Charges</strong></div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                GST %
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="buyer_gst" id="buyer_gst" placeholder="Enter GST % for buyer" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                            Delivery Charge Type
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <select name="delivery_charge_type" id="delivery_charge_type" class="form-select">
                                                    <option value="1">No Charges</option>
                                                    <option value="2">Full Delivery Charges</option>
                                                    <option value="3">Fixed Delivery Charge Per Item</option>
                                                    <option value="4">Fixed Delivery Charge Per Order</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                            Delivery Charges
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="delivery_charge" id="delivery_charge" placeholder="Enter Delivery Charge in RS" value="0" class="form-control">
                                            </div>
                                        </div>

                                        <br><br>
                                        <div><strong>Sellers Charges</strong></div>
                                        <div class="row inner-div">
                                            <div class="col-sm-2">
                                                GST % On Below Charges
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="seller_gst" id="seller_gst" placeholder="Enter GST % for seller" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                            Plateform Charges <small>(in Rs)</small>
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="pf_charge" id="pf_charge" placeholder="Enter Plateform Charge in RS" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row inner-div">
                                            <!-- <div class="col-sm-2">
                                            Delivery Charge Type <small>Apply to seller</small>
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <select name="seller_delivery_charge_type" id="seller_delivery_charge_type" class="form-select">
                                                    <option value="0">Select Type</option>
                                                    <option value="1">No Charges</option>
                                                    <option value="2">Full Delivery Charges</option>
                                                    <option value="3">Fixed Delivery Charges</option>
                                                </select>
                                            </div> -->
                                            <div class="col-sm-2">
                                            Collection Charges %
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="seller_collection_charge" id="seller_collection_charge" placeholder="Enter Collection %"  class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                Commission %
                                            </div>
                                            <div class="col-sm-4 mb-2">
                                                <input type="number" name="commission" id="commission" placeholder="Enter Commission % for seller" class="form-control">
                                            </div>
                                        </div>

                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="endurl" id="endurl" value="addCharges" />
                                        <div class="button-section">
                                            <div class="col-sm-12 m-auto">
                                                <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        


                    </div>
                    <!-- <input type="hidden" name="id" value="" id="id">
                    <input type="hidden" name="foodid" value="" id="foodid"> -->
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
    let delivery_charges=0;

    $("#delivery_charge_type").change(function(){
        if($(this).val() > 2){
            $("#delivery_charge").attr("disabled",false);
            $("#delivery_charge").val(delivery_charges);
        }else{
            $("#delivery_charge").attr("disabled",true);   
            $("#delivery_charge").val(0); 
        }
    });

    // Grocery
    $("#submit").click(function(e){
        e.preventDefault();
        const dataPair = {};

        $("#addCharges :input").each(function() {
            if ($(this).attr("name")) {
                dataPair[$(this).attr("name")] = $(this).val() || 0;
            }
        });

        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: dataPair,
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });

    });

    $(document).ready(function(){
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getCharges"},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                const groceryCharge = result.filter((chrg)=>chrg.program_id==1);
                $("#buyer_gst").val(groceryCharge[0].buyer_gst);
                $("#delivery_charge_type").val(groceryCharge[0].delivery_charge_type);
                $("#delivery_charge").val(groceryCharge[0].delivery_charge);
                $("#seller_gst").val(groceryCharge[0].seller_gst);
                $("#pf_charge").val(groceryCharge[0].pf_charge);
                $("#seller_collection_charge").val(groceryCharge[0].seller_collection_charge);
                $("#commission").val(groceryCharge[0].commission);
                $("#id").val(groceryCharge[0].id);

                delivery_charges = groceryCharge[0].delivery_charge;
                if(groceryCharge[0].delivery_charge_type < 3){
                    $("#delivery_charge").attr("disabled",true); 
                }

            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                location.replace("./login.php");
            },
        });
    });

</script>