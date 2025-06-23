let labels = [];
let detectedFaces = [];
let sendingData = false;
let markedEmployees = new Set(); 
let modelsLoaded = false;

function loadEmployeeDataAndFaceModels() {
  fetch("get_faces.php")
    .then((response) => response.json())
    .then((data) => {
      console.log("API Response:", data);

      if (data.status === "success" && Array.isArray(data.employee_numbers)) {
        labels = data.employee_numbers.map((employee_no) => ({
          label: employee_no,
          name: employee_no // Assuming the employee number is the name as well
        }));

        console.log("Employee data loaded successfully:", labels);
        loadFaceRecognitionModels(); // Load face recognition models after employee data
      } else {
        console.error("No employee data found or invalid format:", data);
        alert("Failed to fetch employee data.");
      }
    })
    .catch((error) => {
      console.error("Error loading employee data:", error);
    });
}

// Load face recognition models
async function loadFaceRecognitionModels() {
  console.log("Loading face recognition models...");

  try {
    await Promise.all([
      faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
      faceapi.nets.faceRecognitionNet.loadFromUri("models"),
      faceapi.nets.faceLandmark68Net.loadFromUri("models"),
    ]);
    modelsLoaded = true;
    console.log("Models loaded successfully.");
    startFaceDetection(); // Start face detection after models are loaded
  } catch (error) {
    console.error("Error loading models:", error);
    alert("Models not loaded, please check your model folder location.");
  }
}

// Get labeled face descriptors for recognition
async function getLabeledFaceDescriptions() {
  const labeledDescriptors = [];
  console.log("Fetching labeled face descriptors");

  for (const label of labels) {
    const descriptions = [];

    for (let i = 1; i <= 5; i++) {
      try {
        const imagePath = `assets/images/faces/${label.label}/${label.label}_face_${i}.png`;
        console.log(`Fetching image: ${imagePath}`);
        const img = await faceapi.fetchImage(imagePath);
        const detections = await faceapi
          .detectSingleFace(img)
          .withFaceLandmarks()
          .withFaceDescriptor();

        if (detections) {
          descriptions.push(detections.descriptor);
          console.log("Face detected for:", label.name);
        } else {
          console.log(`No face detected in ${imagePath}`);
        }
      } catch (error) {
        console.error(`Error processing ${label.label}_face_${i}.png:`, error);
      }
    }

    if (descriptions.length > 0) {
      labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(label.label, descriptions));
    }
  }

  return labeledDescriptors;
}

// Start face detection
async function startFaceDetection() {
  if (!modelsLoaded || labels.length === 0) {
    console.log("Waiting for models or labels to load...");
    return;
  }

  const video = document.getElementById("preview");
  const canvas = document.getElementById("overlay");
  const context = canvas.getContext("2d");

  // Match canvas dimensions with video dimensions
  const displaySize = { width: video.width, height: video.height };
  faceapi.matchDimensions(canvas, displaySize);

  video.addEventListener("play", async () => {
    const labeledFaceDescriptors = await getLabeledFaceDescriptions();
    const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

    setInterval(async () => {
      const detections = await faceapi
        .detectAllFaces(video)
        .withFaceLandmarks()
        .withFaceDescriptors();

      const resizedDetections = faceapi.resizeResults(detections, displaySize);
      context.clearRect(0, 0, canvas.width, canvas.height);

      const results = resizedDetections.map((d) => faceMatcher.findBestMatch(d.descriptor));
      detectedFaces = results.map((result) => result.label);
      markAttendance(detectedFaces);

      console.log("Detected faces:", detectedFaces);

      results.forEach((result, i) => {
        const box = resizedDetections[i].detection.box;
        const label = result.toString();
        const drawBox = new faceapi.draw.DrawBox(box, { label });

        drawBox.draw(canvas);
      });
    }, 100);
  });
}

function showNotification(message, color) {
  const notification = document.getElementById("notification");

  // Update notification text and style
  notification.textContent = message;
  notification.style.color = color;

  // Show the notification for a limited time
  notification.style.display = "block";
  setTimeout(() => {
    notification.style.display = "none";
  }, 5000); // Hide after 5 seconds
}




let isResetScheduled = false; 

function resetDailyState() {
  if (isResetScheduled) return; // Prevent multiple schedules
  isResetScheduled = true;

  const now = new Date();
  const millisTillMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0) - now;

  setTimeout(() => {
    console.log("Resetting daily state...");
    markedEmployees.clear(); // Clear marked employees
    clockOutStatus = {};     // Reset clock-out status
    isResetScheduled = false; // Allow scheduling for the next day
    resetDailyState();       // Schedule the next reset
  }, millisTillMidnight);
}

resetDailyState(); 


let clockOutStatus = {}; 


function resetDailyState() {
  if (isResetScheduled) return; // Prevent multiple schedules
  isResetScheduled = true;

  const now = new Date();
  const millisTillMidnight = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0) - now;

  setTimeout(() => {
    console.log("Resetting daily state...");
    markedEmployees.clear(); // Clear marked employees
    clockOutStatus = {};     // Reset clock-out status
    isResetScheduled = false; // Allow scheduling for the next day
    resetDailyState();       // Schedule the next reset
  }, millisTillMidnight);
}

resetDailyState(); // Initial call to start the reset process

function markAttendance(detectedFaces) {
  console.log("Detected Faces:", detectedFaces);

  if (sendingData) return;
  sendingData = true;

  let promises = [];
  const knownEmployeNos = new Set(labels.map(label => label.label)); // Set of known employee numbers

  detectedFaces.forEach((face) => {
    const employee_no = face;

    if (!knownEmployeNos.has(employee_no)) {
      console.log(`Unknown face detected: ${employee_no}. No attendance will be marked.`);
      return;
    }

    let promise = fetch('check_clock_in_status.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ employee_no: employee_no })
    })
      .then((response) => response.json())
      .then((data) => {

        const currentTime = new Date(); 
        const lastClockInTime = new Date(data.last_time_in); 

        // Check if lastClockInTime is valid
        if (isNaN(lastClockInTime)) {
          console.log("Error: Invalid last clock-in time format.");
          showNotification("Error: Invalid last clock-in time format. Please try again.", "red");
          return;
        }

        const timeDiffInMinutes = Math.floor((currentTime - lastClockInTime) / (1000 * 60));

        if (isNaN(timeDiffInMinutes)) {
          console.log("Error calculating time difference.");
          showNotification("Error calculating time difference. Please try again.", "red");
          return;
        }

        console.log(`Time Difference: ${timeDiffInMinutes} minutes`);

        if (data.status === 'not_clocked_in') {
          console.log(`Employee ${employee_no} is clocking in.`);
          saveAttendanceToDatabase(employee_no, currentTime.toISOString(), "present");
          showNotification(`Employee ${employee_no} clocked in successfully.`, "green");
        } else if (data.status === 'already_clocked_in') {
          // Check if the employee has already clocked out today
          if (clockOutStatus[employee_no]) {
            console.log(`Employee ${employee_no} has already clocked out today. Skipping clock-out.`);
            showNotification(`Employee ${employee_no} has already clocked out today.`, "yellow");
            return;  // Skip clock-out if already clocked out
          }

          // If the employee hasn't clocked out yet, proceed with clock-out
          if (timeDiffInMinutes >= 30) {
            console.log(`Employee ${employee_no} is clocking out.`);
            updateTimeOutInDatabase(employee_no, currentTime.toISOString());
            clockOutStatus[employee_no] = true; // Mark as clocked out
            showNotification(`Employee ${employee_no} clocked out successfully.`, "red");
          } else {
            console.log(`Employee ${employee_no} must wait for ${30 - timeDiffInMinutes} more minutes to clock out.`);
            showNotification(`Employee ${employee_no} must wait ${30 - timeDiffInMinutes} more minutes to clock out.`, "yellow");
          }

        } else if (data.status === 'error') {
          console.log(`Error: ${data.message}`);
        } else {
          console.log("Error: Unknown status received.");
        }
      })
      .catch((error) => {
        console.error('Error checking clock-in status:', error);
      });

    promises.push(promise);
  });

  Promise.all(promises)
    .then(() => {
      sendingData = false;
    })
    .catch((error) => {
      console.error('Error processing attendance:', error);
      sendingData = false;
    });
}


function saveAttendanceToDatabase(employee_no, timeIn, status = "present") {
  fetch('save_attendance.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      employee_no: employee_no,
      time_in: timeIn,
      status: status
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 'success') {
        console.log(`Attendance for employee ${employee_no} saved successfully.`);
        updateEmployeeTable();
      } else {
        console.log('Failed to save attendance:', data.message);
      }
    })
    .catch((error) => {
      console.error('Error saving attendance:', error);
    });
}

function updateTimeOutInDatabase(employee_no, timeOut) {
  fetch('update_time_out.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      employee_no: employee_no,
      time_out: timeOut
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 'success') {
        console.log(`Time out for employee ${employee_no} updated successfully.`);
        updateEmployeeTable();
      } else {
        console.log('Failed to update time out:', data.message);
      }
    })
    .catch((error) => {
      console.error('Error updating time out:', error);
    });
}



function updateEmployeeTable() {
  fetch("fetch_employee_attendance.php")
    .then((response) => response.text())
    .then((html) => {
      document.getElementById("employeeTable").innerHTML = html;
    })
    .catch((error) => {
      console.error("Error updating employee table:", error);
    });
}


// Initialize the employee data and face models loading
loadEmployeeDataAndFaceModels();
