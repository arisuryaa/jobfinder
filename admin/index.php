<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil jumlah data
$jobs  = $conn->query('SELECT COUNT(*) AS c FROM jobs')->fetch_assoc()['c'];
$users = $conn->query('SELECT COUNT(*) AS c FROM users')->fetch_assoc()['c'];
$apps  = $conn->query('SELECT COUNT(*) AS c FROM applications')->fetch_assoc()['c'];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - JobFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .card h4 {
      font-weight: bold;
      color: #0d6efd;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary">Admin Dashboard</h2>
      <div>
        <a class="btn btn-success me-2" href="jobs.php">Kelola Lowongan</a>
        <a class="btn btn-secondary me-2" href="applicants.php">Lihat Pelamar</a>
        <a class="btn btn-outline-danger" href="logout.php">Logout</a>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-center p-4">
          <h5>Total Lowongan</h5>
          <h4><?= $jobs ?></h4>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center p-4">
          <h5>Total Pengguna</h5>
          <h4><?= $users ?></h4>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center p-4">
          <h5>Total Lamaran</h5>
          <h4><?= $apps ?></h4>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
