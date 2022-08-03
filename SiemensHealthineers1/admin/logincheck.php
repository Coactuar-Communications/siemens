<?php
require_once '../inc/config.php';

if (!isset($_SESSION['admin'])) {
    //$_SESSION['flash'] = 'Logged out cause of invalid login';
    header('location: ./');
}
