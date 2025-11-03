<?php
require_once('../config.php'); session_start(); if(!isset($_SESSION['admin_id'])){ header('Location: login.php'); exit; }
$res = $conn->query('SELECT a.id,a.cv_file,a.applied_at,u.name,j.title FROM applications a JOIN users u ON a.user_id=u.id JOIN jobs j ON a.job_id=j.id ORDER BY a.applied_at DESC');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Pelamar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-4"><a href="index.php" class="btn btn-link">&larr; Dashboard</a><h4>Daftar Pelamar</h4>
<table class="table table-striped"><thead><tr><th>#</th><th>Nama</th><th>Posisi</th><th>CV</th><th>Waktu</th></tr></thead><tbody>
<?php while($r=$res->fetch_assoc()): ?><tr><td><?=$r['id']?></td><td><?=htmlspecialchars($r['name'])?></td><td><?=htmlspecialchars($r['title'])?></td><td><a href="../uploads/<?=urlencode($r['cv_file'])?>" target="_blank">Download</a></td><td><?=htmlspecialchars($r['applied_at'])?></td></tr><?php endwhile; ?>
</tbody></table></div></body></html>
