<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports | Dashboard</title>
   <!-- Favicon -->
  <link rel="icon" type="..asserts/images/logo.jpg" href="asserts/images/logo.jpg">
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

    /* Card */
    .form-card {
      max-width: 950px;
      margin: 0 auto;
      border-radius: 10px;
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
      z-index: 1030;
      font-size: 0.9rem;
    }
    @media (max-width: 768px) {
      .footer {
        left: 0;
        width: 100%;
      }
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
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar d-flex flex-column">
  <h4 class="text-white px-3 mb-4">📱 Tablet Admin</h4>
  <a href="dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
  <a href="tablets.PHP"><i class="fa fa-tablet-alt me-2"></i> Tablets</a>
  <a href="assign.PHP" class=""><i class="fa fa-user-plus me-2"></i> Assign Tablet</a>
  <a href="return.PHP"><i class="fa fa-undo me-2"></i> Return Tablet</a>
  <a href="maintenance.PHP"><i class="fa fa-tools me-2"></i> Maintenance</a>
  <a href="reports.PHP" class="active"><i class="fa fa-chart-bar me-2"></i> Reports</a>
  <a href="settings.PHP"><i class="fa fa-cog me-2"></i> Settings</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fa fa-chart-bar me-2""></i>Reports</h2>
    <div>
      <i class="fa fa-bell me-3 fs-5"></i>
      <i class="fa fa-user-circle fs-4"></i>
    </div>
  </div>
<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="topbar d-flex justify-content-between align-items-center p-3">
      <h5 class="m-0">Reports</h5>
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="fa fa-print me-1"></i>Print</button>
        <button class="btn btn-outline-success btn-sm" onclick="exportCSV()"><i class="fa fa-file-csv me-1"></i>Export CSV</button>
      </div>
    </div>

    <div class="row g-3 my-3">
      <div class="col-lg-6"><div class="card shadow-sm p-3">
        <div class="fw-semibold mb-2">Issued vs Available</div>
        <canvas id="issuedChart" height="160"></canvas>
      </div></div>
      <div class="col-lg-6"><div class="card shadow-sm p-3">
        <div class="fw-semibold mb-2">Repairs Over Time</div>
        <canvas id="repairTrend" height="160"></canvas>
      </div></div>
    </div>

    <div class="card shadow-sm p-3 my-3">
      <div class="fw-semibold mb-2">Inventory Snapshot</div>
      <div class="table-responsive">
        <table class="table table-striped" id="reportTable">
          <thead><tr><th>Tablet ID</th><th>Serial</th><th>Status</th><th>Holder</th></tr></thead>
          <tbody>
            <tr><td>T001</td><td>SN12345</td><td>Issued</td><td>John Doe</td></tr>
            <tr><td>T002</td><td>SN67890</td><td>In Repair</td><td>—</td></tr>
            <tr><td>T003</td><td>SN77777</td><td>Available</td><td>—</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div></div>
<!-- Footer -->
<footer class="footer">
  &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="../assets/js/app.js"></script>
<script>
 new Chart(document.getElementById('issuedChart'),{
  type:'doughnut',
  data:{labels:['Issued','Available','In Repair'],datasets:[{data:[85,30,5]}]}
});
new Chart(document.getElementById('repairTrend'),{
  type:'line',
  data:{labels:['Apr','May','Jun','Jul','Aug'],datasets:[{label:'Repairs',data:[2,4,3,6,5]}]},
  options:{plugins:{legend:{display:false}}}
});

function exportCSV(){
  const rows=[...document.querySelectorAll('#reportTable tr')].map(tr=>[...tr.children].map(td=>td.innerText));
  const csv = rows.map(r=>r.map(v=>`"${v.replace(/"/g,'""')}"`).join(',')).join('\n');
  const blob = new Blob([csv],{type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = Object.assign(document.createElement('a'),{href:url,download:'inventory_report.csv'});
  a.click(); URL.revokeObjectURL(url);
  showToast('CSV exported');
}
</script>
</body>
</html>
