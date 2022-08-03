<?php
set_time_limit(0);
require_once "functions.php";

$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
$each = 4;
$pause = 10;
$user = new User();
$all = $user->getUserCount();
echo "Total Members: " . $all . "<br><br>";
sendEmails($offset, $each);

function sendEmails($i, $limit)
{
    //echo $i . '<br>';

    $mail_msg = '';

    $user = new User();

    $mail_sent = 0;
    $mail_notsent = 0;

    $user->__set('limit', $limit);
    $userList = $user->getAllUsers($i);
    //print_r($userList);

    foreach ($userList as $c) {
        $name = $c['title'] . ' ' . $c['first_name'] . ' ' . $c['last_name'];
        $email = $c['emailid'];
        echo $name . ': ' . $email . '<br>';
        $mailer = new Mail();
        $mailer->__set('emailto', $email);
        $mailer->__set('name', $name);
        $status = $mailer->sendReminderEmail();
        if ($status) {
            $mail_sent++;
            $mail_msg .= "<br>Mail sent to " . $email . '<br>';
        } else {
            $mail_notsent++;
            $mail_msg .= "<br>Mail could not be sent to " . $email . '<br>';
        }
    }
    echo "<br>Mail Sent to: " . $mail_sent . "<br>";
    echo "Mail not Sent to: " . $mail_notsent . "<br>";

    echo "<br>" . $mail_msg;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Email</title>
</head>

<body>
    <script>
        var offset = <?= $offset ?>;
        var each = <?= $each ?>;
        var all = <?= $all ?>;
        offset += each;
        console.log(offset);
        if (offset < all) {
            setTimeout(() => {
                location.href = '?offset=' + offset;
            }, <?= $pause * 1000 ?>);
        }
    </script>
</body>

</html>