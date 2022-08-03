<?php
require_once "../functions.php";
$title = 'BOOT';

$member = new User();
$list = $member->getAllMemberList();

$i = 0;
$data = array();
$ev = new Event();

foreach ($list as $user) {
  $country = '';
  $state = '';
  $city = '';
  if ($user['country'] != '0') {
    $country =  $ev->getCountry($user['country']);
  }
  if ($user['state'] != '0') {
    $state =  $ev->getState($user['state']);
  }
  if ($user['city'] != '0') {
    $city =  $ev->getCity($user['city']);
  }

  $data[$i]['Name'] = $user['first_name'] . ' ' . $user['last_name'];
  $data[$i]['E-mail ID'] = $user['emailid'];
  $data[$i]['Mobile No.'] = $user['phone_num'];
  $data[$i]['Country'] = $country;
  $data[$i]['State'] = $state;
  $data[$i]['City'] = $city;
  //  $data[$i]['College'] = $user['college'];
  // $data[$i]['Passing Year'] = $user['yearpassed'];
  // $data[$i]['MCI Registration'] = $user['mci'];
  $data[$i]['Topics of Interest'] = $user['topic_interest'];
  $data[$i]['Updates'] = $user['updates'];
  $data[$i]['Time of Registration'] = $user['reg_date'];

  $i++;
}
$filename = $title . "_users.xls";
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
ExportFile($data);
