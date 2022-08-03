<?php
require_once '../functions.php';
require_once 'logincheck.php';

$errors = [];
$succ = '';

$session_id = '';
$poll_question = '';
$poll_opt1 = '';
$poll_opt2 = '';
$poll_opt3 = '';
$poll_opt4 = '';
$poll_opt5 = '';
$corr_ans = '0';


if ($_GET['ac'] == 'edit') {
  $pollId = $_GET['id'];
  $poll = new Poll();
  $poll->__set('poll_id', $pollId);
  $editPoll = $poll->getPoll();
  //var_dump($editPoll);
  if (empty($editPoll)) {
    header('location: polls.php');
  }

  $session_id = $editPoll[0]['session_id'];
  $poll_question = $editPoll[0]['poll_question'];
  $poll_opt1 = $editPoll[0]['poll_opt1'];
  $poll_opt2 = $editPoll[0]['poll_opt2'];
  $poll_opt3 = $editPoll[0]['poll_opt3'];
  $poll_opt4 = $editPoll[0]['poll_opt4'];
  $poll_opt5 = $editPoll[0]['poll_opt5'];
  $corr_ans = $editPoll[0]['correct_ans'];


  if (isset($_POST['btn-editPoll'])) {

    if ($_POST['editsessId'] == '0') {
      $errors['sess'] = 'Select Session';
    }
    if (empty($_POST['editpollQues'])) {
      $errors['ques'] = 'Poll Question is required';
    }
    if (empty($_POST['editpollOpt1'])) {
      $errors['opt1'] = 'Option 1 is required';
    }
    if (empty($_POST['editpollOpt2'])) {
      $errors['opt2'] = 'Option 2 is required';
    }


    $session_id = $_POST['editsessId'];
    $poll_question = $_POST['editpollQues'];
    $poll_opt1 = $_POST['editpollOpt1'];
    $poll_opt2 = $_POST['editpollOpt2'];

    if (isset($_POST['editpollOpt3'])) {
      $poll_opt3 = $_POST['editpollOpt3'];
    }
    if (isset($_POST['editpollOpt4'])) {
      $poll_opt4 = $_POST['editpollOpt4'];
    }
    if (isset($_POST['editpollOpt5'])) {
      $poll_opt5 = $_POST['editpollOpt5'];
    }

    $corr_ans = $_POST['editcorrAns'];

    if (count($errors) == 0) {
      $poll = new Poll();
      $poll->__set('poll_id', $pollId);
      $poll->__set('session_id', $session_id);
      $poll->__set('poll_ques', $poll_question);
      $poll->__set('poll_opt1', $poll_opt1);
      $poll->__set('poll_opt2', $poll_opt2);
      $poll->__set('poll_opt3', $poll_opt3);
      $poll->__set('poll_opt4', $poll_opt4);
      $poll->__set('poll_opt5', $poll_opt5);
      $poll->__set('corr_ans', $corr_ans);

      $upd = $poll->updatePoll();
      //var_dump($upd);
      $reg_status = $upd['status'];

      if ($reg_status == "success") {
        $succ = $upd['message'];
      } else {
        $errors['reg'] = $upd['message'];
      }
    }
  }
}


if (isset($_POST['btn-addPoll'])) {

  if ($_POST['sessId'] == '0') {
    $errors['sess'] = 'Select Session';
  }
  if (empty($_POST['pollQues'])) {
    $errors['ques'] = 'Poll Question is required';
  }
  if (empty($_POST['pollOpt1'])) {
    $errors['opt1'] = 'Option 1 is required';
  }
  if (empty($_POST['pollOpt2'])) {
    $errors['opt2'] = 'Option 2 is required';
  }


  $session_id = $_POST['sessId'];
  $poll_question = $_POST['pollQues'];
  $poll_opt1 = $_POST['pollOpt1'];
  $poll_opt2 = $_POST['pollOpt2'];

  if (isset($_POST['pollOpt3'])) {
    $poll_opt3 = $_POST['pollOpt3'];
  }
  if (isset($_POST['pollOpt4'])) {
    $poll_opt4 = $_POST['pollOpt4'];
  }
  if (isset($_POST['pollOpt5'])) {
    $poll_opt5 = $_POST['pollOpt5'];
  }

  $corr_ans = $_POST['corrAns'];

  if (count($errors) == 0) {

    $poll = new Poll();
    $poll->__set('session_id', $session_id);
    $poll->__set('poll_ques', $poll_question);
    $poll->__set('poll_opt1', $poll_opt1);
    $poll->__set('poll_opt2', $poll_opt2);
    $poll->__set('poll_opt3', $poll_opt3);
    $poll->__set('poll_opt4', $poll_opt4);
    $poll->__set('poll_opt5', $poll_opt5);
    $poll->__set('corr_ans', $corr_ans);

    $add = $poll->addPoll();
    //var_dump($add);
    $reg_status = $add['status'];

    if ($reg_status == "success") {
      $succ = $add['message'];
      $session_id = '';
      $poll_question = '';
      $poll_opt1 = '';
      $poll_opt2 = '';
      $poll_opt3 = '';
      $poll_opt4 = '';
      $poll_opt5 = '';
      $corr_ans = '0';
    } else {
      $errors['reg'] = $add['message'];
    }
  }
}
?>
<?php
require_once 'header.php';
require_once 'nav.php';
?>

<div class="container-fluid">
  <div class="row p-2">
    <div class="col-12 col-md-6 offset-md-3">
      <div class="card">
        <div class="card-header">
          <?= ($_GET['ac'] == 'add') ? 'Add Poll' : 'Edit Poll' ?>
        </div>
        <div class="card-body">
          <?php
          if (count($errors) > 0) : ?>
            <div class="alert alert-danger alert-msg">
              <ul class="list-unstyled">
                <?php foreach ($errors as $error) : ?>
                  <li>
                    <?php echo $error; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if ($succ != '') { ?>
            <div class="alert alert-success alert-msg">
              <?= $succ ?>
            </div>
          <?php } ?>

          <?php if ($_GET['ac'] == 'add') { ?>
            <form method="post" action="" class="form">
              <div class="form-group">
                <?php
                $sess = new Session();
                $sessList = $sess->getAllSessions();
                //var_dump($sessList);
                if (!empty($sessList)) {
                ?>
                  <select class="form-control" id="sessId" name="sessId" required>
                    <option value="0" <?php if ('0' == $session_id) {
                                        echo 'selected';
                                      } ?>>Select Session</option>
                    <?php
                    foreach ($sessList as $a) {
                    ?>
                      <option value="<?= $a['session_id'] ?>" <?php if ($a['session_id'] === $session_id) {
                                                                echo 'selected';
                                                              } ?>><?= $a['session_title'] ?></option>
                    <?php
                    }

                    ?>
                  </select>
                <?php
                } else {
                  header('location: sess.php?ac=add');
                }
                ?>
              </div>
              <div class="form-group">
                <textarea type="text" id="pollQues" name="pollQues" placeholder="Poll Question" class="form-control" rows="3"><?= $poll_question ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" id="pollOpt1" name="pollOpt1" placeholder="Option 1" class="form-control" value="<?= $poll_opt1 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="pollOpt2" name="pollOpt2" placeholder="Option 2" class="form-control" value="<?= $poll_opt2 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="pollOpt3" name="pollOpt3" placeholder="Option 3" class="form-control" value="<?= $poll_opt3 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="pollOpt4" name="pollOpt4" placeholder="Option 4" class="form-control" value="<?= $poll_opt4 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="pollOpt5" name="pollOpt5" placeholder="Option 5" class="form-control" value="<?= $poll_opt5 ?>">
              </div>
              <div class="form-group">
                <select name="corrAns" id="corrAns" class="form-control">
                  <option value="0" <?= ('0' == $corr_ans) ? 'selected' : '' ?>>Select Correct Answer</option>
                  <option value="opt1" <?= ('opt1' == $corr_ans) ? 'selected' : '' ?>>Option 1</option>
                  <option value="opt2" <?= ('opt2' == $corr_ans) ? 'selected' : '' ?>>Option 2</option>
                  <option value="opt3" <?= ('opt3' == $corr_ans) ? 'selected' : '' ?>>Option 3</option>
                  <option value="opt4" <?= ('opt4' == $corr_ans) ? 'selected' : '' ?>>Option 4</option>
                  <option value="opt5" <?= ('opt5' == $corr_ans) ? 'selected' : '' ?>>Option 5</option>
                </select>

              </div>


              <div class="form-group">
                <button class="btn btn-primary" type="submit" name="btn-addPoll">Add Poll</button>
              </div>
            </form>
          <?php }
          if ($_GET['ac'] == 'edit') { ?>
            <form method="post" action="" class="form">
              <div class="form-group">
                <?php
                $sess = new Session();
                $sessList = $sess->getAllSessions();
                //var_dump($sessList);
                if (!empty($sessList)) {
                ?>
                  <select class="form-control" id="editsessId" name="editsessId" required>
                    <option value="0" <?php if ('0' == $session_id) {
                                        echo 'selected';
                                      } ?>>Select Session</option>
                    <?php
                    foreach ($sessList as $a) {
                    ?>
                      <option value="<?= $a['session_id'] ?>" <?php if ($a['session_id'] === $session_id) {
                                                                echo 'selected';
                                                              } ?>><?= $a['session_title'] ?></option>
                    <?php
                    }

                    ?>
                  </select>
                <?php
                } else {
                  header('location: sess.php?ac=add');
                }
                ?>
              </div>
              <div class="form-group">
                <textarea type="text" id="editpollQues" name="editpollQues" placeholder="Poll Question" class="form-control" rows="3"><?= $poll_question ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" id="editpollOpt1" name="editpollOpt1" placeholder="Option 1" class="form-control" value="<?= $poll_opt1 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="editpollOpt2" name="editpollOpt2" placeholder="Option 2" class="form-control" value="<?= $poll_opt2 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="editpollOpt3" name="editpollOpt3" placeholder="Option 3" class="form-control" value="<?= $poll_opt3 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="editpollOpt4" name="editpollOpt4" placeholder="Option 4" class="form-control" value="<?= $poll_opt4 ?>">
              </div>
              <div class="form-group">
                <input type="text" id="editpollOpt5" name="editpollOpt5" placeholder="Option 5" class="form-control" value="<?= $poll_opt5 ?>">
              </div>
              <div class="form-group">
                <select name="editcorrAns" id="editcorrAns" class="form-control">
                  <option value="0" <?= ('0' == $corr_ans) ? 'selected' : '' ?>>Select Correct Answer</option>
                  <option value="opt1" <?= ('opt1' == $corr_ans) ? 'selected' : '' ?>>Option 1</option>
                  <option value="opt2" <?= ('opt2' == $corr_ans) ? 'selected' : '' ?>>Option 2</option>
                  <option value="opt3" <?= ('opt3' == $corr_ans) ? 'selected' : '' ?>>Option 3</option>
                  <option value="opt4" <?= ('opt4' == $corr_ans) ? 'selected' : '' ?>>Option 4</option>
                  <option value="opt5" <?= ('opt5' == $corr_ans) ? 'selected' : '' ?>>Option 5</option>
                </select>

              </div>


              <div class="form-group">
                <button class="btn btn-primary" type="submit" name="btn-editPoll">Update Poll</button>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>

    </div>

  </div>

</div>

<?php
require_once 'scripts.php';
?>
<?php
require_once 'footer.php';
?>