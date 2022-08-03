<?php
require_once "../functions.php";
$title = 'SiemensHealthineers';

$member = new User();
$list = $member->getOnlineMembers('');
//var_dump($list);

$i = 0;
$data = array();
if (!empty($list)) {
  foreach ($list as $user) {

    $data[$i]['Name'] = $user['title'] . ' ' . $user['first_name'] . ' ' . $user['last_name'];
    $data[$i]['E-mail ID'] = $user['emailid'];
    $data[$i]['Mobile No.'] = $user['phone_num'];
    $data[$i]['City'] = $user['city'];
    $data[$i]['Organization'] = $user['organization'];
    $data[$i]['Time of Registration'] = $user['joining_date'];

    $i++;
  }
}
$filename = $title . "_onlineusers.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
ExportFile($data);
