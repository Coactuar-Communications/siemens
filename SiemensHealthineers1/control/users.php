<?php
//require_once "../inc/config.php";
require_once "../functions.php";

if (isset($_POST['action']) && !empty($_POST['action'])) {

    $action = $_POST['action'];

    switch ($action) {

        case 'getallusers':

            $page_no = $_POST['pagenum'];
            $offset = ($page_no - 1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;

            $member = new User();
            $member->__set('limit', $total_records_per_page);
            $total_records = $member->getUserCount();
            $total_online = $member->getOnlineMemberCount();

            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total page minus 1

            $usersList = $member->getAllUsers($offset);
            //var_dump($usersList);
            if (!empty($usersList)) {
?>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td scope="col"><b>Registered Users:</b> <?= $total_records ?></td>
                            <td scope="col"><b>Online Users:</b> <?= $total_online ?></td>
                            <td scope="col" align="right"><a class="download" href="regusers.php">Download Userlist <i class="fas fa-download"></i></a> <a class="download" href="onlineusers.php">Download Online Users <i class="fas fa-download"></i></a> <a class="download" href="uservisits.php">Download Logins Report <i class="fas fa-download"></i></a></td>
                        </tr>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" width="200">Name</th>
                            <th scope="col" width="280">Email ID</th>
                            <th scope="col" width="100">Phone No.</th>
                            <th scope="col" width="150">Organization</th>
                            <th scope="col" width="150">City</th>
                            <th scope="col">Registered On</th>
                            <th scope="col">Last Login</th>
                            <th scope="col">Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usersList as $a) {
                            $ev = new Event();
                        ?>
                            <tr>
                                <td><?= $a['title'] . ' ' .  $a['first_name'] . ' ' . $a['last_name'] ?></td>
                                <td style="max-width: 200px;"><?= $a['emailid'] ?></td>
                                <td><?= $a['phone_num'] ?></td>
                                <td><?= $a['organization'] ?></td>
                                <td><?= $a['city'] ?></td>
                                <td><?php
                                    $date = date_create($a['reg_date']);
                                    echo date_format($date, "M d, H:i a");
                                    ?></td>
                                <td><?php
                                    if ($a['login_date'] != '') {
                                        $date = date_create($a['login_date']);
                                        echo date_format($date, "M d, H:i a");
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?php
                                    $today = date("Y/m/d H:i:s");

                                    $dateTimestamp1 = strtotime($a['logout_date']);
                                    $dateTimestamp2 = strtotime($today);
                                    //echo $row[5];
                                    if ($dateTimestamp1 > $dateTimestamp2) {
                                        echo "Logged In";
                                        //$loggedin += 1; 
                                    } else {
                                        if ($a['logout_date'] != '') {
                                            $date = date_create($a['logout_date']);
                                            echo "Logged out at " . date_format($date, "M d, H:i a");
                                        } else {
                                            echo '-';
                                        }
                                    }
                                    ?>
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

        case 'getonlineattendees':

            $page_no = $_POST['pagenum'];
            $keyword = $_POST['key'];
            $user_id = $_POST['userId'];

            $user = new User();
            $total_records = $user->getOnlineMemberCount();
            $userList = $user->getOnlineMembers($keyword);
            //var_dump($userList);
            if (!empty($userList)) {
            ?>
                <table class="table table-striped">
                    <tbody>
                        <?php foreach ($userList as $a) { ?>
                            <tr>
                                <td><?= $a['first_name'] . ' ' . $a['last_name']; ?></td>
                                <td width="100">
                                    <?php
                                    if ($a['userid'] != $user_id) {
                                        /* $chat = new Chat();
                                        $chat->__set('user_id_to', $user_id);
                                        $chat->__set('user_id_from', $a['userid']);
                                        $unread = $chat->getUnreadChatCount(); */
                                    ?>
                                        <a href="#" class="btn-chat" data-to="<?php echo $a['userid']; ?>" data-from="<?php echo $user_id ?>"><i class="far fa-comment-alt"></i></a>
                                        <?php if ($unread > 0) { ?>
                                            <span class="badge badge-danger"><?= $unread ?></span>
                                        <?php } ?>
                                        <a href="#" class="btn-share" data-to="<?php echo $a['userid']; ?>" data-from="<?php echo $user_id ?>"><i class="fas fa-address-card"></i></a>
                                    <?php } ?>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            <?php
            }

            break;
        case 'getonlineusers':

            $keyword = '';

            $user = new User();
            $total_records = $user->getOnlineMemberCount();
            $userList = $user->getOnlineMembers($keyword);
            //var_dump($userList);
            if (!empty($userList)) {
            ?>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td scope="col"><b>Online Users:</b> <?= $total_records ?></td>
                            <td scope="col"><a href="onlineusers.php">Download Online Users</a></td>
                        </tr>
                </table>
                <table class="table table-striped">
                    <tbody>
                        <?php foreach ($userList as $a) { ?>
                            <tr>
                                <td><?= $a['first_name'] . ' ' . $a['last_name']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

            <?php
            }

            break;

        case 'getAttendeeName':
            $user_id = $_POST['userId'];
            $user = new User();
            $user->__set('user_id', $user_id);
            $name = $user->getUserName();
            echo $name;
            break;
        case 'deluser':
            $user_id = $_POST['userId'];
            $user = new User();
            $user->__set('user_id', $user_id);
            $status = $user->delUser();
            echo $status;
            break;

        case 'getchathistory':
            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];
            $user_id = $_POST["userId"];

            /* $chat = new Chat();
            $chat->__set('user_id_from', $user_from_id);
            $chat->__set('user_id_to', $user_to_id);
            $chatHistory = $chat->getUserChatHistory(); */
            //var_dump($chatHistory);
            if (!empty($chatHistory)) {
            ?>
                <table class="table table-borderless table-striped">
                    <?php
                    foreach ($chatHistory as $c) {
                        $user_name = '';
                        $user_class = '';
                        if ($c['user_id_from'] == $user_id) {
                            $user_name = 'You';
                            $user_class = 'me';
                        } else {
                            $user_name = $c['first_name'];
                            $user_class = 'you';
                        }
                    ?>
                        <tr>
                            <td><b><?= $user_name ?>:</b> <?= $c['message'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            <?php
            }
            $chat->__set('user_id_from', $user_to_id);
            $chat->__set('user_id_to', $user_from_id);
            $status = $chat->updateChatReadStatus();
            break;

        case 'shareCard':

            $user_to_id = $_POST["to"];
            $user_from_id = $_POST["from"];

            $member = new User();
            $member->__set('user_from', $user_from_id);
            $member->__set('user_to', $user_to_id);
            $card = $member->shareCard();
            if ($card > 0) {
                echo 'done';
            } else {
                echo $card;
            }
            break;

        case 'getsharedcards':
            $user_to_id = $_POST["to"];

            $member = new User();
            $member->__set('user_to', $user_to_id);
            $cards = $member->getCards();

            //var_dump($cards);
            if (!empty($cards)) {
            ?>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?php
                        foreach ($cards as $card) {
                        ?>
                            <div class="e-card">
                                <div class="ecard">
                                    <div class="name"><?= $card['first_name'] . ' ' . $card['last_name'] ?></div>
                                    <div class="info2">
                                        <b>Email:</b> <?= $card['emailid'] ?><br>
                                        <b>Mobile:</b> <?= $card['phone_num'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mt-2 bg-dark p-2">
                    <div class="col-12 text-center">
                        <a href="ecarddl.php?u=<?= $user_to_id ?>">Download Cards</a>
                    </div>
                </div>
<?php

            }


            break;
    }
}
