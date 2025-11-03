<?php
require_once('../config.php'); 
session_start(); 

if(!isset($_SESSION['admin_id'])){
    header('Location: login.php'); 
    exit;
}

$locations = ['Jakarta','Bandung','Bali','Surabaya','Yogyakarta'];
$categories = ['IT','Akuntansi','Marketing','Desain','Administrasi'];
$err=''; 

// === TAMBAH DATA ===
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add'])){
  $title=$_POST['title']; 
  $company=$_POST['company']; 
  $location=$_POST['location']; 
  $category=$_POST['category']; 
  $type=$_POST['type']; 
  $salary=$_POST['salary']; 
  $desc=$_POST['description'];

  // Upload logo
  $logoPath = '';
  if (!empty($_FILES['logo']['name'])) {
      $targetDir = "../uploads/";
      if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
      $fileName = time() . "_" . basename($_FILES['logo']['name']);
      $targetFile = $targetDir . $fileName;
      move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile);
      $logoPath = $fileName;
  }

  $stmt = $conn->prepare('INSERT INTO jobs (title,company,location,category,type,salary,description,logo) VALUES (?,?,?,?,?,?,?,?)');
  $stmt->bind_param('ssssssss',$title,$company,$location,$category,$type,$salary,$desc,$logoPath);

  if($stmt->execute()) header('Location: jobs.php'); 
  else $err='Gagal menyimpan.';
}

// === HAPUS DATA ===
if(isset($_GET['del'])){ 
  $id=intval($_GET['del']); 
  $stmt=$conn->prepare('DELETE FROM jobs WHERE id=?'); 
  $stmt->bind_param('i',$id); 
  $stmt->execute(); 
  header('Location: jobs.php'); 
}

$res = $conn->query('SELECT * FROM jobs ORDER BY posted_at DESC');
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Kelola Lowongan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
<a href="index.php" class="btn btn-link">&larr; Dashboard</a>
<h4>Kelola Lowongan</h4>

<?php if($err): ?>
  <div class="alert alert-danger"><?=htmlspecialchars($err)?></div>
<?php endif; ?>

<!-- === FORM TAMBAH LOWONGAN === -->
<div class="card p-3 mb-3">
<form method="post" enctype="multipart/form-data" class="row g-2">
  <div class="col-md-6"><input name="title" class="form-control" placeholder="Judul" required></div>
  <div class="col-md-6"><input name="company" class="form-control" placeholder="Perusahaan" required></div>

  <div class="col-md-4">
    <select name="location" class="form-select">
      <?php foreach($locations as $l): ?><option value="<?=$l?>"><?=$l?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <select name="category" class="form-select">
      <?php foreach($categories as $c): ?><option value="<?=$c?>"><?=$c?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4"><input name="type" class="form-control" placeholder="Tipe (Full-time)"></div>

  <div class="col-md-6"><input name="salary" class="form-control" placeholder="Gaji"></div>
  <div class="col-md-6"><input name="description" class="form-control" placeholder="Deskripsi"></div>

  <!-- Upload logo -->
  <div class="col-md-6">
    <input type="file" name="logo" class="form-control" accept="image/*" required>
    <small class="text-muted">Upload logo perusahaan (JPG, PNG)</small>
  </div>

  <div class="col-12"><button class="btn btn-primary" name="add">Tambah Lowongan</button></div>
</form>
</div>

<!-- === TABEL DATA === -->
<table class="table table-striped align-middle">
<thead>
  <tr>
    <th>ID</th>
    <th>Logo</th>
    <th>Judul</th>
    <th>Perusahaan</th>
    <th>Lokasi</th>
    <th>Kategori</th>
    <th>Aksi</th>
  </tr>
</thead>
<tbody>
<?php while($r=$res->fetch_assoc()): ?>
<tr>
  <td><?=$r['id']?></td>
  <td>
    <?php if(!empty($r['logo'])): ?>
      <img src="../uploads/<?=htmlspecialchars($r['logo'])?>" alt="logo" width="50" height="50" style="object-fit:contain;border-radius:8px;">
    <?php else: ?>
      <span class="text-muted">No Logo</span>
    <?php endif; ?>
  </td>
  <td><?=htmlspecialchars($r['title'])?></td>
  <td><?=htmlspecialchars($r['company'])?></td>
  <td><?=htmlspecialchars($r['location'])?></td>
  <td><?=htmlspecialchars($r['category'])?></td>
  <td>
    <a class="btn btn-sm btn-warning" href="edit.php?id=<?=$r['id']?>">Edit</a>
    <a class="btn btn-sm btn-danger" href="jobs.php?del=<?=$r['id']?>" onclick="return confirm('Hapus?')">Hapus</a>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</body>
</html>
