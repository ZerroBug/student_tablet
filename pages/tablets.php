<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tablets — Tablet Admin</title>
  <!-- Favicon -->
  <link rel="icon" type="image/jpeg" href="asserts/images/logo.jpg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 220px; background-color: #1d3557; padding-top: 1rem; }
    .sidebar h4 { font-size: 1.2rem; font-weight: 600; }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 20px; margin-bottom: 2px; border-radius: 5px; font-size: 0.95rem; }
    .sidebar a.active, .sidebar a:hover { background-color: #457b9d; }
    .main-content { margin-left: 230px; padding: 20px; }
    .form-card { max-width: 950px; margin: 0 auto; border-radius: 10px; }
    .footer { position: fixed; bottom: 0; left: 230px; width: calc(100% - 230px); background-color: #1d3557; color: white; text-align: center; padding: 10px 0; z-index: 1030; font-size: 0.9rem; }
    @media (max-width: 768px) { .footer { left: 0; width: 100%; } }
    .footer a { color: white; text-decoration: none; }
    .footer a:hover { text-decoration: underline; }
    .form-label { font-weight: 500; }
    .form-control:focus { box-shadow: none; border-color: #457b9d; }
    .form-check-input:checked { background-color: #457b9d; border-color: #457b9d; }
    .badge-status-available { background-color: #198754; }
    .badge-status-issued { background-color: #0d6efd; }
    .badge-status-repair { background-color: #ffc107; }

    /* Custom Toast */
    .custom-toast {
      position: fixed; top: 1rem; right: 1rem; min-width: 260px; max-width: 350px;
      padding: 15px 20px; border-radius: 12px;
      background: linear-gradient(135deg, #1d3557, #457b9d);
      color: #fff; font-weight: 500; box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      display: flex; align-items: center; gap: 12px;
      animation: slideIn 0.5s ease, fadeOut 0.5s ease 3s forwards;
      z-index: 3000;
    }
    .custom-toast i { font-size: 1.3rem; }
    @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes fadeOut { to { transform: translateX(120%); opacity: 0; } }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar d-flex flex-column">
  <h4 class="text-white px-3 mb-4">📱 Tablet Admin</h4>
  <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
  <a href="tablets.php" class="active"><i class="fa fa-tablet-alt me-2"></i> Tablets</a>
  <a href="assign.php"><i class="fa fa-user-plus me-2"></i> Assign Tablet</a>
  <a href="return.php"><i class="fa fa-undo me-2"></i> Return Tablet</a>
  <a href="maintenance.php"><i class="fa fa-tools me-2"></i> Maintenance</a>
  <a href="reports.php"><i class="fa fa-chart-bar me-2"></i> Reports</a>
  <a href="settings.php"><i class="fa fa-cog me-2"></i> Settings</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fa fa-tablet-alt me-2"></i>Tablets</h2>
    <div><i class="fa fa-bell me-3 fs-5"></i><i class="fa fa-user-circle fs-4"></i></div>
  </div>

  <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="topbar d-flex justify-content-between align-items-center p-3">
      <h5 class="m-0">Tablets</h5>
      <!-- <div class="d-flex gap-2">
        <input class="form-control form-control-sm" id="searchInput" placeholder="Search tablets...">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTabletModal"><i class="fa fa-plus me-1"></i>Add Tablet</button>
      </div> -->
    </div>

    <div class="card shadow-sm p-3 my-3">
      <div class="table-responsive">
        <table class="table table-striped align-middle" id="tabletTable">
          <thead><tr>
            <th>Tablet ID</th><th>Serial</th><th>Model</th><th>Assigned To</th><th>Status</th><th class="text-end">Actions</th>
          </tr></thead>
          <tbody>
            <tr>
              <td>T001</td><td>SN12345</td><td>SM1</td><td>John Doe</td>
              <td><span class="badge badge-status-issued">Issued</span></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-info" onclick="viewRow(this)"><i class="fa fa-eye"></i></button>
                <button class="btn btn-sm btn-outline-secondary" onclick="editRow(this)"><i class="fa fa-pen"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
            <tr>
              <td>T002</td><td>SN67890</td><td>MSM1</td><td>-</td>
              <td><span class="badge badge-status-available">Available</span></td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-info" onclick="viewRow(this)"><i class="fa fa-eye"></i></button>
                <button class="btn btn-sm btn-outline-secondary" onclick="editRow(this)"><i class="fa fa-pen"></i></button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Tablet Modal -->
    <div class="modal fade" id="addTabletModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Tablet</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <form id="addTabletForm" class="row g-3">
            <div class="col-md-6"><label class="form-label">Tablet ID</label><input class="form-control" name="id" required></div>
            <div class="col-md-6"><label class="form-label">Serial</label><input class="form-control" name="serial" required></div>
            <div class="col-md-6"><label class="form-label">Model</label><input class="form-control" name="model" required></div>
            <div class="col-md-6"><label class="form-label">Assigned To</label><input class="form-control" name="assigned" placeholder="-"></div>
            <div class="col-12"><label class="form-label">Status</label>
              <select class="form-select" name="status"><option>Available</option><option>Issued</option><option>In Repair</option></select>
            </div>
          </form>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" onclick="submitAddTablet()">Save</button></div>
      </div></div>
    </div>

    <!-- Edit Tablet Modal -->
    <div class="modal fade" id="editTabletModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Tablet</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <form id="editTabletForm" class="row g-3">
            <input type="hidden" name="rowIndex">
            <div class="col-md-6"><label class="form-label">Tablet ID</label><input class="form-control" name="id" required></div>
            <div class="col-md-6"><label class="form-label">Serial</label><input class="form-control" name="serial" required></div>
            <div class="col-md-6"><label class="form-label">Model</label><input class="form-control" name="model" required></div>
            <div class="col-md-6"><label class="form-label">Assigned To</label><input class="form-control" name="assigned"></div>
            <div class="col-12"><label class="form-label">Status</label>
              <select class="form-select" name="status"><option>Available</option><option>Issued</option><option>In Repair</option></select>
            </div>
          </form>
        </div>
        <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button class="btn btn-primary" onclick="submitEditTablet()">Update</button></div>
      </div></div>
    </div>

    <!-- View Tablet Modal -->
    <div class="modal fade" id="viewTabletModal" tabindex="-1">
      <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Tablet Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body" id="viewTabletBody"></div>
      </div></div>
    </div>
  </main>
</div>

<!-- Footer -->
<footer class="footer">&copy; 2025 Okomfo Anlokye Senior High School Tablet Management. All Rights Reserved.</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const searchInput=document.getElementById('searchInput');
const table=document.getElementById('tabletTable').querySelector('tbody');

searchInput.addEventListener('input',()=> {
  const q=searchInput.value.toLowerCase();
  table.querySelectorAll('tr').forEach(tr=>{
    tr.style.display = tr.innerText.toLowerCase().includes(q)?'':'none';
  });
});

/* Custom Toast */
function showToast(message, type = "success") {
  const icons = { success: "fa-circle-check", error: "fa-circle-xmark", warning: "fa-triangle-exclamation", info: "fa-circle-info" };
  const colors = { success: "linear-gradient(135deg, #06d6a0, #118ab2)", error: "linear-gradient(135deg, #ef476f, #d62828)", warning: "linear-gradient(135deg, #ffd166, #f77f00)", info: "linear-gradient(135deg, #219ebc, #023047)" };

  const toast = document.createElement("div");
  toast.className = "custom-toast";
  toast.style.background = colors[type] || colors.success;
  toast.innerHTML = `<i class="fa ${icons[type] || icons.success}"></i><span>${message}</span>`;
  document.body.appendChild(toast);
  setTimeout(() => { toast.remove(); }, 4000);
}

/* Helpers */
function badgeClass(status){ status = (status||'').toLowerCase(); if(status.includes('available')) return 'badge-status-available'; if(status.includes('repair')) return 'badge-status-repair'; return 'badge-status-issued'; }

function deleteRow(btn){ btn.closest('tr').remove(); showToast('Tablet removed ❌','error'); }

function editRow(btn){
  const tr = btn.closest('tr');
  const cells = tr.children;
  const form = document.getElementById('editTabletForm');
  form.rowIndex.value = [...table.children].indexOf(tr);
  form.id.value = cells[0].innerText;
  form.serial.value = cells[1].innerText;
  form.model.value = cells[2].innerText;
  form.assigned.value = cells[3].innerText === "-" ? "" : cells[3].innerText;
  form.status.value = cells[4].innerText.trim();
  new bootstrap.Modal(document.getElementById('editTabletModal')).show();
}

function submitEditTablet(){
  const form = document.getElementById('editTabletForm');
  if(!form.checkValidity()){ showToast("Please fill all required fields ⚠️","warning"); return; }
  const idx = form.rowIndex.value;
  const tr = table.children[idx];
  tr.children[0].innerText = form.id.value;
  tr.children[1].innerText = form.serial.value;
  tr.children[2].innerText = form.model.value;
  tr.children[3].innerText = form.assigned.value || "-";
  tr.children[4].innerHTML = `<span class="badge ${badgeClass(form.status.value)}">${form.status.value}</span>`;
  bootstrap.Modal.getInstance(document.getElementById('editTabletModal')).hide();
  showToast("Tablet updated successfully ✏️","success");
}

function viewRow(btn){
  const row = btn.closest('tr').children;
  const details = `
    <p><strong>Tablet ID:</strong> ${row[0].innerText}</p>
    <p><strong>Serial:</strong> ${row[1].innerText}</p>
    <p><strong>Model:</strong> ${row[2].innerText}</p>
    <p><strong>Assigned To:</strong> ${row[3].innerText}</p>
    <p><strong>Status:</strong> ${row[4].innerText}</p>`;
  document.getElementById('viewTabletBody').innerHTML = details;
  new bootstrap.Modal(document.getElementById('viewTabletModal')).show();
  showToast('Viewing tablet details 👀','info');
}

function submitAddTablet(){
  const form=document.getElementById('addTabletForm');
  if(!form.checkValidity()){ showToast("Please fill all required fields ⚠️","warning"); return; }
  const data=Object.fromEntries(new FormData(form).entries());
  const tr=document.createElement('tr');
  tr.innerHTML=`
    <td>${data.id}</td>
    <td>${data.serial}</td>
    <td>${data.model}</td>
    <td>${data.assigned || '-'}</td>
    <td><span class="badge ${badgeClass(data.status)}">${data.status}</span></td>
    <td class="text-end">
      <button class="btn btn-sm btn-outline-info" onclick="viewRow(this)"><i class="fa fa-eye"></i></button>
      <button class="btn btn-sm btn-outline-secondary" onclick="editRow(this)"><i class="fa fa-pen"></i></button>
      <button class="btn btn-sm btn-outline-danger" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
    </td>`;
  table.appendChild(tr);
  showToast("Tablet added successfully ✅","success");
  bootstrap.Modal.getInstance(document.getElementById('addTabletModal')).hide();
  form.reset();
}
</script>
</body>
</html>
