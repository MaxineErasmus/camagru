var MediaStream;

function camOn(){
    var video = document.querySelector('#video'),
        vendorURL = window.URL || window.webkitURL,
        cam_off_but = document.querySelector('#cam_off_but'),
        cam_snap_but = document.querySelector('#cam_snap_but');

    navigator.getUserMedia =    navigator.getUserMedia ||
                                navigator.webkitGetUserMedia ||
                                navigator.mozGetUserMedia ||
                                navigator.oGetUserMedia ||
                                navigator.msGetUserMedia;

    video.style.width = "500px";
    video.style.height = "375px";

    navigator.getUserMedia({
        video: true
    }, function (stream) {
        video.src = vendorURL.createObjectURL(stream);
        video.play();
        MediaStream = stream.getTracks()[0];
    }, function (error) {
        console.log(error);
    });
}

function camOff() {
    var video = document.querySelector('#video');

    video.pause();
    video.src = "";
    MediaStream.stop();

    video.style.width = "10px";
    video.style.height = "10px";
}

function draw() {
    var width = 500,
        height = 375,
        blank = document.createElement('canvas'),
        base_img_string = document.querySelector('#base_img_string'),
        video = document.querySelector('#video'),
        canvas = document.querySelector('#canvas'),
        context = canvas.getContext('2d');

    context.drawImage(video, 0, 0, width, height);

    blank.width = canvas.width;
    blank.height = canvas.height;

    if (!(canvas.toDataURL() === blank.toDataURL())){
        base_img_string.value = canvas.toDataURL("image/png");
        document.forms['studio_upload'].submit();
    }
}

function show_upload() {
    var x = document.querySelector("#upload_form");
    var button = document.querySelector("#show_upload_button");
    button.style.display = "none";
    x.style.display = "inline-block";
}