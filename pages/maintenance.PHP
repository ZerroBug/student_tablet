<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maintenance | Dashboard</title>

  <!-- Favicon -->
  <link rel="icon" type="image/jpeg" href="assets/images/logo.jpg">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }

    /* Sidebar */
    .sidebar {
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      width: 220px;
      background-color: #1d3557;
      padding-top: 1rem;
    }
    .sidebar h4 {
      font-size: 1.2rem;
      font-weight: 600;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
      margin-bottom: 2px;
      border-radius: 5px;
      font-size: 0.95rem;
    }
    .sidebar a.active, .sidebar a:hover {
      background-color: #457b9d;
    }

    /* Main content */
    .main-content {
      margin-left: 230px;
      padding: 20px;
    }

    /* Footer */
    .footer {
      position: fixed;
      bottom: 0;
      left: 230px;
      width: calc(100% - 230px);
      background-color: #1d3557;
      color: white;
      text-align: center;
      padding: 10px 0;
      font-size: 0.9rem;
    }
    @media (max-width: 768px) {
      .main-content { margin-left: 0; }
      .footer { left: 0; width: 100%; }
    }
    .footer a { color: white; text-decoration: none; }
    .footer a:hover { text-decoration: underline; }

    /* Form */
    .form-label { font-weight: 500; }
    .form-control:focus {
      box-shadow: none;
      border-color: #457b9d;
    }
    .form-check-input:checked {
      background-color: #457b9d;
      border-color: #457b9d;
    }

    /* Status badges */
    .badge-status-repair {
      background-color: #e9c46a;
      color: #000;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar d-flex flex-column">
  <h4 class="text-white px-3 mb-4">📱 Tablet Admin</h4>
  <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
  <a href="tablets.php"><i class="fa fa-tablet-alt me-2"></i> Tablets</a>
  <a href="assign.php"><i class="fa fa-user-plus me-2"></i> Assign Tablet</a>
  <a href="return.php"><i class="fa fa-undo me-2"></i> Return Tablet</a>
  <a href="maintenance.php" class="active"><i class="fa fa-tools me-2"></i> Maintenance</a>
  <a href="reports.php"><i class="fa fa-chart-bar me-2"></i> Reports</a>
  <a href="settings.php"><i class="fa fa-cog me-2"></i> Settings</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fa fa-tools me-2"></i> Tablet Maintenance</h2>
    <div>
      <i class="fa fa-bell me-3 fs-5"></i>
      <i class="fa fa-user-circle fs-4"></i>
    </div>
  </div>

  <!-- Maintenance Section -->
  <div class="topbar d-flex justify-content-between align-items-center p-3 bg-white rounded shadow-sm mb-3">
    <h5 class="m-0">Maintenance & Repairs</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#repairModal">
      <i class="fa fa-plus me-1"></i> Log Repair
    </button>
  </div>

  <div class="card shadow-sm p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle" id="repairTable">
        <thead class="table-light">
          <tr>
            <th>Tablet ID</th>
            <th>Issue</th>
            <th>Logged</th>
            <th>Status</th>
            <th>Cost</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>T002</td>
            <td>Broken screen</td>
            <td>2025-08-01</td>
            <td><span class="badge badge-status-repair">In Repair</span></td>
            <td>—</td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-info" onclick="viewRepair(this)"><i class="fa fa-eye"></i></button>
              <button class="btn btn-sm btn-outline-success" onclick="markFixed(this)"><i class="fa fa-check"></i></button>
              <button class="btn btn-sm btn-outline-danger" onclick="removeRepair(this)"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Log Repair Modal -->
<div class="modal fade" id="repairModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Log Repair</h5>
      <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <form id="repairForm" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Tablet ID</label>
          <input class="form-control" name="id" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Logged Date</label>
          <input type="date" class="form-control" name="date" required>
        </div>
        <div class="col-12">
          <label class="form-label">Issue</label>
          <input class="form-control" name="issue" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Estimated Cost</label>
          <input type="number" class="form-control" name="cost" min="0" step="0.01">
        </div>
        <div class="col-md-6">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
            <option>In Repair</option>
            <option>Fixed</option>
          </select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button class="btn btn-primary" onclick="addRepair()">Save</button>
    </div>
  </div></div>
</div>

<!-- View Tablet Modal -->
<div class="modal fade" id="viewTabletModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Tablet Details</h5>
      <button class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" id="viewTabletBody"></div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
  </div></div>
</div>

<!-- Footer -->
<footer class="footer">
  &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const repairBody = document.querySelector('#repairTable tbody');

function addRepair(){
  const f = document.getElementById('repairForm');
  const d = Object.fromEntries(new FormData(f).entries());
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${d.id}</td>
    <td>${d.issue}</td>
    <td>${d.date}</td>
    <td><span class="badge ${d.status==='Fixed'?'text-bg-success':'badge-status-repair'}">${d.status}</span></td>
    <td>${d.cost ? ('₵' + Number(d.cost).toFixed(2)) : '—'}</td>
    <td class="text-end">
      <button class="btn btn-sm btn-outline-info" onclick="viewRepair(this)"><i class="fa fa-eye"></i></button>
      <button class="btn btn-sm btn-outline-success" onclick="markFixed(this)"><i class="fa fa-check"></i></button>
      <button class="btn btn-sm btn-outline-danger" onclick="removeRepair(this)"><i class="fa fa-trash"></i></button>
    </td>`;
  repairBody.prepend(tr);
  bootstrap.Modal.getInstance(document.getElementById('repairModal')).hide();
  f.reset();
}

function markFixed(btn){
  const td = btn.closest('tr').children[3];
  td.innerHTML = '<span class="badge text-bg-success">Fixed</span>';
}

function removeRepair(btn){
  btn.closest('tr').remove();
}

function viewRepair(btn){
  const tr = btn.closest('tr').children;
  const details = `
    <p><strong>Tablet ID:</strong> ${tr[0].innerText}</p>
    <p><strong>Issue:</strong> ${tr[1].innerText}</p>
    <p><strong>Logged Date:</strong> ${tr[2].innerText}</p>
    <p><strong>Status:</strong> ${tr[3].innerText}</p>
    <p><strong>Cost:</strong> ${tr[4].innerText}</p>
  `;
  document.getElementById('viewTabletBody').innerHTML = details;
  new bootstrap.Modal(document.getElementById('viewTabletModal')).show();
}
</script>
</body>
</html>
