<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Return Tablet — Tablet Admin</title>
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
  <a href="return.PHP" class="active"><i class="fa fa-undo me-2"></i> Return Tablet</a>
  <a href="maintenance.PHP" ><i class="fa fa-tools me-2"></i> Maintenance</a>
  <a href="reports.PHP"><i class="fa fa-chart-bar me-2"></i> Reports</a>
  <a href="settings.PHP"><i class="fa fa-cog me-2"></i> Settings</a>
</nav>

<!-- Main Content -->
<div class="main-content">
  <!-- Topbar -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fa fa-undo me-2"></i>Return-Tablet</h2>
    <div>
      <i class="fa fa-bell me-3 fs-5"></i>
      <i class="fa fa-user-circle fs-4"></i>
    </div>
  </div>
<main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
    <div class="topbar d-flex justify-content-between align-items-center p-3">
      <h5 class="m-0">Return Tablet</h5>
    </div>

    <div class="card shadow-sm p-3 my-3">
      <form id="returnForm" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Tablet ID / Serial</label>
          <input class="form-control" name="query" placeholder="Scan or type..." required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Condition</label>
          <select class="form-select" name="condition" required>
            <option>Good</option><option>Minor scratches</option><option>Broken screen</option><option>Battery issue</option><option>Malfunction</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Return Date</label>
          <input type="date" class="form-control" name="returnDate" required>
        </div>
        <div class="col-12">
          <label class="form-label">Notes</label>
          <textarea class="form-control" name="notes" rows="2"></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-success" type="submit"><i class="fa fa-check me-1"></i>Mark as Returned</button>
        </div>
      </form>
    </div>

    <div class="card shadow-sm p-3 my-3">
      <div class="fw-semibold mb-2">Recent Returns</div>
      <ul id="returnList" class="list-group small"></ul>
    </div>

  </main>
</div></div>

<!-- Footer -->
<footer class="footer">
  &copy; 2025 Okomfo Anokye Senior High School Tablet Management. All Rights Reserved.
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
 document.getElementById('returnForm').addEventListener('submit',(e)=>{
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  const li=document.createElement('li');
  li.className='list-group-item d-flex justify-content-between align-items-center';
  li.innerHTML = `<span><strong>${data.query}</strong> — returned ${data.returnDate} — ${data.condition}</span>
                  <span class="badge text-bg-success">Available</span>`;
  document.getElementById('returnList').prepend(li);
  showToast('Return recorded (front-end demo)');
  e.target.reset();
});

</script>
</body>
</html>
