<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tablet Management Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Custom Styles -->
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #1d3557;
      color: white;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 8px 10px;
      border-radius: 4px;
    }
    .sidebar a:hover {
      background-color: #457b9d;
      padding-left: 15px;
      transition: 0.2s;
    }
    .topbar {
      background-color: white;
      border-bottom: 1px solid #dee2e6;
    }
    .card-stat {
      border-left: 5px solid #457b9d;
    }
    .card {
      margin-bottom: 20px;
    }
    .card-icon {
      font-size: 2rem;
      margin-bottom: 10px;
    }
    .badge {
      font-size: 0.9rem;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f1f1;
    }
    /* Footer styling */
    .footer {
      position: fixed;
      bottom: 0;
      left: 230px; /* sidebar width */
      width: calc(100% - 230px);
      background-color: #1d3557;
      color: white;
      text-align: center;
      padding: 10px 0;
      z-index: 1030;
    }
    @media (max-width: 768px) {
      .footer {
        left: 0;
        width: 100%;
      }
    }
  </style>
</head>