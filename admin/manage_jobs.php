<?php
// ============================
// KELAS: KELOLA LOWONGAN ADMIN
// ============================
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config.php');
session_start();

// Cek login admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Hapus lowongan (aman dari SQL injection)
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $del = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    header('Location: manage_jobs.php');
    exit;
}

// Ambil semua lowongan
$res = $conn->query("SELECT * FROM jobs ORDER BY posted_at DESC");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Lowongan - JobFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .table thead { background-color: #212529; color: #fff; }
    .btn-action { display: flex; gap: 5px; flex-wrap: wrap; }
  </style>
</head>

<body>
<div class="container py-5">
  <div class="d-flex justify-content-between mb-4 align-items-center">
    <h3 class="fw-bold text-primary">Kelola Lowongan</h3>
    <div>
      <a href="add_job.php" class="btn btn-success me-2">+ Tambah Lowongan</a>
      <a href="dashboard.php" class="btn btn-outline-secondary">⬅ Kembali</a>
    </div>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <?php if ($res && $res->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul</th>
              <th>Perusahaan</th>
              <th>Lokasi</th>
              <th>Kategori</th>
              <th>Gaji</th>
              <th>Tanggal Posting</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['company']) ?></td>
              <td><?= htmlspecialchars($row['location']) ?></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><?= htmlspecialchars($row['salary']) ?></td>
              <td><?= htmlspecialchars($row['posted_at']) ?></td>
              <td class="text-center btn-action">
                <a href="edit_job.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                <a href="?delete=<?= $row['id'] ?>" 
                   onclick="return confirm('Yakin ingin menghapus lowongan ini?')" 
                   class="btn btn-sm btn-danger">🗑 Hapus</a>
                <a href="view_applicants.php?job_id=<?= $row['id'] ?>" 
                   class="btn btn-sm btn-info text-white">👁 Lihat Pelamar</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <?php else: ?>
        <p class="text-center text-muted my-4">Belum ada data lowongan pekerjaan.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
