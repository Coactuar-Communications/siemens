<?php
//require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {
            /*Update Attendees Login Status*/
        case 'updateevent':
            if (isset($_SESSION["userid"])) {
                $id = $_POST["userId"];
                $room = $_POST['room'];

                $user = new User();
                $user->__set('user_id', $id);
                $user->__set('curr_room', $room);
                $curr_status = $user->updateMemberLoginStatus();
                if (!$curr_status) {
                    //unsetUser();
                    echo '0';
                }
            } else {
                echo '0';
            }
            break;
        case 'updatesession':
            if (isset($_SESSION["userid"])) {

                $id = $_POST["userId"];
                $sess_id = $_POST["sessId"];

                //echo 'session update';
                $sess = new Session();
                $sess->__set('user_id', $id);
                $sess->__set('session_id', $sess_id);
                $status = $sess->updateMemberSessionStatus();
                //var_dump($status);
            }

            break;

        case 'updatevisitors':

            $user = new User();
            $visitors = $user->getVisitorsCount();
            //var_dump($status);
            echo '<b>Visitors:</b> ' . $visitors;

            break;

        case 'updateonline':

            $user = new User();
            $online = $user->getOnlineMemberCount();
            //var_dump($status);
            echo '<b>Online:</b> ' . $online;

            break;

        case 'updateonpage':
            $page = $_POST['page'];
            $user = new User();
            $onpage = $user->getOnpageCount($page);
            //var_dump($status);
            echo '<b>On this page:</b> ' . $onpage;

            break;
    }
}
