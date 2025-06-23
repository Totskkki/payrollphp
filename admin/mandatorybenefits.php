<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$today = date('Y-m-d');
$year = date('Y');
if (isset($_GET['year'])) {
  $year = $_GET['year'];
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.php'; ?>

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
              <span>Home</span> / <span class="menu-text">Mandatory Benifits</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">

          <?php include 'flash_messages.php'; ?>



          <!-- Row start -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <h5>Benifits List</h5>
                  <!-- Add Employee Button -->



                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnew">
                    <i class="bi bi-plus-circle"></i>Add
                  </button>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <thead>
                        <tr>
                          <th>Benefit types</th>
                          <th>Amount</th>
                          <th>Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $sql = "SELECT * FROM mandatory_benefits";
                        $query = $conn->query($sql);
                        $totalBenefits = 0;

                        $sssEmployeeShare = 0;
                        $sssEmployerShare = 0;
                        $msc = 0;
                        while ($row = $query->fetch_assoc()) {
                          $statusLabel = $row['status'] === 'inactive' ? 'badge bg-danger' : 'badge bg-success';
                          $statusText = $row['status'] === 'inactive' ? 'Inactive' : 'Active';

                          if ($row['status'] === 'active') {
                            if ($row['benefit_type'] === 'SSS') {
                              $msc = $row['amount']; // Assume this is Monthly Salary Credit (e.g., ₱10,000)
                              $sssEmployeeShare = $msc * 0.045; // Employee's 4.5% share
                              $sssEmployerShare = $msc * 0.095; // Employer's 9.5% share
                            } else {
                              $totalBenefits += $row['amount'];
                            }
                          }

                          echo "
                          <tr>
                              <td>" . htmlspecialchars($row['benefit_type']) . "</td>
                              <td>₱" . number_format($row['amount'], 2) . "</td>
                              <td><span class='$statusLabel'>$statusText</span></td>
                              <td class='text-center'>
                                  <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['mandateid'] . "'><i class='bi bi-pencil'></i></button>
                                  <button class='btn btn-warning btn-sm toggle-status btn-flat' data-id='" . $row['mandateid'] . "' data-status='" . $row['status'] . "'><i class='bi bi-toggle-off'></i> Toggle Status</button>
                              </td>
                          </tr>
                          ";
                        }

                        // Calculate shares for other benefits
                        $employeeShare = $totalBenefits / 2;
                        $employerShare = $totalBenefits / 2;
                        ?>

                      </tbody>
                    </table>
                    <hr>
                    <div class="mt-3">
                        <h5><strong>Total Active Mandatory Benefits (Excluding SSS):</strong> ₱<?php echo number_format($totalBenefits, 2); ?></h5>
                        <h6><strong>Formula:</strong> Total Active Benefits ÷ 2 = Employee Share / Employer Share</h6>
                        <h6><strong>Employee Share:</strong> ₱<?php echo number_format($employeeShare, 2); ?></h6>
                        <h6><strong>Employer Share:</strong> ₱<?php echo number_format($employerShare, 2); ?></h6>

                        <hr>
                        <h5><strong>SSS Contribution (Based on MSC ₱<?php echo number_format($msc, 2); ?>):</strong></h5>
                        <h6><strong>Employee Share (4.5%):</strong> ₱<?php echo number_format($sssEmployeeShare, 2); ?></h6>
                        <h6><strong>Employer Share (9.5%):</strong> ₱<?php echo number_format($sssEmployerShare, 2); ?></h6>
                        <h6><strong>Total SSS Contribution:</strong> ₱<?php echo number_format($sssEmployeeShare + $sssEmployerShare, 2); ?></h6>
                        <a href="https://www.sss.gov.ph/wp-content/uploads/2023/02/2023-Schedule-of-Contributions.pdf">Download - SSS Contribution Table</a>
                    </div>

                  </div>


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

  <?php include 'modals/mandbenifits_modal.php'; ?>
  <?php include 'includes/scripts.php'; ?>
  <script>
    $(document).ready(function () {
      // Toggle status button click event
      $('.toggle-status').click(function () {
        var id = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        // Prompt the user with an alert before toggling the status
        var confirmToggle = confirm("Are you sure you want to change the status to " + newStatus + "?");

        if (confirmToggle) {
          // If the user confirms, send AJAX request to update the status in the database
          $.ajax({
            type: 'POST',
            url: 'ajax/update_status_ben.php', // PHP file to handle the status update
            data: {
              id: id,
              status: newStatus
            },
            success: function (response) {
              if (response === 'success') {
                // Reload the page or dynamically update the status on success
                alert('Status updated successfully!');
                location.reload(); // Reload the page to reflect the status change
              } else {
                alert('Error updating status.');
              }
            },
            error: function () {
              alert('An error occurred while updating the status.');
            }
          });
        } else {
          // If the user cancels, do nothing
          return false;
        }
      });
    });
  </script>


  <script>
    $(function () {
      // View button click event
      $('.view').click(function (e) {
        e.preventDefault();
        $('#view').modal('show');
        var id = $(this).data('id');
        getRow(id);

      });

      // Edit button click event
      $('.edit').click(function (e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      // Delete button click event
      $('.delete').click(function (e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });
    });


    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'fetch_row.php',
        data: {
          manid: id
        },
        dataType: 'json',
        success: function (response) {
          console.log(response); // Corrected this line
          $('#mandateid').val(response.mandateid);
          $('#benefit_type').val(response.benefit_type);
          $('#amount').val(response.amount);
        },
        error: function (xhr, status, error) {
          console.error('Error fetching row:', error);
        }
      });
    }
  </script>






</body>


</html>