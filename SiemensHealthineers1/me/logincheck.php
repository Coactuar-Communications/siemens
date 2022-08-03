<?php
require_once '../inc/config.php';

if (!isset($_SESSION['superadmin'])) {
    header('location: ./');
}
