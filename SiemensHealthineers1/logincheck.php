<?php
require_once "functions.php";

$userid = '';
$user_name = '';
$user_email = '';

//echo $_SESSION['user_id'];
$loginUrl = './login.php';
//echo $_SESSION['userid'];
if (!isset($_SESSION['userid'])) {
    header("location: " . $loginUrl);
} else {
    $userid = $_SESSION['userid'];
    $member = new User();
    $member->__set('user_id', $userid);
    $user = $member->getUser();
    //var_dump($user);
    if (!empty($user)) {
        $user_name = $user[0]['first_name'] . ' ' . $user[0]['last_name'];
        $user_email = $user[0]['emailid'];
    } else {
        header("location: " . $loginUrl);
    }
}
