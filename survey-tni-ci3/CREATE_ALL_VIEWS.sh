#!/bin/bash
# Script untuk membuat semua views dengan template Bootstrap

cd /home/user/survey-tni-ci3/application/views

# Template Admin Header
cat > templates/admin_header.php << 'EOF'
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
EOF

# Admin Footer
cat > templates/admin_footer.php << 'EOF'
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"}
            });
        });
    </script>
</body>
</html>
EOF

# Public Header
cat > templates/public_header.php << 'EOF'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Survey Kepuasan Pasien TNI'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .survey-container { margin-top: 50px; margin-bottom: 50px; }
        .card { box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: none; border-radius: 15px; }
        .card-header { background: #2d5016; color: white; border-radius: 15px 15px 0 0 !important; padding: 20px; }
        .btn-primary { background: #2d5016; border: none; }
        .btn-primary:hover { background: #1a2f0d; }
        .rating-btn { font-size: 1.5rem; margin: 0 5px; cursor: pointer; transition: transform 0.2s; }
        .rating-btn:hover { transform: scale(1.2); }
        .rating-btn.selected { color: #ffc107; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background: #2d5016;">
        <div class="container">
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <i class="fas fa-hospital"></i> Survey Kepuasan Pasien TNI
            </a>
        </div>
    </nav>
    <div class="container survey-container">
EOF

# Public Footer
cat > templates/public_footer.php << 'EOF'
    </div>
    <footer class="text-center text-white pb-3">
        <p>&copy; <?php echo date('Y'); ?> Survey Kepuasan Pasien TNI</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
EOF

echo "✅ Templates created (4 files)"

# Admin Login
cat > admin/login.php << 'EOF'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Survey TNI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .login-card { max-width: 400px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card login-card">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="fas fa-lock"></i> Login Admin</h4>
            </div>
            <div class="card-body">
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                <?php endif; ?>
                <?php echo form_open('admin/login'); ?>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Login</button>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="<?php echo base_url(); ?>" class="text-white">← Kembali ke Survey</a>
        </div>
    </div>
</body>
</html>
EOF

echo "✅ Admin login view created"
echo ""
echo "🎉 All critical views created successfully!"
echo "Total views: $(find . -name "*.php" | wc -l) files"
