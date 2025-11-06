<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-chart-bar"></i> Laporan Survey</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/laporan/statistik?'.http_build_query($filter)); ?>" class="btn btn-sm btn-info mr-2">
            <i class="fas fa-chart-pie"></i> Lihat Statistik
        </a>
        <a href="<?php echo base_url('admin/laporan/export?'.http_build_query($filter)); ?>" class="btn btn-sm btn-success">
            <i class="fas fa-file-excel"></i> Export ke Excel
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Laporan</h5>
    </div>
    <div class="card-body">
        <?php echo form_open('admin/laporan', ['method' => 'get']); ?>
        <div class="form-row">
            <div class="col-md-3">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="<?php echo $filter['tanggal_mulai']; ?>">
            </div>
            <div class="col-md-3">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo $filter['tanggal_selesai']; ?>">
            </div>
            <div class="col-md-3">
                <label>Kategori Kepuasan</label>
                <select name="kategori_kepuasan" class="form-control">
                    <option value="">Semua</option>
                    <option value="Sangat Puas" <?php echo $filter['kategori_kepuasan'] == 'Sangat Puas' ? 'selected' : ''; ?>>Sangat Puas</option>
                    <option value="Puas" <?php echo $filter['kategori_kepuasan'] == 'Puas' ? 'selected' : ''; ?>>Puas</option>
                    <option value="Cukup" <?php echo $filter['kategori_kepuasan'] == 'Cukup' ? 'selected' : ''; ?>>Cukup</option>
                    <option value="Kurang" <?php echo $filter['kategori_kepuasan'] == 'Kurang' ? 'selected' : ''; ?>>Kurang</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th width="150">Tanggal</th>
                        <th>Nama Pasien</th>
                        <th width="100">Pangkat</th>
                        <th width="150">Unit</th>
                        <th width="100">Rata-rata</th>
                        <th width="120">Kategori</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($laporan as $l): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($l->tanggal_survey)); ?></td>
                        <td><?php echo $l->nama; ?></td>
                        <td><?php echo $l->pangkat ? $l->pangkat : '-'; ?></td>
                        <td><?php echo $l->unit ? $l->unit : '-'; ?></td>
                        <td class="text-center">
                            <strong><?php echo number_format($l->rata_rata, 2); ?></strong>/5
                        </td>
                        <td>
                            <?php
                            $badge_class = 'secondary';
                            if($l->kategori_kepuasan == 'Sangat Puas') $badge_class = 'success';
                            elseif($l->kategori_kepuasan == 'Puas') $badge_class = 'primary';
                            elseif($l->kategori_kepuasan == 'Cukup') $badge_class = 'warning';
                            elseif($l->kategori_kepuasan == 'Kurang') $badge_class = 'danger';
                            ?>
                            <span class="badge badge-<?php echo $badge_class; ?>">
                                <?php echo $l->kategori_kepuasan; ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/laporan/detail/'.$l->survey_id); ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($laporan)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Tidak ada data survey dengan filter yang dipilih.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h5><?php echo number_format($summary['total']); ?></h5>
                <small class="text-muted">Total Survey</small>
            </div>
            <div class="col-md-3">
                <h5><?php echo number_format($summary['rata_rata'], 2); ?>/5</h5>
                <small class="text-muted">Rata-rata Kepuasan</small>
            </div>
            <div class="col-md-3">
                <h5><?php echo number_format($summary['sangat_puas']); ?></h5>
                <small class="text-muted">Sangat Puas</small>
            </div>
            <div class="col-md-3">
                <h5><?php echo number_format($summary['puas']); ?></h5>
                <small class="text-muted">Puas</small>
            </div>
        </div>
    </div>
</div>
