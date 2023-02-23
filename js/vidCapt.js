function startVideo(){
    let constraintObj = {
        video: {
            facingMode: "user",
            width: {
                min: 640,
                ideal: 640,
                max: 640
            },
            height: {
                min: 360,
                ideal: 360,
                max: 360
            }
        }
    };
    if (navigator.mediaDevices === undefined) {
        navigator.mediaDevices = {};
        navigator.mediaDevices.getUserMedia = function(constraintObj) {
            let getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            if (!getUserMedia) {
                recError = true;
                return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
    
            }
            return new Promise(function(resolve, reject) {
                getUserMedia.call(navigator, constraintObj, resolve, reject);
            });
        }
    } else {
        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                devices.forEach(device => {
                    console.log(device.kind.toUpperCase(), device.label);
                    //, device.deviceId
                })
            })
            .catch(err => {
                console.log(err.name, err.message);
            })
    }
    
    navigator.mediaDevices.getUserMedia(constraintObj)
        .then(function(mediaStreamObj) {
           
    
            let video = document.querySelector('video');
            if ("srcObject" in video) {
                video.srcObject = mediaStreamObj;
            } else {
                video.src = window.URL.createObjectURL(mediaStreamObj);
            }
            video.onloadedmetadata = function(ev) {
                video.play();
            }
    
        }).catch(function(err) {
    
            alert("Video Permissions Denied!! Try again..");
            window.location.href = "account.php?q=1";
            console.log(err.name, err.message);
        });
}

function startAudio(){
    let constraintObj = {
        audio:true };
    if (navigator.mediaDevices === undefined) {
        navigator.mediaDevices = {};
        navigator.mediaDevices.getUserMedia = function(constraintObj) {
            let getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            if (!getUserMedia) {
                recError = true;
                return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
    
            }
            return new Promise(function(resolve, reject) {
                getUserMedia.call(navigator, constraintObj, resolve, reject);
            });
        }
    } else {
        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                devices.forEach(device => {
                    // console.log(device.kind.toUpperCase(), device.label);
                    //, device.deviceId
                })
            })
            .catch(err => {
                console.log(err.name, err.message);
            })
    }

    navigator.mediaDevices.getUserMedia(constraintObj)
    .then(function(mediaStreamObj) {
       
        let mediaRecorder = new MediaRecorder(mediaStreamObj);
    
        mediaRecorder.start();
        console.log(mediaRecorder.state);
        getQuestion();

        let start = document.getElementById('btnStart');
        let stop = document.getElementById('btnStop');
        let chunks = [];

        start.addEventListener('click', (ev) => {
            mediaRecorder.start();
            console.log(mediaRecorder.state);
        })
        stop.addEventListener('click', (ev) => {
            mediaRecorder.stop();
            console.log(mediaRecorder.state);
        });
        mediaRecorder.ondataavailable = function(ev) {
            if (ev.data.size > 0)
                chunks.push(ev.data);
        }
        mediaRecorder.onstop = (ev) => {
            globblob = new Blob(chunks, {
                type: 'audio/mp3'
            }); 
            chunks = [];
            uploadVid();
        }
    }).catch(function(err) {
        alert(err.name, err.message);

        alert("Audio Permissions Denied!! Try again..");
        window.location.href = "account.php?q=1";
        console.log(err.name, err.message);
    });
}

