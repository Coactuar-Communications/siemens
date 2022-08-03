<?php
require_once '../functions.php';

/* $username = 'mahesh';
$password = 'sh@rm@';
$role = 'me'; */

/* $username = 'superadmin';
$password = 'Anubhav2021';
$role = 'superadmin'; */

/* $username = 'admin';
$password = 'password';
$role = 'admin'; */

/*$username = 'helpdesk';
$password = 'integr@ce';
$role = 'helpdesk';*/

/*$username = 'moderator';
$password = 'summit@123';
$role = 'questions';
*/
//$hash_pwd = password_hash($password, PASSWORD_DEFAULT);

//echo $hash_pwd;
//echo "INSERT INTO tbl_admins(username,password,role) values('$username','$hash_pwd','$role')";

$admin = new Admin();
$admin->__set('username', $username);
$admin->__set('password', $password);
$admin->__set('role', $role);

$result = $admin->addAdmin();
var_dump($result);
