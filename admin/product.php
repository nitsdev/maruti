|<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Maruti Studio | Product</title>
        <?php
            include('css.php')
        ?>
        <style>
            .imgprod{
                width:150px;
                height:100px;
            }
            @import url('https://fonts.googleapis.com/css?family=Lato:700');
            /* body {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background: #efefef;
                font-family: 'Lato', sans-serif;
            } */
            .pagination {
                overflow: hidden;
                background: #fff;
                border-radius: 5px;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.05);
                font-size: 1.5em;
                display: flex;
                position: relative;
                user-select: none;
            }
            .pagination .nav-btn {
                font-family: Font Awesome\ 5 Free;
                font-weight: 900;
                background: linear-gradient(45deg, #00aaed, #3ac8ff);
                display: inline-block;
                padding: 18px 24px;
                color: #fff;
                z-index: 2;
                cursor: pointer;
                text-shadow: 0 0 0 rgba(0, 0, 0, 0);
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
                transition: all 300ms ease;
            }
            .pagination .nav-btn:hover {
                background: linear-gradient(45deg, #07baff, #3ac8ff);
                text-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            }
            .pagination .nav-btn.next {
                position: absolute;
                right: 0;
            }
            .pagination .nav-btn.prev::before {
                content: "\f053";
            }
            .pagination .nav-btn.next::before {
                content: "\f054";
            }
            .pagination .nav-pages {
                margin: 0;
                padding: 0;
                left: 0;
                transition: left 300ms ease;
                position: absolute;
                width: max-content;
            }
            .pagination .nav-pages li {
                list-style: none;
                display: inline-block;
                padding: 18px 10px;
                border-right: 1px solid #eee;
                color: rgba(0, 0, 0, 0.25);
                text-align: center;
                cursor: pointer;
            }
            .pagination .nav-pages li.active, .pagination .nav-pages li:hover {
                color: rgba(0, 0, 0, 0.9);
            }

            .paginationDiv{
                justify-items: center;
            }

            .pagination .nav-btn.next::before{
                content: "➡️"
            }

            .pagination .nav-btn.prev::before{
                content: "⬅️"
            }
            .datatable-top, .datatable-bottom{
                display: none;
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
                        <h1 class="mt-4">Product</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product</li>
                        </ol>
                        <div class="card mb-4 buttons-top">
                                    <a href="importProduct.php"><p class="border border-2 border-success rounded-pill bgCat text-center mt-3 mrgright  p-2"><b>Import Product</b></p></a>

                                    <a href="addProduct.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>Add Product</b></p></a>
                        </div>
                        <div class='paginationDiv'>
                            <nav class="pagination" style="width: 100% !important;">
                                <div class="nav-btn prev"></div>
                                <ul class="nav-pages"></ul>
                                <div class="nav-btn next"></div>
                            </nav>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Product List
                            </div>
                            <div class="row filterSection">
                                <div class="col-sm-2">
                                    <strong>Status</strong>
                                </div> 
                                <div class="col-sm-2">
                                    <select value="productStatus" id="productStatus" class="form-select">
                                        <option value="*">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                        <option value="2">Approval Pending</option>
                                    </select>
                                </div> 
                                <div class="col-sm-2">
                                    <strong>Product Id</strong>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Product Id" id="product_id" class="form-control">
                                </div>
                                <div class="col-sm-2">
                                    <strong>Product Name</strong>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" placeholder="Product Name" id="product_name" class="form-control">
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>P.ID</th>
                                            <th>Name</th>
                                            <th>Images</th>
                                            <th>Description</th>
                                            <th>Medicine Type</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Stock</th>
                                            <th>Added by</th>
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
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class='paginationDiv'>
                            <nav class="pagination">
                                <div class="nav-btn prev"></div>
                                <ul class="nav-pages"></ul>
                                <div class="nav-btn next"></div>
                            </nav>
                        </div>
                                       
                    </div>
                    <!-- <a href="allProducts.php"><p class="border border-2 border-success bgCat rounded-pill text-center mt-3 p-2"><b>View All Products</b></p></a> -->
                    

                    <input type="hidden" name="deleteProductId" value="" id="deleteProductId">
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
    let be;
    var toPage = 0;
    var perpage = 10;

    let productStatus='*';
    let pId;
    let pName;

    (function(baseElement, pages, pageShow){
        var pageNum = 0, pageOffset = 0;

        function _initNav(){
            //create pages
            for(i=1;i<pages+1;i++){
                $((i==1?'<li class="active">':'<li>')+(i)+'</li>').appendTo('.nav-pages', baseElement).css('min-width',pages.toString().length+'em');
            }
            
            //calculate initial values
            function ow(e){return e.first().outerWidth()}
            var w = ow($('.nav-pages li', baseElement)),bw = ow($('.nav-btn', baseElement));
            baseElement.css('width',w*pageShow+(bw*2)+'px');
            $('.nav-pages', baseElement).css('margin-left',bw+'px');
            
            //init events
            baseElement.on('click', '.nav-pages li, .nav-btn', function(e){
            if($(e.target).is('.nav-btn')){
                var toPage = $(this).hasClass('prev') ? pageNum-1 : pageNum+1;
            }else{
                var toPage = $(this).index(); 
            }
            _navPage(toPage);
            });
        }

        function _navPage(toPage){
            var sel = $('.nav-pages li', baseElement), w = sel.first().outerWidth(),
                diff = toPage-pageNum;
            
            if(toPage>=0 && toPage <= pages-1){
                sel.removeClass('active').eq(toPage).addClass('active');
                pageNum = toPage;
            }else{
                return false;
            }
            
            if(toPage<=(pages-(pageShow+(diff>0?0:1))) && toPage>=0){
                pageOffset = pageOffset + -w*diff;  
            }else{
                pageOffset = (toPage>0)?-w*(pages-pageShow):0;
            }
            
            sel.parent().css('left',pageOffset+'px');

            const perpage = $(".datatable-selector").val();
            getProductData(toPage,perpage)
        }

        be = baseElement;
        
        _initNav();

    })($('.pagination'), 1000, 5);

    $(document).on("change",".datatable-selector",function(){
        const perpage = $(".datatable-selector").val();
        getProductData(toPage,perpage);

    });

    function getProductData(toPage,perpage){
        const productStatus = $("#productStatus").val();
        getProduct(productStatus,toPage,perpage);
    }

    // AJAX Call
    const token = localStorage.getItem("token");
    function getProduct(status,toPage,perpage, pId="", pName=""){
        const from = (toPage*perpage)+1;
        const to = (toPage+1)*perpage;
        status = $("#productStatus").val();

        $('#datatablesSimple tbody').empty();
        $.ajax({
            url: `${base_url}/api/admin/routes.php`, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            data: {"endurl":"getProduct","status":status,from,to,perpage,pId,pName},
            headers: {
                'Authorization':`Bearer ${token}`
            },
            success: function(result) {
                $(".datatable-info").text(`Showing ${from} to ${to} of ${result.countData.pcount} entries`);
                const data = result.data;

                for(let product = 0; product<data.length; product++){
                    let enableDisableAction = "";
                        let approvalButton = "";
                        if(data[product].status == 2 ||  data[product].status == 1)
                            enableDisableAction = `<a href=""><i class="fa-solid fa-rotate-right m-2 actionEnDisApp" data-id="${data[product].product_id}" data-status="${data[product].status}" title="Change Product Status" style="color:orange"></i></a>`;
                            if(data[product].status == 0)
                            approvalButton = `<a href=""><i class="fa fa-check-square m-2 actionEnDisApp" data-id="${data[product].product_id}" data-status="${data[product].status}" title="Approve Product" style="color:green"></i></a>`;
                        
                    let imgURl = "https://media.istockphoto.com/id/1409329028/vector/no-picture-available-placeholder-thumbnail-icon-illustration-design.jpg?s=612x612&w=0&k=20&c=_zOuJu755g2eEUioiOUdz_mHKJQJn-tDgIAhQzyeKUQ=";
                    if(data[product]?.url){
                        if(data[product].url.includes("https") || data[product].url.includes("http")){
                            imgURl = `<img class="imgprod" src=${data[product].url} alt="${data[product].url}">`;
                        }else{
                            imgURl = `<img class="imgprod" src=../${encodeURI(data[product].url)} alt="${data[product].url}">`;
                        }
                    }else{
                        imgURl = `<img class="imgprod" src='./img/no_image.png' alt="no image">`;
                    }          
                
                    const productHtml = `<tr>
                                            <td>${data[product].product_id}</td>
                                            <td>${data[product].product_name}</td>
                                            <td>${imgURl}</td>
                                            <td>${data[product].description}</td>
                                            <td>${data[product].medicine_type == 1 ? "No Prescription" : data[product].medicine_type == 2 ? "RX" : "nRX"}</td>
                                            <td>${data[product].price}</td>
                                            <td>${data[product].discount} %</td>
                                            <td>${data[product].stock}</td>
                                            <td>${data[product].first_name} ${data[product].last_name}</td>
                                            <td>${data[product].status == 1 ? "Active" : data[product].status == 2 ? "In active" : "Approval Pending"}</td>
                                            <td>
                                                <a href="editProduct.php?id=${data[product].product_id}"><i class="fa-regular fa-pen-to-square m-2" title="Edit"></i></a>
                                                ${enableDisableAction}
                                                ${approvalButton}
                                            </td>
                                        </tr>`;
                    
                    $('#datatablesSimple').find('tbody').append(productHtml);
                }

                if(!data.length)
                    $('#datatablesSimple').find('tbody').append("<tr><td colspan=9>No record found...</td></tr>");
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
            data: {"endurl":"changeProductStatus","id":id,status},
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

    $("#productStatus").change(function(){
        productStatus = $("#productStatus").val();
        getProduct(productStatus,0,10,pId,pName);
    });

    $("#product_id").keyup(function(){
        pId = $("#product_id").val();
        getProduct(productStatus,0,10,pId,pName);
    });

    $("#product_name").keyup(function(){
        pName = $("#product_name").val();
        getProduct(productStatus,0,10,pId,pName);
    });

    $(document).ready(function(){
        getProduct("*",0,10);
    });

</script>
