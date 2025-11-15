<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href="../css/jquery-validation.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>


<div class="container">
	<div class="screen register-screen">
        <div class='page_head'>Registration</div>
		<div class="screen__content">
			<form class="register" action="" name="registration" id="registration">
            <div class="row">
            <div class="col-sm-6">
				<div class="login__field">
					<i class="login__icon fas fa-address-book"></i>
					<input type="text" class="login__input" name="first_name" id="first_name" placeholder="First Name" autocomplete="false" />
				</div>
                <div class="login__field">
					<i class="login__icon fas fa-address-book"></i>
					<input type="text" class="login__input" name="last_name" id="last_name" placeholder="Last Name" autocomplete="false" />
				</div>
                <div class="login__field">
					<i class="login__icon fas fa-envelope"></i>
					<input type="text" class="login__input" name="email" id="email" placeholder="Email" />
				</div>
            </div>
            <div class="col-sm-6">
                <div class="login__field">
					<i class="login__icon fas fa-phone"></i>
					<input type="text" class="login__input" name="mobile" id="mobile" placeholder="Phone Number" />
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" class="login__input" name="password" id="password" placeholder="Password" />
				</div>
                <div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" class="login__input" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" />
				</div>
            </div>
            </div>
                <input type="hidden" name="endurl" id="endurl" value="register" />
				<button type="submit" class="button login__submit" id="register">
                        <!-- <button type="submit" id="loginUser">Login</button> -->
					<span class="button__text">Register</span>
					<i class="button__icon fas fa-chevron-right"></i>
				</button>				
			</form>
            <div class="social-login sign-in">
				Already a account 
                <div class="social-icons">
                    <a href="login.php"> Sign in Now</a>
                </div>
			</div>
			<div class="social-login forgot">
                <a href="login.php"> Forgot Password?</a>
			</div>
		</div>
		<div class="screen__background">
			<!-- <span class="screen__background__shape screen__background__shape4"></span> -->
			<span class="screen__background__shape screen__background__shape3"></span>		
			<span class="screen__background__shape screen__background__shape2"></span>
			<span class="screen__background__shape screen__background__shape1"></span>
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
    $.validator.addMethod("regex", function(value, element, param) {
        return this.optional(element) ||
            value.match(typeof param == "string" ? new RegExp(param) : param);
    }); // Add method to plugin for processing regex……………

    $.validator.addMethod("pwcheck", function(value, element) {
        let password = value;
        if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
            return false;
        }
        return true;
    }, function(value, element) {
        let password = $(element).val();
        if (!(/^(.{8,20}$)/.test(password))) {
            return 'Password must be between 8 to 20 characters long.';
        } else if (!(/^(?=.*[A-Z])/.test(password))) {
            return 'Password must contain at least one uppercase.';
        } else if (!(/^(?=.*[a-z])/.test(password))) {
            return 'Password must contain at least one lowercase.';
        } else if (!(/^(?=.*[0-9])/.test(password))) {
            return 'Password must contain at least one digit.';
        } else if (!(/^(?=.*[@#$%&])/.test(password))) {
            return "Password must contain special characters from @#$%&.";
        }
        return false;
    });

    // Wait for the DOM to be ready
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        $("form[name='registration']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    // Specify that email should be validated
                    // by the built-in "email" rule
                    email: true
                },
                mobile: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                password: {
                    required: true,
                    pwcheck: true
                },
                confirmpassword: {
                    required: true,
                    equalTo: "#password"
                }
            },
            // Specify validation error messages
            messages: {
                first_name: "Please enter your firstname",
                last_name: "Please enter your lastname",
                password: {
                    required: "Please provide a password",
                    regex: "The password must be eight character long and must contain one uppercase, one lower case letter and special character"
                },
                email: "Please enter a valid email address",
                confirmpassword: {
                    equalTo: "Confirm password should be same as password value"
                }
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    $("#register").click(function(e) {
        e.preventDefault();
        if ($("form[name='registration']").valid()) {
            $("#register").attr("disabled",true);
            // Prepare data
            const dataPair = {};
            $("#registration :input").each(function() {
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
                    toastr.success(result.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#register").attr("disabled",false);
                        location.replace("login.php");
                    }, 2000);
                },
                error: function(result) {
                    toastr.error(result.responseJSON.msg, {
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        $("#register").attr("disabled",false);
                    }, 2000);
                },
            });
        }else{
            $(".register-screen").attr('style', 'height: 990px !important');
        }
    });

    const textShadowColor = ["#ff0029","#ff00d9","#8c00ff","#0083ff","#00ffbf","#09ff00","#fffe00","#ff4100"];
    const min =0;
    const max = textShadowColor.length;
    const indexColor = Math.floor(min + Math.random()*(max - min + 1));
    const colorSelected = textShadowColor[indexColor];
    $(".page_head").css("text-shadow",`0 0 3px ${colorSelected}`);


    $(document).ready(function(){
        setTimeout(() => {   
            $("#first_name").val("");
            $("#last_name").val("");
            $("#email").val("");
            $("#mobile").val("");
            $("#password").val("");
            $("#confirmpassword").val("");
        }, 1000);
    })
</script>