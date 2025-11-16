<style>
  /* ==========================
   MAIN CARD FORM STYLING
   ========================== */
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

  label {
    display: block;
    font-weight: bold;
    color: #666;
    margin-bottom: 6px;
    font-size: 16px;
  }

  input[type="text"],
  select {
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    margin-bottom: 4px;
    box-sizing: border-box;
    font-size: 16px;
  }

  input:focus,
  select:focus {
    outline: none;
    border-color: #bfbfbf;
    box-shadow: none;
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

  button:hover:not(:disabled) {
    background: #e6e6e6;
  }

  button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* ==========================
   ERROR CARD STYLING
   ========================== */
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

  .error-content.collapsed {
    display: none;
  }

  .error-group {
    margin-bottom: 18px;
  }

  .error-title {
    display: inline-block;
    background-color: #ddd;
    padding: 3px 8px;
    font-weight: bold;
    color: #000000ce;
    margin-bottom: 6px;
    font-size: 16px;
  }

  .error-container {
    margin-left: 0px;
    color: red;
    margin-top: 5px;
    font-size: 15px;
    max-height: 200px;
    overflow-y: auto;
    padding-right: 5px;
  }

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
    to {
      transform: rotate(360deg);
    }
  }
</style>

<div class="card-form">
  <div class="card-form-section">
    <div class="card-form-section-header"><span>Form Redirect</span></div>

    <div class="card-form-section-body">

      <!-- ERROR CARD -->
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
          <div class="error-group">
            <div class="error-container" id="genericErrorList"></div>
          </div>
        </div>
      </div>

      <!-- FORM SERIAL NUMBER -->
      <form id="serialForm">
        <!-- DEFECT CODE SELECT -->
        <label>Defect Code <span style="color:red">*</span></label>
        <select id="defectSelect" name="defect_code" required>
          <option value="NULL" selected>No Defect</option>
          <?php foreach ($defects as $d): ?>
            <option value="<?= $d['defect_code'] ?>"><?= $d['defect_code'] ?> - <?= $d['defect_name'] ?></option>
          <?php endforeach; ?>
        </select>

        <!-- COUNTRY -->
        <label style="margin-top:15px;">Country <span style="color:red">*</span></label>
        <select id="countrySelect" name="country" required>
          <option value="ALL" selected>All</option>
          <option value="CNY">China (CNY)</option>
          <option value="JPY">Japan (JPY)</option>
        </select>

        <!-- JUDGEMENT -->
        <label style="margin-top:15px;">Judgement <span style="color:red">*</span></label>
        <select id="judgementSelect" name="judgement" required>
          <option value="1" selected>Pass</option>
          <option value="0">Not Pass</option>
        </select>

        <hr>
        <!-- SERIAL NUMBER -->
        <label style="margin-top:15px;">Serial Number <span style="color:red">*</span></label>
        <input type="text" id="serial_no" name="serial_no" placeholder="Scan or type serial number..." required />

        <div class="btn-group">
          <button type="reset" id="resetBtn" disabled>Reset</button>
          <button type="submit" id="saveBtn" disabled>Submit</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  const defectSelect = document.getElementById("defectSelect");
  const serialInput = document.getElementById("serial_no");
  const saveBtn = document.getElementById("saveBtn");
  const resetBtn = document.getElementById("resetBtn");

  const errorCard = document.getElementById("errorCard");
  const errorListDiv = document.getElementById("genericErrorList");
  const collapseBtn = document.querySelector(".collapse-btn");
  const closeBtn = document.querySelector(".close-btn");
  const errorTitle = document.getElementById("errorTitle");
  const errorContent = document.querySelector(".error-content");

  function checkInput() {
    const hasSerial = serialInput.value.trim() !== "";
    const hasDefect = defectSelect.value !== "";
    saveBtn.disabled = !(hasSerial && hasDefect);
    resetBtn.disabled = !(hasSerial || hasDefect);
  }

  serialInput.addEventListener("input", checkInput);
  defectSelect.addEventListener("change", checkInput);

  document.getElementById("serialForm").addEventListener("submit", async function(e) {
    e.preventDefault();
    submitSerial();
  });

  resetBtn.addEventListener("click", function() {
    serialInput.value = "";
    defectSelect.value = "";
    saveBtn.disabled = true;
    resetBtn.disabled = true;
    hideCard();
  });

  closeBtn.addEventListener("click", hideCard);

  collapseBtn.addEventListener("click", function() {
    const collapsed = errorContent.classList.toggle("collapsed");
    collapseBtn.textContent = collapsed ? "+" : "-";
  });

  function hideCard() {
    errorCard.classList.remove("show");
    setTimeout(() => errorCard.style.display = "none", 400);
  }

  async function submitSerial() {
    if (!defectSelect.value) {
      showError("Please select a defect from the list.");
      return;
    }

    saveBtn.disabled = true;
    resetBtn.disabled = true;

    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = `<span class="spinner"></span> Submitting...`;

    const formData = new FormData();
    formData.append("serial_no", serialInput.value.trim());
    formData.append("defect_code", defectSelect.value);
    formData.append("country", document.getElementById("countrySelect").value);
    formData.append("judgement", document.getElementById("judgementSelect").value);

    try {
      const r = await fetch("<?= site_url('ag/qcgibson/savedirect'); ?>", {
        method: "POST",
        body: formData
      });
      const txt = await r.text();
      let result;
      try {
        result = JSON.parse(txt.trim());
      } catch {
        showError("Invalid server JSON");
        return;
      }

      if (result.status === "error") showError(result.message, result.errors || []);
      else if (result.status === "success") {
        showSuccess(result.message);
        serialInput.value = "";
        defectSelect.value = "";
      } else showError("Unknown server response.");

    } catch (err) {
      showError("Network error or server unreachable.");
    } finally {
      saveBtn.innerHTML = originalText;
      checkInput();
    }
  }

  function showError(message, groups = []) {
    errorTitle.textContent = "Error!";
    errorTitle.style.color = "red";
    collapseBtn.style.display = "inline-block";
    closeBtn.style.display = "none";

    errorListDiv.innerHTML = "";
    if (groups.length > 0) {
      groups.forEach(g => {
        if (g.title && g.items) {
          errorListDiv.innerHTML += `<div class="error-title">${g.title}</div>
            <div class="error-list">${g.items.map(i => `<p>${i}</p>`).join("")}</div>`;
        } else errorListDiv.innerHTML += `<p>${g}</p>`;
      });
    } else errorListDiv.innerHTML = `<p>${message}</p>`;

    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);
  }

  function showSuccess(message) {
    errorTitle.textContent = "Success!";
    errorTitle.style.color = "green";
    collapseBtn.style.display = "none";
    closeBtn.style.display = "inline-block";
    errorListDiv.innerHTML = `<p style="color:black;">${message}</p>`;
    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);
    setTimeout(hideCard, 5000);
  }
</script>