<?php
include 'includes/session.php';




?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>
<style>
   button.close {
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
button.close.hidden {
    opacity: 0;
    visibility: hidden;
}

</style>


<body>
  <!-- Page wrapper start -->
  <div class="page-wrapper">

    <!-- Main container start -->
    <div class="main-container">

      <!-- Sidebar wrapper start -->
      <nav id="sidebar" class="sidebar-wrapper">

        <!-- App brand starts -->
        <?php include 'includes/navbar.php'; ?>
        <!-- Sidebar profile ends -->

        <!-- Sidebar menu starts -->
        <?php include 'includes/menubar.php'; ?>
        <!-- Sidebar menu ends -->

      </nav>
      <!-- Sidebar wrapper end -->

      <!-- App container starts -->
      <div class="app-container">

        <!-- App header starts -->
        <div class="app-header d-flex align-items-center">

          <!-- Toggle buttons start -->
          <div class="d-flex">
            <button class="btn btn-outline-dark me-2 toggle-sidebar" id="toggle-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
            <button class="btn btn-outline-dark me-2 pin-sidebar" id="pin-sidebar">
              <i class="bi bi-chevron-left fs-5"></i>
            </button>
          </div>
          <!-- Toggle buttons end -->

          <!-- App brand sm start -->
          <div class="app-brand-sm d-md-none d-sm-block">
           
          </div>
          <!-- App brand sm end -->

          <!-- App header actions start -->
          <?php include 'includes/navheader.php'; ?>
          <!-- App header actions end -->

        </div>
        <!-- App header ends -->

        <!-- App hero header starts -->
        <div class="app-hero-header">

          <!-- Page Title start -->
          <div>


            <h3 class="fw-light">
              <span>Home</span> / <span class="menu-text">Pay Period</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">
                    <!-- Flash Messages -->
                    <?php include 'flash_messages.php'; ?>

                    <!-- Payroll Run Table -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>Pay Period</h5>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addnew">
                                        <i class="bi bi-plus-circle"></i> New
                                    </button>
                                </div>




                                <div class="card-body">


                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ref No</th>
                                                <th>Year</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <th class="text-center">Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <?php

                                        $query = "SELECT * FROM pay_periods ORDER BY created_at DESC";
                                        $result = $conn->query($query);

                                        // Check if the query was successful
                                        if ($result && $result->num_rows > 0) {
                                            ?>
                                            <tbody>
                                                <?php while ($period = $result->fetch_assoc()):
                                                    $status = $period['status'];
                                                    $statusBadge = '';

                                                    switch ($status) {
                                                        case 'open':
                                                            $statusBadge = "<span class='badge bg-success'>$status</span>";
                                                            break;
                                                        case 'closed':
                                                            $statusBadge = "<span class='badge bg-warning'>$status</span>";
                                                            break;
                                                        default:
                                                            $statusBadge = "<span class='badge bg-danger'>$status</span>";
                                                            break;
                                                    } ?>


                                                    <tr>
                                                        <td><?= htmlspecialchars($period['ref_no']); ?></td>
                                                        <td><?= htmlspecialchars($period['year']); ?></td>
                                                        <td><?= htmlspecialchars($period['from_date']); ?></td>
                                                        <td><?= htmlspecialchars($period['to_date']); ?></td>

                                                        <td class="text-center">
                                                            <span class="status-badge">
                                                                <?= $statusBadge; ?>
                                                            </span>
                                                            <button class="btn btn-warning btn-sm close"
                                                                data-id="<?= $period['payid']; ?>">
                                                                <i class="bi bi-x-diamond-fill"></i> Close
                                                            </button>
                                                            <button class="btn btn-danger btn-sm lock"
                                                                data-id="<?= $period['payid']; ?>">
                                                                <i class="bi bi-lock-fill"></i> Lock
                                                            </button>
                                                        </td>

                                                        <td>
                                                            <button class="btn btn-info btn-sm edit"
                                                                data-id="<?= $period['payid']; ?>">
                                                                <i class='bi bi-pencil'></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm delete"
                                                                data-id="<?= $period['payid']; ?>">
                                                                <i class='bi bi-trash'></i>
                                                            </button>
                                                        </td>

                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                            <?php
                                        }
                                        ?>

                                        </tbody>

                                    </table>





                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <!-- App body ends -->

            <!-- App footer start -->
            <?php include 'includes/footer.php'; ?>

            <!-- App footer end -->

        </div>
        <!-- App container ends -->

    </div>
    <!-- Main container end -->

    </div>
    <!-- Page wrapper end -->

    <?php include 'modals/payperiod_modal.php'; ?>
    <?php include 'includes/scripts.php'; ?>



    <script>
        $(function () {
            $('.edit').click(function (e) {
                e.preventDefault();
                $('#edit').modal('show');
                var id = $(this).data('id');
                getRow(id);
                console.log(id);
            });

            $('.delete').click(function (e) {
                e.preventDefault();
                $('#delete').modal('show');
                var id = $(this).data('id');
                getRow(id);
                console.log(id);
            });
        });

        function getRow(id) {
            $.ajax({
                type: 'POST',
                url: 'fetch_row.php',
                data: {
                    payid: id
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#payid').val(response.payid);
                    $('#ref_nos').val(response.ref_no);
                    $('#from_date').val(response.from_date);
                    $('#to_date').val(response.to_date);
                    $('#del_pay').val(response.payid);
                    $('#del_paytitle').val(
                        `Pay Period: <strong>${response.from_date}</strong> to <strong>${response.to_date}</strong>`
                    );


                }


            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addNewModal = document.getElementById('addnew');
            const refNoInput = document.getElementById('ref_no');
            const yearInput = document.getElementById('year');

            addNewModal.addEventListener('show.bs.modal', function () {
                const year = yearInput.value;

                // Fetch the next reference number from the server
                fetch(`ajax/get_next_ref_no.php?year=${year}`)
                    .then(response => response.json())
                    .then(data => {
                        refNoInput.value = data.ref_no || `PP-${year}-01`; // Default fallback
                    })
                    .catch(error => {
                        console.error('Error generating ref_no:', error);
                        refNoInput.value = `PP-${year}-01`;
                    });
            });
        });
    </script>


    <script>
    $(document).ready(function () {
    // Handle lock button click
    $(document).on('click', '.lock', function () {
        const payid = $(this).data('id'); // Get payid from button data
        const row = $(this).closest('tr'); // Reference to the row

        if (confirm('Are you sure you want to lock this pay period?')) {
            $.ajax({
                type: 'POST',
                url: 'ajax/update_status.php',
                data: { payid: payid, status: 'locked' },
                success: function (response) {
                    if (response === 'success') {
                        // Update the status badge to 'Locked'
                        row.find('.status-badge').html("<span class='badge bg-danger'>locked</span>");

                        // Disable Edit and Delete buttons
                        row.find('.edit, .delete').prop('disabled', true);

                        // Hide the Close button
                        row.find('.close').hide();

                        alert('Pay period successfully locked.');
                    } else {
                        alert('Failed to lock the pay period. Please try again.');
                    }
                },
                error: function () {
                    alert('An error occurred while locking the pay period.');
                }
            });
        }
    });

    // Handle close button click
    $(document).on('click', '.close', function () {
        const payid = $(this).data('id');
        const row = $(this).closest('tr');

        if (confirm('Are you sure you want to close this pay period?')) {
            $.ajax({
                type: 'POST',
                url: 'ajax/update_status.php',
                data: { payid: payid, status: 'closed' },
                success: function (response) {
                    if (response === 'success') {
                        // Update the status badge to 'Closed'
                        row.find('.status-badge').html("<span class='badge bg-warning'>closed</span>");

                        // Disable Edit and Delete buttons
                        row.find('.edit, .delete').prop('disabled', true);

                        alert('Pay period successfully closed.');
                    } else {
                        alert('Failed to close the pay period. Please try again.');
                    }
                },
                error: function () {
                    alert('An error occurred while closing the pay period.');
                }
            });
        }
    });

    // Disable buttons and hide the Close button based on status on page load
    $('tr').each(function () {
        const status = $(this).find('.status-badge').text().trim();
        if (status === 'locked') {
            $(this).find('.edit, .delete').prop('disabled', true);
            $(this).find('.close').hide();
        } else if (status === 'closed') {
            $(this).find('.edit, .delete').prop('disabled', true);
        }
    });
});


    </script>


</body>

</html>