var labels = []; // Array to store employee numbers
let detectedFaces = [];

// Fetch employee data with face paths
function fetchEmployeeData() {
  fetch("getfaces.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        labels = data.data.map((item) => item.employee_no);
        loadFaceRecognitionModels();
      } else {
        console.error(data.message);
      }
    })
    .catch((error) => console.error("Error fetching employee data:", error));
}

// Load Face API models
function loadFaceRecognitionModels() {
  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models")
  ])
    .then(() => console.log("Models loaded"))
    .catch((err) => console.error("Error loading models:", err));
}

// Mark attendance on match
function markAttendance(detectedFaces) {
  const rows = document.querySelectorAll("#studentTableContainer tr");
  rows.forEach((row) => {
    const employee_no = row.cells[0].innerText.trim();
    if (detectedFaces.includes(employee_no)) {
      row.cells[3].innerText = "Present";
    }
  });
}

async function getLabeledFaceDescriptions() {
  const labeledDescriptors = [];
  const detectedFaces = [];

  for (const label of labels) {
    console.log(`Processing faces for label: ${label}`);
    const descriptions = [];

    for (let i = 1; i <= 5; i++) {
      try {
        const img = await faceapi.fetchImage(`uploads/faces/${label}/${i}.png`);
        const detections = await faceapi
          .detectSingleFace(img)
          .withFaceLandmarks()
          .withFaceDescriptor();

        if (detections) {
          descriptions.push(detections.descriptor);
        } else {
          console.warn(`No face detected in ${label}/${i}.png`);
        }
      } catch (error) {
        console.error(`Error processing ${label}/${i}.png:`, error);
      }
    }

    if (descriptions.length > 0) {
      detectedFaces.push(label);
      labeledDescriptors.push(
        new faceapi.LabeledFaceDescriptors(label, descriptions)
      );
      console.log(`Successfully labeled: ${label}`);
    } else {
      console.warn(`No valid faces detected for label: ${label}`);
    }
  }

  return labeledDescriptors;
}

// Initialize Webcam and Face Recognition
function startFaceDetection() {
  const video = document.getElementById("preview");
  navigator.mediaDevices
    .getUserMedia({ video: true })
    .then((stream) => {
      video.srcObject = stream;
      video.addEventListener("play", () => {
        const canvas = faceapi.createCanvasFromMedia(video);
        document.body.append(canvas);
        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
          const detections = await faceapi
            .detectAllFaces(video)
            .withFaceLandmarks()
            .withFaceDescriptors();
          const resizedDetections = faceapi.resizeResults(detections, displaySize);
          const faceMatcher = new faceapi.FaceMatcher(labels);

          const results = resizedDetections.map((d) =>
            faceMatcher.findBestMatch(d.descriptor)
          );

          detectedFaces = results.map((result) => result.label);
          markAttendance(detectedFaces);
        }, 1000);
      });
    })
    .catch((err) => console.error("Error accessing webcam:", err));
}

// Call on page load
fetchEmployeeData();
