<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Survey TNI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .navbar { background: #2d5016 !important; }
        .sidebar { min-height: 100vh; background: #343a40; padding-top: 20px; }
        .sidebar .nav-link { color: #adb5bd; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #495057; }
        .content { padding: 20px; }
        .card { box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark sticky-top flex-md-nowrap p-3">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="<?php echo base_url('admin'); ?>">
            <i class="fas fa-hospital"></i> Survey TNI
        </a>
        <ul class="navbar-nav px-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" data-toggle="dropdown">
                    <i class="fas fa-user"></i> <?php echo $this->session->userdata('nama'); ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?php echo base_url('admin/logout'); ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $this->uri->segment(2) == 'dashboard' ? 'active' : ''; ?>" href="<?php echo base_url('admin/dashboard'); ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $this->uri->segment(2) == 'kategori' ? 'active' : ''; ?>" href="<?php echo base_url('admin/kategori'); ?>">
                                <i class="fas fa-folder"></i> Kategori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $this->uri->segment(2) == 'pertanyaan' ? 'active' : ''; ?>" href="<?php echo base_url('admin/pertanyaan'); ?>">
                                <i class="fas fa-question-circle"></i> Pertanyaan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $this->uri->segment(2) == 'laporan' ? 'active' : ''; ?>" href="<?php echo base_url('admin/laporan'); ?>">
                                <i class="fas fa-chart-bar"></i> Laporan
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 content">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
