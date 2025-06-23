<!-- *************
            ************ JavaScript Files *************
        ************* -->
<!-- Required jQuery first, then Bootstrap Bundle JS -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- *************
            ************ Vendor Js Files *************
        ************* -->

<!-- Overlay Scroll JS -->
<script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
<script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

<!-- Apex Charts -->
<!-- <script src="assets/vendor/apex/apexcharts.min.js"></script>
        <script src="assets/vendor/apex/custom/graphs/custom-sparkline.js"></script> -->
<!-- <script src="assets/vendor/apex/custom/home/sales.js"></script>
        <script src="assets/vendor/apex/custom/home/sparkline.js"></script>
        <script src="assets/vendor/apex/custom/home/sparkline2.js"></script> -->

<!-- Rating -->
<script src="assets/vendor/rating/raty.js"></script>
<script src="assets/vendor/rating/raty-custom.js"></script>



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

<script src="assets/js/qrcode.min.js"></script>

<script src="assets/timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/select2/js/select2.js"></script>
<script src="assets/select2/js/select2.full.min.js"></script>
<script src="assets/datepicker/bootstrap-datepicker.js"></script>
<script src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script src="assets/js/notifications.js"></script>

<script src="assets/jquery-validation/jquery.validate.min.js"></script>
<script src="assets/jquery-validation/additional-methods.min.js"></script>


<!-- Custom JS files -->
<script src="assets/js/custom.js"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "dom": '<"row"<"col-sm-6"f><"col-sm-6"l>>' + // Search and length menu
                '<"row"<"col-sm-12"tr>>' +            // Table
                '<"row"<"col-sm-5"i><"col-sm-7"p>>', // Info and pagination
            // Uncomment and configure buttons if needed
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]


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
<script>

    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        locale: {
            format: "YYYY-MM-DD",
        },
       
        maxDate: moment().subtract(1, 'days'),
        autoUpdateInput: false
    }).on('apply.daterangepicker', function (ev, picker) {

        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    $('.timepicker').timepicker({
        showInputs: false
    });
    if ($('.datepickeradd').length > 0) {
    $('.datepickeradd').datepicker({
        format: 'yyyy-mm-dd',
        endDate: new Date(), // Restricts selection to today or earlier
        autoclose: true
    });
    $('.datepickeredit').datepicker({
        format: 'yyyy-mm-dd',
        startDate: new Date(), 
        autoclose: true
    });
    $('.datepickerpayperiod').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
       
    });

    

    
}


    document.addEventListener('DOMContentLoaded', function () {
        // Automatically hide the alert after 5 seconds
        setTimeout(function () {
            const errorAlert = document.getElementById('errorAlert');
            const successAlert = document.getElementById('successAlert');

            if (errorAlert) {
                errorAlert.classList.remove('show');
                errorAlert.classList.add('fade');
            }
            if (successAlert) {
                successAlert.classList.remove('show');
                successAlert.classList.add('fade');
            }
        }, 5000); // 5000 milliseconds = 5 seconds
    });

</script>
<script>
    // Basic date range picker
    $('#reservation').daterangepicker({
        opens: 'right', // Ensures the picker opens on the right
        locale: {
            format: 'MM/DD/YYYY' // Adjust date format as needed
        }
    });

    // Date range picker with time picker
    $('#reservationtime').daterangepicker({
        opens: 'right', // Ensures the picker opens on the right
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY h:mm A' // Format includes time
        }
    });

    // Date range picker with predefined ranges
    $('#daterange-btn').daterangepicker({
        opens: 'right', // Ensures the picker opens on the right
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
    }, function(start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    });
</script>
