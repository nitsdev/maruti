<footer class="footer-section">
			<div class="container relative">

				<div class="sofa-img">
					<img src="images/sofa.png" alt="Image" class="img-fluid">
				</div>

				<div class="row">
					<div class="col-lg-8">
						<div class="subscription-form">
							<h3 class="d-flex align-items-center"><span class="me-1"><img src="images/envelope-outline.svg" alt="Image" class="img-fluid"></span><span>Subscribe to Newsletter</span></h3>

							<form action="#" class="row g-3">
								<div class="col-auto">
									<input type="text" class="form-control" placeholder="Enter your name">
								</div>
								<div class="col-auto">
									<input type="email" class="form-control" placeholder="Enter your email">
								</div>
								<div class="col-auto">
									<button class="btn btn-primary">
										<span class="fa fa-paper-plane"></span>
									</button>
								</div>
							</form>

						</div>
					</div>
				</div>

				<div class="row g-5 mb-5">
					<div class="col-lg-4">
						<div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Furni<span>.</span></a></div>
						<p class="mb-4">Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant</p>

						<ul class="list-unstyled custom-social">
							<li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
							<li><a href="#"><span class="fa fa-brands fa-linkedin"></span></a></li>
						</ul>
					</div>

					<div class="col-lg-8">
						<div class="row links-wrap">
							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">About us</a></li>
									<li><a href="#">Services</a></li>
									<li><a href="#">Blog</a></li>
									<li><a href="#">Contact us</a></li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">Support</a></li>
									<li><a href="#">Knowledge base</a></li>
									<li><a href="#">Live chat</a></li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">Jobs</a></li>
									<li><a href="#">Our team</a></li>
									<li><a href="#">Leadership</a></li>
									<li><a href="#">Privacy Policy</a></li>
								</ul>
							</div>

							<div class="col-6 col-sm-6 col-md-3">
								<ul class="list-unstyled">
									<li><a href="#">Nordic Chair</a></li>
									<li><a href="#">Kruzo Aero</a></li>
									<li><a href="#">Ergonomic Chair</a></li>
								</ul>
							</div>
						</div>
					</div>

				</div>

				<div class="border-top copyright">
					<div class="row pt-4">
						<div class="col-lg-6">
							<p class="mb-2 text-center text-lg-start">Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by <a href="https://untree.co">Untree.co</a> Distributed By <a hreff="https://themewagon.com">ThemeWagon</a>  <!-- License information: https://untree.co/license/ -->
            </p>
						</div>

						<div class="col-lg-6 text-center text-lg-end">
							<ul class="list-unstyled d-inline-flex ms-auto">
								<li class="me-4"><a href="#">Terms &amp; Conditions</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul>
						</div>

					</div>
				</div>

			</div>
		</footer>
		<!-- End Footer Section -->	
     
	</body>

</html>


		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/user.js"></script>
    <script src="js/authenticate.js"></script>
    <script type="text/javascript" src="js/apiurl.js"></script>
    <script type="text/javascript" src="./js/authenticate.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php          
        include("./login.php");
    ?>

<script>
  // Helper function to get cookie value by name
  function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
      return [];
  }

  const token = localStorage.getItem('token');

  let logoutHeader = $(".logoutHeader").html();
  let profileHeader = $(".profileHeader").html();
  let addressHeader = $(".addressHeader").html();
  let ordersHeader = $(".ordersHeader").html();
  
  $(document).ready(function() {   
    setTimeout(() => {
      if(token){
          $(".loginHeader").remove();
      }else{
          //If not loged in
          $(".logoutHeader").remove();
          $(".profileHeader").remove();
          $(".addressHeader").remove();
          $(".ordersHeader").remove();
      }

      const catId = localStorage.getItem('catId');
      if(catId){
          $('.catListTop').val(catId);
      } 
    }, 1000);    

    setTimeout(() => {      
      $(".quantity").attr("disabled", true);
    }, 5000);
  });


  function loadProfile(){
      const newtoken = localStorage.getItem("token");
      if(newtoken){
          const itemCount=localStorage.getItem("cartcount");
          $(".cartCount").text(itemCount);
          const wishlistData = localStorage.getItem("wishlistcount");
          $(".wishlistCount").text(wishlistData);
          
          const jwtdata = parseJwt(newtoken);

          $(".menuSubItem").remove();

          $(".menuItem").append(profileHeader);
          $(".menuItem").append(addressHeader);
          $(".menuItem").append(ordersHeader);
          $(".menuItem").append(logoutHeader);
          
      }else{
          // $(".loginAction").remove();
      }
  }

  $(".logout").click(function(){
      toastr.success("Logging out!!!", {
          timeOut: 5000
      });
      localStorage.clear();
      setTimeout(() => {
          location.replace("./user/login.php");
      }, 1500);
  });

    // Fetch category data with cookie caching

  setTimeout(() => { 
        //Read data from cookie
        // Decode the cookie value and json parse it
        let catCookieData = decodeURIComponent(getCookie('categoryData'));
        let categoryData = JSON.parse(catCookieData || '[]');

        if(!categoryData.length){
          $.ajax({
              url: `${base_url}/api/user/routes.php`, //the page containing php script
              type: "post", //request type,
              dataType: 'json',
              data: {"endurl":"getCategory","status":1},
              
              success: function(result) {
                  const data = result.data;
                  // Add result data into cookie for 60 min
                  if(data?.length)
                  document.cookie = `categoryData=${encodeURIComponent(JSON.stringify(data))}; max-age=3600; path=/`;
                  
                  // for(let cat = 0; cat<data.length; cat++){
                  //     const categoryHtml = `  <a href="shop.php?a=1&id=${data[cat].id}" class="nav-link category-item swiper-slide">
                  //                               <img src="./upload/category/${data[cat].category_image}" alt="Shuttleshop Online Market | ${data[cat].category_name}">
                  //                               <h3 class="category-title">${data[cat].category_name}</h3>
                  //                               <h6 class="category-count"><small>${data[cat].count} Products</small></h6>
                  //                             </a>`; 
                      
                  //     $('.categoryList').append(categoryHtml);

                  //     const catListTopHtml = `<option value="${data[cat].id}">${data[cat].category_name}</option>`;
                  //     $('.catListTop').append(catListTopHtml);
                  // }
                  
              },
              error: function(result) {
                  console.log (result);
                  toastr.error(result.responseJSON.msg, {
                      timeOut: 5000
                  });
              },
          });
        }

        setTimeout(() => {
            data = JSON.parse(decodeURIComponent(getCookie('categoryData') || 'null'));

            for(let cat = 0; cat<data.length; cat++){
                  const categoryHtml = `  <a href="shop.php?a=1&id=${data[cat].id}" class="nav-link category-item swiper-slide">
                                            <img src="./upload/category/${data[cat].category_image}" alt="Maruti Studio | ${data[cat].category_name}">
                                            <h3 class="category-title">${data[cat].category_name}</h3>
                                            <h6 class="category-count"><small>${data[cat].count} Products</small></h6>
                                          </a>`; 
                  
                  $('.categoryList').append(categoryHtml);

                  const catListTopHtml = `<option value="${data[cat].id}">${data[cat].category_name}</option>`;
                  $('.catListTop').append(catListTopHtml);
              }
        }, 200);
    }, 100);

    setTimeout(() => { 
      $.ajax({
          url: `${base_url}/api/user/routes.php`, //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {"endurl":"getHomeImg"},
          
          success: function(result) {
              const data = result.data;
              const offerdata = result.offerdata;
              for(let prod = 0; prod<data.length; prod++){
                const prodNameHtml = `<a href="detail.php?id=${data[prod].id}" class="btn btn-warning me-2 mb-2">${data[prod].product_name}</a>`;
                $('.prodNameList').append(prodNameHtml);
              }

                $('#homeImg1').attr('src',`./upload/homeImg/${data.homeImg1}`);
                $('#homeImg1').attr('alt',`Cosmeds`);
                $('#homeImg2').attr('src',`./upload/homeImg/${data.homeImg2}`);
                $('#homeImg2').attr('alt',`Cosmeds`);
                $('#homeImg3').attr('src',`./upload/homeImg/${data.homeImg3}`);
                $('#homeImg3').attr('alt',`Cosmeds`);
              
               const offerHtml = ` <div class="col-md-6">
                      <div class="banner-ad bg-danger mb-3" style="background: url('upload/offerImg/${offerdata.homeImg1}');background-repeat: no-repeat;background-position: right bottom;">
                        <div class="banner-content p-5">

                          <div class="categories text-primary fs-3 fw-bold">Upto ${offerdata.discount}% Off</div>
                          <h3 class="banner-title">${offerdata.heading1}</h3>
                          <p>${offerdata.textDesc1}</p>
                          <a href="#" class="btn btn-dark text-uppercase">Show Now</a>

                        </div>
                      
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="banner-ad bg-info" style="background: url('upload/offerImg/${offerdata.homeImg2}');background-repeat: no-repeat;background-position: right bottom;">
                        <div class="banner-content p-5">

                          <div class="categories text-primary fs-3 fw-bold">Upto ${offerdata.discount}% Off</div>
                          <h3 class="banner-title">${offerdata.heading2}</h3>
                          <p>${offerdata.textDesc2}</p>
                          <a href="#" class="btn btn-dark text-uppercase">Show Now</a>

                        </div>
                      
                      </div>
                    </div>`; 
                    
                    $('.offerImageBox').append(offerHtml);
          },
          error: function(result) {
              console.log (result);
              toastr.error(result.responseJSON.msg, {
                  timeOut: 5000
              });
              //location.replace("./login.php");
          },
      });
    }, 500);

    $(".searchProduct").click(function(e){
        e.preventDefault();
        searchProduct(e);
        
    });

    $('.searchitem').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            searchProduct(event);	
        }
    });

    function searchProduct(e){
        e.preventDefault();
        const searchVal=$(".searchitem").val();
        if(searchVal){
            location.replace(`products.php?s=${searchVal}`);
        }
    };

    $(function() {
        $("form[name='addSubscribe']").validate({
            // Specify validation rules
            rules: {
                email: "required"
            },
            // Specify validation error messages
            messages: {
                email: "Please enter email address"
            },
        });
    });


    $("#subButton").click(function(e) {
        e.preventDefault();
        
        if ($("form[name='addSubscribe']").valid()) {
            const dataPair = {};
            $("#addSubscribe :input").each(function() {
                if ($(this).attr("name")) {
                    dataPair[$(this).attr("name")] = $(this).val();
                }
            });

            dataPair['endurl'] = $("#endurlSub").val();

            $.ajax({
                url: `${base_url}/api/user/routes.php`,
                type: "POST", //request type,
                dataType: 'json',
                data: dataPair,
                success: function (result) {
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    $("form").trigger("reset");                    
                },
                error: function(result) {
                    toastr.error(JSON.parse(result.responseText).msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#submit").attr("disabled",false);
                        if(result.status == 500){
                            location.replace("contact.php");
                        }
                    }, 2000);
                },
            });
        }
    });

    $(document).on("change", ".catListTop", function(){
        const catId = $(this).val();
        if(catId){
            location.replace(`shop.php?a=1&id=${catId}`);
        }else{
            location.replace(`index.php`);
        }
        localStorage.setItem('catId', catId);
    });


    $(document).on('click','.addToCart',function(e){
        $(this).addClass("addToCartAfterlogin");
        productid = $(this).attr("data-product");
        const token = localStorage.getItem("token");
        const user = localStorage.getItem("user");

        if(token){
            const tokenData = parseJwt(token);

            const currenttime = Date.now()/1000;
            const tokenexptime = tokenData.exp;
            cart_id = tokenData.cart_id;

            if(tokenexptime<currenttime){
                loginNeeded();
            }else{
                const qty = $(".addToCartAfterlogin").parents().find(".quantity").val();
                addToCart(qty);
                $(".addToCartAfterlogin").removeClass("addToCartAfterlogin"); 
            }

        }else{
            loginNeeded();
        }
        
    });

    function loginNeeded(){
        $('.showmodal').click();
    }

    // $(document).on('click','.addToCartAfterlogin',function(e){
    //     addToCart();
    // });

    function addToCart(qty){
        const token = localStorage.getItem("token");
        const tokenData = parseJwt(token);
        cart_id = tokenData.cart_id;
        $(".closemodal").click();

        if(qty <=0 && qty > 12){
          toastr.error("Min 1 and Max 12 quantity allowed...", {
              timeOut: 5000
          });
          return;
        }

        $.ajax({
            url: `${base_url}/api/user/routes.php`,
            type: "post", //request type,
            dataType: 'json',
            headers: {
                'Authorization':`Bearer ${token}`
            },
            data: {
                "endurl":"addToCart",
                productid,
                cart_id,
                qty
            },
            success: function(result) {
                toastr.success(result.msg, {
                    timeOut: 5000
                });
                const items = result.data;
                const itemCount = items.reduce((sum,item) => sum + Number(item.quantity), 0);

                $(".cartCount").text(itemCount);
                localStorage.setItem("cartcount",itemCount);
            },
            error: function(result) {
                toastr.error(result.responseJSON.msg, {
                    timeOut: 5000
                });
                setTimeout(() => {
                    // location.replace("index.php");
                }, 1000);
            }
        });
    }


    $(document).on("click", ".quantity-right-plus", function(e){
        e.preventDefault();
        const quantityInput = $(this).parent().parent().find('.quantity');
        let currentQuantity = parseInt(quantityInput.val());
        if (!isNaN(currentQuantity) && currentQuantity < 12) {
            quantityInput.val(currentQuantity + 1);
        }
    });

    $(document).on("click", ".quantity-left-minus", function(e){
        e.preventDefault();
        const quantityInput = $(this).parent().parent().find('.quantity');
        let currentQuantity = parseInt(quantityInput.val());
        if (!isNaN(currentQuantity) && currentQuantity > 1) {
            quantityInput.val(currentQuantity - 1);
        }
    });


     $(document).on('click','.addToWishlist',function(e){
          e.preventDefault();
          
          productid = $(this).attr("data-product");
          const token = localStorage.getItem("token");
          if(token){
              const tokenData = parseJwt(token);

              const currenttime = Date.now()/1000;
              const tokenexptime = tokenData.exp;

              if(tokenexptime<currenttime){
                  loginNeeded();
              }else{
                  allToWishlist(productid);
              }

          }else{
              loginNeeded();
          }
          
      });

      function allToWishlist(productid){ 
            const token = localStorage.getItem("token");
            const tokenData = parseJwt(token);
            cart_id = tokenData.cart_id;
            $(".closemodal").click();

            $.ajax({
                url: `${base_url}/api/user/routes.php`,
                type: "post", //request type,
                dataType: 'json',
                headers: {
                    'Authorization':`Bearer ${token}`
                },
                data: {
                    "endurl":"addToWishlist",
                    productid,
                    cart_id
                },
                success: function(result) {
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    const items = result.data;
                    const itemCount = items.length;

                    $(".wishlistCount").text(itemCount);
                    localStorage.setItem("wishlistcount",itemCount);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                }
            });
        };

        $(".cartDiv").click(function(){
            const token = localStorage.getItem("token");
            if(token){
                location.replace("cart.php");
            }else{
                loginNeeded();
            }
        });
</script>