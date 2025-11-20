<?php
require_once('../config.php'); 
session_start(); 

if(!isset($_SESSION['admin_id'])){
    header('Location: login.php'); 
    exit;
}

// Handle Delete Request
if(isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    
    // Get logo file path before deleting
    $logo_query = "SELECT logo FROM jobs WHERE id = $delete_id";
    $logo_result = mysqli_query($conn, $logo_query);
    if($logo_row = mysqli_fetch_assoc($logo_result)){
        if($logo_row['logo'] && file_exists($logo_row['logo'])){
            unlink($logo_row['logo']); // Delete logo file
        }
    }
    
    $delete_query = "DELETE FROM jobs WHERE id = $delete_id";
    
    if(mysqli_query($conn, $delete_query)){
        $_SESSION['success_message'] = "Lowongan berhasil dihapus!";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus lowongan: " . mysqli_error($conn);
    }
    
    header('Location: jobs.php');
    exit;
}

// Fetch all jobs
$query = "SELECT * FROM jobs ORDER BY posted_at DESC";
$result = mysqli_query($conn, $query);

if(!$result){
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JobFinder | Kelola Lowongan</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="./plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="./plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./css/adminlte.min.css">

    <style>
    .alert {
        margin: 15px;
    }

    .description-cell {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-buttons {
        white-space: nowrap;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
                <span class="brand-text font-weight-light">JobFinder</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- SidebarSearch Form -->
                <div class="form-inline mt-2">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <p>
                                    Menu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="./index.php" class="nav-link ">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./jobs.php" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kelola Lowongan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./applicant.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Lihat Lamaran</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./logout.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Logout</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Kelola Lowongan Pekerjaan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Kelola Lowongan</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <?php if(isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php 
                                echo $_SESSION['success_message']; 
                                unset($_SESSION['success_message']);
                            ?>
                    </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?php 
                                echo $_SESSION['error_message']; 
                                unset($_SESSION['error_message']);
                            ?>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Data Lowongan Pekerjaan</h3>
                                    <div class="card-tools">
                                        <a href="add_job.php" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus"></i> Tambah Lowongan
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="3%">ID</th>
                                                <th width="15%">Judul</th>
                                                <th width="12%">Perusahaan</th>
                                                <th width="10%">Lokasi</th>
                                                <th width="10%">Kategori</th>
                                                <th width="8%">Tipe</th>
                                                <th width="10%">Gaji</th>
                                                <th width="8%">Status</th>
                                                <th width="12%">Tanggal Posting</th>
                                                <th width="12%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                <td><?php echo htmlspecialchars($row['company']); ?></td>
                                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($row['category']); ?></small>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $type = $row['type'];
                                                        $type_badge = 'badge-secondary';
                                                        if ($type == 'Full Time') $type_badge = 'badge-success';
                                                        elseif ($type == 'Part Time') $type_badge = 'badge-warning';
                                                        elseif ($type == 'Contract') $type_badge = 'badge-info';
                                                        elseif ($type == 'Freelance') $type_badge = 'badge-primary';
                                                        elseif ($type == 'Internship') $type_badge = 'badge-dark';
                                                    ?>
                                                    <span class="badge <?php echo $type_badge; ?>">
                                                        <?php echo htmlspecialchars($row['type']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($row['salary']); ?></small>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $status = isset($row['status']) ? $row['status'] : 'pending';
                                                        $status_badge = 'badge-secondary';
                                                        $status_text = 'pending';
                                                        
                                                        if ($status == 'active') {
                                                            $status_badge = 'badge-success';
                                                            $status_text = 'Active';
                                                        } elseif ($status == 'inactive') {
                                                            $status_badge = 'badge-danger';
                                                            $status_text = 'In Active';
                                                        } 
                                                    ?>
                                                    <span class="badge <?php echo $status_badge; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>
                                                        <?php 
                                                        if($row['posted_at']){
                                                            echo date('d M Y', strtotime($row['posted_at'])); 
                                                            echo '<br>';
                                                            echo date('H:i', strtotime($row['posted_at'])); 
                                                        } else {
                                                            echo '-';
                                                        }
                                                        ?>
                                                    </small>
                                                </td>
                                                <td class="action-buttons">
                                                    <div class="btn-group">
                                                        <a href="edit_job.php?id=<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            data-id="<?php echo $row['id']; ?>"
                                                            data-title="<?php echo htmlspecialchars($row['title']); ?>"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2024 JobFinder.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus lowongan "<strong id="jobTitle"></strong>"?</p>
                    <p class="text-warning"><small><i class="fas fa-exclamation-triangle"></i> Data yang dihapus tidak
                            dapat dikembalikan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="./plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="./plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="./plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="./plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="./plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="./plugins/jszip/jszip.min.js"></script>
    <script src="./plugins/pdfmake/pdfmake.min.js"></script>
    <script src="./plugins/pdfmake/vfs_fonts.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="./plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./js/adminlte.min.js"></script>

    <!-- Page specific script -->
    <script>
    $(function() {
        // Initialize DataTable
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "order": [
                [0, "desc"]
            ], // Sort by ID descending
            "buttons": ["copy", "csv", "excel", "pdf", "print"],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Handle delete button click
        $(document).on('click', '.delete-btn', function() {
            var jobId = $(this).data('id');
            var jobTitle = $(this).data('title');

            $('#jobTitle').text(jobTitle);
            $('#confirmDelete').attr('href', 'jobs.php?delete_id=' + jobId);
            $('#deleteModal').modal('show');
        });
    });
    </script>
</body>

</html>