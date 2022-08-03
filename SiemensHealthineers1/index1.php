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
    <title>Anubhav 2021</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7 d-grid m-auto">
                <img src="assets/img/anubhav2021.jpg" class="img-fluid" alt="">
                <a href="register.php">
                    <img src="assets/img/btn-register-now.png" height="30" alt="">
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-5 d-grid m-auto">
                <div class="ratio ratio-16x9 border border-white border-2">
                    <iframe src="https://player.vimeo.com/video/583725781?autoplay=1&loop=1&muted=1" width="100%" height="100%" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-2 ">
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

                        <input type="image" src="assets/img/btn-login.png" value="Submit">
                    </form>
                </div>
            </div>
            <div class=" col-12 col-md-6 col-lg-5">
                <div class="row">
                    <div class="col-8">
                        <a href="https://www.siemens-healthineers.com/en-in/laboratory-automation/systems/aptio-automation" target="_blank">
                            <img src="assets/img/aptio-automation.png" class="img-fluid mb-3" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="https://www.siemens-healthineers.com/en-in/diagnostics-it/atellica-diagnostics-it/atellica-data-manager" target="_blank">
                            <img src="assets/img/atellica.png" class="img-fluid mb-3" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>