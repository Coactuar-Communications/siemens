<?php
require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'submitques':

            $sess_id = $_POST['sessId'];
            $user_id = $_POST['userId'];
            $question = $_POST['ques'];

            $ques = new Question();
            $ques->__set('sessionid', $sess_id);
            $ques->__set('userid', $user_id);
            $ques->__set('question', $question);
            $quesInfo = $ques->submitQues();

            print_r(json_encode($quesInfo));


            break;

        case 'getallquestions':
            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $ques = new Question();
            $ques->__set('limit', $total_records_per_page);
            $total_records = $ques->getQuesCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1
?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="50%">Total Ques: <div id="ques_count" style="display:inline-block;"><?php echo $total_records; ?></div>
                        </th>
                        <th scope="col" width="50%">
                            <div id="ques_update"></div>
                        </th>
                    </tr>
                </thead>
            </table>
            <?php
            $quesList = $ques->getQuestions($offset);
            //var_dump($quesList);

            if (!empty($quesList)) {
            ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Question</th>
                            <th scope="col" width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quesList as $a) { ?>
                            <tr>
                                <td><small><?php
                                            $date = date_create($a['asked_at']);
                                            echo date_format($date, "M d, H:i a");
                                            ?></small><br><b><?= $a['first_name'] . ' ' . $a['last_name'] ?>:</b> <?= $a['question']; ?></td>
                                <td>
                                    <a class="btn btn-danger" href="#" onClick="delQues('<?= $a['quesid'] ?>')"><i class="far fa-trash-alt"></i></a>
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
            } else {
                echo 'No questions asked yet.';
            }
            break;

        case 'delques':
            $quesid = $_POST['quesId'];
            $ques = new Question();
            $ques->__set('quesid', $quesid);
            $quesInfo = $ques->delQues();

            print_r(json_encode($quesInfo));

            break;

        case 'getquesupdate':
            $ques = new Question();
            $total_records = $ques->getquesupdate();

            echo $total_records;
            break;

        case 'getsessquestions':
            $sess_id = $_POST['sessId'];
            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $ques = new Question();
            $ques->__set('limit', $total_records_per_page);
            $ques->__set('sessionid', $sess_id);
            $total_records = $ques->getSessQuesCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1
            ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="50%">Total Ques: <div id="ques_count" style="display:inline-block;"><?php echo $total_records; ?></div>
                        </th>
                        <th scope="col" width="50%">
                            <div id="ques_update"></div>
                        </th>
                    </tr>
                </thead>
            </table>
            <?php
            $quesList = $ques->getSessQuestions($offset);
            //var_dump($quesList);

            if (!empty($quesList)) {
            ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Question</th>
                            <th scope="col" width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quesList as $a) { ?>
                            <tr>
                                <td><small><?php
                                            $date = date_create($a['asked_at']);
                                            echo date_format($date, "M d, H:i a");
                                            ?></small><br><b><?= $a['first_name'] . ' ' . $a['last_name'] ?>:</b> <?= $a['question']; ?></td>
                                <td>
                                    <a class="btn btn-danger" href="#" onClick="delQues('<?= $a['quesid'] ?>')"><i class="far fa-trash-alt"></i></a>
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
            } else {
                echo 'No questions asked yet.';
            }
            break;

        case 'getsessquesupdate':
            $ques = new Question();
            $ques->__set('sessionid', $_POST['sessId']);
            $total_records = $ques->getsessquesupdate();

            echo $total_records;
            break;
    }
}
