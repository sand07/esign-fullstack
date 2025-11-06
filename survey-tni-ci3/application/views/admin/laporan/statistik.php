<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-pie"></i> Statistik Survey</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/laporan'); ?>" class="btn btn-sm btn-secondary mr-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="<?php echo base_url('admin/laporan/export?'.http_build_query($filter)); ?>" class="btn btn-sm btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Periode</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('admin/laporan/statistik', ['method' => 'get']); ?>
        <div class="form-row">
            <div class="col-md-4">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo $filter['tanggal_mulai']; ?>">
            </div>
            <div class="col-md-4">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo $filter['tanggal_selesai']; ?>">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-smile"></i> Distribusi Kategori Kepuasan</h5>
            </div>
            <div class="card-body">
                <canvas id="chartKategoriKepuasan" height="250"></canvas>
                <div class="mt-3">
                    <table class="table table-sm">
                        <tr>
                            <td>Sangat Puas</td>
                            <td class="text-right"><strong><?php echo $summary['sangat_puas']; ?></strong> (<?php echo number_format($summary['persen_sangat_puas'], 1); ?>%)</td>
                        </tr>
                        <tr>
                            <td>Puas</td>
                            <td class="text-right"><strong><?php echo $summary['puas']; ?></strong> (<?php echo number_format($summary['persen_puas'], 1); ?>%)</td>
                        </tr>
                        <tr>
                            <td>Cukup</td>
                            <td class="text-right"><strong><?php echo $summary['cukup']; ?></strong> (<?php echo number_format($summary['persen_cukup'], 1); ?>%)</td>
                        </tr>
                        <tr>
                            <td>Kurang</td>
                            <td class="text-right"><strong><?php echo $summary['kurang']; ?></strong> (<?php echo number_format($summary['persen_kurang'], 1); ?>%)</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Tren Kepuasan</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTren" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-question-circle"></i> Statistik Per Pertanyaan</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2" width="50">No</th>
                        <th rowspan="2">Pertanyaan</th>
                        <th rowspan="2" width="100">Rata-rata</th>
                        <th colspan="5" class="text-center">Distribusi Rating</th>
                        <th rowspan="2" width="100">Total Respons</th>
                    </tr>
                    <tr>
                        <th class="text-center" width="60">1</th>
                        <th class="text-center" width="60">2</th>
                        <th class="text-center" width="60">3</th>
                        <th class="text-center" width="60">4</th>
                        <th class="text-center" width="60">5</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $current_kategori = null;
                    foreach($statistik_pertanyaan as $p):
                        if($p->nama_kategori != $current_kategori):
                            $current_kategori = $p->nama_kategori;
                    ?>
                    <tr class="table-secondary">
                        <td colspan="9"><strong><i class="fas fa-folder"></i> <?php echo $p->nama_kategori ? $p->nama_kategori : 'Tanpa Kategori'; ?></strong></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $p->pertanyaan; ?></td>
                        <td class="text-center">
                            <strong class="text-primary"><?php echo number_format($p->rata_rata, 2); ?></strong>
                        </td>
                        <td class="text-center"><?php echo $p->rating_1; ?></td>
                        <td class="text-center"><?php echo $p->rating_2; ?></td>
                        <td class="text-center"><?php echo $p->rating_3; ?></td>
                        <td class="text-center"><?php echo $p->rating_4; ?></td>
                        <td class="text-center"><?php echo $p->rating_5; ?></td>
                        <td class="text-center"><strong><?php echo $p->total_jawaban; ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Chart Kategori Kepuasan
    var ctx1 = document.getElementById('chartKategoriKepuasan').getContext('2d');
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Sangat Puas', 'Puas', 'Cukup', 'Kurang'],
            datasets: [{
                data: [
                    <?php echo $summary['sangat_puas']; ?>,
                    <?php echo $summary['puas']; ?>,
                    <?php echo $summary['cukup']; ?>,
                    <?php echo $summary['kurang']; ?>
                ],
                backgroundColor: ['#28a745', '#007bff', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart Tren - Load via AJAX
    $.get('<?php echo base_url('admin/laporan/chart_tren?'.http_build_query($filter)); ?>', function(data) {
        var ctx2 = document.getElementById('chartTren').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Rata-rata Kepuasan',
                    data: data.values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                }
            }
        });
    });
});
</script>
