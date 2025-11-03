<?php
require_once('../config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: jobs.php');
    exit;
}

$id = (int)$_GET['id'];
$job = $conn->query("SELECT * FROM jobs WHERE id=$id")->fetch_assoc();

if (!$job) {
    die("Lowongan tidak ditemukan.");
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $salary = $_POST['salary'];
    $description = $_POST['description'];

    $logoPath = $job['logo']; // gunakan logo lama dulu

    // Jika ada logo baru diupload
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($_FILES['logo']['name']);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile);
        $logoPath = $fileName;
    }

    $stmt = $conn->prepare("UPDATE jobs SET title=?, company=?, location=?, category=?, salary=?, description=?, logo=? WHERE id=?");
    $stmt->bind_param("sssssssi", $title, $company, $location, $category, $salary, $description, $logoPath, $id);

    if ($stmt->execute()) {
        header('Location: jobs.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui data.</div>";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Lowongan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h3 class="mb-4">Edit Lowongan</h3>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Judul Pekerjaan</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($job['title']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Perusahaan</label>
      <input type="text" name="company" class="form-control" value="<?= htmlspecialchars($job['company']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Lokasi</label>
      <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($job['location']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Kategori</label>
      <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($job['category']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Gaji</label>
      <input type="text" name="salary" class="form-control" value="<?= htmlspecialchars($job['salary'] ?? '') ?>" placeholder="Contoh: Rp 5.000.000 - Rp 7.000.000">
    </div>
    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($job['description'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Logo Perusahaan</label><br>
      <?php if (!empty($job['logo'])): ?>
        <img src="../uploads/<?= htmlspecialchars($job['logo']) ?>" alt="Logo" style="height:80px;border-radius:8px;margin-bottom:10px;"><br>
      <?php endif; ?>
      <input type="file" name="logo" class="form-control">
      <small class="text-muted">Biarkan kosong jika tidak ingin mengubah logo.</small>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="jobs.php" class="btn btn-secondary">Batal</a>
  </form>
</div>
</body>
</html>
