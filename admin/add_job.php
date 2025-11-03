<?php
require_once('../config.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $salary = $_POST['salary'];
    $description = $_POST['description'];
    $logo = null;

    // Upload logo (jika ada)
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../uploads/";
        $fileName = time() . "_" . basename($_FILES["logo"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
            $logo = $fileName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO jobs (title, company, location, category, salary, description, logo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $company, $location, $category, $salary, $description, $logo);
    $stmt->execute();

    header('Location: manage_jobs.php');
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Lowongan - JobFinder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="mb-3">Tambah Lowongan Baru</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Judul</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Perusahaan</label>
      <input type="text" name="company" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Logo Perusahaan (opsional)</label>
      <input type="file" name="logo" class="form-control" accept="image/*">
    </div>
    <div class="mb-3">
      <label class="form-label">Lokasi</label>
      <input type="text" name="location" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Kategori</label>
      <input type="text" name="category" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Gaji</label>
      <input type="text" name="salary" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" class="form-control" rows="4"></textarea>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="manage_jobs.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>

