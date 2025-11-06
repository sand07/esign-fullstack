<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-file-alt"></i> Detail Survey</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/laporan'); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Laporan
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Data Responden</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="120">Nama:</th>
                        <td><?php echo $survey->nama; ?></td>
                    </tr>
                    <tr>
                        <th>NRP:</th>
                        <td><?php echo $survey->nrp ? $survey->nrp : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Pangkat:</th>
                        <td><?php echo $survey->pangkat ? $survey->pangkat : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Unit:</th>
                        <td><?php echo $survey->unit ? $survey->unit : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $survey->email ? $survey->email : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Telepon:</th>
                        <td><?php echo $survey->telepon ? $survey->telepon : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal:</th>
                        <td><?php echo date('d/m/Y H:i', strtotime($survey->tanggal_survey)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> Hasil Kepuasan</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4 text-success"><?php echo number_format($survey->rata_rata, 2); ?></h1>
                <p class="text-muted">dari skala 5</p>
                <hr>
                <?php
                $badge_class = 'secondary';
                if($survey->kategori_kepuasan == 'Sangat Puas') $badge_class = 'success';
                elseif($survey->kategori_kepuasan == 'Puas') $badge_class = 'primary';
                elseif($survey->kategori_kepuasan == 'Cukup') $badge_class = 'warning';
                elseif($survey->kategori_kepuasan == 'Kurang') $badge_class = 'danger';
                ?>
                <h3><span class="badge badge-<?php echo $badge_class; ?>"><?php echo $survey->kategori_kepuasan; ?></span></h3>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Detail Jawaban</h5>
            </div>
            <div class="card-body">
                <?php
                $current_kategori = null;
                foreach($jawaban as $j):
                    if($j->nama_kategori != $current_kategori):
                        if($current_kategori !== null):
                            echo '</div></div>';
                        endif;
                        $current_kategori = $j->nama_kategori;
                ?>
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2">
                        <i class="fas fa-folder"></i> <?php echo $j->nama_kategori ? $j->nama_kategori : 'Tanpa Kategori'; ?>
                    </h6>
                    <div class="ml-3">
                <?php endif; ?>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong><?php echo $j->pertanyaan; ?></strong>
                            </div>
                            <div class="ml-3">
                                <?php if($j->tipe_jawaban == 'skala'): ?>
                                    <span class="badge badge-primary badge-lg" style="font-size: 1.1em;">
                                        <?php echo $j->jawaban; ?>/5
                                    </span>
                                <?php elseif($j->tipe_jawaban == 'ya_tidak'): ?>
                                    <span class="badge badge-<?php echo $j->jawaban == 'Ya' ? 'success' : 'secondary'; ?>">
                                        <?php echo $j->jawaban; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-info"><?php echo $j->jawaban; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
                <?php if($current_kategori !== null): ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(!empty($survey->komentar)): ?>
                <hr>
                <div class="mt-3">
                    <h6 class="text-primary"><i class="fas fa-comment"></i> Komentar & Saran:</h6>
                    <div class="alert alert-light">
                        <?php echo nl2br(htmlspecialchars($survey->komentar)); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
