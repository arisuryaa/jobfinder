<?php
require_once('../config.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Hapus user
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header('Location: manage_users.php');
    exit;
}

$res = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Pengguna - JobFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="d-flex justify-content-between mb-3">
    <h3>Kelola Pengguna</h3>
    <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
  </div>

  <table class="table table-striped table-bordered bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Tanggal Daftar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
        <td>
          <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>

