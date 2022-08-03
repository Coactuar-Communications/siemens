<?php
require_once '../functions.php';
require_once 'header.php';
?>

<body>
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-8 col-md-6 offset-2 offset-md-3 ">
        <div class="form-wrapper">
          <h6>Select Session</h6>
          <div id="sessions" class="mt-2">
            <table class="table table-bordered">
              <tbody>
                <?php
                $sess = new Session();
                $sessList = $sess->getAllSessions();
                //var_dump($sessList);
                $i = 1;
                foreach ($sessList as $s) {
                ?>
                  <tr>
                    <td><a href="questions.php?sess=<?= $s['session_id'] ?>"><b><?= $s['session_title'] ?></b></a> (<?= $s['audi_name'] ?>)</td>
                  </tr>
                <?php
                }
                ?>
                <!-- <tr>
                  <td>Track 01 - <a href="questions.php?sess=081f157915787054113f6abbd691adf531d2a64a2fd2efb8b855855d9930de83"><b>High Risk Pregnancy</b></a></td>
                </tr>
                <tr>
                <tr>
                  <td>Track 02 - <a href="questions.php?sess=4ec4d31188c2eac21d8f0f9cf3646c93cc6638c1f9aedda3ae68c572ff6ce4a7"><b>Infertility Updates</b></a></td>
                </tr>
                <tr>
                  <td>Track 03 - <a href="questions.php?sess=5419f95cacf69ce789132fe44c6373ff42362923476d7d928ab0744792a7de95"><b>Oncology</b></a></td>
                </tr>
                <tr>
                  <td>Track 04 - <a href="questions.php?sess=4f6a868d61802fedff0f7902068ec95e7cf02cd8db9f5fba8cd8ff32f985a4e1"><b>Maternal Fetal Medicine</b></a></td>
                </tr>
                <tr>
                  <td>Track 05 - <a href="questions.php?sess=b08d39bffb4b313e947d364df886d80b5b2f8cc16efb0a4810f6e047bec9e44e"><b>Endoscopy Challenges</b></a></td>
                </tr>
                <tr>
                  <td>Track 06 - <a href="questions.php?sess=29ba49e77cfb3efb049a6a8984b62cc84843fb5d2834481bac1b19bdddd7d51f"><b>Urogynaecology</b></a></td>
                </tr> -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
  require_once 'footer.php';
  ?>