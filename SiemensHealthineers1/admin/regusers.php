<?php
require_once "../functions.php";
$title = 'SiemensHealthineers';

$member = new User();
$list = $member->getAllMemberList();
//var_dump($list);

$i = 0;
$data = array();
$ev = new Event();

foreach ($list as $user) {
  $data[$i]['Name'] = $user['title'] . ' ' . $user['first_name'] . ' ' . $user['last_name'];
  $data[$i]['E-mail ID'] = $user['emailid'];
  $data[$i]['Phone No.'] = $user['phone_num'];
  $data[$i]['Organization'] = $user['organization'];
  $data[$i]['City'] = $user['city'];
  $data[$i]['Time of Registration'] = $user['joining_date'];
  $data[$i]['Last Login'] = $user['login_date'];
  $data[$i]['Last Logout'] = $user['logout_date'];

  $i++;
}
$filename = $title . "_users.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
ExportFile($data);
