<?php
include 'conn.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $attendanceData = json_decode(file_get_contents("php://input"), true);

//     if ($attendanceData) {
//         $stmt = $pdo->prepare(
//             "INSERT INTO attendance (employee_no, date, time_in, status)
//              VALUES (:employee_no, :date, :time_in, :status)
//              ON DUPLICATE KEY UPDATE time_in = VALUES(time_in), status = VALUES(status)"
//         );

//         foreach ($attendanceData as $data) {
//             $stmt->execute([
//                 ':employee_no' => $data['employee_no'],
//                 ':date' => date('Y-m-d'),
//                 ':time_in' => $data['time_in'],
//                 ':status' => $data['status']
//             ]);
//         }
//         echo json_encode(["status" => "success", "message" => "Attendance recorded."]);
//     } else {
//         echo json_encode(["status" => "error", "message" => "No data received."]);
//     }
// }

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>J-VENUS EMPLOYEE ATTENDANCE</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--     


    <script type="text/javascript" src="assets/js/instascan.min.js"></script> -->
    <!-- DataTables -->
    <!-- DataTables -->
    <link rel="stylesheet" href="assets/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/datatables-buttons/css/buttons.bootstrap4.min.css">


    <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css">
    <script defer src="assets/face_logics/face-api.min.js"></script>
    <script defer src="script.js"></script>



    <style>
        #video {
            box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.1);
        }

        .time-date-container {
            display: grid;
            grid-template-columns: auto auto;
            gap: 5px;
            font-weight: bold;
        }

        .form-text {
            color: #fff;
            padding-bottom: 10px;
            font-size: 40px;
            font-weight: bold;

        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }
    </style>
</head>

<body style="background:#c0c0c0">
    <nav class="navbar" style="background:#2c3e50; text-align: center;">
        <div class="container-fluid">
            <div class="navbar-header">
                <!-- If you have elements here, they need to be considered in the flex setup -->
            </div>
            <ul class="nav navbar-nav " style="width: 100%; display: flex; justify-content: center;">
                <li style="flex-grow: 1; text-align: center;">
                    <h2 class="form-text">J-VENUS EMPLOYEE ATTENDANCE</h2>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!-- Right aligned items here -->
            </ul>
        </div>
    </nav>


    <div class="container">
        <div class="row">
            <div class="col-md-4" style="padding:10px;background:#fff;border-radius: 5px;" id="divvideo">
                <center>
                    <p class="login-box-msg"> <i class="glyphicon glyphicon-camera"></i> TAP HERE</p>
                </center>
                <video id="preview" width="100%" height="50%" style="border-radius:10px;" autoplay></video>
                <canvas id="overlay" style="position:absolute; top:0; left:0;"></canvas>
                <br>
                <br>


            </div>




            <div class="col-md-8">
                <form   class="form-horizontal"
                    style="border-radius: 5px;padding:10px;background:#fff;" id="divvideo">
<!-- Add this button after the video element -->
<button id="markAttendanceBtn" class="btn btn-primary" style="margin-top: 20px;">Mark Attendance</button>


                    <div class="time-date-container">
                        <p id="time"></p>
                        <p id="date"></p>
                    </div>


                </form>
                <div style="border-radius: 5px;padding:10px;background:#fff;" id="divideo">
    <table id="example1" class="table table-bordered">
        <thead>
            <tr>
                <th>Employee No.</th>
                <th>Name</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody id="employeeTable">
            <?php
            // Get today's date
            $date = date('Y-m-d');
            // SQL query to fetch attendance data for today
            $sql = "SELECT a.attendanceid, a.employee_no, a.time_in, a.time_out,
                   CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name, ' ', u.name_extension) AS full_name
                    FROM attendance a 
                    JOIN employee u ON a.employee_no = u.employee_no
                    WHERE a.date = '$date'";

            // Execute the query
            $query = $conn->query($sql);
            
            // Display each attendance record in a table row
            while ($row = $query->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['employee_no']; ?></td>
                    <td><?php echo $row['full_name']; ?></td> <!-- Employee name -->
                    <td><?php echo $row['time_in']; ?></td> <!-- Time in -->
                    <td><?php echo $row['time_out']; ?></td> <!-- Time out -->
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>

                </div>

            </div>


        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>

        <!-- *************
            ************ Vendor Js Files *************
        ************* -->

        <!-- Overlay Scroll JS -->
        <script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
        <script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="assets/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="assets/datatables-buttons/js/buttons.colVis.min.js"></script>

        <!-- Date Range JS -->
        <script src="assets/js/moment.min.js"></script>
        <script src="assets/vendor/daterange/daterange.js"></script>
        <script src="assets/vendor/daterange/custom-daterange.js"></script>
        <script src="assets/js/custom.js"></script>


   
        <script>
            async function startVideo() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                    document.getElementById("preview").srcObject = stream;
                } catch (error) {
                    console.error("Webcam error:", error);
                }
            }
            document.addEventListener("DOMContentLoaded", startVideo);
        </script>



        <script>
            // let labels = [];
            // let detectedFaces = [];
            // let sendingData = false;

            // function loadEmployeeDataAndFaceModels() {
            //     fetch("get_faces.php")
            //         .then((response) => response.json())
            //         .then((data) => {
            //             if (data.status === "success") {
            //                 labels = data.data.map((employee) => ({
            //                     label: employee.employee_no,
            //                     name: employee.name,
            //                     facePath: employee.face_path,
            //                 }));
            //                 console.log("Employee data loaded successfully:", labels);
            //                 loadFaceRecognitionModels();
            //             } else {
            //                 console.error("Error fetching employee data:", data.message);
            //                 alert("Failed to fetch employee data.");
            //             }
            //         })
            //         .catch((error) => {
            //             console.error("Error loading employee data:", error);
            //         });
            // }

            // function loadFaceRecognitionModels() {
            //     Promise.all([
            //         faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
            //         faceapi.nets.faceRecognitionNet.loadFromUri("models"),
            //         faceapi.nets.faceLandmark68Net.loadFromUri("models"),
            //     ])
            //         .then(() => {
            //             console.log("Models loaded successfully");
            //             startFaceDetection();
            //         })
            //         .catch((err) => {
            //             console.error("Error loading face-api.js models:", err);
            //             alert("Error loading face recognition models. Please try again.");
            //         });
            // }

            // async function getLabeledFaceDescriptions() {
            //     const labeledDescriptors = [];
            //     for (const employee of labels) {
            //         const descriptions = [];
            //         const imagePaths = employee.facePath.split(','); // Split the paths by commas
            //         for (const imgPath of imagePaths) {
            //             try {
            //                 console.log(`Fetching image: ${imgPath}`); // Debug log
            //                 const img = await faceapi.fetchImage(imgPath.trim()); // Trim in case of extra spaces
            //                 const detections = await faceapi
            //                     .detectSingleFace(img)
            //                     .withFaceLandmarks()
            //                     .withFaceDescriptor();
            //                 if (detections) {
            //                     descriptions.push(detections.descriptor);
            //                 } else {
            //                     console.warn(`No face detected for image: ${imgPath}`);
            //                 }
            //             } catch (error) {
            //                 console.error(`Error processing image: ${imgPath}`, error);
            //             }
            //         }
            //         if (descriptions.length > 0) {
            //             labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(employee.label, descriptions));
            //         }
            //     }
            //     return labeledDescriptors;
            // }



            // async function startFaceDetection() {
            //     const video = document.getElementById("preview");
            //     const canvas = document.getElementById("overlay");

            //     // Wait for the video to be ready
            //     video.addEventListener("loadeddata", async () => {
            //         console.log("Video is ready, starting face detection");

            //         const displaySize = { width: video.width, height: video.height };
            //         faceapi.matchDimensions(canvas, displaySize);

            //         const labeledFaceDescriptors = await getLabeledFaceDescriptions();
            //         const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

            //         video.addEventListener("play", () => {
            //             setInterval(async () => {
            //                 const detections = await faceapi
            //                     .detectAllFaces(video)
            //                     .withFaceLandmarks()
            //                     .withFaceDescriptors();

            //                 const resizedDetections = faceapi.resizeResults(detections, displaySize);

            //                 canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

            //                 const results = resizedDetections.map((d) =>
            //                     faceMatcher.findBestMatch(d.descriptor)
            //                 );
            //                 detectedFaces = results.map((result) => result.label);
            //                 markAttendance(detectedFaces);

            //                 results.forEach((result, i) => {
            //                     const box = resizedDetections[i].detection.box;
            //                     const drawBox = new faceapi.draw.DrawBox(box, {
            //                         label: result.toString(),
            //                     });
            //                     drawBox.draw(canvas);
            //                 });
            //             }, 100);
            //         });
            //     });
            // }

         
        </script>
        <!-- 
<script>
    
            // function sendAttendanceData(attendanceData) {
                if (sendingData) return; // Prevent multiple submissions
            sendingData = true;

            fetch('save_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(attendanceData),
            })
                .then((response) => response.json())
                .then((data) => {
                    sendingData = false;
                    if (data.status === 'success') {
                        console.log('Attendance saved successfully:', data);
                    } else {
                        console.error('Error saving attendance:', data.message);
                    }
                })
                .catch((error) => {
                    sendingData = false;
                    console.error('Error submitting attendance:', error);
                });
            }

</script> -->
















        <script>
            $(function () {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "dom": '<"row"<"col-sm-6"f><"col-sm-6"l>>' + // Search and length menu
                        '<"row"<"col-sm-12"tr>>' +            // Table
                        '<"row"<"col-sm-5"i><"col-sm-7"p>>', // Info and pagination



                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });

            });
        </script>

        <script type="text/javascript">
            $(function () {
                var interval = setInterval(function () {
                    var momentNow = moment();
                    $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
                    $('#time').html(momentNow.format('hh:mm:ss A'));
                }, 100);



            });
        </script>


</body>


</html>