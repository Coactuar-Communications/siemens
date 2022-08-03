<?php
require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'addaudi':
            $data = array();
            foreach (explode('&', $_POST['data']) as $value) {
                $value1 = explode('=', $value);
                $data[$value1[0]] = $value1[1];
            }
            $audi = new Auditorium();
            $audi->__set('audiname', $data['audiname']);

            $status = $audi->addAudi();

            print_r(json_encode($status));

            break;

        case 'getaudis':

            $audi = new Auditorium();
            $audiList = $audi->getaudis();
            $output = '';
            if (!empty($audiList)) {
                $output .= '<table class = "table table-striped">';
                $output .= '<thead><tr>
                    <th>Auditorium Name</th>
                    <th width="100"></th>
                </tr></thead>';
                foreach ($audiList as $a) {
                    $output .= '<tr>
                            <td>' . $a['audi_name'] . '</td>
                            <td>';
                    $output .= '<a class="getaudi" data-id="' . $a['audi_id'] . '" href="#"><i class="fas fa-user-edit"></i></a> <a class="delaudi" data-id="' . $a['audi_id'] . '"  href="#"><i class="far fa-minus-square"></i></a>';
                    $output .= '</td>
                        </tr>';
                }
                $output .= '</table>';
            }
            echo $output;

            break;

        case 'getaudi':
            $audiid = $_POST['audi_id'];
            $audi = new Auditorium();
            $audi->__set('audi_id', $audiid);
            $audiInfo = $audi->getAudi();

            print_r(json_encode($audiInfo));

            break;

        case 'audientry':
            $audiid = $_POST['audi_id'];
            $audi = new Auditorium();
            $audi->__set('audi_id', $audiid);
            $audiInfo = $audi->updAudiEntry();

            print_r(json_encode($audiInfo));

            break;

        case 'delaudi':
            $audiid = $_POST['audi_id'];
            $audi = new Auditorium();
            $audi->__set('audi_id', $audiid);
            $audiInfo = $audi->delAudi();

            print_r(json_encode($audiInfo));

            break;
    }
}
