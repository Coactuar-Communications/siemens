<?php
require_once "../functions.php";
$res_id = $_GET['e'];

$exhib = new Exhibitor();
$exhib->__set('video_id', $res_id);
$res = $exhib->getTitle();
$title = $res[0]['video_title'];
// print_r($res); die();
$dlList = $exhib->getVideoViewers();
//var_dump($dlList);

$data = array();
if (!empty($dlList)) {
  $i = 0;
  foreach ($dlList as $c) {
    // $data[$i]['First Name'] = $c['first_name'];
    // $data[$i]['Last Name'] = $c['last_name'];
    $data[$i]['E-mail ID'] = $c['emailid'];

    $i++;
  }

  //var_dump($data);
  $filename = $title . "_downloads.xls";
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$filename\"");
  ExportFile($data);
}
