<?php
require_once('../config.php'); session_start();
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM jobs WHERE id = ?'); $stmt->bind_param('i',$id); $stmt->execute();
$job = $stmt->get_result()->fetch_assoc(); if(!$job){ header('Location: index.php'); exit; }
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=htmlspecialchars($job['title'])?> - JobFinder</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body>
<div class="container py-4"><a href="index.php" class="btn btn-link">&larr; Kembali</a>
<div class="card"><div class="card-body">
<h3><?=htmlspecialchars($job['title'])?></h3>
<p class="text-muted"><?=htmlspecialchars($job['company'])?> — <?=htmlspecialchars($job['location'])?> · <small><?=htmlspecialchars($job['category'])?></small></p>
<p><strong>Type:</strong> <?=htmlspecialchars($job['type'])?> | <strong>Salary:</strong> <?=htmlspecialchars($job['salary'])?></p>
<div class="mt-3"><?=nl2br(htmlspecialchars($job['description']))?></div>
<div class="mt-4">
<?php if(isset($_SESSION['user_id'])): ?>
  <a href="apply.php?job_id=<?=$job['id']?>" class="btn btn-primary">Lamar Sekarang</a>
  <a href="dashboard.php" class="btn btn-outline-secondary">Dashboard Pelamar</a>
<?php else: ?>
  <a href="login.php" class="btn btn-outline-primary">Masuk untuk Melamar</a>
<?php endif; ?>
</div>
</div></div></div></body></html>
