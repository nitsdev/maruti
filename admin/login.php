<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href="./../css/jquery-validation.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="./../user/style.css">
</head>
<style>
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


<div class="container">
	<div class="screen">
        <div class='page_head'>Log In</div>
		<div class="screen__content">
			<form class="login" action="" name="login" id="login">
				<div class="login__field">
					<i class="login__icon fas fa-user"></i>
					<input type="text" class="login__input" name="email" id="email" placeholder="User Email" />
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" class="login__input" name="password" id="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" />
                    <i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
				</div>
                <input type="hidden" name="endurl" id="endurl" value="login" />
				<button type="submit" class="button login__submit" id="loginAdmin">
                        <!-- <button type="submit" id="loginAdmin">Login</button> -->
					<span class="button__text">Log In Now</span>
					<i class="button__icon fas fa-chevron-right"></i>
				</button>				
			</form>
		</div>	
	</div>
</div>
<!-- partial -->
  
</body>
<?php
    include("./../common/appjs.php");
?>
</html>



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

    $("#loginAdmin").click(function(e) {
        e.preventDefault();
        if ($("form[name='login']").valid()) {
            $("#loginAdmin").attr("disabled",true);

            const dataPair = {};
            $("#login :input").each(function() {
                if ($(this).attr("name")) {
                    dataPair[$(this).attr("name")] = $(this).val();
                }
            });

            // AJAX Call
            $.ajax({
                url: `${base_url}/api/admin/routes.php`, //the page containing php script
                type: "post", //request type,
                dataType: 'json',
                data: dataPair,
                success: function(result) {
                    localStorage.setItem("token",result.token)
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#loginAdmin").attr("disabled",false);
                        location.replace("./product.php");
                    }, 2000);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#loginAdmin").attr("disabled",false);
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