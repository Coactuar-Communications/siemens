<?php
require_once '../functions.php';
require_once 'logincheck.php';

$errors = [];
$succ = '';

$session_id = '';
$audi_id = 0;
$start_time = '';
$session_title = '';
$session_desc = '';
$session_url = '';


if ($_GET['ac'] == 'edit') {
  $sessId = $_GET['id'];
  $sess = new Session();
  $sess->__set('session_id', $sessId);
  $editSess = $sess->getSession();
  //var_dump($editSess);
  if (empty($editSess)) {
    header('location: sessions.php');
  }

  $audi_id = $editSess[0]['audi_id'];
  $session_id = $editSess[0]['session_id'];
  $start_time = $editSess[0]['start_time'];
  $session_title = $editSess[0]['session_title'];
  $session_desc = $editSess[0]['session_desc'];
  $session_url = $editSess[0]['session_url'];


  if (isset($_POST['btn-editSess'])) {

    if (empty($_POST['editaudiId'])) {
      $errors['audi'] = 'Auditorium Name is required';
    }
    if (empty($_POST['editsessStart'])) {
      $errors['start'] = 'Start Time is required';
    }

    if (empty($_POST['editsessTitle'])) {
      $errors['title'] = 'Session Title is required';
    }

    $audi_id = $_POST['editaudiId'];
    $start_time = $_POST['editsessStart'];
    $session_title = $_POST['editsessTitle'];
    if (isset($_POST['editsessUrl'])) {
      $session_url = $_POST['editsessUrl'];
    }

    if (isset($_POST['editsessDesc'])) {
      $session_desc = $_POST['editsessDesc'];
    }

    if (count($errors) == 0) {
      $sess = new Session();
      $sess->__set('audi_id', $audi_id);
      $sess->__set('session_id', $session_id);
      $sess->__set('session_title', $session_title);
      $sess->__set('session_desc', $session_desc);
      $sess->__set('start_time', $start_time);
      $sess->__set('session_url', $session_url);

      $upd = $sess->updateSession();
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


if (isset($_POST['btn-addSess'])) {

  if ($_POST['audiId'] == '0') {
    $errors['audi'] = 'Select Auditorium';
  }
  if (empty($_POST['sessStart'])) {
    $errors['start'] = 'Start Time is required';
  }
  if (empty($_POST['sessTitle'])) {
    $errors['title'] = 'Session Title is required';
  }
  /*if (empty($_POST['sessUrl'])) {
    $errors['url'] = 'Session Url is required';
  }*/

  $audi_id = $_POST['audiId'];
  $start_time = $_POST['sessStart'];
  $session_title = $_POST['sessTitle'];
  if (isset($_POST['sessUrl'])) {
    $session_url = $_POST['sessUrl'];
  }

  if (isset($_POST['sessDesc'])) {
    $session_desc = $_POST['sessDesc'];
  }

  if (count($errors) == 0) {

    $sess = new Session();
    $sess->__set('audi_id', $audi_id);
    $sess->__set('session_title', $session_title);
    $sess->__set('session_desc', $session_desc);
    $sess->__set('start_time', $start_time);
    $sess->__set('session_url', $session_url);

    $add = $sess->addSession();
    //var_dump($add);
    $reg_status = $add['status'];

    if ($reg_status == "success") {
      $succ = $add['message'];
      $audiName = '';
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
          <?= ($_GET['ac'] == 'add') ? 'Add Session' : 'Edit Session' ?>
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
                $audi = new Auditorium();
                $audiList = $audi->getAudis();
                if (!empty($audiList)) {
                ?>
                  <select class="form-control" id="audiId" name="audiId" required>
                    <option value="0" <?php if ('0' == $audi_id) {
                                        echo 'selected';
                                      } ?>>Select Auditorium</option>
                    <?php
                    foreach ($audiList as $a) {
                    ?>
                      <option value="<?= $a['audi_id'] ?>" <?php if ($a['audi_id'] === $audi_id) {
                                                              echo 'selected';
                                                            } ?>><?= $a['audi_name'] ?></option>
                    <?php
                    }

                    ?>
                  </select>
                <?php
                } else {
                  header('location: audi.php?ac=add');
                }
                ?>
              </div>
              <?php
              if ($start_time != '') {
                $sdate = date('Y-m-d\TH:i', strtotime($start_time));
              }
              ?>
              <div class="form-group">
                <input type="datetime-local" id="sessStart" name="sessStart" placeholder="Start Time" class="form-control" value="<?php if ($start_time != '') {
                                                                                                                                    echo $sdate;
                                                                                                                                  } ?>" required>
              </div>
              <div class="form-group">
                <input type="text" id="sessTitle" name="sessTitle" placeholder="Session Title" class="form-control" value="<?= $session_title ?>" required>
              </div>
              <div class="form-group">
                <textarea id="sessDesc" name="sessDesc" placeholder="Description" class="form-control" rows="3"><?= $session_desc ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" id="sessUrl" name="sessUrl" placeholder="Live URL" class="form-control" value="<?= $session_url ?>">
              </div>


              <div class="form-group">
                <button class="btn btn-primary" type="submit" name="btn-addSess">Add Session</button>
              </div>
            </form>
          <?php }
          if ($_GET['ac'] == 'edit') { ?>
            <form method="post" action="" class="form">
              <div class="form-group">
                <?php
                $audi = new Auditorium();
                $audiList = $audi->getAudis();
                if (!empty($audiList)) {
                ?>
                  <select class="form-control" id="editaudiId" name="editaudiId" required>
                    <option value="0" <?php if ('0' == $audi_id) {
                                        echo 'selected';
                                      } ?>>Select Auditorium</option>
                    <?php
                    foreach ($audiList as $a) {
                    ?>
                      <option value="<?= $a['audi_id'] ?>" <?php if ($a['audi_id'] === $audi_id) {
                                                              echo 'selected';
                                                            } ?>><?= $a['audi_name'] ?></option>
                    <?php
                    }

                    ?>
                  </select>
                <?php
                } else {
                  header('location: audi.php?ac=add');
                }
                ?>
              </div>
              <?php
              if ($start_time != '') {
                $sdate = date('Y-m-d\TH:i', strtotime($start_time));
              }
              ?>
              <div class="form-group">
                <input type="datetime-local" id="editsessStart" name="editsessStart" placeholder="Start Time" class="form-control" value="<?php if ($start_time != '') {
                                                                                                                                            echo $sdate;
                                                                                                                                          } ?>" required>
              </div>
              <div class="form-group">
                <input type="text" id="editsessTitle" name="editsessTitle" placeholder="Session Title" class="form-control" value="<?= $session_title ?>" required>
              </div>
              <div class="form-group">
                <textarea id="editsessDesc" name="editsessDesc" placeholder="Description" class="form-control" rows="3"><?= $session_desc ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" id="editsessUrl" name="editsessUrl" placeholder="Live URL" class="form-control" value="<?= $session_url ?>">
              </div>


              <div class="form-group">
                <button class="btn btn-primary" type="submit" name="btn-editSess">Update Session</button>
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