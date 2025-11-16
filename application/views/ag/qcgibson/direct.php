<style>
  /* === CARD FORM INPUT STYLING === */
  .card-form label {
    display: block;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
    font-size: 18px;
  }

  .required-star {
    color: red;
    margin-left: 4px;
  }

  input[type="text"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d9d9d9;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
    margin-bottom: 12px;
    transition: border 0.2s, box-shadow 0.2s;
  }

  input[type="text"]:focus {
    outline: none;
    border-color: #acd1f8ff;
    box-shadow: 0 0 4px #acd1f8ff;
  }

  .scan-container {
    border: 2px dashed #d9d9d9;
    border-radius: 6px;
    background: #f9f9f9;
    min-height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    color: #555;
    cursor: text;
    padding: 10px;
    text-align: center;
    transition: all 0.25s ease-in-out;
  }

  .scan-container.active {
    border: 2px solid #acd1f8ff !important;
    background: #eef6ff;
  }

  .scan-container .cursor-blink {
    width: 2px;
    height: 22px;
    background: #333;
    display: inline-block;
    margin-left: 4px;
    animation: blinkCursor 0.8s steps(2, start) infinite;
    vertical-align: middle;
  }

  @keyframes blinkCursor {

    0%,
    100% {
      opacity: 1;
    }

    50% {
      opacity: 0;
    }
  }

  #serial_no {
    display: none;
  }

  /* === SUBMIT BUTTON === */
  #submitBtn {
    width: 100%;
    padding: 12px 0;
    border: 1px solid #d9d9d9;
    background: #f2f2f2;
    cursor: pointer;
    font-weight: bold;
    color: #555;
    border-radius: 6px;
    font-size: 16px;
    transition: 0.2s;
  }

  #submitBtn:hover:not(:disabled) {
    background: #e6e6e6;
  }

  #submitBtn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* Spinner kecil */
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