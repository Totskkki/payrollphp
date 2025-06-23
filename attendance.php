<?php
include 'conn.php';

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


    <style>
        .navbar {
            background: #2c3e50;
            text-align: center;
        }

        .form-text {
            color: #fff;
            padding-bottom: 10px;
            font-size: 40px;
            font-weight: bold;
        }

        #main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin: 20px auto;
            width: 90%;
        }

        #video-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            max-width: 800px;
            width: 100%;
        }

        #preview {
            border-radius: 10px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
            max-width: 100%;
            height: auto;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
        }

        #table-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 100%;
        }

        .time-date-container {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background-color: #2c3e50;
            color: #fff;
            text-align: left;
            padding: 10px;
        }

        table tbody td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #e0e0e0;
        }
        #notification {
    display: none;
    text-align: center;
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
    transition: all 0.5s ease-in-out;
}
    </style>
</head>


<body>
    <nav class="navbar">
        <div class="container-fluid">
            <h2 class="form-text">J-VENUS EMPLOYEE ATTENDANCE</h2>
        </div>
    </nav>

    <div id="main-container">
        <!-- Video container -->
        <div class="video-container" style="position: relative; width: 600px; height: 450px;">
            <video id="preview" width="600" height="450" autoplay></video>
            <canvas id="overlay" style="position: absolute; top: 0; left: 0;"></canvas>
        </div>

        <div id="notification" style="text-align: center; margin-top: 10px; color: green; font-size: 18px;"></div>
        <!-- Table container -->
        <div id="table-container">
            <div class="time-date-container">
                <p id="time"></p>
                <p id="date"></p>
            </div>

            
            <table id="example1" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Employee No.</th>

                        <th>Time in</th>
                        <th>Time out</th>
                    </tr>
                </thead>
                <tbody id="employeeTable">

                </tbody>
            </table>
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

    <script defer src="script.js"></script>


    <script>
        async function startVideo() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                document.getElementById("preview").srcObject = stream;
            } catch (error) {
                console.error("Webcam error:", error);
            }
        }
        document.addEventListener("DOMContentLoaded", startVideo);
    </script>







    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "dom": '<"row"<"col-sm-6"f><"col-sm-6"l>>' + // Search and length menu
                    '<"row"<"col-sm-12"tr>>' + // Table
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
        $(function() {
            var interval = setInterval(function() {
                var momentNow = moment();
                $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
                $('#time').html(momentNow.format('hh:mm:ss A'));
            }, 100);



        });
    </script>


</body>


</html>