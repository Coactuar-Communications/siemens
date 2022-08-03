<?php
require_once "logincheck.php";
require_once "functions.php";

$audi_id = 'b0dc60f87abb8d33229e2822d4a440bfc1550b1411ed790f83d81f92caba126b';
$audi = new Auditorium();
$audi->__set('audi_id', $audi_id);
$a = $audi->getEntryStatus();
$entry = $a[0]['entry'];
if (!$entry) {
    header('location: lobby.php');
}
$curr_room = 'auditorium';
$webcastUrl = 'https://player.vimeo.com/video/583725781';

$sess_id = 0;
if (isset($_GET['ses'])) {
    $sess_id = $_GET['ses'];
    $sess = new Session();
    $sess->__set('session_id', $sess_id);
    $curr_sess = $sess->getSession();
    if ((empty($curr_sess)) || (!$curr_sess[0]['launch_status'])) {
        header('location: auditorium.php');
    }

    $webcastUrl = $sess->getWebcastSessionURL();
    $webcastUrl .= '?autoplay=1';
} else {
    $webcastUrl .= '?autoplay=1&loop=1&muted=1';
}
?>
<?php require_once 'header.php';  ?>

<?php require_once 'preloader.php';  ?>

<div class="page-content">
    <div id="content">
        <div id="header-menu">
            <?php require_once "header-navmenu.php" ?>
        </div>
        <div id="bg">
            <img src="assets/img/AUDITORIUM F_1 SIEMENS compressed.jpg">
            <div id="webcast-area">
            <iframe src="video.php" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Insignia"></iframe>
            <!-- <iframe src="<?= $webcastUrl ?>" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="Insignia"></iframe> -->
            </div>
        </div>

        <div id="audiAgenda">
            <a href="assets/img/Final Agenda.jpg" class="view resdl" data-docid="3"><i class="far fa-list-alt"></i>Agenda</a>
        </div>
        
        <?php
        if ($sess_id != '0') {
        ?>
            <div id="ask-ques">
                <a href="#" id="askques"><i class="fas fa-question-circle"></i>Ask Ques</a>
            </div>
            <!-- <div id="take-poll">
                <a href="#" id="takepoll"><i class="fas fa-poll"></i>Take Poll</a>
            </div> -->

            <div class="panel ques">
                <div class="panel-heading">
                    Ask A Question
                    <a href="#" class="close" id="close_ques"><i class="fas fa-times"></i></a>
                </div>
                <div class="panel-content">
                    <div id="ques-message" style="display:none;"></div>
                    <form>
                        <div class="form-group mb-2">
                            <textarea class="form-control input" name="userques" id="userques" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" name="send_sesques" data-ses="<?= $sess_id ?>" data-user="<?= $userid ?>" class="send_sesques btn btn-sm btn-primary btn-sendmsg">Submit Question</button>
                        </div>
                    </form>
                    <div id="askedQues">
                        <div id="quesList" class="scroll">

                        </div>

                    </div>
                </div>

            </div>

            <!-- <div class="panel poll">
                <div class="panel-heading">
                    Take Poll
                    <a href="#" class="close" id="close_poll"><i class="fas fa-times"></i></a>
                </div>
                <div class="panel-content">
                    <div id="poll-message" style="display:none;"></div>
                    <div id="currpollid" style="display:none;">0</div>
                    <div id="currpoll" style="display:none;"></div>
                    <div id="currpollresults" style="display:none"></div>
                </div>
            </div> -->

        <?php
        }
        ?>
        <div id="bottom-menu">
            <?php require_once "bottom-navmenu.php" ?>
        </div>
    </div>
</div>
<?php require_once "commons.php" ?>
<?php require_once "scripts.php" ?>
<?php require_once "audi-common.php" ?>
<?php require_once "audi-script.php" ?>
<?php require_once "ga.php"; ?>
<?php require_once 'footer.php';  ?>