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
  const form = document.getElementById("defectForm");
  const category = document.getElementById("category_code");
  const defectCode = document.getElementById("defect_code");
  const defectName = document.getElementById("defect_name");
  const saveBtn = document.getElementById("saveBtn");
  const resetBtn = document.getElementById("resetBtn");

  const errorCard = document.getElementById("errorCard");
  const errorListDiv = document.getElementById("genericErrorList");
  const closeBtn = document.querySelector(".close-btn");
  const collapseBtn = document.querySelector(".collapse-btn");

  // Initial button state
  saveBtn.disabled = true;
  resetBtn.disabled = true;

  function checkInput() {
    const isCategoryFilled = category.value.trim() !== "";
    const isDefectCodeFilled = defectCode.value.trim() !== "";
    const isDefectNameFilled = defectName.value.trim() !== "";

    saveBtn.disabled = !(isCategoryFilled && isDefectCodeFilled && isDefectNameFilled);
    resetBtn.disabled = !(isCategoryFilled || isDefectCodeFilled || isDefectNameFilled);
  }

  category.addEventListener("change", checkInput);
  defectCode.addEventListener("input", checkInput);
  defectName.addEventListener("input", checkInput);

  resetBtn.addEventListener("click", () => {
    category.value = "";
    defectCode.value = "";
    defectName.value = "";
    checkInput();
    hideCard();
  });

  collapseBtn.addEventListener("click", () => {
    const errorContent = document.querySelector(".error-content");
    const collapsed = errorContent.classList.toggle("collapsed");
    collapseBtn.textContent = collapsed ? "+" : "-";
  });

  closeBtn.addEventListener("click", hideCard);

  function hideCard() {
    errorCard.classList.remove("show");
    setTimeout(() => errorCard.style.display = "none", 400);
  }

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const catVal = category.value.trim();
    const codeVal = defectCode.value.trim();
    const nameVal = defectName.value.trim();

    if (!catVal || !codeVal || !nameVal) return;

    saveBtn.disabled = true;
    resetBtn.disabled = true;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = `<span class="spinner"></span> Saving...`;

    const formData = new FormData();
    formData.append("category_code", catVal);
    formData.append("defect_code", codeVal);
    formData.append("defect_name", nameVal);

    try {
      const response = await fetch("<?= site_url('ag/qcgibson/savedefect'); ?>", {
        method: "POST",
        body: formData
      });
      const result = await response.json();

      if (result.status === "success") {
        showCard(result.message, "success");
        category.value = "";
        defectCode.value = "";
        defectName.value = "";
      } else {
        // result.status === "error"
        const errors = result.errors || [];
        if (errors.length > 0) {
          let html = "";
          errors.forEach(g => {
            if (g.title && g.items) {
              html += `<div class="error-title">${g.title}</div>`;
              html += `<div class="error-list">${g.items.map(i=>`<p>${i}</p>`).join("")}</div>`;
            } else {
              html += `<p>${g}</p>`;
            }
          });
          errorListDiv.innerHTML = html;
        } else {
          errorListDiv.innerHTML = `<p style="color:red;">${result.message}</p>`;
        }
        showCard("", "error"); // only show card, message already set
      }

    } catch (err) {
      showCard("Failed to connect to server.", "error");
    } finally {
      saveBtn.innerHTML = originalText;
      checkInput();
    }
  });

  function showCard(message, type) {
    const errorTitle = document.getElementById("errorTitle");
    const errorContent = document.querySelector(".error-content");

    errorTitle.textContent = type === "success" ? "Success!" : "Error!";
    errorTitle.style.color = type === "success" ? "green" : "red";

    if (type === "success") {
      errorListDiv.innerHTML = `<p style="color:black;">${message}</p>`;
      collapseBtn.style.display = "none";
      closeBtn.style.display = "inline-block";
    } else {
      collapseBtn.style.display = "inline-block";
      closeBtn.style.display = "none";
    }

    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);

    // Auto-hide success after 5 sec
    if (type === "success") {
      setTimeout(hideCard, 5000);
    }
  }
</script>