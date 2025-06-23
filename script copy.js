let labels = [];
let detectedFaces = [];
let sendingData = false;
let markedEmployees = new Set(); // Track employees who have already been marked
let modelsLoaded = false;
let clockInTimes = {};
let clockoutTimes = {};

const faceMatcherThreshold = 0.5; // Threshold for better matching

// Load employee data and face recognition models
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
      detectedFaces.push(label);
      labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(label.label, descriptions));
    }
  }

  return labeledDescriptors;
}

// Start face detection
async function startFaceDetection() {
  if (!modelsLoaded) {
    console.log("Waiting for models to load...");
    return; // Do nothing until models are loaded
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
        const label = result.toString(); // Get label as a string
        const drawBox = new faceapi.draw.DrawBox(box, { label });

        drawBox.draw(canvas); // Draw directly on the overlay canvas
      });
    }, 100);
  });
}

// Save attendance to the database
function saveAttendanceToDatabase(employee_no, timeIn, status = "present") {
  const currentTime = new Date().toISOString();

  fetch('save_attendance.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      employee_no: employee_no,  
      time_in: timeIn || currentTime, 
      status: status
    }),
  })
  .then((response) => response.json())  
  .then((data) => {
    if (data.status === 'success') {
      console.log(`Attendance for employee ${employee_no} saved successfully.`);
      updateEmployeeTable();  // Trigger table update after saving attendance
      localStorage.setItem(employee_no, 'clocked_in');  // Store clock-in status
    } else {
      console.log('Failed to save attendance:', data.message);
    }
  })
  .catch((error) => {
    console.error('Error saving attendance:', error);
  });
}

// Mark attendance only for new employees
function markAttendance(detectedFaces) {
  console.log("Detected Faces:", detectedFaces);



  if (sendingData) return; 
  sendingData = true;

  

  detectedFaces.forEach((face) => {
    
    const currentTime = new Date().toISOString();
    const employee_no = face; 

    // Skip if this employee has already been marked in this session or is already clocked-in
    if (markedEmployees.has(employee_no) || localStorage.getItem(employee_no) === 'clocked_in') {
      console.log(`Employee ${employee_no} has already been marked or clocked-in.`);
      return;
    }


    fetch('check_clock_in_status.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        employee_no: employee_no
      }),
    })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 'clocked_in') {
        console.log(`Employee ${employee_no} has already clocked in.`);
        localStorage.setItem(employee_no, 'clocked_in');  // Ensure the status is persisted
        
      } else {

        saveAttendanceToDatabase(employee_no, currentTime, "present");
        markedEmployees.add(employee_no); // Mark as processed
      }
      sendingData = false; // Allow the next request
    })
    .catch((error) => {
      console.error('Error checking clock-in status:', error);
      sendingData = false;
    });
  });
}

// Update the employee table with the latest attendance data
function updateEmployeeTable() {
  var xhr = new XMLHttpRequest();
  var url = "fetch_employee_attendance.php"; 

  xhr.open("GET", url, true);

  xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
          // Update the employee table with the new data
          document.getElementById("employeeTable").innerHTML = xhr.responseText;
      }
  };

  xhr.send();
}

// Initialize the employee data and face models loading
loadEmployeeDataAndFaceModels();
