<style>
    /* === INPUT FORM SIMPLE STYLE (FOLLOW SCAN STYLE THEME) === */
    .simple-form {
        border-radius: 8px;
        padding: 20px;
        max-width: 500px;
        margin: 30px auto;
        font-family: Arial, sans-serif;
        font-size: 17px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        font-weight: bold;
        margin-bottom: 6px;
        display: block;
        color: #333;
    }

    .text-input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #d9d9d9;
        border-radius: 6px;
        font-size: 17px;
        transition: 0.25s;
        background: #fff;
    }

    .text-input:focus {
        border-color: #acd1f8ff;
        background: #eef6ff;
        outline: none;
    }

    #submitBtn2 {
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
        margin-top: 15px;
    }

    #submitBtn2:hover {
        background: #e6e6e6;
    }

    #submitBtn2:disabled {
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

    /* === ERROR / SUCCESS CARD (COPY FROM MAIN) === */
    .error-card2 {
        background: #fff;
        border: 1px solid #d9d9d9;
        border-radius: 6px;
        padding: 20px 25px;
        margin-bottom: 15px;
        display: none;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.4s ease, transform 0.4s ease;
        font-size: 16px;
    }

    .error-card2.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="simple-form">
    <div id="errorBox2" class="error-card2"></div>

    <form id="categoryForm">
        <div class="form-group">
            <label>Category Code <span style="color:red">*</span></label>
            <input type="text" id="category_code" class="text-input" required />
        </div>

        <div class="form-group">
            <label>Category Name <span style="color:red">*</span></label>
            <input type="text" id="category_name" class="text-input" required />
        </div>

        <button type="submit" id="submitBtn2" disabled>Submit</button>
    </form>
</div>

<script>
    const codeInput = document.getElementById("category_code");
    const nameInput = document.getElementById("category_name");
    const submitBtn2 = document.getElementById("submitBtn2");
    const errorBox2 = document.getElementById("errorBox2");

    function checkInputs() {
        submitBtn2.disabled = !(codeInput.value.trim() && nameInput.value.trim());
    }

    codeInput.addEventListener("input", checkInputs);
    nameInput.addEventListener("input", checkInputs);

    function showMessage(type, message) {
        errorBox2.style.display = "block";
        errorBox2.innerHTML = message;
        errorBox2.style.color = type === "success" ? "green" : "red";

        setTimeout(() => errorBox2.classList.add("show"), 10);
        setTimeout(() => {
            errorBox2.classList.remove("show");
            setTimeout(() => (errorBox2.style.display = "none"), 400);
        }, 4000);
    }

    document.getElementById("categoryForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const originalText = submitBtn2.innerHTML;
        submitBtn2.disabled = true;
        submitBtn2.innerHTML = `<span class="spinner"></span>Processing...`;

        const formData = new FormData();
        formData.append("category_code", codeInput.value.trim());
        formData.append("category_name", nameInput.value.trim());

        try {
            const res = await fetch("<?= site_url('ag/qcgibson/savecategory'); ?>", {
                method: "POST",
                body: formData,
            });

            const result = await res.json();

            if (result.status === "success") {
                showMessage("success", result.message);
                codeInput.value = "";
                nameInput.value = "";
            } else {
                showMessage("error", result.message);
            }

            checkInputs();
        } catch (err) {
            showMessage("error", "Failed to connect to server.");
        } finally {
            submitBtn2.innerHTML = originalText;
        }
    });
</script>