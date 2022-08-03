<?php
require_once 'functions.php';

$title = '';
$fname = '';
$lname = '';
$org = '';
$city = '';
$mobile = '';
$emailid = '';
$consent = '';

$errors = [];
$succ = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['title'])) {
        $errors['title'] = 'Title is required';
    } else {
        $title = $_POST['title'];
    }
    if (empty($_POST['userFirstName'])) {
        $errors['fname'] = 'First Name is required';
    } else {
        $fname = $_POST['userFirstName'];
    }
    if (empty($_POST['userLastName'])) {
        $errors['lname'] = 'Last Name is required';
    } else {
        $lname = $_POST['userLastName'];
    }
    if (empty($_POST['userOrganization'])) {
        $errors['org'] = 'Organization is required';
    } else {
        $org = $_POST['userOrganization'];
    }
    if (empty($_POST['userCity'])) {
        $errors['city'] = 'City is required';
    } else {
        $city = $_POST['userCity'];
    }
    if (empty($_POST['userMobile'])) {
        $errors['mobile'] = 'Mobile is required';
    } else {
        $mobile = $_POST['userMobile'];
    }
    if (empty($_POST['userEmail'])) {
        $errors['email'] = 'Email Address is required';
    } else 
    if (!filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email Address is invalid";
        $emailid = $_POST['userEmail'];
    } else {
        $emailid = $_POST['userEmail'];
    }
    if (!isset($_POST['userConsent'])) {
        $errors['consent'] = 'Consent is required';
    } else {
        $consent = $_POST['userConsent'];
    }

    if (count($errors) == 0) {
        $newuser = new User();
        $newuser->__set('title', $title);
        $newuser->__set('firstname', $fname);
        $newuser->__set('lastname', $lname);
        $newuser->__set('emailid', $emailid);
        $newuser->__set('mobilenum', $mobile);
        $newuser->__set('organization', $org);
        $newuser->__set('city', $city);

        $add = $newuser->addUser();
        //var_dump($add);
        $reg_status = $add['status'];

        if ($reg_status == "success") {
            $succ = $add['message'];
            $title = '';
            $fname = '';
            $lname = '';
            $org = '';
            $city = '';
            $mobile = '';
            $emailid = '';
            $consent = '';
        } else {
            $errors['reg'] = $add['message'];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siemens Healthineers </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
::-webkit-input-placeholder { /* Edge */
  color: black !important;
  font-weight: 400;
  font-family: Verdana, sans-serif;
 
  
}

:-ms-input-placeholder { /* Internet Explorer */
  color: black !important;
  font-weight: 400;
  font-family: Verdana, sans-serif;
  
}

::placeholder {
  color: black !important;
  font-weight: 400;
  font-family: Verdana, sans-serif;
  
}

.form-control.input {
    background-color: white;
    border: 1.5px solid #e2e3e5;
    border-radius: 7px;
    padding: 0.3em;
    font-size: 1em;
}
.col-3{
    width:70px;
}


</style>
</head>

<body>
    <div class="container-fluid">
        <div class="row vh-100">
            <!-- <div class="col-12 col-md-6 col-lg-7 d-grid m-auto"> -->
            <div class="col-12 col-md-8 col-lg-8 d-grid m-auto">
                <!-- <img src="assets/img/anubhav2021.jpg" class="img-fluid" alt=""> -->
                <img src="assets/img/MAGNETOM FreeStar.png" class="img-fluid" alt="">
               
            </div>
            <div class="col-12 col-md-4 col-lg-4 bg-white">
            <!-- <div class="col-12 col-md-6 col-lg-5 bg-white"> -->
                <div class="form-wrapper">
               
                    <!-- <h5>Registration</h5>
                    
                    <h6>Thank you for your interest.</h6> -->
                    <img src="assets/img/regStar2.png" width=400px class="img-fluid" alt=""/ >
                  
                    <form action="" method="post" class="mt-2" autocomplete="off">
                   
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
                       
                        <!-- <small><b>All fields are mandatory</b></small> -->
                        <div class="mb-1" style="background-color: white; font-size: 1em; margin:0;">
                            <div class="row">
                                <div class="col-3">
                                    <input type="checkbox" class="chb" name="title" <?= ($title == 'Miss') ? 'checked' : '' ?> value="Miss"> Miss
                                </div>
                                <div class="col-3">
                                    <input type="checkbox" class="chb" name="title" <?= ($title == 'Mrs') ? 'checked' : '' ?> value="Mrs"> Mrs
                                </div>
                                <div class="col-3">
                                    <input type="checkbox" class="chb" name="title" <?= ($title == 'Mr') ? 'checked' : '' ?> value="Mr"> Mr
                                </div>
                                <div class="col-3">
                                    <input type="checkbox" class="chb" name="title" <?= ($title == 'Dr') ? 'checked' : '' ?> value="Dr"> Dr
                                </div>
                            </div>


                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userFirstName" name="userFirstName" value="<?= $fname ?>" placeholder="First Name">
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userLastName" name="userLastName" value="<?= $lname ?>" placeholder="Last Name">
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userOrganization" name="userOrganization" value="<?= $org ?>" placeholder="Organization">
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userCity" name="userCity" value="<?= $city ?>" placeholder="City">
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userMobile" name="userMobile" value="<?= $mobile ?>" placeholder="Mobile Number">
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control input" id="userEmail" name="userEmail" value="<?= $emailid ?>" placeholder="Email Address">
                        </div>
                        <!-- <div class="mb-1">
                            <small>The protection of your data and recognition of your rights with regards to the collection, processing and use of your data are important to Siemens Healthineers. As part of this event, Siemens Healthineers may process your personal data and pass it on to third parties. This is limited to personal data that you actively and voluntarily provide during the registration process.<br>
                                The collected data will only be passed on to the third parties to provide services and functionsin relation to this event, to verify your identity and to answer and fulfil your specific requests e.g. digital virtual, hybrid or live event participation and similar services. Any data collected in relation to this event shall be handled in accordance with Siemens Healthineers Data Privacy Notice.</small>
                        </div>
                        <div class="mb-3">
                            <input type="checkbox" name="userConsent" id="userConsent" value='Yes' <?= ($consent == 'Yes') ? 'checked' : '' ?>>
                            <small>I hereby consent to the processing of my above given personal data by the Siemens Healthineers company as referred to under Corporate Information for the purposes as described in the <u>Declaration of Consent to enter the virtual event platform.</u> I have also read the further information regarding the processing of my personal data contained in the <a href="#">Data Privacy Policy</a>.<br>
                                I am aware that I can partially or completely revoke this consent at any time for the future by declaring my revocation to the contact address given in the Corporate information or by sending it to the following e-mail address: <a href="mailto:dataprivacy.communication@siemens-healthineers.com">dataprivacy.communication@siemens-healthineers.com</a>
                            </small>
                        </div> -->
                        <div class="mb-1">
                                    <p  class="m-0 p-0" style="font-size:9px; text-align:left;">By registering you acknowledge your consent to continue and agree not to share
                                    or distribute information from this domain.</p>
                                            <p  class="m-0 p-0" style="font-size:9px; text-align:left;  "><input type="checkbox" name="userConsent" id="userConsent" value='Yes' <?= ($consent == 'Yes') ? 'checked' : '' ?>> 
                                    I hereby consent to the processing of my above given personal data by the Siemens Healthineers company as referred to under Corporate Information
                            for the purposes as described in the declaration of consent to enter the virtual event platform. I have also read the further information regarding the processing of my personal data contained in the <u><a href="https://www.siemens-healthineers.com/en-in/privacy-policy">Data Privacy Policy.</a></u>
                            <br>

                            <span class="mt-3">
                            I am aware that I can partially or completely revoke this consent at any time for the future by declaring my revocation to the contact address given in the Corporate information or by sending it to the following e-mail address: dataprivacy.communications@siemens-healthineers.com

                            </span>
                        </div>
                        <div class="mb-1">
                            <p style="font-size:8px; text-align:left;"><input type='checkbox' name='concent1' required> I hereby confirm that I agree with and adhere to the<u><a href="https://events.siemens-healthineers.com/event-platform-privacy-policy"> special Terms of Use for Siemens Healthineers Event Website.</a></u></p>
                        </div>
                        <b style="font-size:12px;">Please reach out at +91 81052 78762 in case of any difficulty for registering</b>
                        <!-- <input type="image" src="assets/img/btn-register.png" alt="Submit and Register" value="submit"> -->
                        <input type="image" src="assets/img/button.png" width="130px" value="submit">
                        </br>
                        <!-- <input type="image" src="img/submit.png" width="200px" value="submit"> -->
                        <img onclick="window.location.href='https://www.siemens-healthineers.com/en-in/magnetic-resonance-imaging/high-v-mri/magnetom-free-star'" class="alignnone size-full wp-image-1303" src="assets/img/submit.png" width="200px" />
                        <p style="font-size:9px; text-align:left;">*as per the data calculated on basis of charges and internal rate of return assumed under present economic
                            conditions. This data can be provided separately on request.</p> 
                            
                    </form>
                </div>
            </div>

        </div>
</div> 

<script>
    $(".chb").change(function() {
    $(".chb").prop('checked', false);
    $(this).prop('checked', true);
});
</script>


</body>

</html>