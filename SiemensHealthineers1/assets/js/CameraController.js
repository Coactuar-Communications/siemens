'use strict';
// Put variables in global scope to make them available to the browser console.
const video = document.querySelector('#cam-feed video');
const canvas = window.canvas = document.querySelector('#cam-feed canvas');

const camBtn = document.querySelector('#cam-btn');
const beforeCapture = document.querySelector('#before-capture');
const afterCapture = document.querySelector('#after-capture');

const retakeBtn = document.querySelector('#retake-btn');
retakeBtn.onclick = initCamera;

const saveBtn = document.querySelector('#save-btn');
const emailBtn = document.querySelector('#email-btn');
//   const camContainer = document.querySelector('#cam-container');
//   const closeBtn = document.querySelector('#close-btn');


//var openCameraModel = function () { 
//$("#cameraModal").modal("show");
// initCamera when it is displayed
//   $("#cameraModal").on("shown.bs.modal", initCamera);
//initCamera();
// };

//  var close_cameraModel=function(){
//stop();
//$("#cameraModal").modal("hide");
//  }

camBtn.onclick = function () {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    console.log(canvas.width, canvas.height)
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    video.style.display = 'none';
    beforeCapture.style.cssText = 'display:none !important';
    afterCapture.style.cssText = 'display:flex !important';
    canvas.style.display = 'block';
};


saveBtn.onclick = function () {

    afterCapture.style.cssText = 'display:none !important';
    beforeCapture.style.cssText = 'display:none !important';
    window.scrollTo(0, 0);
    html2canvas(document.querySelector(".camera-panel"), { backgroundColor: "#000000" }).then(c => {
        c.toBlob(function (blob) {
            saveAs(blob, "selfie.jpg");
            beforeCapture.style.cssText = 'display:none !important';
            afterCapture.style.cssText = 'display:flex !important';
        });
        var dataURL = c.toDataURL();
        $.ajax({
            type: 'POST',
            url: 'savetoserver.php',
            data: {
                imgBase64: dataURL
            }
        }).done(function (o) {
            console.log('saved');
        }).success(function (response) {
            console.log(response);
            document.getElementById("facebook").setAttribute('href', 'https://www.facebook.com/sharer.php?u=' + response + '&quote=');
            document.getElementById("linkedin").setAttribute('href', 'https://www.linkedin.com/shareArticle?mini=true&url=' + response + '&summary=%20&source=LinkedIn');
            document.getElementById("twitter").setAttribute('href', 'https://twitter.com/intent/tweet?url=' + response + '&text=');
            $('#social').fadeIn();
        });


    });
};


const constraints = {
    audio: false,
    //video: true
    video: { width: 1280, height: 720 }
};

function handleSuccess(stream) {
    //window.stream = stream; // make stream available to browser console
    video.srcObject = stream;
    video.onloadmetadata = function (e) {
        video.play();
    }
    beforeCapture.style.cssText = 'display:flex !important';
}

function handleError(error) {
    alert('navigator.MediaDevices.getUserMedia error: ', error.message, error.name);
}

function stop() {
    //console.log('stopping camera');
    var stream = video.srcObject;
    if (stream) {
        var tracks = stream.getTracks();

        for (var i = 0; i < tracks.length; i++) {
            var track = tracks[i];
            track.stop();
        }
        video.srcObject = null;
    }
}


function initCamera() {
    //console.log('starting camera');
    // camContainer.style.display = 'block';
    canvas.style.display = 'none';
    afterCapture.style.cssText = 'display:none !important';
    beforeCapture.style.cssText = 'display:none !important';
    video.style.display = 'block';

    navigator.mediaDevices.getUserMedia(constraints).then(handleSuccess).catch(handleError);
}
//initCamera();