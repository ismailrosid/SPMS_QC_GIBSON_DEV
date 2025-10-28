<style>
  /* === CARD FORM UTAMA === */
  .card-form {
    border-radius: 8px;
    padding: 20px;
    max-width: 900px;
    margin: 40px auto;
    font-family: Arial, sans-serif;
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
    font-size: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #555;
  }

  .card-form-section-body {
    padding: 20px;
    background: #fff;
  }

  label {
    display: block;
    font-weight: bold;
    color: #666;
    margin-bottom: 6px;
  }

  input[type="file"] {
    width: 100%;
    padding: 7px 10px;
    border: 1px solid #d9d9d9;
    border-radius: 4px;
    margin-bottom: 12px;
    box-sizing: border-box;
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
  }

  button:hover {
    background: #e6e6e6;
  }

  button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  /* === ERROR CARD === */
  .error-card {
    background: #fff;
    border: 1px solid #d9d9d9;
    border-radius: 6px;
    padding: 20px 25px;
    position: relative;
    margin-bottom: 25px;
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.4s ease, transform 0.4s ease;
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
    font-size: 18px;
    font-weight: bold;
    color: #000;
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
    font-size: 14px;
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
    overflow: hidden;
    max-height: 1000px;
    opacity: 1;
    transition:
      opacity 0.3s ease,
      max-height 0.5s ease 0.3s;
  }

  .error-content.collapsed {
    opacity: 0;
    max-height: 0;
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
    position: relative;
    margin-bottom: 6px;
  }

  .error-list {
    margin-left: 10px;
    color: red;
    margin-top: 5px;
  }

  .error-list p {
    margin: 3px 0;
  }
</style>

<div class="card-form">
  <div class="card-form-section">
    <div class="card-form-section-header">
      <span>Form Upload</span>
    </div>

    <div class="card-form-section-body">
      <!-- === ERROR CARD === -->
      <div class="error-card" id="errorCard">
        <div class="error-header">
          <div class="error-header-left">
            <h2>Error!</h2>
          </div>
          <div class="error-controls">
            <button class="error-btn collapse-btn">-</button>
            <button class="error-btn close-btn">X</button>
          </div>
        </div>

        <hr />

        <div class="error-content">
          <div class="error-group">
            <div class="error-title">Upload Error</div>
            <div class="error-list" id="genericErrorList"></div>
          </div>
        </div>
      </div>

      <!-- === FORM UPLOAD === -->
      <form id="uploadForm" action="#" method="post" enctype="multipart/form-data">
        <label for="f_file_name">* File Upload (Hanya .txt)</label>
        <input type="file" name="f_file_name" id="f_file_name" required accept=".txt" />

        <div class="btn-group">
          <button type="reset" id="resetBtn" disabled>Reset</button>
          <button type="submit" id="uploadBtn" disabled>Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- <?php echo $siteurl; ?> -->
<script>
  const uploadForm = document.getElementById("uploadForm");
  const fileInput = document.getElementById("f_file_name");
  const errorCard = document.getElementById("errorCard");
  const collapseBtn = document.querySelector(".collapse-btn");
  const closeBtn = document.querySelector(".close-btn");
  const errorContent = document.querySelector(".error-content");
  const errorListDiv = document.getElementById("genericErrorList");
  const uploadBtn = document.getElementById("uploadBtn");
  const resetBtn = document.getElementById("resetBtn");

  // === Saat halaman pertama kali dimuat ===
  uploadBtn.disabled = true;
  resetBtn.disabled = true;
  closeBtn.disabled = true;

  // === Cek perubahan input file ===
  fileInput.addEventListener("change", function () {
    const hasFile = fileInput.files.length > 0;
    uploadBtn.disabled = !hasFile;
    resetBtn.disabled = !hasFile;
    closeBtn.disabled = !hasFile;
  });

  // === Fungsi Upload AJAX ===
 async function uploadFileAjax() {
  const formData = new FormData(uploadForm);
  const url = "http://localhost/spmsg/index.php/ag/qcgibson/doupload";
  
  try {
    const response = await fetch(url, {
      method: "POST",
      body: formData
    });

    // Cek status HTTP dulu
    console.log("Response status:", response.status);

    const text = await response.text(); // ambil raw text-nya dulu
    console.log("Raw Response:", text);

    let result;
    try {
      result = JSON.parse(text); // baru parse ke JSON
    } catch (e) {
      console.error("Gagal parse JSON:", e);
      showErrorCard("Response dari server bukan JSON valid.", []);
      return;
    }

    console.table(result);

    // if (result.status === "error") {
    //   showErrorCard(result.message, result.errors);
    // } else if (result.status === "success") {
    //   showSuccessCard(result.message);
    // }

  } catch (error) {
    console.error("Fetch error:", error);
    showErrorCard("Terjadi kesalahan jaringan atau server tidak merespon.", []);
  }
}


  // === Fungsi Tampilkan Error Card ===
  function showErrorCard(message, errorGroups = []) {
    errorListDiv.innerHTML = "";

    if (errorGroups.length > 0) {
      errorGroups.forEach(group => {
        const title = `<div class="error-title">${group.title}</div>`;
        const list = `<div class="error-list">${group.items.map(item => `<p>${item}</p>`).join("")}</div>`;
        errorListDiv.innerHTML += title + list;
      });
    } else {
      errorListDiv.innerHTML = `<p>${message}</p>`;
    }

    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);
  }

  // === Fungsi Tampilkan Sukses Card ===
  function showSuccessCard(message) {
    errorListDiv.innerHTML = `<p style="color:green;font-weight:bold">${message}</p>`;
    errorCard.style.display = "block";
    setTimeout(() => errorCard.classList.add("show"), 10);

    // Reset input setelah sukses
    fileInput.value = "";
    uploadBtn.disabled = true;
    resetBtn.disabled = true;
    closeBtn.disabled = true;
  }

  // === Event submit form ===
  uploadForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Validasi awal di client
    if (fileInput.files.length === 0) {
      showErrorCard("Tidak ada file yang dipilih.");
      return;
    }

    const fileName = fileInput.files[0].name;
    const fileExt = fileName.split(".").pop().toLowerCase();

    if (fileExt !== "txt") {
      showErrorCard("File harus berekstensi .txt");
      fileInput.value = "";
      uploadBtn.disabled = true;
      resetBtn.disabled = true;
      closeBtn.disabled = true;
      return;
    }

    // Jalankan upload lewat AJAX
    uploadFileAjax();
  });

  // === Event tombol Reset ===
  resetBtn.addEventListener("click", function () {
    fileInput.value = "";
    uploadBtn.disabled = true;
    resetBtn.disabled = true;
    closeBtn.disabled = true;
  });

  // === Tombol Close (untuk card error) ===
  closeBtn.addEventListener("click", function () {
    errorCard.classList.remove("show");
    setTimeout(() => (errorCard.style.display = "none"), 400);
  });

  // === Tombol Collapse ===
  collapseBtn.addEventListener("click", function () {
    if (errorContent.classList.contains("collapsed")) {
      errorContent.classList.remove("collapsed");
      collapseBtn.textContent = "-";
    } else {
      errorContent.classList.add("collapsed");
      collapseBtn.textContent = "+";
    }
  });
</script>
