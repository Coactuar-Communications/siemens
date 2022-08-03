<?php
require_once "../functions.php";
$res_id = $_GET['e'];

$exhib = new Event();
$exhib->__set('res_id', $res_id);
$res = $exhib->getResource();
$title = $res[0]['resource_title'];

$dlList = $exhib->getResDownloads();
//var_dump($dlList);

$data = array();
if (!empty($dlList)) {
  $i = 0;
  foreach ($dlList as $c) {
    $data[$i]['First Name'] = $c['first_name'];
    $data[$i]['Last Name'] = $c['last_name'];
    $data[$i]['E-mail ID'] = $c['emailid'];
    $data[$i]['Phone No.'] = $c['phone_num'];
    $data[$i]['City'] = $c['city'];
    $data[$i]['Organization'] = $c['organization'];

    $i++;
  }

  //var_dump($data);
  $filename = $title . "_downloads.xls";
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$filename\"");
  ExportFile($data);
}
