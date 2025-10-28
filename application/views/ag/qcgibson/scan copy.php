
    <style>
   

      /* ====== MAIN CARD ====== */
      .main-card {
        /* background: #b4d4fa;
        border: 2px solid #666; */
        border-radius: 8px;
        padding: 20px;
        max-width: 900px;
        margin: auto;
      }

   .main-card h1 {
      font-size: 22px;
      font-weight: bold;
      color: #666;
      margin-bottom: 25px;
      /* border-bottom: 1px solid #d9d9d9; */
      padding-bottom: 8px;
    }

    /* ====== SUB CARD ====== */
    .sub-card {
      background: #fafafa;
      border: 1px solid #d9d9d9;
      border-radius: 6px;
      margin-bottom: 20px;
      overflow: hidden;
    }

    .sub-card-header {
      background:  #d1e0f3;
      padding: 12px 20px;
      font-weight: bold;
      font-size: 16px;
      /* border-bottom: 1px solid #d9d9d9; */
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
       color: #666;
    }

    .sub-card-body {
      padding: 20px;
      display: block;
      background: #fff;
    }

    label {
      display: block;
      font-weight: bold;
      color: #666;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="file"] {
      width: 100%;
      padding: 7px 10px;
      border: 1px solid #d9d9d9;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 12px;
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
      color: #666;
      margin-left: 6px;
      border-radius: 4px;
      transition: 0.2s;
    }

    button:hover {
      background: #e6e6e6;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      /* border: 1px solid #d9d9d9; */
      padding: 8px;
      text-align: left;
    }

    /* th {
      background-color: #f2f2f2;
      font-weight: bold;
      color: #666;
    }

    td {
      background-color: #fff;
    } */

    .delete-icon {
      cursor: pointer;
      text-align: center;
      color: #999;
    }

    .delete-icon:hover {
      color: red;
    }

    .collapse-icon {
      font-size: 10px;
      color: #666;
    }
    </style>

    <div class="main-card">
      <!-- <h1>QC Gibson Form</h1> -->

      <!-- CARD: Form Upload -->
      <div class="sub-card">
        <div class="sub-card-header">
          <span>Form Upload</span>
          <span class="collapse-icon">Expand</span>
        </div>
        <div class="sub-card-body">
           
        <form action="{siteurl}doupload" method="post" enctype="multipart/form-data">

            <label for="f_file_name">* File Upload (Hanya .txt)</label>
            <input type="file" name="f_file_name" id="f_file_name" required accept=".txt"/>
            
            <div class="btn-group">
                <button type="reset">Reset</button>
                <button type="submit">Upload</button>
            </div>
            
        </form>
        </div>
      </div>

    </div>

    <script>
      // === COLLAPSE FUNCTION ===
      document.querySelectorAll(".sub-card-header").forEach((header) => {
        header.addEventListener("click", function () {
          const body = this.nextElementSibling;
          const icon = this.querySelector(".collapse-icon");

          if (body.style.display === "none") {
            body.style.display = "block";
            icon.textContent = "Expand";
          } else {
            body.style.display = "none";
            icon.textContent = "Show";
          }
        });
      });

      // === DELETE ROW FUNCTION ===
      function reindexTable() {
        const rows = document.querySelectorAll("#dataTable tbody tr");
        rows.forEach((row, index) => {
          row.querySelector("td:first-child").textContent = index + 1;
        });
      }

      document.querySelector("#dataTable tbody").addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-icon")) {
          e.target.closest("tr").remove();
          reindexTable();
        }
      });
    </script>
