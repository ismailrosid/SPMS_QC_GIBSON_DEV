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

  input[type="file"] {
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    margin-bottom: 4px;
    box-sizing: border-box;
    font-size: 16px;
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

  /* ==========================
   ERROR FIELD STYLING
   ========================== */
  .input-error {
    border-color: red;
    background-color: #ffe6e6;
  }

  #fileErrorMsg {
    color: red;
    font-size: 14px;
    display: none;
    margin-top: 4px;
  }

  /* ==========================
   ERROR / SUCCESS CARD STYLING
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

  .error-list p {
    margin-left: 10px;
    animation: fadeIn 0.3s ease forwards;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-3px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
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

<body>
  <div class="card-form">
    <div class="card-form-section">
      <div class="card-form-section-header"><span>File Upload</span></div>
      <div class="card-form-section-body">

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

        <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
          <label for="f_file_name"><span style="color: red;">*</span> Choose a File (Only .txt)</label>
          <input type="file" name="f_file_name" id="f_file_name" required accept=".txt" />
          <div id="fileErrorMsg"></div>

          <div class="btn-group">
            <button type="reset" id="resetBtn" disabled>Reset</button>
            <button type="submit" id="uploadBtn" disabled>Upload</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script>
    const uploadForm = document.getElementById("uploadForm");
    const fileInput = document.getElementById("f_file_name");
    const fileErrorMsg = document.getElementById("fileErrorMsg");
    const errorCard = document.getElementById("errorCard");
    const collapseBtn = document.querySelector(".collapse-btn");
    const closeBtn = document.querySelector(".close-btn");
    const errorContent = document.querySelector(".error-content");
    const errorListDiv = document.getElementById("genericErrorList");
    const uploadBtn = document.getElementById("uploadBtn");
    const resetBtn = document.getElementById("resetBtn");

    uploadBtn.disabled = true;
    resetBtn.disabled = true;

    fileInput.addEventListener("change", function() {
      fileErrorMsg.style.display = "none";
      fileInput.classList.remove("input-error");
      const hasFile = fileInput.files.length > 0;
      uploadBtn.disabled = !hasFile;
      resetBtn.disabled = !hasFile;
      closeBtn.disabled = !hasFile;
    });

    uploadForm.addEventListener("submit", function(e) {
      e.preventDefault();
      fileErrorMsg.style.display = "none";
      fileInput.classList.remove("input-error");

      if (fileInput.files.length === 0) {
        fileErrorMsg.textContent = "No file selected.";
        fileErrorMsg.style.display = "block";
        fileInput.classList.add("input-error");
        return;
      }

      const fileExt = fileInput.files[0].name.split(".").pop().toLowerCase();
      if (fileExt !== "txt") {
        fileErrorMsg.textContent = "File must be a .txt file.";
        fileErrorMsg.style.display = "block";
        fileInput.classList.add("input-error");
        return;
      }

      uploadFileAjax();
    });

    resetBtn.addEventListener("click", function() {
      fileInput.value = "";
      uploadBtn.disabled = true;
      resetBtn.disabled = true;
      fileErrorMsg.style.display = "none";
      fileInput.classList.remove("input-error");
      errorCard.classList.remove("show");
      setTimeout(() => errorCard.style.display = "none", 400);
    });

    closeBtn.addEventListener("click", function() {
      errorCard.classList.remove("show");
      setTimeout(() => errorCard.style.display = "none", 400);
    });

    collapseBtn.addEventListener("click", function() {
      if (errorContent.classList.contains("collapsed")) {
        errorContent.classList.remove("collapsed");
        collapseBtn.textContent = "-";
      } else {
        errorContent.classList.add("collapsed");
        collapseBtn.textContent = "+";
      }
    });

    async function uploadFileAjax() {
      const formData = new FormData(uploadForm);
      const url = "<?= site_url('ag/qcgibson/doupload'); ?>";

      uploadBtn.disabled = true;
      resetBtn.disabled = true;
      const originalText = uploadBtn.innerHTML;
      uploadBtn.innerHTML = `<span class="spinner"></span> Uploading...`;

      try {
        const response = await fetch(url, {
          method: "POST",
          body: formData
        });
        const text = await response.text();
        let result;
        try {
          result = JSON.parse(text.trim());
        } catch (e) {
          showErrorCard("Server response is not valid JSON.");
          return;
        }

        if (result.status === "error") showErrorCard(result.message, result.errors || []);
        else if (result.status === "success") showSuccessCard(result.message);
        else showErrorCard("Unknown server response.");
      } catch (err) {
        showErrorCard("Network error or server did not respond.");
      } finally {
        uploadBtn.innerHTML = originalText;
        uploadBtn.disabled = false;
        resetBtn.disabled = false;
      }
    }

    function showErrorCard(message, errorGroups = []) {
      const errorTitle = document.getElementById("errorTitle");
      errorTitle.textContent = "Error!";
      errorTitle.style.color = "red";
      collapseBtn.style.display = "inline-block";
      collapseBtn.textContent = "-";
      closeBtn.style.display = "none";

      errorListDiv.innerHTML = "";
      if (Array.isArray(errorGroups) && errorGroups.length > 0) {
        errorGroups.forEach(group => {
          if (typeof group === "object" && group.title && Array.isArray(group.items)) {
            const title = `<div class="error-title">${group.title}</div>`;
            const list = `<div class="error-list">${group.items.map(item => `<p>${item}</p>`).join("")}</div>`;
            errorListDiv.innerHTML += title + list;
          } else if (typeof group === "string") {
            errorListDiv.innerHTML += `<p>${group}</p>`;
          }
        });
      } else {
        errorListDiv.innerHTML = `<p>${message}</p>`;
      }

      errorCard.style.display = "block";
      setTimeout(() => errorCard.classList.add("show"), 10);
    }

    function showSuccessCard(message) {
      const errorTitle = document.getElementById("errorTitle");
      errorTitle.textContent = "Success!";
      errorTitle.style.color = "green";
      collapseBtn.style.display = "none";
      closeBtn.style.display = "inline-block";
      errorListDiv.innerHTML = `<p style="color: black;">${message}</p>`;
      errorCard.style.display = "block";
      setTimeout(() => errorCard.classList.add("show"), 10);

      fileInput.value = "";
      setTimeout(() => {
        errorCard.classList.remove("show");
        setTimeout(() => errorCard.style.display = "none", 400);
      }, 10000);
    }
  </script>