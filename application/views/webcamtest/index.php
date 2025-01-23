<?php if ($this->session->flashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <?= $this->session->flashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif ($this->session->flashdata('message_error')) : ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <?= $this->session->flashdata('message_error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif ($this->session->flashdata('flash-swal')) : ?>
    <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash-swal'); ?>"></div>

<?php elseif ($this->session->flashdata('flash-data-gagal')) : ?>
    <div class="flash-data-gagal" data-flashdata="<?= $this->session->flashdata('flash-data-gagal'); ?>"></div>
<?php endif ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Web Camera UI</h1>
            <video id="webcam" class="w-100 mb-3" playsinline autoplay></video>
            <div class="d-flex justify-content-center mb-3">
                <button id="switchCameraBtn" class="btn btn-primary me-2"><i class="bi bi-camera-reverse"></i> Switch Camera</button>
                <button id="captureBtn" class="btn btn-success"><i class="bi bi-camera"></i> Ambil Foto</button>

                <button id="startRecordBtn" class="btn btn-warning"><i class="bi bi-camera-video"></i> Start Recording</button>
                <button id="stopRecordBtn" class="btn btn-danger" disabled><i class="bi bi-stop-circle"></i> Stop Recording</button>


                <button id="retakeBtn" class="btn btn-warning"><i class="bi bi-arrow-counterclockwise"></i> Retake</button>
                <button id="saveBtn" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>

            </div>
            <canvas id="canvas" class="w-100"></canvas>
            <audio id="snapSound" src="<?= base_url('assets/camera/audio/camera-shutter-click-01.wav') ?>" preload="auto"></audio>
        </div>
    </div>
</div>

<script>
    //https://github.com/bensonruan/webcam-easy

    $('#retakeBtn').hide();
    $('#saveBtn').hide();
    $(document).ready(function() {
        const webcamElement = document.getElementById('webcam');
        const canvasElement = document.getElementById('canvas');
        const snapSoundElement = document.getElementById('snapSound');
        const startRecordBtn = document.getElementById('startRecordBtn');
        const stopRecordBtn = document.getElementById('stopRecordBtn');
        const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
        let mediaRecorder;
        let recordedChunks = [];
        webcam.start()
            .then(result => {
                console.log("webcam started");
                webcam.flip();
                webcam.start();
            })
            .catch(err => {
                console.log(err);
            });

        $('#switchCameraBtn').on('click', function() {

            webcam.flip();
            webcam.start();
        });

        $('#captureBtn').on('click', function() {
            let takePicture = webcam.snap();
            console.log(takePicture);

            // Hide the capture button and show the retake button
            $('#captureBtn').hide();
            $('#retakeBtn').show();
            $('#saveBtn').show();
        });

        $('#retakeBtn').on('click', function() {
            // Show the capture button and hide the retake button
            $('#captureBtn').show();
            $('#retakeBtn').hide();
            $('#saveBtn').hide();

            // Clear the canvas
            const context = canvasElement.getContext('2d');
            context.clearRect(0, 0, canvasElement.width, canvasElement.height);
        });

        $('#saveBtn').on('click', function() {
            // Get the captured image data (base64 format)
            const imageData = canvasElement.toDataURL('image/png');

            // Send the image data to the server for saving
            saveToDatabase(imageData);
        });

        function saveToDatabase(imageData) {
            let BASE_URL = "<?= base_url(); ?>";
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'webcamtest/save',
                data: {
                    imageData: imageData
                },
                success: function(response) {
                    if (response.err_code == 0) {
                        alert(response.message);
                        location.reload();
                        // console.log('Image saved successfully:', response);

                    }
                },
                error: function(error) {
                    console.error('Error saving image:', error);
                }
            });
        }


        $('#startRecordBtn').on('click', function() {
            startRecordBtn.disabled = true;
            stopRecordBtn.disabled = false;

            recordedChunks = [];

            // Explicitly call getUserMedia to obtain the MediaStream
            navigator.mediaDevices.getUserMedia({
                video: true
            }).then(stream => {
                if (stream instanceof MediaStream) {
                    // Use the obtained stream to initialize MediaRecorder
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = () => {
                        const blob = new Blob(recordedChunks, {
                            type: 'video/webm'
                        });
                        const url = URL.createObjectURL(blob);

                        // You can send the blob to the server for saving or playback
                        // For simplicity, let's just open it in a new tab for playback
                        window.open(url);
                    };

                    mediaRecorder.start();
                } else {
                    console.error('Invalid stream type:', stream);
                }
            }).catch(error => {
                console.error('Error accessing webcam:', error);
            });
        });

        $('#stopRecordBtn').on('click', function() {
            // Disable the stop recording button and enable the start recording button
            startRecordBtn.disabled = false;
            stopRecordBtn.disabled = true;

            if (mediaRecorder) {
                mediaRecorder.stop();
            }
        });


        // function startRecording() {
        //     // Disable the start recording button and enable the stop recording button

        //     startRecordBtn.disabled = true;
        //     stopRecordBtn.disabled = false;


        //     recordedChunks = [];
        //     const stream = webcam.stream();
        //     mediaRecorder = new MediaRecorder(stream);

        //     mediaRecorder.ondataavailable = (event) => {
        //         if (event.data.size > 0) {
        //             recordedChunks.push(event.data);
        //         }
        //     };

        //     mediaRecorder.onstop = () => {
        //         const blob = new Blob(recordedChunks, {
        //             type: 'video/webm'
        //         });
        //         const url = URL.createObjectURL(blob);

        //         // You can send the blob to the server for saving or playback
        //         // For simplicity, let's just open it in a new tab for playback
        //         window.open(url);
        //     };

        //     mediaRecorder.start();
        // }

        // function stopRecording() {
        //     // Disable the stop recording button and enable the start recording button
        //     startRecordBtn.disabled = false;
        //     stopRecordBtn.disabled = true;

        //     mediaRecorder.stop();
        // }
    });
</script>