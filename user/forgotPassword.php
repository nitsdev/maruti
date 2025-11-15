<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Maruti Studio | Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/all.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.2.0/css/fontawesome.css'>
    <link rel="stylesheet" href="./../css/jquery-validation.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./../user/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
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
    .regLink{
        margin-left: 15%;
    }
    p{
      font-size: 22px;
    }

    ::-ms-input-placeholder { /* Edge 12 -18 */
      color: black !important;
    }

    ::placeholder {
      color: black;
      opacity: 1; /* Firefox */
    }
    .otc , .formOTP{
      position: relative;
      width: 320px;
      margin: 0 auto;
    }

    .otc fieldset, .formOTP fieldset{
      border: 0;
      padding: 0;
      margin: 0;
    }

    .otc fieldset div, .formOTP fieldset div{
      display: flex;
      align-items: center;
      justify-content: center
    }

    .otc legend, .formOTP legend{
      margin: 0 auto 1em;
      color: #fff;
      font-weight: bold;
    }

    input[type="number"] {
      width: .86em;
      line-height: 1;
      margin: .1em;
      padding: 8px 0 4px;
      font-size: 2.65em;
      text-align: center;
      appearance: textfield;
      -webkit-appearance: textfield;
      border: 0;
      color: #073A39;
      border-radius: 6px;
    }
    

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* 2 groups of 3 items */
    input[type="number"]:nth-child(n+4) {
      order: 2;
    }
    .otc div::before, .formOTP div::before {
      content: '';
      height: 2px;
      width: 12px;
      margin: 0 .25em;
      order: 1;
      background: #fff;
      border-radius: 2px;
      opacity: .4;
    }

    /* From: https://gist.github.com/ffoodd/000b59f431e3e64e4ce1a24d5bb36034 */
    .otc label, .formOTP label{
      border: 0 !important;
      clip: rect(1px, 1px, 1px, 1px) !important;
      -webkit-clip-path: inset(50%) !important;
      clip-path: inset(50%) !important;
      height: 1px !important;
      margin: -1px !important;
      overflow: hidden !important;
      padding: 0 !important;
      position: absolute !important;
      width: 1px !important;
      white-space: nowrap !important;
    }

    body, html {
      height: 100%;
    }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      /* background: #073A39; */
      /* color: white; */
    }

    .otpSection,#submitOtp,.passwordSec{
      display:none;
    }

    .resend, .resend a{
      text-align: center;
      color:black;
      font-weight: bolder;
    }

    .errorSec{
      text-align: center;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-evenly;
      flex-direction: column;
      align-content: center;
    }

    .successMsg{
      display:none;
      color: green;
      font-weight: bolder;
      background: black;
      background: #ffffff45;
      padding: 1px 10px;
      font-size: 16px;
    }

    .errorMsg{
      display:none;
      color: #ff8282;
      font-weight: bolder;
      background: black;
      background: #00000045;
      padding: 1px 10px;
      font-size: 16px;
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
      top: 55%;
      right: 2%;
      cursor: pointer;
      color: blue;
    }
    
    .login__icon{
      top: 34px !important;
    }

    .login__field {
      padding: 0px !important;
    }

    input {
      background: aliceblue !important;
    }
    
</style>
<body>


  <div class="container">
    <div class="col-md-6">
      <h2 class="m-3 mt-5"><b>Forgot Password</b></h2><br><br>
      <p class="m-3">Please enter your registerd email id for getting otp to reset your password</p>
      
      <form action="" name="forgot" id="forgot" class='formOTP'>
          <div class="login__field">
            <i class="login__icon fas fa-envelope"></i>
            <input type="email" class="login__input m-5" name="email" id="email" placeholder="User Email" />
          </div>
          <button type="submit" class="button login__submit" id="getOtp">
            <span class="button__text getOTPText" >Get Otp</span>
            <i class="button__icon fas fa-chevron-right"></i>
          </button>
          <div class='m-5 t-5 otpSection otc'>
            <fieldset>
              <strong>Enter OTP</strong>
              <label for="otc-1">Number 1</label>
              <label for="otc-2">Number 2</label>
              <label for="otc-3">Number 3</label>
              <label for="otc-4">Number 4</label>
              <label for="otc-5">Number 5</label>
              <label for="otc-6">Number 6</label>

              <!-- https://developer.apple.com/documentation/security/password_autofill/enabling_password_autofill_on_an_html_input_element -->
              <div>
              <input type="number" pattern="[0-9]*"  value="" inputtype="numeric" autocomplete="one-time-code" id="otc-1" >

              <!-- Autocomplete not to put on other input -->
              <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-2" >
              <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-3" >
              <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-4" >
              <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-5" >
              <input type="number" pattern="[0-9]*" min="0" max="9" maxlength="1"  value="" inputtype="numeric" id="otc-6" >
              </div>
            </fieldset>
          </div>
          <div class="login__field passwordSec">
            <i class="login__icon fas fa-lock"></i>
            <input type="password" class="login__input" name="password" id="password" placeholder="Password" autocomplete="off"/>
                      <i class="far fa-eye" id="togglePassword"></i>
          </div>
          <div class="login__field passwordSec">
            <i class="login__icon fas fa-lock"></i>
            <input type="password" class="login__input" name="retry_password" id="retry_password" placeholder="Confirm Password" />
                      <i class="far fa-eye" id="toggleRetryPassword"></i>
          </div>
          
          
          <button type="submit" class="button login__submit" id="submitOtp">
            <span class="button__text" >Submit</span>
            <i class="button__icon fas fa-chevron-right"></i>
          </button>
          <div class='resend'>
            <!-- <a href="resend">Resend OTP</a> -->
          </div>
          <div class='errorSec'>
            <span class="successMsg"></span>
            <span class="errorMsg"></span>
          </div>
      </form>
    </div>
  </div>
  
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
      $("form[name='forgot']").validate({
          // Specify validation rules
          rules: {
              // The key name on the left side is the name attribute
              // of an input field. Validation rules are defined
              // on the right side
              email: {
                required: true,
                email: true
              }
          },
          // Specify validation error messages
          messages: {
              email: "Please enter valid email"
          },
              
          // Make sure the form is submitted to the destination defined
          // in the "action" attribute of the form when valid
          // submitHandler: function(form) {
          //     form.submit();
          // }
      });
  });

  let in1 = document.getElementById('otc-1'),
  ins = document.querySelectorAll('input[type="number"]'),
  splitNumber = function(e) {
    let data = e.data || e.target.value; // Chrome doesn't get the e.data, it's always empty, fallback to value then.
    if ( ! data ) return; // Shouldn't happen, just in case.
    if ( data.length === 1 ) return; // Here is a normal behavior, not a paste action.
    
    popuNext(e.target, data);
    //for (i = 0; i < data.length; i++ ) { ins[i].value = data[i]; }
	},
	popuNext = function(el, data) {
		el.value = data[0]; // Apply first item to first input
		data = data.substring(1); // remove the first char.
		if ( el.nextElementSibling && data.length ) {
			// Do the same with the next element and next data
			popuNext(el.nextElementSibling, data);
		}
	};
  
  ins.forEach(function(input) {
    /**
     * Control on keyup to catch what the user intent to do.
     * I could have check for numeric key only here, but I didn't.
     */
    input.addEventListener('keyup', function(e){
      // Break if Shift, Tab, CMD, Option, Control.
      if (e.keyCode === 16 || e.keyCode == 9 || e.keyCode == 224 || e.keyCode == 18 || e.keyCode == 17) {
        return;
      }
      
      // On Backspace or left arrow, go to the previous field.
      if ( (e.keyCode === 8 || e.keyCode === 37) && this.previousElementSibling && this.previousElementSibling.tagName === "INPUT" ) {
        this.previousElementSibling.select();
      } else if (e.keyCode !== 8 && this.nextElementSibling) {
        this.nextElementSibling.select();
      }
      
      // If the target is populated to quickly, value length can be > 1
      if ( e.target.value.length > 1 ) {
        splitNumber(e);
      }
    });
    
    /**
     * Better control on Focus
     * - don't allow focus on other field if the first one is empty
     * - don't allow focus on field if the previous one if empty (debatable)
     * - get the focus on the first empty field
     */
    input.addEventListener('focus', function(e) {
      // If the focus element is the first one, do nothing
      if ( this === in1 ) return;
      
      // If value of input 1 is empty, focus it.
      if ( in1.value == '' ) {
        in1.focus();
      }
      
      // If value of a previous input is empty, focus it.
      // To remove if you don't wanna force user respecting the fields order.
      if ( this.previousElementSibling.value == '' ) {
        this.previousElementSibling.focus();
      }
    });
  });

  /**
   * Handle copy/paste of a big number.
   * It catches the value pasted on the first field and spread it into the inputs.
   */
  in1.addEventListener('input', splitNumber);


  $("#getOtp").click(function(e){
    const email = $("#email").val();

    e.preventDefault();
    if ($("form[name='forgot']").valid()) {
      $.ajax({
          url: `${base_url}/api/user/routes.php`, //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {
              "endurl":"sendOtp",email
          },
          success: function(result) {
            $(".successMsg").text(result.msg);
            $(".successMsg").css("display","block");
            $(".errorMsg").text(`You have only ${result.resendLeft} retry left`);
            $(".errorMsg").css("display","block");
            $(".getOTPText").text("Resent OTP");
            $(".otpSection").css("display","block");
            $(".passwordSec").css("display","block");
          },
          error: function(result) {
            $(".successMsg").text("");
            $(".successMsg").css("display","none");
            $(".errorMsg").text(result.responseJSON.msg);
            $(".errorMsg").css("display","block");
            $(".otpSection").css("display","none");
            $('#otc-1').val("");
            $('#otc-2').val("");
            $('#otc-3').val("");
            $('#otc-4').val("");
            $('#otc-5').val("");
            $('#otc-6').val("");
          },
      });
    }
  });

  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');
  
  togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('fa-eye-slash');
  });

  const toggleRetryPassword = document.querySelector('#toggleRetryPassword');
  const retryPassword = document.querySelector('#retry_password');
  
  toggleRetryPassword.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = retryPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      retryPassword.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('fa-eye-slash');
  });

  $('#otc-1').keyup(function(){
    const val = $(this).val();
    if(val){
      $("#getOtp").css("display","none");
      $("#submitOtp").css("display","flex");
    }else{
      $("#getOtp").css("display","flex");
      $("#submitOtp").css("display","none");
    }
    $(".successMsg").text("");
    $(".successMsg").css("display","none");
    $(".errorMsg").css("display","none");
  });

  //submitOtp
  $("#submitOtp").click(function(e){
    e.preventDefault();

    const otp1 = $('#otc-1').val();
    const otp2 = $('#otc-2').val();
    const otp3 = $('#otc-3').val();
    const otp4 = $('#otc-4').val();
    const otp5 = $('#otc-5').val();
    const otp6 = $('#otc-6').val();
    const otp = `${otp1}${otp2}${otp3}${otp4}${otp5}${otp6}`;

    if(otp.length != 6){
      $(".errorMsg").text("Entered OTP should be 6 digit");
      $(".errorMsg").css("display","block");
    }else if(!$('#password').val()){
      $(".errorMsg").text("Please Enter valid password");
      $(".errorMsg").css("display","block");
    }else if($('#password').val() != $('#retry_password').val()){
      $(".errorMsg").text("Password and Confirm password should match");
      $(".errorMsg").css("display","block");
    }else{
      $(".errorMsg").css("display","none");
      //Ajax call to reset password

      const email = $("#email").val();
      const password = $("#password").val();
      const retry_password = $("#retry_password").val();

      $.ajax({
          url: `${base_url}/api/user/routes.php`, //the page containing php script
          type: "post", //request type,
          dataType: 'json',
          data: {
              "endurl":"resetPassword",email,otp,password,retry_password
          },
          success: function(result) {
            toastr.success(result.msg, {
                timeOut: 5000
            });
            setTimeout(() => {
                location.replace("./login.php");
            }, 3000);
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
