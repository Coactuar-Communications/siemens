<?php
session_start();
define('__ROOT__', dirname(dirname(__FILE__)));

$timezone = 'Asia/Kolkata';
date_default_timezone_set($timezone);

$limit = 50;
$total_records_per_page = 50;
