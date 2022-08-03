<?php
require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'exhibentry':
            $exhibid = $_POST['exhib_id'];
            $exhib = new Exhibitor();
            $exhib->__set('exhib_id', $exhibid);
            $exhibInfo = $exhib->updExhibEntry();

            print_r(json_encode($exhibInfo));

            break;

        case 'delexhib':
            $exhibid = $_POST['exhib_id'];
            $exhib = new Exhibitor();
            $exhib->__set('exhib_id', $exhibid);
            $exhibInfo = $exhib->delExhib();

            print_r(json_encode($exhibInfo));

            break;

        case 'delvideo':
            $videoid = $_POST['video_id'];
            $exhib = new Exhibitor();
            $exhib->__set('video_id', $videoid);
            $videoInfo = $exhib->delVideo();

            print_r(json_encode($videoInfo));

            break;

        case 'delres':
            $resid = $_POST['res_id'];
            $exhib = new Exhibitor();
            $exhib->__set('res_id', $resid);
            $resInfo = $exhib->delResource();

            print_r(json_encode($resInfo));

            break;

        case 'updateVideoView':

            $videoid = $_POST["vidId"];
            $userid = $_POST['userId'];
            $exhib = new Exhibitor();
            $exhib->__set('video_id', $videoid);
            $exhib->__set('user_id', $userid);
            $vidupd = $exhib->updateVideoView();

            //var_dump($vidupd);

            break;

        case 'updateFileDLCount':

            $resid = $_POST["resId"];
            $userid = $_POST['userId'];

            $exhib = new Exhibitor();
            $exhib->__set('res_id', $resid);
            $exhib->__set('user_id', $userid);
            $vidupd = $exhib->updateFileDLCount();

            //var_dump($vidupd);

            break;

        case 'samplereq':
            $exhibid = $_POST['exhId'];
            $userid = $_POST['userId'];
            $exhib = new Exhibitor();
            $exhib->__set('exhib_id', $exhibid);
            $exhib->__set('user_id', $userid);
            $exhib->__set('req', 'Samples');
            $exhibInfo = $exhib->subRequests();

            print_r(json_encode($exhibInfo));

            break;

        case 'prodreq':
            $exhibid = $_POST['exhId'];
            $userid = $_POST['userId'];
            $exhib = new Exhibitor();
            $exhib->__set('exhib_id', $exhibid);
            $exhib->__set('user_id', $userid);
            $exhib->__set('req', 'Telephonic Product Detailing');
            $exhibInfo = $exhib->subRequests();

            print_r(json_encode($exhibInfo));

            break;

        case 'campreq':
            $exhibid = $_POST['exhId'];
            $userid = $_POST['userId'];
            $exhib = new Exhibitor();
            $exhib->__set('exhib_id', $exhibid);
            $exhib->__set('user_id', $userid);
            $exhib->__set('req', 'Camp');
            $exhibInfo = $exhib->subRequests();

            print_r(json_encode($exhibInfo));

            break;
    }
}
