<style>
/* === MAIN CARD FORM === */
.card-form {
  border-radius: 8px;
  padding: 20px;
  max-width: 900px;
  margin: 40px auto;
  font-family: Arial, sans-serif;
  font-size: 18px;
}

.card-form-section {
  background: #fafafa;
  border: 1px solid #d9d9d9;
  border-radius: 6px;
  margin-bottom: 20px;
  overflow: hidden;
}

.card-form-section-header {
  background: #d1e0f3;
  padding: 12px 20px;
  font-weight: bold;
  font-size: 18px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #555;
}

.card-form-section-body {
  position: relative;
  padding: 20px;
  background: #fff;
  font-size: 17px;
}

/* === FORM ELEMENTS === */
label {
  display: block;
  font-weight: bold;
  color: #666;
  margin-bottom: 6px;
  font-size: 16px;
}

/* Red star for required field */
.required-star {
  color: red;
  margin-left: 4px;
  font-weight: bold;
}

input[type="text"] {
  width: 100%;
  padding: 8px 10px;
  border: 1px dashed #999;
  border-radius: 4px;
  font-size: 16px;
  box-sizing: border-box;
  margin-bottom: 15px;
}

.btn-group {
  margin-top: 15px;
  text-align: right;
}

button {
  padding: 6px 14px;
  border: 1px solid #d9d9d9;
  background: #f2f2f2;
  cursor: pointer;
  font-weight: bold;
  color: #555;
  margin-left: 6px;
  border-radius: 4px;
  transition: 0.2s;
  font-size: 16px;
}

button:hover {
  background: #e6e6e6;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* === ERROR / SUCCESS CARD === */
.error-card {
  position: relative;
  background: #fff;
  border: 1px solid #d9d9d9;
  border-radius: 6px;
  padding: 20px 25px;
  margin-bottom: 20px;
  display: none;
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity 0.4s ease, transform 0.4s ease;
  font-size: 16px;
  max-height: 300px;
  overflow: hidden;
  z-index: 5;
}

.error-card.show {
  display: block;
  opacity: 1;
  transform: translateY(0);
}

.error-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.error-header-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.error-card h2 {
  font-size: 20px;
  font-weight: bold;
  margin: 0;
  color: #000;
}

.error-controls {
  display: flex;
  gap: 6px;
}

.error-btn {
  background: #fff;
  border: 1px solid #d9d9d9;
  cursor: pointer;
  font-weight: bold;
  font-size: 16px;
  border-radius: 4px;
  text-align: center;
  line-height: 22px;
}

.error-btn:hover {
  background: #e6e6e6;
  color: #555;
}

.error-card hr {
  border: none;
  border-top: 1px solid #d9d9d9;
  margin: 15px 0;
}

/* === CONTENT INSIDE CARD === */
.error-content {
  opacity: 1;
  margin-top: 10px;
}

.error-content.collapsed {
  display: none;
}

.error-container {
  color: red;
  margin-top: 5px;
  font-size: 15px;
  max-height: 200px;
  overflow-y: auto;
  padding-right: 5px;
}

.error-list p {
  margin-left: 10px;
  animation: fadeIn 0.3s ease forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-3px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Small loading spinner */
.spinner {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid #999;
  border-top-color: transparent;
  border-radius: 50%;
  margin-right: 6px;
  animation: spin 0.8s linear infinite;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.error-container::-webkit-scrollbar {
  width: 6px;
}
.error-container::-webkit-scrollbar-thumb {
  background-color: rgba(0,0,0,0.3);
  border-radius: 3px;
}
.error-container::-webkit-scrollbar-track {
  background: transparent;
}
</style>

<div class="card-form">
  <div class="card-form-section">
    <div class="card-form-section-header">
      <span>Direct Serial Input</span>
    </div>
    <div class="card-form-section-body">

      <!-- === MESSAGE CARD === -->
      <div class="error-card" id="errorCard">
        <div class="error-header">
          <div class="error-header-left">
            <h2 id="errorTitle">Error!</h2>
          </div>
          <div class="error-controls">
            <button class="error-btn collapse-btn">-</button>
            <button class="error-btn close-btn">X</button>
          </div>
        </div>
        <hr />
        <div class="error-content">
        <div class="error-content">
          <div class="error-container" id="genericErrorList"></div>
        </div>
      </div>

      <!-- === FORM === -->
      <form id="directForm">
        <label for="serial_no">
          <span class="required-star">*</span> Serial Number
        </label>
        <input type="text" id="serial_no" name="serial_no" required placeholder="Example: 25122300375" />
        <div class="btn-group">
          <button type="reset" id="resetBtn" disabled>Reset</button>
          <button type="submit" id="submitBtn" disabled>Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
const form = document.getElementById("directForm");
const serialInput = document.getElementById("serial_no");
const submitBtn = document.getElementById("submitBtn");
const resetBtn = document.getElementById("resetBtn");
const errorCard = document.getElementById("errorCard");
const errorTitle = document.getElementById("errorTitle");
const errorListDiv = document.getElementById("genericErrorList");
const collapseBtn = document.querySelector(".collapse-btn");
const closeBtn = document.querySelector(".close-btn");
const errorContent = document.querySelector(".error-content");

// Initial state
submitBtn.disabled = true;
resetBtn.disabled = true;
closeBtn.disabled = true;

// Enable buttons when input is not empty
serialInput.addEventListener("input", () => {
  const hasText = serialInput.value.trim().length > 0;
  submitBtn.disabled = !hasText;
  resetBtn.disabled = !hasText;
  closeBtn.disabled = !hasText;
});

// Submit form
form.addEventListener("submit", async function(e) {
  e.preventDefault();
  const serialNo = serialInput.value.trim();
  if (!serialNo) {
    showErrorCard("Serial number cannot be empty!");
    return;
  }

  const url = "http://localhost/spmsg/index.php/ag/qcgibson/savedirect";
  const data = new FormData();
  data.append("serial_no", serialNo);

  // Loading spinner
  const originalText = submitBtn.innerHTML;
  submitBtn.disabled = true;
  resetBtn.disabled = true;
  submitBtn.innerHTML = `<span class="spinner"></span> Saving...`;

  try {
    const response = await fetch(url, { method: "POST", body: data });
    console.log("Response status:", response.status);

    const text = await response.text();
    console.log("Raw server response:", text);

    let result;
    try { result = JSON.parse(text); } 
    catch {
      showErrorCard("Invalid JSON response from server.");
      return;
    }

    if (result.status === "error") showErrorCard(result.message);
    else if (result.status === "success") showSuccessCard(result.message);
    else showErrorCard("Unknown server response.");
  } 
  catch (err) {
    console.error("Fetch error:", err);
    showErrorCard("Failed to connect to server. Please check if backend is running.");
  } 
  finally {
    submitBtn.innerHTML = originalText;
    submitBtn.disabled = false;
    resetBtn.disabled = false;
  }
});

/* ==========================
   SHOW ERROR CARD
   ========================== */
function showErrorCard(message) {
  errorTitle.textContent = "Error!";
  errorTitle.style.color = "red";
  collapseBtn.style.display = "inline-block";
  closeBtn.style.display = "none";
  errorListDiv.innerHTML = `<p>${message}</p>`;
  errorCard.style.display = "block";
  setTimeout(() => errorCard.classList.add("show"), 10);
}

/* ==========================
   SHOW SUCCESS CARD
   ========================== */
function showSuccessCard(message) {
  errorTitle.textContent = "Success!";
  errorTitle.style.color = "#2b7a0b"; // green

  collapseBtn.style.display = "none";
  closeBtn.style.display = "inline-block";

  errorListDiv.innerHTML = `<p style="color: black;">${message}</p>`;
  errorCard.style.display = "block";
  setTimeout(() => errorCard.classList.add("show"), 10);

  // Clear input and disable buttons after success
  serialInput.value = "";
  submitBtn.disabled = true;
  resetBtn.disabled = true;
  closeBtn.disabled = true;

  // Auto-hide success message after 10 seconds
  setTimeout(() => {
    errorCard.classList.remove("show");
    setTimeout(() => (errorCard.style.display = "none"), 400);
  }, 10000);
}

// Close and collapse
closeBtn.addEventListener("click", () => {
  errorCard.classList.remove("show");
  setTimeout(() => (errorCard.style.display = "none"), 400);
});

collapseBtn.addEventListener("click", () => {
  if (errorContent.classList.contains("collapsed")) {
    errorContent.classList.remove("collapsed");
    collapseBtn.textContent = "-";
  } else {
    errorContent.classList.add("collapsed");
    collapseBtn.textContent = "+";
  }
});

resetBtn.addEventListener("click", () => {
  serialInput.value = "";
  submitBtn.disabled = true;
  resetBtn.disabled = true;
  closeBtn.disabled = true;
  errorCard.classList.remove("show");
  setTimeout(() => (errorCard.style.display = "none"), 400);
});
</script>
