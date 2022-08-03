
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php
require_once "logincheck.php";
$curr_room = 'lobby';
$curr_session = "Lobby";
?>
<?php require_once 'header.php';  ?>
<?php require_once 'preloader.php';  ?>

<style>
    .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #000;
  margin: auto;
  padding: 20px;
  border: 1px solid #000;
  width: 50%;
}

/* The Close Button */
.close {
  color: #fff !important;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}

.bg-orange{
  background-color:#ec6608;
  color:white;
  font-weight:bold;
}

a{
    text-decoration:none;
    color:white;
}

a:hover{
    text-decoration:none;
    color:white;
}
a:active{
    text-decoration:none;
    color:white;
}

a:visited{
    text-decoration:none;
    color:white;
}



</style>
<div class="page-content">
    <div id="content">
        <div id="header-menu">
            <?php require_once "header-navmenu.php" ?>
        </div>
        <div id="bg">
            <img src="RENDER - Final REVISED RED TABS REMOVED.jpg">
            <a href="auditorium.php" id="enterAudi">
                <div class="indicator d-6" ></div>
            </a>
            <a href="assets/img/MRI- Left BNR.jpg" class="view resdl" data-docid="1" target="_blank" id="aptio">
                <div class="indicator d-6 d-6-1"></div>
            </a>

            <a href="assets/img/Banner MRI.jpg" class="view resdl" data-docid="2" target="_blank" id="atellica">
                <div class="indicator d-6 d-6-2"></div>
            </a>
            <a href="assets/img/Final Agenda.jpg" class="view resdl" data-docid="3" id="showAgenda">
                <div class="indicator d-4"></div>
            </a>

            <a href="#"   data-docid="3" id="showAgenda1">  
                <div id="myBtn" class="indicator d-4">
                </div>   
            </a>


        </div>

        <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
  <div class="row text-center">
    <!-- <div class="col-md-6">
        <button class="btn btn-lg bg-orange"> <a class="text-white" href="MAGNETOM Free.Star Event Flow.pdf" class="showpdf" id="showAgenda">
                           Show PDF
                            </a></button>
       
        
    </div>
    <div class="col-md-6">
    <button class="btn btn-lg bg-orange"> <a class="text-white" href="MAGNETOM Free.Star_Highlights.pdf" class="showpdf" id="showAgenda">
                           Show PDF
                            </a></button>
    </div> -->
    <div class="col-md-6">
        <button class="btn "  style="text-decoration:none;"> <a href="MAGNETOM Free.Star Event Flow.pdf"  class="showpdf btn btn-lg bg-orange" id="showAgenda">
                           Show Event Flow
                            </a></button>
       
        
    </div>
    <div class="col-md-6">
    <button class="btn " > <a href="MAGNETOM Free.Star_Highlights.pdf" class="showpdf btn btn-lg bg-orange" id="showAgenda">
                           Show Flyer PDF
                            </a></button>
    </div>
  </div>
  <div class="row mt-4 text-center">
    <div class="col-md-6">
    <a href="AGNETOM Free.Star Event Flow.pdf " download>
    <button class="text-white btn btn-lg bg-orange eventflow"  id="eventflow" data-vidid="1" >Download Event Flow</button>
</a>
        
    </div>
    <div class="col-md-6">
    <a href="AGNETOM Free.Star Event Flow.pdf" download>
    <button class="text-white btn btn-lg bg-orange bg-orange flyerpdf" data-vidid="2">Download Flyer PDF</button>
</a>
   
    </div>
  </div>
  </div>

</div>
        <div id="bottom-menu">
            <?php require_once "bottom-navmenu.php";            ?>
        </div>
    </div>
    <?php require_once "commons.php" ?>
</div>
<audio id="lobbyMusic">
    <source src="assets/resources/lobby-music.mp3" type="audio/mpeg">
</audio>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}




</script>
<?php require_once "scripts.php" ?>
<script>
    var lobbyAudio = document.getElementById("lobbyMusic");

    $(function() {
        lobbyAudio.currentTime = 0;
        lobbyAudio.play();
    });

    $(function() {
        $('#eventflow').on('click', function() {
            var vid_id = $(this).data('vidid');
           
            var userid="<?php echo $_SESSION['userid']; ?>"
            $.ajax({
                url: 'control/exhib.php',
                data: {
                    action: 'updateVideoView',
                    vidId: vid_id,
                    userId:userid 
                },

                type: 'post',
                success: function(response) {
                    console.log(response);
                }
            });

        });
    });
    $(function() {
        $('.flyerpdf').on('click', function() {
            var vid_id = $(this).data('vidid');
           
            var userid="<?php echo $_SESSION['userid']; ?>"
            $.ajax({
                url: 'control/exhib.php',
                data: {
                    action: 'updateVideoView',
                    vidId: vid_id,
                    userId:userid 
                },

                type: 'post',
                success: function(response) {
                    //console.log(response);
                }
            });

        });
    });
</script>

<?php require_once "ga.php"; ?>

<?php require_once 'footer.php';  ?>