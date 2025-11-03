<?php
require_once('../config.php');
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$query = "
    SELECT 
        a.id,
        u.name,
        j.title AS job_title,
        a.cv_file,
        a.created_at
    FROM applications a
    JOIN users u ON a.user_id = u.id
    JOIN jobs j ON a.job_id = j.id
    ORDER BY a.created_at DESC
";
$result = $conn->query($query);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pelamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <a href="index.php" class="btn btn-link mb-3">← Dashboard</a>
    <h4 class="mb-4">Daftar Pelamar</h4>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>CV</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['job_title']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary"
                                       href="../uploads/<?= htmlspecialchars($row['cv_file']) ?>"
                                       target="_blank">Lihat CV</a>
                                </td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Belum ada pelamar.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
