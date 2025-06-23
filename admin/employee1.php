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
            <a href="index.html">
              <img src="assets/images/logo-dark.svg" class="logo" alt="Bootstrap Gallery">
            </a>
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
              <span>Home</span> / <span class="menu-text">Employee records</span>
            </h3>
          </div>
          <!-- Page Title end -->

          <!-- Header graphs start -->

          <!-- Header graphs end -->

        </div>
        <!-- App Hero header ends -->

        <!-- App body starts -->
        <div class="app-body">

          <?php
          if (isset($_SESSION['error'])) {
            echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert' id='errorAlert'>
        <i class='fa fa-exclamation-circle me-2'></i>
        <strong>Error!</strong> {$_SESSION['error']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
            unset($_SESSION['error']);
          }
          if (isset($_SESSION['success'])) {
            echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert' id='successAlert'>
        <i class='fa fa-check-circle me-2'></i>
        <strong>Success!</strong> {$_SESSION['success']}
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
            unset($_SESSION['success']);
          }
          ?>


          <!-- Row start -->
          <div class="row">
            <div class="col-sm-12">
              <div class="card mb-4">
                <div class="card-title d-flex justify-content-between align-items-center px-3 py-3">
                  <h5>Employee List</h5>
                  <!-- Add Employee Button -->

                  <!--         
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>     Add Employee
										</button> -->

                  <a href="employee_add.php" type="button" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Employee
                  </a>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table align-middle table-hover m-0">
                      <?php
                    
                    $sql = "SELECT u.*,u.employee_id as empid,   addr.*, ed.*, d.department  as dep , p.position as pos,
                      CONCAT(u.first_name, ', ', u.middle_name, ', ', u.last_name, ' ', u.name_extension) AS `full_name`,         
                      CONCAT(addr.street, ', ', addr.city, ', ', addr.province) AS full_address        
                      FROM employee u
                      LEFT JOIN employee_details ed ON ed.employee_id = u.employee_id 
                      LEFT JOIN department d ON d.depid = ed.department 
                      LEFT JOIN position p ON p.positionid = ed.position  
                      LEFT JOIN address addr ON addr.addressid = u.employee_id 
                    
                      ORDER BY u.employee_id DESC";
              

                      $query = $conn->query($sql);
                      ?>
                      <thead>
                        <tr>

                          <th>#</th>
                          <th>Photo</th>
                          <th>Name</th>
                          <th>Department</th>
                          <th>Position</th>
                          <th>Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $counter = 1; // Start the counter for auto-increment 
                        ?>
                        <?php while ($row = $query->fetch_assoc()) { ?>
                          <tr>
                            <td><?php echo $counter++; // Increment the counter 
                                ?></td> <!-- Display auto-increment number -->

                            <td><img src="<?php echo (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/profile.jpg'; ?>" width="30px" height="30px">
                              <a href="#edit_photo" data-toggle="modal" class="pull-right photo" data-id="<?php echo $row['empid']; ?>"><span class="fa fa-edit"></span></a>
                            </td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['dep']); ?></td>
                            <td><?php echo htmlspecialchars($row['pos']); ?></td>
                            <td>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" data-id="<?php echo $row['empid']; ?>"
                                  <?php echo ($row['status'] == 'Active') ? 'checked' : ''; ?>>
                                <span class="badge <?php echo ($row['status'] == 'Active') ? 'bg-success' : 'bg-danger'; ?>">
                                  <?php echo ($row['status'] == 'Active') ? 'Active' : 'Inactive'; ?>
                                </span>
                              </div>
                            </td>
                            <td class="text-center">
                              <a href="employee_details.php?id=<?php echo $row['empid']; ?>" class="btn btn-info btn-sm mr-3"><i class="bi bi-eye"></i></a>
                              <a href="#" class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="bi bi-pencil"></i></button>
                              <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="bi bi-trash"></i></button>
                            </td>
                          </tr>
                        <?php } ?>
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
  <?php include 'modals/employee_modal.php'; ?>

  <?php include 'includes/scripts.php'; ?>


  <script>
    $(document).ready(function() {
      $('.form-check-input').change(function() {
        var empId = $(this).data('id');
        var newStatus = $(this).is(':checked') ? 'active' : 'inactive';
        var statusBadge = $(this).siblings('.badge');

        $.ajax({
          url: 'ajax/status.php',
          type: 'POST',
          data: {
            empid: empId,
            status: newStatus
          },
          success: function(response) {
            if (response === 'success') {
              // Update badge class and text based on the new status
              statusBadge
                .removeClass('bg-success bg-danger')
                .addClass(newStatus === 'active' ? 'bg-success' : 'bg-danger')
                .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

              alert('Status updated successfully!');
            } else {
              alert('Failed to update status.');
              // Revert the checkbox to its previous state
              $(this).prop('checked', !$(this).is(':checked'));
            }
          }.bind(this),
          error: function() {
            alert('An error occurred while updating status.');
            // Revert the checkbox to its previous state
            $(this).prop('checked', !$(this).is(':checked'));
          }.bind(this)
        });
      });
    });
  </script>


  <script>
    // $(function() {

    //   $('#type').change(function() {
    //     if ($(this).val() == 3) {
    //       $('#dfield').show();
    //     } else {
    //       $('#dfield').hide();
    //       $('#edate').val('');
    //     }
    //   });


    //   $('#add_list').click(function() {
    //     var deduction = $('#deduction_type').val();
    //     var deductionText = $('#deduction_type option:selected').text();
    //     var type = $('#type').val();
    //     var amount = $('#deduction_amount').val();
    //     var edate = $('#edate').val();


    //     if (type !== '3') {
    //       edate = null;
    //     }


    //     if (!deduction || !type || !amount) {
    //       alert('Please fill in all required fields');
    //       return;
    //     }


    //     var tr = $('#tr_clone table tr').clone();

    //     tr.find('[name="deduction_type[]"]').val(deductionText);
    //     tr.find('[name="type[]"]').val(type);
    //     tr.find('[name="deduction_amount[]"]').val(amount);
    //     tr.find('[name="effective_date[]"]').val(edate);

    //     tr.find('.deduction_type').text(deductionText);
    //     tr.find('.type').text($('#type option:selected').text());
    //     tr.find('.deduction_amount').text(amount);
    //     tr.find('.edate').text(edate ? edate : 'N/A');

    //     $('#deduction-list tbody').append(tr);

    //     $('#deduction_type').val('');
    //     $('#type').val('');
    //     $('#deduction_amount').val('');
    //     $('#edate').val('');
    //     $('#dfield').hide();
    //   });



    //   $('#new_deduction').click(function() {
    //     $('#view').modal('hide');
    //     $('#deductionModal').modal('show');
    //   });

    //   $(document).on('submit', '#deductionForm', function(event) {
    //     event.preventDefault();

    //     var employeeId = $('.empid').val();

    //     var deductionData = [];


    //     var formData = $(this).serializeArray();
    //     $('#deduction-list tbody tr').each(function() {
    //       var rowData = $(this).find('input, select').serializeArray();
    //       deductionData.push(...rowData);
    //     });


    //     deductionData.push({
    //       name: 'id',
    //       value: employeeId
    //     });

    //     console.log("Form data:", deductionData);

    //     $.ajax({
    //       url: 'ajax/deduction_add.php',
    //       type: 'POST',
    //       data: deductionData,
    //       dataType: 'json',
    //       success: function(response) {
    //         console.log("Server response:", response);
    //         if (response.success) {
    //           $('#deductionModal').modal('hide');
    //           getDeductions($('.empid').val());
    //           $('#view').modal('show');
    //         } else {
    //           alert('Failed to save data: ' + response.error);
    //         }
    //       },
    //       error: function(xhr, status, error) {
    //         console.error("AJAX error:", xhr.responseText);
    //         alert("AJAX error: " + error);
    //       }
    //     });
    //   });


    // });


    // function getDeductions(employee_id) {
    //   $.ajax({
    //     url: 'ajax/get_deductions.php',
    //     type: 'POST',
    //     data: {
    //       id: employee_id
    //     },
    //     success: function(response) {
    //       $('.list-group').html(response);
    //     }
    //   });
    // }

    $(function() {
     
      $('.delete').click(function(e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.photo').click(function(e) {
        e.preventDefault();
        $('#edit_photo').modal('show');
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
          emp: id
        },
        dataType: 'json',
        success: function(response) {
          console.log(response);
          $('.empid').val(response.employeeid);
          $('.names_id').val(response.namesid);
            $('.address_id').val(response.addressid);
          $('.employee_id').html(response.employeeid);
          $('.del_employee_name').html(response.firstname + ' ' + response.lastname);
               
        }
      });

    }
  </script>
  <!-- <script>
    $(document).on('click', '.remove_deduction', function() {
      var deductionId = $(this).data('id');
      $('#deductionIdToDelete').val(deductionId);
    });
    $(document).ready(function() {
      if (window.location.hash === '#view') {
        $('#view').modal('show');
      }
    });
  </script> -->

  <!-- <script>
    function generateRandomCode(length) {
      const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      let randomString = 'JV-';
      for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
      }
      return randomString;
    }

    function generateQrCode() {
      const text = generateRandomCode(10);
      if (!text) {
        alert("Failed to generate QR code.");
        return;
      }

      const qrContainer = document.getElementById('qrCode');
      qrContainer.innerHTML = ""; // Clear previous QR code

      new QRCode(qrContainer, {
        text: text,
        width: 150,
        height: 150
      });

      document.getElementById('generatedCode').value = text;
      document.querySelector('.qr-con').style.display = 'block';
    }

    // Automatically generate QR code when modal is shown
    const exampleModal = document.getElementById('exampleModal');
    exampleModal.addEventListener('shown.bs.modal', generateQrCode);
  </script> -->



  <!-- <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const faceImageInput = document.getElementById('face_image');

        // Start webcam
        navigator.mediaDevices.getUserMedia({ video: true }).then((stream) => {
            video.srcObject = stream;
        });

        // Capture face
        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            faceImageInput.value = canvas.toDataURL('image/png');
            alert("Face captured!");
        });

        // Submit form
        document.getElementById('registrationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const response = await fetch('register.php', {
                method: 'POST',
                body: formData
            });
            alert(await response.text());
        });
    </script> -->

  <!-- <script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');
    const capturedImages = document.getElementById('capturedImages');
    const faceImagesInput = document.getElementById('face_images');

    let capturedImageArray = [];

    // Access webcam
    navigator.mediaDevices.getUserMedia({
        video: true
      })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(error => console.error(error));

    // Capture face on button click
    captureButton.addEventListener('click', () => {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

      // Get the image as a base64 string
      const imageData = canvas.toDataURL('image/png');
      capturedImageArray.push(imageData);

      // Show thumbnails of captured images
      const imgElement = document.createElement('img');
      imgElement.src = imageData;
      imgElement.style.width = '100px';
      imgElement.style.margin = '5px';
      capturedImages.appendChild(imgElement);

      // Update hidden input
      faceImagesInput.value = JSON.stringify(capturedImageArray);
    });
  </script> -->
</body>


</html>