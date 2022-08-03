<?php
require_once 'functions.php';

$mail = new Mail();
$mail->__set('emailto', 'mahesh.c.sharma@gmail.com');
$mail->__set('password', '1234');
$mail->__set('name', 'Mahesh');

if ($mail->sendReminderEmail()) {
    echo 'Mail sent!';
} else {
    echo 'Mail not sent!';
}
