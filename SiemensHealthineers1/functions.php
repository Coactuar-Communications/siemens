<?php

require_once 'inc/config.php';
require_once 'inc/constants.php';
$event_title = "SiemensHealthineers";
$admin_title = "SiemensHealthineers";

spl_autoload_register(function ($classname) {
    $path =  __ROOT__ . '/models/' . strtolower($classname) . ".php";
    //echo $path.'<br>'; 
    if (file_exists($path)) {
        require_once($path);
        //echo "File $path is found.<br>";
    } else {
        echo "File $path is not found.";
    }
});

function setResponse($status, $message)
{
    $response = array("status" => $status, "message" => $message);
    return $response;
}

function unsetUser()
{
    if (isset($_SESSION["userid"])) {
        unset($_SESSION['userid']);
    }
    header('location: ./');
}

function ExportFile($records)
{
    $heading = false;
    if (!empty($records))
        foreach ($records as $row) {
            if (!$heading) {
                // display field/column names as a first row
                echo implode("\t", array_keys($row)) . "\n";
                $heading = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
    exit;
}
