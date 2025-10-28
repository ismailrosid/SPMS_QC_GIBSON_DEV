

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

// === Init tombol ===
uploadBtn.disabled = true;
resetBtn.disabled = true;
closeBtn.disabled = true;

// === Cek perubahan file ===
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
    const response = await fetch(url, { method: "POST", body: formData });
    console.log("Response status:", response.status);
    const text = await response.text();
    console.log("Raw Response:", text);

    let result;
    try { result = JSON.parse(text); } 
    catch (e) { 
      console.error("Gagal parse JSON:", e); 
      showErrorCard("Response dari server bukan JSON valid."); 
      return; 
    }

    // === Tampilkan sesuai status server ===
    if (result.status === "error") {
      showErrorCard(result.message, result.errors || []);
    } else if (result.status === "success") {
      showSuccessCard(result.message);
    } else {
      showErrorCard("Response server tidak dikenal.");
    }

  } catch (error) {
    console.error("Fetch error:", error);
    showErrorCard("Terjadi kesalahan jaringan atau server tidak merespon.");
  }
}

// === Fungsi Error Card ===
function showErrorCard(message, errorGroups = []) {
  const errorTitle = document.getElementById("errorTitle");
  errorTitle.textContent = "Detail Error";
  errorListDiv.innerHTML = "";

  if (Array.isArray(errorGroups) && errorGroups.length > 0) {
    errorGroups.forEach(group => {
      if (typeof group === "object" && group.title && Array.isArray(group.items)) {
        const title = `<div class="error-title">${group.title}</div>`;
        const list = `<div class="error-list">${group.items.map(item => `<p>${item}</p>`).join("")}</div>`;
        errorListDiv.innerHTML += title + list;
      } else if (typeof group === "string") {
        errorListDiv.innerHTML += `<p>${group}</p>`;
      } else if (group && typeof group === "object") {
        // kalau object polos
        errorListDiv.innerHTML += `<p>${JSON.stringify(group)}</p>`;
      }
    });
  } else {
    errorListDiv.innerHTML = `<p>${message}</p>`;
  }

  errorCard.style.display = "block";
  setTimeout(() => errorCard.classList.add("show"), 10);
}

// === Fungsi Success Card ===
function showSuccessCard(message) {
  const errorTitle = document.getElementById("errorTitle");
  errorTitle.textContent = "Sukses!";
  errorListDiv.innerHTML = `<p style="color:green;">${message}</p>`;
  errorCard.style.display = "block";
  setTimeout(() => errorCard.classList.add("show"), 10);
}

// === Submit Form ===
uploadForm.addEventListener("submit", function (e) {
  e.preventDefault();
  if (fileInput.files.length === 0) { showErrorCard("Tidak ada file yang dipilih."); return; }
  const fileName = fileInput.files[0].name;
  const fileExt = fileName.split(".").pop().toLowerCase();
  if (fileExt !== "txt") { showErrorCard("File harus berekstensi .txt"); return; }
  uploadFileAjax();
});

// === Reset tombol ===
resetBtn.addEventListener("click", function () {
  fileInput.value = "";
  uploadBtn.disabled = true;
  resetBtn.disabled = true;
  closeBtn.disabled = true;
});

// === Close tombol ===
closeBtn.addEventListener("click", function () {
  errorCard.classList.remove("show");
  setTimeout(() => (errorCard.style.display = "none"), 400);
});

// === Collapse tombol ===
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
