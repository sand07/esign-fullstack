<div class="row justify-content-center">
    <div class="col-md-10">
        <?php echo form_open('survey/submit', ['id' => 'form-survey']); ?>

        <!-- Data Responden -->
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="fas fa-user"></i> Data Responden</h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>NRP <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="nrp" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pangkat <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="pangkat" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit/Kesatuan <span class="text-muted">(Opsional)</span></label>
                            <input type="text" name="unit" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <span class="text-muted">(Opsional)</span></label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telepon <span class="text-muted">(Opsional)</span></label>
                            <input type="tel" name="telepon" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertanyaan Survey -->
        <?php
        $current_kategori = null;
        $pertanyaan_no = 1;
        foreach($pertanyaan as $p):
            if($p->kategori_id != $current_kategori):
                if($current_kategori !== null):
                    echo '</div></div>';
                endif;
                $current_kategori = $p->kategori_id;
        ?>
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-folder"></i> <?php echo $p->nama_kategori ? $p->nama_kategori : 'Pertanyaan Umum'; ?>
                </h5>
            </div>
            <div class="card-body">
        <?php endif; ?>

            <div class="mb-4 pb-3 border-bottom">
                <label class="font-weight-bold">
                    <?php echo $pertanyaan_no++; ?>. <?php echo $p->pertanyaan; ?>
                    <?php if($p->is_wajib): ?>
                        <span class="text-danger">*</span>
                    <?php endif; ?>
                </label>

                <?php if($p->tipe_jawaban == 'skala'): ?>
                    <!-- Rating Skala 1-5 -->
                    <div class="rating-container mt-2">
                        <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                            <label class="btn btn-outline-primary flex-fill">
                                <input type="radio" name="jawaban[<?php echo $p->id; ?>]" value="<?php echo $i; ?>" <?php echo $p->is_wajib ? 'required' : ''; ?>>
                                <?php echo $i; ?>
                            </label>
                            <?php endfor; ?>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Sangat Kurang</small>
                            <small class="text-muted">Sangat Baik</small>
                        </div>
                    </div>

                <?php elseif($p->tipe_jawaban == 'pilihan_ganda'): ?>
                    <!-- Pilihan Ganda -->
                    <?php
                    $pilihan = explode(',', $p->pilihan_jawaban);
                    foreach($pilihan as $idx => $pil):
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jawaban[<?php echo $p->id; ?>]" id="pil_<?php echo $p->id; ?>_<?php echo $idx; ?>" value="<?php echo trim($pil); ?>" <?php echo $p->is_wajib ? 'required' : ''; ?>>
                        <label class="form-check-label" for="pil_<?php echo $p->id; ?>_<?php echo $idx; ?>">
                            <?php echo trim($pil); ?>
                        </label>
                    </div>
                    <?php endforeach; ?>

                <?php elseif($p->tipe_jawaban == 'ya_tidak'): ?>
                    <!-- Ya/Tidak -->
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-outline-success">
                            <input type="radio" name="jawaban[<?php echo $p->id; ?>]" value="Ya" <?php echo $p->is_wajib ? 'required' : ''; ?>> Ya
                        </label>
                        <label class="btn btn-outline-danger">
                            <input type="radio" name="jawaban[<?php echo $p->id; ?>]" value="Tidak" <?php echo $p->is_wajib ? 'required' : ''; ?>> Tidak
                        </label>
                    </div>

                <?php elseif($p->tipe_jawaban == 'text'): ?>
                    <!-- Text Bebas -->
                    <textarea name="jawaban[<?php echo $p->id; ?>]" class="form-control mt-2" rows="3" <?php echo $p->is_wajib ? 'required' : ''; ?>></textarea>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
        <?php if($current_kategori !== null): ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Komentar & Saran -->
        <div class="card mb-3">
            <div class="card-header">
                <h5><i class="fas fa-comment"></i> Komentar & Saran</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Silakan berikan komentar, kritik, atau saran untuk perbaikan pelayanan</label>
                    <textarea name="komentar" class="form-control" rows="4" placeholder="Tuliskan komentar atau saran Anda..."></textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card mb-3">
            <div class="card-body text-center">
                <p class="text-muted">Mohon periksa kembali jawaban Anda sebelum mengirim</p>
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane"></i> Kirim Survey
                </button>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('#form-survey').on('submit', function(e) {
        var requiredFields = $(this).find('[required]');
        var allFilled = true;

        requiredFields.each(function() {
            if($(this).is(':radio')) {
                var name = $(this).attr('name');
                if(!$('input[name="' + name + '"]:checked').length) {
                    allFilled = false;
                    $(this).closest('.mb-4').addClass('border-danger');
                }
            } else if(!$(this).val()) {
                allFilled = false;
                $(this).addClass('is-invalid');
            }
        });

        if(!allFilled) {
            e.preventDefault();
            alert('Mohon lengkapi semua pertanyaan yang wajib diisi (bertanda *)');
            $('html, body').animate({
                scrollTop: $('.border-danger, .is-invalid').first().offset().top - 100
            }, 500);
            return false;
        }

        return confirm('Apakah Anda yakin ingin mengirim survey ini? Data tidak dapat diubah setelah dikirim.');
    });

    // Remove error styling on input
    $('input, textarea').on('change', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.mb-4').removeClass('border-danger');
    });
});
</script>
