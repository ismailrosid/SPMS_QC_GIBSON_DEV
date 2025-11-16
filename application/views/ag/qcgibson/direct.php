<style>
  /* === MAIN CARD FORM === */
  .card-form {
    border-radius: 8px;
    padding: 20px;
    max-width: 600px;
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

  .card-form-section-body {
    position: relative;
    padding: 20px;
    background: #fff;
    font-size: 17px;
  }

  label {
    display: block;
    font-weight: bold;
    color: #333;
    margin-bottom: 16px;
    font-size: 24px;
  }

  .required-star {
    color: red;
    margin-left: 4px;
    font-weight: bold;
  }

  .scan-container {
    border: 2px dashed #d9d9d9;
    border-radius: 8px;
    background: #f9f9f9;
    min-height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: #555;
    cursor: text;
    text-align: center;
    padding: 10px;
    transition: all 0.25s ease-in-out;
    position: relative;
  }

  .scan-container.active,
  .scan-container:focus {
    border: 2px solid #acd1f8ff !important;
    background: #eef6ff;
    outline: none;
  }

  /* ===== CURSOR BLINK ===== */
  /* ===== CURSOR BLINK ===== */
  .scan-container.active .cursor-blink {
    width: 2px;
    height: 22px;
    background: #333;
    display: inline-block;
    margin-left: 4px;
    animation: blinkCursor 0.8s steps(2, start) infinite;
    vertical-align: middle;
  }

  @keyframes blinkCursor {
    0% {
      opacity: 1;
    }

    50% {
      opacity: 0;
    }

    100% {
      opacity: 1;
    }
  }

  /* ========================= */

  #serial_no {
    display: none;
  }

  .btn-group {
    margin-top: 20px;
    text-align: right;
  }

  #submitBtn {
    width: 100%;
    padding: 12px 0;
    border: 1px solid #d9d9d9;
    background: #f2f2f2;
    cursor: pointer;
    font-weight: bold;
    color: #555;
    border-radius: 6px;
    font-size: 18px;
    transition: 0.2s;
  }

  #submitBtn:hover {
    background: #e6e6e6;
  }

  #submitBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #999;
    border-top-color: transparent;
    border-radius: 50%;
    margin-right: 8px;
    animation: spin 0.8s linear infinite;
    vertical-align: middle;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

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
    opacity: 1;
    transform: translateY(0);
    display: block;
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

  .error-content {
    opacity: 1;
    margin-top: 10px;
  }

  .error-container {
    color: red;
    font-size: 15px;
    max-height: 200px;
    overflow-y: auto;
    padding-right: 5px;
  }

  .error-container::-webkit-scrollbar {
    width: 6px;
  }

  .error-container::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 3px;
  }

  .error-container::-webkit-scrollbar-track {
    background: transparent;
  }
</style>

<div class="card-form">
  <div class="card-form-section">
    <div class="card-form-section-body">
      <div class="error-card" id="errorCard">
        <div class="error-header">
          <div class="error-header-left">
            <h2 id="errorTitle">Error!</h2>
          </div>
          <div class="error-controls">
            <button class="error-btn close-btn">X</button>
          </div>
        </div>
        <hr />
        <div class="error-content">
          <div class="error-container" id="genericErrorList"></div>
        </div>
      </div>

      <form id="directForm">
        <label><span class="required-star">*</span> Serial Number</label>
        <div class="scan-container" id="scanContainer" tabindex="0">
          Scan or type serial number here
        </div>
        <input type="text" id="serial_no" name="serial_no" required />
        <div class="btn-group">
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
  const errorCard = document.getElementById("errorCard");
  const errorListDiv = document.getElementById("genericErrorList");
  const closeBtn = document.querySelector(".close-btn");
  const scanContainer = document.getElementById("scanContainer");
  submitBtn.disabled = true;

  function updateSubmitState() {
    submitBtn.disabled = !serialInput.value.trim();
  }

  scanContainer.addEventListener("click", () => {
    scanContainer.classList.add("active");
    scanContainer.focus();
    refreshDisplay();
  });

  scanContainer.addEventListener("blur", () => {
    if (!serialInput.value.trim()) scanContainer.classList.remove("active");
    refreshDisplay();
  });

  scanContainer.addEventListener("keydown", (e) => {
    if (e.key === "Backspace") {
      serialInput.value = serialInput.value.slice(0, -1);
    } else if (e.key.length === 1) {
      serialInput.value += e.key.toUpperCase();
    }

    refreshDisplay();
    updateSubmitState();
  });

  scanContainer.addEventListener("paste", (e) => {
    e.preventDefault();
    const pasteText = (e.clipboardData || window.clipboardData).getData("text").trim();
    if (pasteText) {
      serialInput.value = pasteText.toUpperCase();
      scanContainer.classList.add("active");
    }
    refreshDisplay();
    updateSubmitState();
  });

  function refreshDisplay() {
    if (!serialInput.value.trim()) {
      scanContainer.innerHTML = "Scan or type serial number here";
      return;
    }

    scanContainer.innerHTML =
      serialInput.value.toUpperCase() + `<span class="cursor-blink"></span>`;
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const serialNo = serialInput.value.trim();
    if (!serialNo) return;

    submitBtn.disabled = true;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `<span class="spinner"></span> Processing...`;

    try {
      const data = new FormData();
      data.append("serial_no", serialNo);
      const response = await fetch("<?= site_url('ag/qcgibson/savedirect'); ?>", {
        method: "POST",
        body: data,
      });
      const result = await response.json();
      if (result.status === "success") showCard(result.message, "success");
      else showCard(result.message, "error");
    } catch {
      showCard("Failed to connect to server.", "error");
    } finally {
      submitBtn.innerHTML = originalText;
      updateSubmitState();
    }
  });

  function showCard(message, type) {
    const errorTitle = document.getElementById("errorTitle");
    errorTitle.textContent = type === "success" ? "Success!" : "Error!";
    errorTitle.style.color = type === "success" ? "black" : "red";
    const textColor = type === "success" ? "green" : "red";
    errorListDiv.innerHTML = `<p style="color:${textColor};">${message}</p>`;
    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);

    // if (type === "success") {
    //   serialInput.value = "";
    //   scanContainer.innerHTML = "Scan or type serial number here";
    //   scanContainer.classList.remove("active");
    //   submitBtn.disabled = true;
    // }

    setTimeout(() => {
      errorCard.classList.remove("show");
      setTimeout(() => (errorCard.style.display = "none"), 400);
    }, 5000);
  }

  closeBtn.addEventListener("click", () => {
    errorCard.classList.remove("show");
    setTimeout(() => (errorCard.style.display = "none"), 400);
  });
</script>