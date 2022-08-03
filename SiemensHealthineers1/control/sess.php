<?php
require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'checkforlive':
            $audi_id = $_POST['audiId'];

            $sess = new Session();
            $sess->__set('audi_id', $audi_id);

            $currLiveSess = $sess->getCurrLiveSession();

            if (empty($currLiveSess)) {
                echo '0';
            } else {
                echo $currLiveSess[0]['session_id'];
            }

            break;

        case 'getallsessions':

            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $sess = new Session();
            $sess->__set('limit', $total_records_per_page);
            $total_records = $sess->getSessionCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1

            $sessList = $sess->getSessionsList($offset);
            //var_dump($sessList);
            if (!empty($sessList)) {
?>
                <div id="message"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="100">Auditorium</th>
                            <th scope="col">Session Title</th>
                            <th scope="col" width="150">Actions</th>
                            <th scope="col" width="250">Session Status</th>
                            <th scope="col" width="150">Live Status</th>
                            <th scope="col" width="120"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessList as $a) { ?>
                            <tr>
                                <td><?= $a['audi_name'] ?></td>
                                <td><small><?php
                                            $date = date_create($a['start_time']);
                                            echo date_format($date, "M d, H:i a");
                                            ?></small><br><b><?= $a['session_title']; ?></b><br><small><?= $a['session_url']; ?></small></td>
                                <td>
                                    <a class="btn btn-warning" href="sess.php?ac=edit&id=<?= $a['session_id'] ?>"><i class="far fa-edit"></i></a> <a class="btn btn-danger" href="#" onClick="delSes('<?= $a['session_id'] ?>')"><i class="far fa-trash-alt"></i></a>
                                </td>
                                <td><select class="form-control" id="session<?= $a['session_id']; ?>" onChange="updSession('<?= $a['session_id']; ?>')">
                                        <option value="yet" <?php if ($a['session_status'] == 'yet') echo 'selected'; ?>>Yet to Start</option>
                                        <option value="next" <?php if ($a['session_status'] == 'next') echo 'selected'; ?>>Coming Up Next</option>
                                        <option value="live" <?php if ($a['session_status'] == 'live') echo 'selected'; ?>>LIVE Now</option>
                                        <option value="over" <?php if ($a['session_status'] == 'over') echo 'selected'; ?>>Completed</option>
                                    </select></td>
                                <td>
                                    <a href="#" onClick="updateLiveStatus('<?= $a['session_id']; ?>','<?= $a['live_status']; ?>')" class="btn btn-sm <?php if ($a['live_status'] == '1') {
                                                                                                                                                            echo 'btn-danger';
                                                                                                                                                        } else echo 'btn-success';  ?>"><?php if ($a['live_status'] == '1') {
                                                                                                                                                                                            echo 'Unmark';
                                                                                                                                                                                        } else echo 'Mark';  ?>
                                        Live</a>
                                </td>
                                <td>
                                    <input type="checkbox" <?= ($a['launch_status']) ? 'checked' : '' ?> class="form-check-inline" name="entry<?= $a['session_id'] ?>" id="entry<?= $a['session_id'] ?>" onClick="updEntry('<?= $a['session_id'] ?>')" /> Entry<br>
                                    <input type="checkbox" <?= ($a['show_session']) ? 'checked' : '' ?> class="form-check-inline" name="show<?= $a['session_id'] ?>" id="show<?= $a['session_id'] ?>" onClick="updShow('<?= $a['session_id'] ?>')" /> Show
                                </td>



                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

                <ul class="pagination">
                    <?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } 
                    ?>

                    <li class='page-item <?php if ($page_no <= 1) {
                                                echo "disabled";
                                            } ?>'>
                        <a class='page-link' <?php if ($page_no > 1) {
                                                    echo "onClick='gotoPage($previous_page)' href='#'";
                                                } ?>>Previous</a>
                    </li>

                    <?php
                    if ($total_no_of_pages <= 10) {
                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                            if ($counter == $page_no) {
                                echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                            } else {
                                echo "<li class='page-item'><a class='page-link' onClick='gotoPage($counter)' href='#'>$counter</a></li>";
                            }
                        }
                    } elseif ($total_no_of_pages > 10) {

                        if ($page_no <= 4) {
                            for ($counter = 1; $counter < 8; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' onClick='gotoPage($counter)' href='#'>$counter</a></li>";
                                }
                            }
                            echo "<li class='page-item'><a>...</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage($second_last)' href='#'>$second_last</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage($total_no_of_pages)' href='#'>$total_no_of_pages</a></li>";
                        } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage(1)' href='#'>1</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage(2)' href='#'>2</a></li>";
                            echo "<li class='page-item'><a>...</a></li>";
                            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' onClick='gotoPage($counter)' href='#'>$counter</a></li>";
                                }
                            }
                            echo "<li class='page-item'><a>...</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage($second_last)' href='#'>$second_last</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage($total_no_of_pages)' href='#'>$total_no_of_pages</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage(1)' href='#'>1</a></li>";
                            echo "<li class='page-item'><a class='page-link' onClick='gotoPage(2)' href='#'>2</a></li>";
                            echo "<li class='page-item'><a>...</a></li>";

                            for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                if ($counter == $page_no) {
                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' onClick='gotoPage($counter)' href='#'>$counter</a></li>";
                                }
                            }
                        }
                    }
                    ?>

                    <li class='page-item <?php if ($page_no >= $total_no_of_pages) {
                                                echo "disabled";
                                            } ?>'>
                        <a class='page-link' <?php if ($page_no < $total_no_of_pages) {
                                                    echo "onClick='gotoPage($next_page)' href='#'";
                                                } ?>>Next</a>
                    </li>
                    <?php if ($page_no < $total_no_of_pages) {
                        echo "<li class='page-item'><a class='page-link' onClick='gotoPage($total_no_of_pages)' href='#'>Last &rsaquo;&rsaquo;</a></li>";
                    } ?>
                </ul>
            <?php
            }

            break;

        case 'updatelivestatus':
            $session_id = $_POST['sessId'];
            $value = $_POST['value'];

            $value = (($value) ? 0 : 1);

            $sess = new Session();
            $sess->__set('session_id', $session_id);
            $sess->__set('live_status', $value);
            $status = $sess->updSessionLiveStatus();

            print_r(json_encode($status));

            break;

        case 'updsessstatus':
            $session_id = $_POST['sessId'];
            $status = $_POST['status'];

            $sess = new Session();
            $sess->__set('session_id', $session_id);
            $sess->__set('session_status', $status);

            $status = $sess->updSessionStatus();

            print_r(json_encode($status));

            break;

        case 'delsess':
            $sessid = $_POST['sessId'];
            $sess = new Session();
            $sess->__set('session_id', $sessid);
            $sessInfo = $sess->delSess();

            print_r(json_encode($sessInfo));

            break;

        case 'updateentrystatus':
            $session_id = $_POST['sessId'];
            $value = $_POST['value'];

            $sess = new Session();
            $sess->__set('session_id', $session_id);
            $sess->__set('launch_status', $value);
            $status = $sess->updSessionEntry();

            print_r(json_encode($status));

            break;

        case 'updateshowstatus':
            $session_id = $_POST['sessId'];
            $value = $_POST['value'];

            $sess = new Session();
            $sess->__set('session_id', $session_id);
            $sess->__set('show_session', $value);
            $status = $sess->updSessionShow();

            print_r(json_encode($status));

            break;

        case 'getSessions':
            $audi = $_POST['audiId'];
            $day = $_POST['day'];
            $date = '7th May 2021';
            if ($day == 'day2') {
                $date = '8th May 2021';
            }
            if ($day == 'day3') {
                $date = '9th May 2021';
            }
            $sess = new Session();
            $list = $sess->getWebcastSessions($audi, $day);
            //var_dump($list);

            if (!empty($list)) {
            ?>
                <table class="table table-striped agenda">
                    <tbody>

                        <?php
                        foreach ($list as $session) {
                            $status = $session['session_status'];
                            $date = date_create($session['start_time']);
                            $join_text = 'Watch Video';
                            $sessUrl = '?ses=' . $session['session_id'];
                        ?>
                            <tr>
                                <td>
                                    <!--<div class="status <?= $status ?>"><?= $status ?></div>
                                     <div class="date"><?= date_format($date, "H:i a") ?></div>
                                    <h4><?= $session['session_title'] ?></h4> -->
                                    <img src="assets/resources/<?= $session['session_desc'] ?>" class="img-fluid" alt="">

                                </td>
                                <td width="150">
                                    <?php
                                    if (($session['launch_status']) && (($session['session_status'] == 'live') || ($session['session_status'] == 'over'))) {
                                    ?>
                                        <a href="<?= $sessUrl ?>" class="btn btn-launch text-white"><?= $join_text ?></a>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            }

            break;

        case 'getLiveSessionViewerCount':
            $sessId = $_POST["sessId"];

            $session = new Session();
            $session->__set('session_id', $sessId);
            $count = $session->getLiveSessionViewerCount();
            echo $count;
            break;

        case 'getLiveSessionViewers':
            $sessId = $_POST["sessId"];

            $session = new Session();
            $session->__set('session_id', $sessId);
            $list = $session->getLiveSessionViewers();
            //var_dump($list);
            if (empty($list)) {
                echo 'No viewers.';
            } else {
            ?>
                <table class="table table-borderless table-striped">
                    <?php
                    foreach ($list as $a) {
                    ?>
                        <tr>
                            <td>
                                <?= $a['first_name'] . ' ' . $a['last_name'] ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            <?php
            }
            break;

        case 'getSessionQuestions':
            $sessId = $_POST["sessId"];

            $ques = new Question();
            $ques->__set('sessionid', $sessId);
            $quesList = $ques->getSessAllQuestions();
            //var_dump($quesList);
            if (empty($quesList)) {
                echo 'No questions.';
            } else {
            ?>
                <table class="table table-borderless table-striped">
                    <?php
                    foreach ($quesList as $a) {
                        $date = date_create($a['asked_at']);
                    ?>
                        <tr>
                            <td>
                                <small>
                                    <?= date_format($date, "d-M-Y, H:i a") ?>
                                </small><br>
                                <b><?= $a['first_name'] . ' ' . $a['last_name'] ?>:</b> <?= $a['question'] ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
<?php
            }

            break;
    }
}
