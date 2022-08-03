<?php
require_once 'functions.php';
$emailid = '';
$password = '';
$errors = [];
$succ = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['userEmail'])) {
        $errors['email'] = 'Email Address is required';
    } else 
    if (!filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email Address is invalid";
        $emailid = $_POST['userEmail'];
    } else {
        $emailid = $_POST['userEmail'];
    }
    /* if (!isset($_POST['userPassword'])) {
        $errors['password'] = 'Password is required';
    } else {
        $password = $_POST['userPassword'];
    } */
    if (count($errors) == 0) {
        $user = new User();
        $user->__set('emailid', $emailid);
        //$user->__set('password', $password);
        $login = $user->userLogin();

        $login_status = $login['status'];
        if ($login_status == "success") {
            $succ = $login['message'];
        } else {
            $errors['login'] = $login['message'];
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Siemens Healthineers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Lobster+Two&family=Open+Sans&family=Saira+Extra+Condensed:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  
        p {
  font-size: 1.5vmin;

  color:white;
}
h6{
    margin-top: -4%;
    margin-left: 7%;
    font-size: 2rem;
    border: 1px solid #ec6608;
    padding: 4px;
    width: 202px;
    border-radius: 4px
}
.fwrap {
  background-color: #000000;
  color: #ffffff;
}
#demo{
  
margin-top:9%;
font-size:10vmin;
color:#ec6608;
/* font-family: 'Lobster Two', cursive,'Open Sans', sans-serif;; */
/* font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
/* font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; */
/* font-family: 'Open Sans', sans-serif;
font-family: 'Saira Extra Condensed', sans-serif; */
font-family: 'Calibri';
/* font-family: 'Comic Neue', cursive; */
font-weight: bolder;
   
}

@media only screen and (min-device-width: 319px) and (max-device-width: 912px){
   h6{
    margin-top: -10%;
    margin-left: 7%;
    font-size: 1rem;
    border: 1px solid #ec6608;
    padding: 4px;
    width: 152px;
    border-radius: 4px;
   }

   .reg{
       
    margin-bottom: 6px;

   }

   /* a{
    margin-bottom: -12px; 
   } */
}

</style>
<body>
    <div class="container-fluid">
        <div class="row">
       
            <div class="col-12 col-md-6 col-lg-7 d-grid m-auto">
            
                <!-- <img src="assets/img/seimens-login.png" class="img-fluid" alt=""> -->
                <img src="assets/img/LoginPage3.png" class="img-fluid" alt="">
                <!-- <p class="demo text-white">June 12</p> -->
               <!-- <h6 >June 16 2022 |</h6> -->
               
                <a href="register.php" style="margin-left:21px; margin-bottom:12px">
                    <img class="reg" src="assets/img/btn-register-now.png" height="30" alt="">
                    
                </a>
                
            </div>
            <div class="col-12 col-md-6 col-lg-5 d-grid m-auto">
                <div class="ratio ratio-16x9 border border-white border-2">
                <p class="reg" src="" height="30" alt="">
                <h1 id="demo" class="text-center"></h1>
                </p>
                    <!-- <iframe src="assets/img/HoldingSlide_AfterLoginPage.jpg" width="200px" height="100px" allowfullscreen></iframe> -->
                        <!-- <iframe src="https://player.vimeo.com/video/583725781?autoplay=1&loop=1&muted=1" width="100%" height="100%" allowfullscreen></iframe> -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="form-wrapper">
                    <?php
                    if (count($errors) > 0) : ?>
                        <div class="alert alert-danger alert-msg">
                            <ul class="list-unstyled">
                                <?php foreach ($errors as $error) : ?>
                                    <li>
                                        <?php echo $error; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif;
                    if ($succ != '') { ?>
                        <div class="alert alert-success alert-msg">
                            <?= $succ ?>
                        </div>
                    <?php } ?>
                    <form id="login-form" action="#" method="post">
                        If already registered, please login here:
                        <br>
                        <div class="mb-3">
                            <input type="text" class="form-control input" id="userEmail" name="userEmail" value="<?= $emailid ?>" placeholder="Enter your Email Id">
                        </div>
                        <!-- <div class="mb-1">
                                    <p  class="m-0 p-0" style="font-size:12px; text-align:left; ">By logging in you acknowledge your consent to continue and agree not to share
                                    or distribute information from this domain.</p>
                                            <p  class="m-0 p-0" style="font-size:12px; text-align:left; "><input type="checkbox" name="userConsent" id="userConsent" required> 
                                    I hereby consent to the processing of my above given personal data by the Siemens Healthineers company as referred to under Corporate Information
                            for the purposes as described in the declaration of consent to enter the virtual event platform. I have also read the further information regarding the processing of my personal data contained in the <u><a href="https://www.siemens-healthineers.com/en-in/privacy-policy">Data Privacy Policy.</a></u>
                            <br>

                            <span class="mt-3">
                            I am aware that I can partially or completely revoke this consent at any time for the future by declaring my revocation to the contact address given in the Corporate information or by sending it to the following e-mail address: dataprivacy.communications@siemens-healthineers.com

                            </span>
                        </div> -->
                        <!-- <div class="mb-1">
                            <p style="font-size:12px; text-align:left;"><input type='checkbox' name='concent1' required> I hereby confirm that I agree with and adhere to the<u><a href="https://events.siemens-healthineers.com/event-platform-privacy-policy"> special Terms of Use for Siemens Healthineers Event Website.</a></u></p>
                        </div> -->
                        <!-- <a href="assets/img/HoldingSlide_AfterLoginPage.jpg" class="view" data-docid="3"><i class="far fa-list-alt"></i>Agenda</a> -->
                        
                        <input type="image" src="assets/img/btn-login.png" value="Submit">
                    </form>
                   
                </div>
                <p style="margin-left:25px">*As per the data calculated on basis of charges and internal rate of return under present economic conditions. This data can be provided separately on request.</p>
            </div>
            <div class=" col-12 col-md-6 col-lg-5">
                <div class="row">
                    <!-- <div class="col-8">
                        <a href="https://www.siemens-healthineers.com/en-in/laboratory-automation/systems/aptio-automation" target="_blank">
                            <img src="assets/img/aptio-automation.png" class="img-fluid mb-3" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="https://www.siemens-healthineers.com/en-in/diagnostics-it/atellica-diagnostics-it/atellica-data-manager" target="_blank">
                            <img src="assets/img/atellica.png" class="img-fluid mb-3" alt="">
                        </a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <img src="//placehold.it/1000x600" class="img-responsive">
        </div>
    </div>
  </div>
</div>
        

<script>
// Set the date we're counting down to
var countDownDate = new Date("June 16, 2022 18:00:00").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  // Output the result in an element with id="demo"
 document.getElementById("demo").innerHTML =days + " Days</br> " + hours + ":"
  + minutes + ":" + seconds + " Hrs";
    
    
  // If the count down is over, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "WE ARE LIVE";
  }
}, 1000);
</script>
</body>

</html>