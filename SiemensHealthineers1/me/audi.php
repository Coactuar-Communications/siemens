<?php
require_once '../functions.php';
require_once 'logincheck.php';

$errors = [];
$succ = '';

$audiName = '';
$audiId = '';

if($_GET['ac'] == 'edit')
{
    $audiId = $_GET['id'];
    $audi = new Auditorium();
    $editAudi = $audi->getAudi($audiId);
    //var_dump($editAudi);
    if(!empty($editAudi))
    {
        $audiName = $editAudi[0]['audi_name'];
    }
    else{
        header('location: auditoriums.php');
    }
    
    if (isset($_POST['btn-editAudi'])) {
  
      if (empty($_POST['editaudiName'])) {
        $errors['audi'] = 'Auditorium Name is required';
      }
    
      if (count($errors) == 0) {
        $audiName =   $_POST['editaudiName'];
        $audi = new Auditorium();
        $audi->__set('audi_name', $_POST['editaudiName']);
        $audi->__set('audi_id', $_POST['editaudiId']);
        
        $upd = $audi->updAudi();
        //var_dump($add);
        $reg_status = $upd['status'];
        
        if ($reg_status == "success") {
          $succ = $upd['message'];  
          //$audiName = '';    
        } else {
          $errors['reg'] = $upd['message'];
        }
      } 
    }
    
}


if (isset($_POST['btn-addAudi'])) {
  
  if (empty($_POST['audiName'])) {
    $errors['audi'] = 'Auditorium Name is required';
  }

  if (count($errors) == 0) {
    $audiName =   $_POST['audiName'];
    $audi = new Auditorium();
    $audi->__set('audi_name', $_POST['audiName']);
    
    $add = $audi->addAudi();
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
            
            <?php if($_GET['ac'] == 'add'){ ?>
            <form method="post" action="" class="form">
                <div class="form-group">
                    <input type="text" id="audiName" name="audiName" class="form-control" value="<?= $audiName ?>" required>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="btn-addAudi">Add Auditorium</button>
                </div>
            </form>
            <?php } 
            if($_GET['ac'] == 'edit'){ ?>
            <form method="post" action="" class="form">
                <div class="form-group">
                    <input type="text" id="editaudiName" name="editaudiName" value="<?= $audiName ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="hidden" id="editaudiId" name="editaudiId" value="<?= $audiId ?>" class="form-control" required>
                    <button class="btn btn-primary" type="submit" name="btn-editAudi">Update Auditorium</button>
                </div>
            </form>
            <?php } ?>
        </div>
    
    </div>

</div>

<?php
require_once 'scripts.php';
?>
<?php
require_once 'footer.php';
?>