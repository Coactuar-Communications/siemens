<?php
require_once '../inc/config.php';
session_unset($_SESSION['superadmin']);
header('location: ./');
