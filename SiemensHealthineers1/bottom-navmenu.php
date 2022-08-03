<!DOCTYPE html>
<html lang="en">
<head>
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
  border: 1px solid #fff;
  width: 50%;
}

/* The Close Button */
.close {
  color: #fff !important;
  float: right;
  font-size: 28px;
  font-weight: bold;
  opacity: 5;
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
</head>
<body>
<nav class="navbar bottom-nav">
  <ul class="nav me-auto ms-auto">
    <li class="nav-item">
      <a class="nav-link" href="lobby.php" title="Go To Lobby"><i class="fa fa-home"></i><span class="hide-menu">Lobby</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="auditorium.php" title="Go To Auditorium"><i class="fa fa-chalkboard-teacher"></i><span class="hide-menu">Auditorium</span></a>
    </li>
   <li class="nav-item">
   <a class=" nav-link" href="#" title="Go To Auditorium"><i class="fa fa-briefcase" id="myBtn1"></i><span class="hide-menu">Briefcase</span></a>
        <!-- <a href="assets/resources/agenda.jpg"  title="Go To Auditorium" class="view resdl" data-docid="3"><i class="far fa-briefcase"></i></a> -->
    </li>
    <li class="nav-item">
      <a class="nav-link logout" href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </li>
  </ul>

</nav>

<div id="myModal1" class="modal">

<!-- Modal content -->
<div class="modal-content">
  <!-- <span class="close">&times;</span> -->
<div class="row text-center">

  <div class="col-md-6">
      <button class="btn" style="text-decoration:none;"> <a href="MAGNETOM Free.Star Event Flow.pdf" class="showpdf btn btn-lg bg-orange" id="showAgenda">
                         Show Event Flow
                          </a></button>
     
      
  </div>
  <div class="col-md-6">
  <button class="btn"> <a href="MAGNETOM Free.Star_Highlights.pdf" class="showpdf btn btn-lg bg-orange" id="showAgenda">
                         Show Flyer PDF
                          </a></button>
  </div>
</div>
<div class="row mt-4 text-center">
  <div class="col-md-6">
  <a href="assets/img/MAGNETOM Free.Star Event Flow.pdf" download>
  <button class="text-white btn btn-lg bg-orange">Download Event Flow</button>
</a>
      
  </div>
  <div class="col-md-6">
  <a href="assets/img/MAGNETOM Free.Star_Highlights.pdf" download>
  <button class="text-white btn btn-lg bg-orange bg-orange">Download Flyer PDF</button>
</a>
 
  </div>
</div>
</div>
</div>
<script>
// Get the modal
var modal = document.getElementById("myModal1");

// Get the button that opens the modal
var btn = document.getElementById("myBtn1");

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
</script>

</body>
</html>
