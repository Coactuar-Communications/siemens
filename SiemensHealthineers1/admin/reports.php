<?php
require_once '../functions.php';
require_once 'logincheck.php';
?>
<?php
require_once 'header.php';
require_once 'nav.php';
?>
<div class="container-fluid">
    <div id="superdashboard">
        <div class="row">
            <div class="col-12 col-md-4">
                <h6>Session Attendees</h6>
                <?php
                $sess = new Session();
                $attList = $sess->getSessionAttendees();
                //var_dump($attList);
                if (!empty($attList)) {
                ?>
                    <table class="table table-borderless table-striped">
                        <?php
                        foreach ($attList as $a) {
                        ?>
                            <tr>
                                <td><?= $a['session_title'] ?></td>
                                <td><?= $a['cnt']; ?></td>
                                <td width="50"><a href="sesatt.php?s=<?php echo $a['sessionid']; ?>"><i class="fas fa-download"></i></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>
                <?php
                }
                ?>
            </div>


            <div class="col-12 col-md-4">


                <h6>Resources</h6>
                <?php

                $exhib = new Event();
                $resList = $exhib->getResources();
                //var_dump($resList);

                if (!empty($resList)) {
                ?>
                    <table class="table table-borderless table-striped">
                        <?php
                        foreach ($resList as $res) {
                        ?>
                            <tr>
                                <td><?= $res['resource_title']; ?></td>
                                <td><?= $res['download_count']; ?></td>
                                <td width="50"><a href="exhresdl.php?e=<?= $res['resource_id']; ?>"><i class="fas fa-download"></i></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                <?php
                }
                ?>

            </div>
            <div class="col-12 col-md-4"> 
          <h6>Lobby Highlights </h6>
                <?php
                $exhib = new Exhibitor();
                $vidList = $exhib->getVideoViewersCount();
                //var_dump($vidList);
                if (!empty($vidList)) {
                ?>
                    <table class="table table-borderless table-striped">
                        <?php
                        foreach ($vidList as $vid) {
                        ?>
                     
                            <tr>
                                <td><?= '<b>' . $vid['video_title'] ?></td>
                                <td><?= $vid['cnt']; ?></td>
                                <td width="50"><a href="videoanalytics.php?e=<?php echo $vid['video_id']; ?>"><i class="fas fa-download"></i></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                <?php
                }

                ?>
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