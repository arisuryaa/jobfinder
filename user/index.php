<?php
// user/index.php (perbaikan: memastikan $res selalu terdefinisi)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config.php');
session_start();

$locations = ['Jakarta','Bandung','Bali','Surabaya','Yogyakarta'];
$categories = ['IT','Akuntansi','Marketing','Desain','Administrasi'];

$loc = $_GET['location'] ?? '';
$cat = $_GET['category'] ?? '';
$q   = $_GET['q'] ?? '';

$sql = "SELECT id, title, company, location, category, type, salary, posted_at, IFNULL(logo,'') AS logo FROM jobs WHERE 1=1";
$params = [];
$types = '';

if ($q !== '') {
    $sql .= " AND (title LIKE ? OR company LIKE ?)";
    $like = "%{$q}%";
    $params[] = $like;
    $params[] = $like;
    $types .= 'ss';
}
if ($loc !== '') {
    $sql .= " AND location = ?";
    $params[] = $loc;
    $types .= 's';
}
if ($cat !== '') {
    $sql .= " AND category = ?";
    $params[] = $cat;
    $types .= 's';
}

$sql .= " ORDER BY posted_at DESC";

// Jika ada parameter, gunakan prepared statement. Kalau tidak, gunakan query langsung.
// Ini mencegah $res menjadi null jika prepare() gagal.
$res = false;
if (count($params) > 0) {
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // bind dengan splat operator tidak didukung di beberapa versi PHP+mysqli, gunakan call_user_func_array fallback
        $bind_names[] = $types;
        for ($i=0; $i<count($params); $i++) {
            $bind_name = 'bind' . $i;
            $$bind_name = $params[$i];
            $bind_names[] = &$$bind_name;
        }
        // bind_param butuh reference array
        call_user_func_array([$stmt, 'bind_param'], $bind_names);
        $stmt->execute();
        $res = $stmt->get_result();
    } else {
        // prepare gagal => fallback ke query sederhana (sanitasi q untuk keamanan)
        $safe_sql = $sql;
        foreach ($params as $p) {
            $safe_sql = preg_replace('/\?/', "'" . $conn->real_escape_string($p) . "'", $safe_sql, 1);
        }
        $res = $conn->query($safe_sql);
    }
} else {
    $res = $conn->query($sql);
}

// pastikan $res valid
if (!$res) {
    $error_msg = htmlspecialchars($conn->error ?: 'Query gagal dijalankan.');
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JobFinder - Lowongan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">JobFinder</a>
      <div class="d-flex">
        <?php if (isset($_SESSION['user_id'])): ?>
          <span class="me-2">Halo, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
          <a class="btn btn-outline-secondary btn-sm" href="logout.php">Logout</a>
        <?php else: ?>
          <a class="btn btn-outline-primary btn-sm me-2" href="login.php">Masuk</a>
          <a class="btn btn-primary btn-sm" href="register.php">Daftar</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <div class="card p-3 mb-3 shadow-sm">
      <form class="row g-2" method="get">
        <div class="col-md-4">
          <input name="q" class="form-control" placeholder="Cari posisi atau perusahaan" 
                 value="<?= htmlspecialchars($q) ?>">
        </div>
        <div class="col-md-3">
          <select name="location" class="form-select">
            <option value="">Semua Lokasi</option>
            <?php foreach ($locations as $l): ?>
              <option value="<?= htmlspecialchars($l) ?>" <?= ($loc == $l) ? 'selected' : '' ?>>
                <?= htmlspecialchars($l) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <select name="category" class="form-select">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>" <?= ($cat == $c) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Cari</button>
        </div>
      </form>
    </div>

    <?php if (!empty($error_msg)): ?>
      <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-8">
        <?php if ($res && $res->num_rows > 0): ?>
          <?php while ($job = $res->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
              <div class="card-body d-flex align-items-center">
                <?php if (!empty($job['logo'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($job['logo']) ?>" 
                       alt="Logo <?= htmlspecialchars($job['company']) ?>" 
                       style="width:80px; height:80px; object-fit:contain; margin-right:15px; border-radius:8px; background:#fff;">
                <?php else: ?>
                  <div style="width:80px; height:80px; background:#e9ecef; border-radius:8px; margin-right:15px; display:flex; align-items:center; justify-content:center; color:#6c757d;">
                    No Logo
                  </div>
                <?php endif; ?>

                <div>
                  <h5 class="card-title mb-1"><?= htmlspecialchars($job['title']) ?></h5>
                  <h6 class="card-subtitle mb-2 text-muted">
                    <?= htmlspecialchars($job['company']) ?> — <?= htmlspecialchars($job['location']) ?> 
                    · <small><?= htmlspecialchars($job['category']) ?></small>
                  </h6>
                  <p class="mb-1 small text-secondary">
                    <?= htmlspecialchars($job['type']) ?> | <?= htmlspecialchars($job['salary']) ?>
                  </p>
                  <a href="job_detail.php?id=<?= $job['id'] ?>" class="stretched-link">Lihat detail</a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-muted">Belum ada lowongan yang sesuai.</p>
        <?php endif; ?>
      </div>

      <div class="col-md-4">
        <div class="card p-3 mb-3">
          <h6>Tips Melamar</h6>
          <ul class="small mb-0">
            <li>Sesuaikan CV</li>
            <li>Tulis cover letter singkat</li>
            <li>Periksa data kontak</li>
          </ul>
        </div>
        <div class="card p-3">
          <h6>Perusahaan Populer</h6>
          <p class="small mb-0">Bintang Digital, Solusi Keuangan, Studio Desain...</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
