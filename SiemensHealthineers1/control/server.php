<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';
require_once "config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'getcountries':
            $curr = '0'; //$_POST['cntry'];
            $sql = "SELECT * FROM countries";
            $res = mysqli_query($link, $sql);
            $output = '<select class="input" id="country" name="country" onChange="updateState()" required>';
            $output .= '<option value="0">Select Country</option>';
            while ($data = mysqli_fetch_assoc($res)) {
                $sel = '';
                if ($curr == $data['id']) {
                    $sel = 'selected';
                }
                $output .= '<option value="' . $data['id'] . '" ' . $sel . '>' . iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $data['name']) . '</option>';
            }
            $output .= '</select>';

            echo $output;

            break;

        case 'getstates':
            $sql = "SELECT * FROM states where country_id='" . $_POST['country'] . "' order by name";
            $res = mysqli_query($link, $sql);
            $output = '<select class="input" id="state" name="state" onChange="updateCity()" required>';
            $output .= '<option value="0">Select State</option>';
            while ($data = mysqli_fetch_assoc($res)) {
                $output .= '<option value="' . $data['id'] . '">' . iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $data['name']) . '</option>';
            }
            $output .= '</select>';

            echo $output;

            break;

        case 'getcities':
            $sql = "SELECT * FROM cities where state_id='" . $_POST['state'] . "' order by name";
            $res = mysqli_query($link, $sql);
            $output = '<select class="input" id="city" name="city" required>';
            $output .= '<option value="0">Select City</option>';
            while ($data = mysqli_fetch_assoc($res)) {
                $output .= '<option value="' . $data['id'] . '">' . iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $data['name']) . '</option>';
            }
            $output .= '</select>';

            echo $output;

            break;

        case 'update-event':

            $userid = $_SESSION['user_id'];
            $room = $_POST['room'];

            $member = new User();
            $status = $member->updateMemberLoginStatus($userid, $room);
            echo $status;

            break;

        case 'update-exh':

            $user_id = $_SESSION['user_id'];
            $exhibitor_id = $_POST['hallId'];

            $halls = new Hall();
            $update = $halls->updateMemberExhStatus($exhibitor_id, $user_id);

            break;

        case 'gettotatt':

            $member = new User();
            $total_attendees = $member->getTotalAttendees();
            //var_dump($total_live_attendees);

            echo '<b>Total Visitors:</b> ' . $total_attendees;

            break;

        case 'getliveatt':

            $member = new User();
            $total_live_attendees = $member->getLiveAttendeesCount();

            echo '<b>Online Attendees:</b> ' . $total_live_attendees;

            break;

        case 'getonpageatt':

            $cur_room = $_POST["room"];

            $member = new User();
            $total_onthispage = $member->getPageAttendeesCount($cur_room);

            echo '<b>In this room:</b> ' . $total_onthispage;

            break;

        case 'getOnlineAttendees':
            $today   = date('Y/m/d H:i:s');
            $you = $_SESSION["user_id"];
            $keyword = $_POST['key'];

            $member = new User();
            $online = $member->getonlinemembers($keyword);

            if (!empty($online)) {
                foreach ($online as $user) {
?>

                    <div class="attendee">
                        <div class="name"><?php echo $name = $user['first_name'] . ' ' . $user['last_name']; ?></div>
                        <?php if ($user['userid'] != $you) { ?>
                            <div class="attendee-actions">
                                <button type="button" class="btn-chat" data-to="<?php echo $user['userid']; ?>" data-from="<?php echo $_SESSION['user_id']; ?>"><i class="far fa-comment-alt"></i></button>
                                <!--        <button type="button" class="btn-email" data-to="<?php echo $user['userid']; ?>" data-from="<?php echo $_SESSION['user_id']; ?>"><i class="far fa-envelope"></i></button>
-->
                            </div>
                        <?php } ?>
                    </div>
                <?php
                }
            }

            break;

        case 'getAttendeeName':

            $user_id = $_POST["user"];

            $member = new User();
            $name = $member->getMemberName($user_id);

            echo $name;


            break;

        case 'checknewchat':
            $to_id = $_POST['to'];

            $member = new User();
            $unread = $member->getMemberTotalUnreadChatCount($to_id);

            echo $unread;

            break;

        case 'getAttendeesChat':

            $user_to_id = $_POST["to"];

            $member = new User();
            $chats = $member->getMemberChats($user_to_id);
            //var_dump($chats);
            $output = '';

            if (!empty($chats)) {
                $output = "<ul class='list-unstyled'>";
                foreach ($chats as $chat) {
                ?>
                    <div class="attendee">
                        <div class="name"><?php echo $chat['first_name'] . ' ' . $chat['last_name']; ?></div>
                        <div class="attendee-actions">
                            <button type="button" class="btn-chat" data-to="<?php echo $chat['user_id_from']; ?>" data-from="<?php echo $user_to_id; ?>"><i class="far fa-comment-alt"></i></button>
                            <?php
                            $from = $chat['user_id_from'];
                            $unread = $member->getMemberUnreadChatCount($from, $user_to_id);
                            if ($unread != 0) {
                                echo '<span class="badge badge-danger">' . $unread . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                $output .= "</ul>";
            } else {
                $output = 'You have not interacted with anyone yet.';
            }

            echo $output;


            break;

        case 'getAttendeesDown':

            $user_id = $_POST["to"];

            $member = new User();
            $downloads = $member->getMemberDownloads($user_id);

            $output = '';
            if (!empty($downloads)) {
                $output = "<ul>";
                foreach ($downloads as $dl) {

                    $output .= "<li><b>" . $dl['exhibitor_name'] . '</b> - ' . $dl['resource_title'] . "</li>";
                }
                $output .= "</ul>";
            } else {
                $output = 'You have not downloaded anything yet.';
            }
            echo $output;

            break;

        case 'getAttendeesVideos':

            $user_id = $_POST["to"];

            $member = new User();
            $videos = $member->getMemberVideos($user_id);

            $output = '';
            if (!empty($videos)) {

                $output = "<ul>";
                foreach ($videos as $video) {

                    $output .= "<li><b>" . $video['exhibitor_name'] . '</b> - ' . $video['video_title'] . "</li>";
                }
                $output .= "</ul>";
            } else {
                $output = 'You have not viewed any videos yet.';
            }

            echo $output;


            break;

        case 'getSessions':
            $today   = date('Y/m/d H:i:s');
            $keyword = $_POST['key'];
            $audi_id = $_POST['audiId'];
            $day = $_POST['day'];

            $ses = new Session();
            $audiSessList = $ses->getAudiSessionList($audi_id, $day, $keyword);

            if (!empty($audiSessList)) {
                ?>
                <table class="table">
                    <?php
                    foreach ($audiSessList as $session) {
                    ?>
                        <tr class="session">
                            <td class="ses_details">
                                <div class="ses_time">
                                    <?php
                                    $date = date_create($session['start_time']);
                                    echo date_format($date, "h:i A");
                                    ?>
                                </div>
                                <h4><?php echo $session['session_title']; ?></h4>
                                <div class="ses_short"><?php echo nl2br($session['session_short']); ?></div>
                                <div class="ses_long"><?php echo nl2br($session['session_long']); ?></div>
                            </td>
                            <td class="ses_status">
                                <?php if ($session['session_webcast_url'] != '') { ?>
                                    <div class="ses_launch">
                                        <?php if ($session['launch_status'] == '0') { ?>
                                            <a href="#" class="disabled">Join</a>
                                        <?php } else { ?>
                                            <a href="?ses=<?php echo $session['session_id']; ?>" class="btn-launch">Join</a>
                                        <?php } ?>
                                    </div>
                                    <div class="status">
                                        <?php
                                        //echo $data['session_status'];
                                        switch ($session['session_status']) {

                                            case 'live':
                                                echo '<img src="img/status_live.png" class="img-fluid w-50" alt=""/>';
                                                break;

                                            case 'next':
                                                echo '<div class="next blinking">NEXT</div>'; //'<img src="img/status_live.png" class="img-fluid" alt=""/>';
                                                break;

                                            case 'over':
                                                echo '<div class="over">Completed</div>'; //'<img src="img/status_live.png" class="img-fluid" alt=""/>';
                                                break;

                                            default:
                                                echo '';
                                                break;
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            <?php
            }

            break;

        case 'sendTeamMessage':

            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];
            $from_src = $_POST["source"];
            $message = $_POST["msg"];

            $member = new User();
            $chat = $member->sendMsgTeam($user_to_id, $user_from_id, $message, $from_src);

            break;

        case 'getteamchathistory':

            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];
            $output = '';

            $member = new User();
            $chatHistory = $member->getTeamChatHistory($user_to_id, $user_from_id);

            if (!empty($chatHistory)) {
                $output = '<ul class="list-unstyled">';
                foreach ($chatHistory as $chat) {
                    $user_name = '';
                    $user_class = '';
                    if ($chat['user_id_from'] == $_SESSION['user_id']) {
                        $user_name = 'You';
                        $user_class = 'me';
                    } else {
                        $user_name = 'Team Integrace';
                        $user_class = 'team';
                    }
                    $output .= '<li class="' . $user_class . '"><b>' . $user_name . '</b>: ' . $chat['message'] . '</li>';
                }
                $output .= "</ul>";
            }

            $member->updateTeamReadStatus($user_to_id, $user_from_id);

            echo $output;


            break;

        case 'checkforlive':
            $curr = $_POST['sessId'];
            $audi = $_POST['audiId'];

            $sess = new Session();
            $curr_sess = $sess->getCurrLiveSession($audi);

            $live_sess = 0;
            $goLive = 0;

            if (!empty($curr_sess)) {
                //if (!empty($curr_sess)) {
                $live_sess = $curr_sess[0]['session_id'];
            }
            echo $live_sess;
            //var_dump($curr_sess);
            //0-stay where u are
            //1-go to live session
            //-1 go to home audi.
            //echo $live_sess;

            if ($curr == $live_sess) {
                $goLive = 0;
            } else
            if (($curr == 0) && ($live_sess != 0)) {
                $goLive = $live_sess; // 1
            } else
            if (($curr != 0) && ($live_sess == 0)) {
                $goLive = -1;
            }


            //echo $goLive;


            break;

        case 'updatesession':

            ///$today=date('Y/m/d H:i:s', time());
            $user_id = $_SESSION["user_id"];
            $sesId = $_POST['sessId'];

            if ($sesId != '0') {
                echo 'updating session';
                $sess = new Session();
                $status = $sess->updateMemberSessionStatus($sesId, $user_id);
            }

            /*$sql = "select * from tbl_sessions_attendee where session_id = '$sesId' and user_id='$user_id' and leave_time >= '$today' limit 1";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $row_cnt = mysqli_num_rows($result);
            //echo $row_cnt;
            if($row_cnt > 0)
            {
                $leave_time  = date('Y/m/d H:i:s', time() + 10);
                $query="UPDATE tbl_sessions_attendee set leave_time='$leave_time' where user_id='$user_id' and session_id='$sesId' and leave_time >= '$today'";
                $res = mysqli_query($link, $query) or die(mysqli_error($link));
                
            }
            else{
                $join_time  = date('Y/m/d H:i:s', time());
                $leave_time  = date('Y/m/d H:i:s', time() + 10);
                $query="Insert into tbl_sessions_attendee(session_id, user_id, join_time, leave_time) values('$sesId','$user_id','$join_time','$leave_time')";
                $res = mysqli_query($link, $query) or die(mysqli_error($link));
                
            }*/

            break;

        case 'askquestion':

            $user_id = $_POST["userid"];
            $sess_id = $_POST['sessid'];
            $question = $_POST['ques'];

            $sess = new Session();
            $ques = $sess->askQuestion($sess_id, $user_id, $question);

            if ($ques > 0) {
                echo '1';
            }


            break;

        case 'checknewpoll':
            $sess_id = $_POST['sessId'];

            $poll = new Poll();
            if ($sess_id != '0') {
                $new_poll = $poll->currPoll($sess_id);
                //var_dump($new_poll);
                //$poll_id = 0;
                if (!empty($new_poll)) {
                    $poll_id = $new_poll[0]['poll_id'];
                    echo $poll_id;
                } else {
                    echo '0';
                }
            } else {
                echo '0';
            }


            break;

        case 'updatepoll':

            $output = ''; //'<i>No polls are available right now.</i>';
            $sess_id = $_POST['sessId'];
            $poll = new Poll();
            $new_poll = $poll->currPoll($sess_id);
            if (!empty($new_poll)) {
                $poll_id = $new_poll[0]['poll_id'];
                $user_id = $_SESSION['user_id'];
                $status = $poll->isAnswered($poll_id, $user_id);
                if ($status > 0) {
                    $output = "<div class='alert alert-danger alert-msg'>You have already taken the current poll. Please wait for the next poll.</div>";
                } else {
                    $output = '<form method="post">
                            <div class="form-group">
                                <h6>Q. ' . $new_poll[0]['poll_question'] . '</h6>
                            </div>
                            <div class="form-group">
                              <div class="custom-control custom-radio">
                                <input type="radio" id="pollopt1" name="pollopts" class="custom-control-input" value="opt1">
                                <label class="custom-control-label" for="pollopt1">' . $new_poll[0]['poll_opt1'] . '</label>
                              </div>
                              <div class="custom-control custom-radio">
                                <input type="radio" id="pollopt2" name="pollopts" class="custom-control-input" value="opt2">
                                <label class="custom-control-label" for="pollopt2">' . $new_poll[0]['poll_opt2'] . '</label>
                              </div>';
                    if ($new_poll[0]['poll_opt3'] != '') {
                        $output .= '<div class="custom-control custom-radio">
                                <input type="radio" id="pollopt3" name="pollopts" class="custom-control-input" value="opt3">
                                <label class="custom-control-label" for="pollopt3">' . $new_poll[0]['poll_opt3'] . '</label>
                              </div>';
                    }
                    if ($new_poll[0]['poll_opt4'] != '') {
                        $output .= '<div class="custom-control custom-radio">
                                <input type="radio" id="pollopt4" name="pollopts" class="custom-control-input" value="opt4">
                                <label class="custom-control-label" for="pollopt4">' . $new_poll[0]['poll_opt4'] . '</label>
                              </div>';
                    }
                    if ($new_poll[0]['poll_opt5'] != '') {
                        $output .= '<div class="custom-control custom-radio">
                                <input type="radio" id="pollopt5" name="pollopts" class="custom-control-input" value="opt5">
                                <label class="custom-control-label" for="pollopt5">' . $new_poll[0]['poll_opt5'] . '</label>
                              </div>
                            </div>';
                    }
                    $output .= '<div class="form-group mt-2">
                                <button type="button" name="send_takepoll" data-user="' . $_SESSION['user_id'] . '" data-poll="' . $new_poll[0]['poll_id'] . '" class="send_takepoll btn-sendmsg">Submit</button>
                            </div>
                        </form>';
                }
            } else {
                $output = ''; //'<i>No polls are available right now.</i>';
            }

            echo $output;


            break;


        case 'checkpollres':
            $sess_id = $_POST['sessId'];

            $poll = new Poll();
            $poll_res = $poll->showPollRes($sess_id);

            $output = '';
            //var_dump($poll_res);

            if (!empty($poll_res)) {
                $poll_id    = $poll_res[0]['poll_id'];
                $poll_ques  = $poll_res[0]['poll_question'];
                $poll_opt1  = $poll_res[0]['poll_opt1'];
                $poll_opt2  = $poll_res[0]['poll_opt2'];
                $poll_opt3  = $poll_res[0]['poll_opt3'];
                $poll_opt4  = $poll_res[0]['poll_opt4'];
                $corrans    = $poll_res[0]['correct_ans'];

                $pollAnsCount = $poll->getAnsCount($poll_id);
                $total_count = $pollAnsCount;

                echo "<h6>" . $poll_ques . "</h6>";

                $opt1Res = $poll->getOptAnsCount($poll_id, 'opt1');
                $opt1_count = $opt1Res;
                $opt1width = 0;
                if ($total_count != '0') {
                    $opt1width = ($opt1_count / $total_count) * 100;
                }
                $ans = '';
                if ($corrans == 'opt1') {
                    $ans = 'corrans';
                }
            ?>
                <div class="option mt-1"> <strong><?php echo $poll_opt1; ?></strong><span class="float-right badge badge-success"><?php echo round($opt1width, 1) . '%'; ?></span>
                    <div class="progress <?php echo $ans; ?>">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: <?php echo $opt1width; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <?php
                $opt2Res = $poll->getOptAnsCount($poll_id, 'opt2');
                $opt2_count = $opt2Res;
                $opt2width = 0;
                if ($total_count != '0') {
                    $opt2width = ($opt2_count / $total_count) * 100;
                }
                $ans = '';
                if ($corrans == 'opt2') {
                    $ans = 'corrans';
                }
                ?>
                <div class="option mt-1"> <strong><?php echo $poll_opt2; ?></strong><span class="float-right  badge badge-primary"><?php echo round($opt2width, 1) . '%'; ?></span>
                    <div class="progress <?php echo $ans; ?>">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: <?php echo $opt2width; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <?php
                if ($poll_opt3 != '') {
                    $opt3Res = $poll->getOptAnsCount($poll_id, 'opt3');
                    $opt3_count = $opt3Res;
                    $opt3width = 0;
                    if ($total_count != '0') {
                        $opt3width = ($opt3_count / $total_count) * 100;
                    }
                    $ans = '';
                    if ($corrans == 'opt3') {
                        $ans = 'corrans';
                    }
                ?>
                    <div class="option mt-1"> <strong><?php echo $poll_opt3; ?></strong><span class="float-right badge badge-warning"><?php echo round($opt3width, 1) . '%'; ?></span>
                        <div class="progress <?php echo $ans; ?>">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: <?php echo $opt3width; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php
                }
                if ($poll_opt4 != '') {
                    $opt4Res = $poll->getOptAnsCount($poll_id, 'opt4');
                    $opt4_count = $opt4Res;
                    $opt4width = 0;
                    if ($total_count != '0') {
                        $opt4width = ($opt4_count / $total_count) * 100;
                    }
                    $ans = '';
                    if ($corrans == 'opt4') {
                        $ans = 'corrans';
                    }
                ?>
                    <div class="option mt-1"> <strong><?php echo $poll_opt4; ?></strong><span class="float-right badge badge-danger"><?php echo round($opt4width, 1) . '%'; ?></span>
                        <div class="progress <?php echo $ans; ?>">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: <?php echo $opt4width; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php
                }
                if ($poll_opt5 != '') {
                    $opt5Res = $poll->getOptAnsCount($poll_id, 'opt5');
                    $opt5_count = $opt5Res;
                    $opt5width = 0;
                    if ($total_count != '0') {
                        $opt5width = ($opt5_count / $total_count) * 100;
                    }
                    $ans = '';
                    if ($corrans == 'opt5') {
                        $ans = 'corrans';
                    }
                ?>
                    <div class="option mt-1"> <strong><?php echo $poll_opt5; ?></strong><span class="float-right badge badge-info"><?php echo round($opt5width, 1) . '%'; ?></span>
                        <div class="progress <?php echo $ans; ?>">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: <?php echo $opt5width; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
<?php
                }
            } else {
                //echo '0';
            }


            echo $output;


            break;

        case 'submitPollResp':
            $poll_id = $_POST['pollId'];
            $user_id = $_POST['userId'];
            $option = $_POST['answer'];

            //$poll_time   = date('Y/m/d H:i:s');
            //$query="insert into tbl_pollanswers(poll_id, user_id,poll_answer, poll_at) values(?,?,?,?)";
            $poll = new Poll();
            $pollResp = $poll->submitResponse($poll_id, $user_id, $option);

            if ($pollResp > 0) {
                echo $pollResp;
            } else {
                echo '0';
            }


            break;

        case 'sendMessage':

            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];
            $message = $_POST["msg"];

            $chat = 0;
            if (!empty($message)) {
                $member = new User();
                $chat = $member->sendChatToUser($user_from_id, $user_to_id, $message);
            }

            echo $chat;
            /*$query="";
        $res = mysqli_query($link, $query) or die(mysqli_error($link));
        */
            break;

        case 'getchathistory':

            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];

            $member = new User();
            $chatHistory = $member->getUserChatHistory($user_to_id, $user_from_id);
            //var_dump($chatHistory);
            /*$query="select first_name, message, user_id_from, chat_time from tbl_attendee_chat,tbl_users where ((user_id_from='$user_from_id' AND user_id_to ='$user_to_id') OR (user_id_from='$user_to_id' AND user_id_to='$user_from_id')) AND tbl_attendee_chat.user_id_from = tbl_users.id order by chat_time desc";
            $res = mysqli_query($link, $query) or die(mysqli_error($link));*/
            $output = "<ul class='list-unstyled'>";
            if (!empty($chatHistory)) {
                foreach ($chatHistory as $chat) {
                    $user_name = '';
                    $user_class = '';
                    if ($chat['user_id_from'] == $_SESSION['user_id']) {
                        $user_name = 'You';
                        $user_class = 'me';
                    } else {
                        $user_name = $chat['first_name'];
                        $user_class = 'you';
                    }
                    //            $output.="<li class='$user_class border-bottom'><i><small>".$data['chat_time']."</small></i><br><b>".$user_name."</b>: ".$data['message']."</li>";   
                    $output .= "<li class='$user_class'><b>" . $user_name . "</b>: " . $chat['message'] . "</li>";
                }
            }
            $output .= "</ul>";

            $sql = "update tbl_attendee_chat set read_status = '1' where user_id_to ='$user_from_id' and user_id_from ='$user_to_id'";
            $r = mysqli_query($link, $sql);
            //echo $sql;
            echo $output;


            break;

        case 'updateFileDLCount':

            $res_id = $_POST["resid"];
            $user_id = $_SESSION['user_id'];
            $dl_time   = date('Y/m/d H:i:s');

            $halls = new Hall();
            $resupd = $halls->updateResDL($res_id, $user_id, $dl_time);


            /*$sql = "select download_count from tbl_exhibitor_resources where id='$res_id'";
        $res = mysqli_query($link, $sql) or die(mysqli_error($link));
        $d = mysqli_fetch_assoc($res);
        $dl_count = $d['download_count'] + 1;

		
        $query="UPDATE tbl_exhibitor_resources set download_count='$dl_count' where id='$res_id'";
        $res = mysqli_query($link, $query) or die(mysqli_error($link));

        $query="Insert into tbl_exhibitor_resource_downloads(res_id, attendee_id, dl_time) values('$res_id','$user_id','$dl_time')";
        $res = mysqli_query($link, $query) or die(mysqli_error($link));
        
        //echo $dl_count;    */

            break;

        case 'updateVideoView':

            $videoid = $_POST["vidid"];
            $userid = $_SESSION['user_id'];
            $viewtime   = date('Y/m/d H:i:s');

            $halls = new Hall();
            $vidupd = $halls->updateVideoView($videoid, $userid, $viewtime);

            break;



        case 'samplereq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Samples');

            echo $req;

            break;

        case 'productreq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Products');

            echo $req;

            break;

        case 'litreq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Literature');

            echo $req;

            break;

        case 'videoreq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Video');

            echo $req;

            break;

        case 'demoreq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Demo Call');

            echo $req;

            break;

        case 'appreq':

            $exhId = $_POST["exhId"];
            $userid = $_POST['userId'];

            $halls = new Hall();
            $req = $halls->subRequests($exhId, $userid, 'Telemedicine');

            echo $req;

            break;


        case 'getLiveSessionViewerCount':

            $sessId = $_POST["sessId"];

            $session = new Session();
            $count = $session->getLiveSessionViewerCount($sessId);

            echo $count;

            break;

        case 'getLiveSessionViewers':

            $sessId = $_POST["sessId"];

            $session = new Session();
            $viewers = $session->getLiveSessionViewers($sessId);

            $output = 'No viewers.';
            if (!empty($viewers)) {
                $output = '<ul class="sessviewers list-unstyled">';
                foreach ($viewers as $viewer) {
                    $name = $viewer['first_name'] . ' ' . $viewer['last_name'];
                    $loc = $viewer['city'] . ', ' . $viewer['state'];
                    $output .= '<li>' . $name . '(' . $loc . ')' . '</li>';
                }
                $output .= '</ul>';
            }
            echo $output;
            break;

        case 'getSessionQuestions':

            $sessId = $_POST["sessId"];

            $session = new Session();
            $questions = $session->getSessionQuestions($sessId);
            $output = 'No questions.';
            if (!empty($questions)) {
                $output = '<ul class="sessques list-unstyled">';
                foreach ($questions as $ques) {
                    $name = $ques['first_name'] . ' ' . $ques['last_name'];
                    $ques_time = new DateTime($ques['asked_at']);
                    $output .= '<li><div class="ques_time">' . date_format($ques_time, "M d Y, H:i:s a") . '</div>
                    <b>' . $name . ':</b> ' . $ques['question'] . '</li>';
                }
                $output .= '</ul>';
            }
            echo $output;

            break;

        case 'sendrememail':

            $emailId = $_POST['emailId'];

            $mail = new PHPMailer(true);

            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'support@coact.co.in';                     // SMTP username
            $mail->Password   = 'coact2020';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('support@coact.co.in', 'AMITY');
            $mail->addAddress($emailId);     // Add a recipient

            // Content
            $mail->isHTML(true);
            $message = file_get_contents('https://coact.live/pharma/email_templates/rem-email.html');

            $mail->Subject = 'AMITY';
            $mail->MsgHTML($message);
            //$mail->AddAttachment("https://coact.live/pharma/resources/BOOT_Agenda%20-%20Final.pdf");

            if ($mail->send()) {
                echo 'mail sent to ' . $emailId;
            } else {
                echo 'mail not sent to ' . $emailId;
            }

            break;
    }
}




?>