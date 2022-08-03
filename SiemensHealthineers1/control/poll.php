<?php
require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'getallpolls':

            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $poll = new Poll();
            $poll->__set('limit', $total_records_per_page);
            $total_records = $poll->getPollCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1

            $pollList = $poll->getPollsList($offset);
            //var_dump($pollList);
            if (!empty($pollList)) {
?>
                <div id="message"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="300">Session</th>
                            <th scope="col">Poll Question</th>
                            <th scope="col" width="470">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pollList as $a) { ?>
                            <tr>
                                <td><small><?php
                                            $date = date_create($a['start_time']);
                                            echo date_format($date, "M d, H:i a");
                                            ?></small><br><b><?= $a['session_title']; ?></b></td>
                                <td><?= $a['poll_question'] ?></td>
                                <td>
                                    <a class="btn btn-warning" href="poll.php?ac=edit&id=<?= $a['poll_id'] ?>"><i class="far fa-edit"></i></a> <a class="btn btn-danger" href="#" onClick="delPoll('<?= $a['poll_id'] ?>')"><i class="far fa-trash-alt"></i></a> <a href="#" onClick="updatePoll('<?= $a['poll_id']; ?>','<?= $a['session_id']; ?>','<?= $a['active']; ?>')" class="btn <?= ($a['active']) ? 'btn-danger' : 'btn-success'  ?>"><?= ($a['active']) ? 'Deactivate Poll' : 'Activate Poll' ?> </a> <a href=" pollresults.php?id=<?php echo $a['poll_id']; ?>" class="btn btn-info">View Results</a></td>




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

        case 'updatepoll':
            $poll_id = $_POST['pollId'];
            $session_id = $_POST['sessId'];
            $value = $_POST['value'];

            $value = (($value) ? 0 : 1);

            $poll = new Poll();
            $poll->__set('poll_id', $poll_id);
            $poll->__set('session_id', $session_id);
            $poll->__set('active', $value);
            $status = $poll->updateActivePoll();

            print_r(json_encode($status));

            break;

        case 'delpoll':
            $pollid = $_POST['pollId'];
            $poll = new Poll();
            $poll->__set('poll_id', $pollid);
            $pollInfo = $poll->delPoll();

            print_r(json_encode($pollInfo));

            break;

        case 'checknewpoll':
            $sess_id = $_POST['sessId'];

            $poll = new Poll();
            $poll->__set('session_id', $sess_id);
            $currPoll = $poll->getCurrSessionPoll();
            if (!empty($currPoll)) {
                echo $currPoll[0]['poll_id'];
            } else {
                echo '0';
            }

            break;
        case 'getpoll':
            $poll_id = $_POST['pollId'];
            $sess_id = $_POST['sessId'];
            $user_id = $_POST['userId'];

            $poll = new Poll();
            $poll->__set('poll_id', $poll_id);
            $poll->__set('session_id', $sess_id);
            $poll->__set('user_id', $user_id);
            $answer = $poll->isAnswered();
            if ($answer > 0) {
                echo '-1';
            } else {
                if ($poll_id != '0') {
                    $currPoll = $poll->getPoll();
                    //var_dump($currPoll);
                ?><form method="post">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        <?= $currPoll[0]['poll_question'] ?>
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $poll_opt[1] = $currPoll[0]['poll_opt1'];
                            $poll_opt[2] = $currPoll[0]['poll_opt2'];
                            $poll_opt[3] = $currPoll[0]['poll_opt3'];
                            $poll_opt[4] = $currPoll[0]['poll_opt4'];
                            $poll_opt[5] = $currPoll[0]['poll_opt5'];
                            ?>
                            <tbody>
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <?php if (!empty($poll_opt[$i])) { ?>
                                        <tr>
                                            <td width="20"><input type="radio" id="pollopt<?= $i ?>" name="pollopts" class="form-check-input" value="opt<?= $i ?>"></td>
                                            <td align="left"><?= $poll_opt[$i] ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button type="button" name="send_takepoll" data-user="<?= $user_id ?>" data-poll="<?= $currPoll[0]['poll_id'] ?>" class="send_takepoll btn-sendmsg btn btn-sm btn-primary">Submit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
            <?php
                }
            }

            break;

        case 'submitPollResp':
            $poll_id = $_POST['pollId'];
            $user_id = $_POST['userId'];
            $option = $_POST['answer'];

            $poll = new Poll();
            $poll->__set('poll_id', $poll_id);
            $poll->__set('user_id', $user_id);
            $poll->__set('answer', $option);
            $pollResp = $poll->submitResponse();

            if ($pollResp > 0) {
                echo $pollResp;
            } else {
                echo '0';
            }


            break;

        case 'getpollresults':

            $poll_id = $_POST['pollId'];
            $poll = new Poll();
            $poll->__set('poll_id', $poll_id);
            $resPoll = $poll->getPoll();
            $total_count = $poll->getAnsCount();

            $pollbg_color = ['#000', '#28a745', '#007bff', '#dc3545', '#ffc107', '#17a2b8'];

            $poll_opt[1] = $resPoll[0]['poll_opt1'];
            $poll_opt[2] = $resPoll[0]['poll_opt2'];
            $poll_opt[3] = $resPoll[0]['poll_opt3'];
            $poll_opt[4] = $resPoll[0]['poll_opt4'];
            $poll_opt[5] = $resPoll[0]['poll_opt5'];

            ?>
            <ul id="pollResults">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if (!empty($poll_opt[$i])) {
                        $optWidth = 0;
                        $optRes = $poll->getOptAnsCount('opt' . $i);
                        if ($total_count != '0') {
                            $optWidth = ($optRes / $total_count) * 100;
                        }
                    ?>
                        <li>
                            <div class="perc-back" style="width: <?= $optWidth ?>%; background-color: <?= $pollbg_color[$i] ?>;"></div>
                            <label for="answer1"><?= $poll_opt[$i] ?></label>
                            <div class="perc-number"><?= $optWidth ?>%</div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>

        <?php
            break;

        case 'showpollres':

            $poll_id = $_POST['pollId'];
            $poll = new Poll();
            $poll->__set('poll_id', $poll_id);
            $resPoll = $poll->getPoll();
            $total_count = $poll->getAnsCount();

            $pollbg_color = ['#000', '#28a745', '#007bff', '#dc3545', '#ffc107', '#17a2b8'];

            $poll_ques = $resPoll[0]['poll_question'];
            $poll_opt[1] = $resPoll[0]['poll_opt1'];
            $poll_opt[2] = $resPoll[0]['poll_opt2'];
            $poll_opt[3] = $resPoll[0]['poll_opt3'];
            $poll_opt[4] = $resPoll[0]['poll_opt4'];
            $poll_opt[5] = $resPoll[0]['poll_opt5'];

        ?>
            <div class="p-2"><b><?= $poll_ques ?></b></div>
            <ul id="pollResults">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if (!empty($poll_opt[$i])) {
                        $optWidth = 0;
                        $optRes = $poll->getOptAnsCount('opt' . $i);
                        if ($total_count != '0') {
                            $optWidth = ($optRes / $total_count) * 100;
                        }
                    ?>
                        <li>
                            <div class="perc-back" style="width: <?= $optWidth ?>%; background-color: <?= $pollbg_color[$i] ?>;"></div>
                            <label for="answer1"><?= $poll_opt[$i] ?></label>
                            <div class="perc-number"><?= $optWidth ?>%</div>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>

            <?php
            break;

        case 'getsesspolls':

            $sess_id = $_POST['sessId'];
            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $poll = new Poll();
            $poll->__set('limit', $total_records_per_page);
            $poll->__set('session_id', $sess_id);
            $total_records = $poll->getSessPollCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1

            $pollList = $poll->getSessPollsList($offset);
            //var_dump($pollList);
            if (!empty($pollList)) {
            ?>
                <div id="message"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Poll Question</th>
                            <th scope="col" width="470">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pollList as $a) { ?>
                            <tr>

                                <td><?= $a['poll_question'] ?></td>
                                <td>
                                    <a href=" pollresults.php?sess=<?= $sess_id ?>&id=<?php echo $a['poll_id']; ?>" class="btn btn-info">View Results</a></td>
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
    }
}
