<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin - JobFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card p-4 shadow-sm">
      <h3 class="mb-3">Selamat Datang, <?= htmlspecialchars($_SESSION['admin_name']) ?>!</h3>
      <p>Anda berhasil login ke <strong>Dashboard Admin JobFinder</strong>.</p>
      <hr>
      <a href="manage_jobs.php" class="btn btn-primary">Kelola Lowongan</a>
      <a href="manage_users.php" class="btn btn-secondary">Kelola Pengguna</a>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</body>
</html>

