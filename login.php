<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Modal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <style>
        .footersection{
            display: flex;
            justify-content: space-between;
        }
        .password-container{
            position: relative;
        }
        .password-container input[type="password"],
        .password-container input[type="text"]{
            box-sizing: border-box;
        }
        .fa-eye{
            position: absolute;
            top: 38%;
            right: 2%;
            cursor: pointer;
            color: blue;
        }
    </style>
<body>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary showmodal" data-bs-toggle="modal" data-bs-target="#loginModal" style="display:none">
  Open Login Modal
</button>

<!-- Generate bootstrap modal here -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="login" action="" name="login" id="login">
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control email" id="email" aria-describedby="email" placeholder="Enter Email" name="email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control password" id="password" placeholder="Enter Password" name="password">
            <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
          </div>
          <input type="hidden" name="endurl" id="endurl" value="login" />
          <span type="submit" id="loginUser" class="btn btn-primary">Submit</span>
        </form>
      </div>
      <div class="modal-footer footersection">
        <a href="../user/registration.php">Register now</a>
        <button type="button" class="btn btn-secondary closemodal" data-bs-dismiss="modal">Close</button>
      </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

<script>
    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='login']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                email: "required",
                password: "required"
            },
            // Specify validation error messages
            messages: {
                email: "Please enter your email",
                password: "Please provide a password"
            },
                
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            // submitHandler: function(form) {
            //     form.submit();
            // }
        });
    });

    $("#toLogin").click(function(){
        $("#email").val("");
        $("#password").val();
        location.replace("registration.php");
    });

    $("#loginUser").click(function(e) {
        e.preventDefault();
        if ($("form[name='login']").valid()) {
            // $("#loginUser").attr("disabled",true);

            const dataPair = {};
            $("#login :input").each(function() {
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
                success: function(result) {
                    localStorage.setItem("token",result.token);
                    
                    $(".closemodal").click();

                    const items = result.cartData;
                    const itemCount = items.reduce((sum,item) => sum + Number(item.quantity), 0);
                    $(".cartCount").text(itemCount);
                    localStorage.setItem("cartcount",itemCount);
                    $(".wishlistCount").text(result.wishlistData.length);
                    localStorage.setItem("wishlistcount",result.wishlistData.length); 
                    setTimeout(() => {
                    loadProfile(); 

                    if(localStorage.getItem("loginAction") == 'wishlist'){
                        location.replace("wishlist.php");
                    }else if(localStorage.getItem("loginAction") == 'cart'){
                        location.replace("cart.php");
                    }
                    localStorage.setItem("extLogedIn",true);
                    }, 200);                  
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#loginUser").attr("disabled",false);
                        const qty = $(".addToCartAfterlogin").parents().find(".quantity").val();
                        addToCart(qty);
                        $(".addToCartAfterlogin").removeClass("addToCartAfterlogin");   
                    }, 1000);
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

    const textShadowColor = ["#ff0029","#ff00d9","#8c00ff","#0083ff","#00ffbf","#09ff00","#fffe00","#ff4100"];
    const min =0;
    const max = textShadowColor.length;
    const indexColor = Math.floor(min + Math.random()*(max - min + 1));
    const colorSelected = textShadowColor[indexColor];
    $(".page_head").css("text-shadow",`0 0 3px ${colorSelected}`);


    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
    });
</script>