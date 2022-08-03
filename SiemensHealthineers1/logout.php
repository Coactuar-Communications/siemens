<?php
require_once "inc/config.php";
require_once "functions.php";
$userid = $_SESSION['userid'];

$member = new User();
$member->__set('user_id', $userid);
$status = $member->userLogout();

//var_dump($status);
//echo $status['status'];

if ($status['status'] == 'success') {
  header("location: ./");
}
