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
