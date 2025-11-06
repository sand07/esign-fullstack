<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-<?php echo isset($pertanyaan) ? 'edit' : 'plus'; ?>"></i>
        <?php echo isset($pertanyaan) ? 'Edit' : 'Tambah'; ?> Pertanyaan
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/pertanyaan'); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('', ['id' => 'form-pertanyaan']); ?>
            <div class="form-group">
                <label>Kategori <span class="text-muted">(Opsional)</span></label>
                <select name="kategori_id" class="form-control">
                    <option value="">-- Pilih Kategori (Opsional) --</option>
                    <?php foreach($kategori_list as $k): ?>
                        <option value="<?php echo $k->id; ?>" <?php echo set_select('kategori_id', $k->id, isset($pertanyaan) && $pertanyaan->kategori_id == $k->id); ?>>
                            <?php echo $k->nama_kategori; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pertanyaan <span class="text-danger">*</span></label>
                <textarea name="pertanyaan" class="form-control" rows="3" required><?php echo set_value('pertanyaan', isset($pertanyaan) ? $pertanyaan->pertanyaan : ''); ?></textarea>
                <?php echo form_error('pertanyaan', '<small class="text-danger">', '</small>'); ?>
            </div>

            <div class="form-group">
                <label>Tipe Jawaban <span class="text-danger">*</span></label>
                <select name="tipe_jawaban" id="tipe_jawaban" class="form-control" required>
                    <option value="skala" <?php echo set_select('tipe_jawaban', 'skala', isset($pertanyaan) && $pertanyaan->tipe_jawaban == 'skala'); ?>>
                        Skala (1-5)
                    </option>
                    <option value="pilihan_ganda" <?php echo set_select('tipe_jawaban', 'pilihan_ganda', isset($pertanyaan) && $pertanyaan->tipe_jawaban == 'pilihan_ganda'); ?>>
                        Pilihan Ganda
                    </option>
                    <option value="text" <?php echo set_select('tipe_jawaban', 'text', isset($pertanyaan) && $pertanyaan->tipe_jawaban == 'text'); ?>>
                        Text Bebas
                    </option>
                    <option value="ya_tidak" <?php echo set_select('tipe_jawaban', 'ya_tidak', isset($pertanyaan) && $pertanyaan->tipe_jawaban == 'ya_tidak'); ?>>
                        Ya/Tidak
                    </option>
                </select>
            </div>

            <div class="form-group" id="pilihan-wrapper" style="<?php echo (isset($pertanyaan) && $pertanyaan->tipe_jawaban == 'pilihan_ganda') ? '' : 'display:none;'; ?>">
                <label>Pilihan Jawaban <span class="text-muted">(Pisahkan dengan koma, contoh: Sangat Baik, Baik, Cukup, Kurang)</span></label>
                <textarea name="pilihan_jawaban" class="form-control" rows="2" placeholder="Pilihan 1, Pilihan 2, Pilihan 3"><?php echo set_value('pilihan_jawaban', isset($pertanyaan) ? $pertanyaan->pilihan_jawaban : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>Urutan <span class="text-danger">*</span></label>
                <input type="number" name="urutan" class="form-control" value="<?php echo set_value('urutan', isset($pertanyaan) ? $pertanyaan->urutan : 0); ?>" required>
                <small class="form-text text-muted">Menentukan urutan pertanyaan dalam survey</small>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_wajib" class="form-check-input" id="is_wajib" value="1" <?php echo set_checkbox('is_wajib', '1', isset($pertanyaan) ? $pertanyaan->is_wajib : true); ?>>
                <label class="form-check-label" for="is_wajib">
                    Pertanyaan Wajib Dijawab
                </label>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" <?php echo set_checkbox('is_active', '1', isset($pertanyaan) ? $pertanyaan->is_active : true); ?>>
                <label class="form-check-label" for="is_active">
                    Status Aktif
                </label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?php echo base_url('admin/pertanyaan'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tipe_jawaban').on('change', function() {
        if($(this).val() == 'pilihan_ganda') {
            $('#pilihan-wrapper').show();
        } else {
            $('#pilihan-wrapper').hide();
        }
    });
});
</script>
