<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-<?php echo isset($kategori) ? 'edit' : 'plus'; ?>"></i>
        <?php echo isset($kategori) ? 'Edit' : 'Tambah'; ?> Kategori
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/kategori'); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?php echo form_open(''); ?>
                    <div class="form-group">
                        <label>Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" value="<?php echo set_value('nama_kategori', isset($kategori) ? $kategori->nama_kategori : ''); ?>" required autofocus>
                        <?php echo form_error('nama_kategori', '<small class="text-danger">', '</small>'); ?>
                        <small class="form-text text-muted">
                            Contoh: Pelayanan Medis, Fasilitas, Administrasi, dll.
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi <span class="text-muted">(Opsional)</span></label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo set_value('deskripsi', isset($kategori) ? $kategori->deskripsi : ''); ?></textarea>
                        <small class="form-text text-muted">
                            Penjelasan singkat tentang kategori ini
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="<?php echo base_url('admin/kategori'); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
            </div>
            <div class="card-body">
                <h6>Nama Kategori yang Baik:</h6>
                <ul class="small">
                    <li>Singkat dan jelas</li>
                    <li>Menggambarkan aspek yang akan disurvey</li>
                    <li>Mudah dipahami responden</li>
                </ul>

                <hr>

                <h6>Contoh Kategori:</h6>
                <ul class="small mb-0">
                    <li><strong>Pelayanan Medis</strong><br>
                        <small class="text-muted">Kualitas pelayanan dokter dan perawat</small>
                    </li>
                    <li><strong>Fasilitas</strong><br>
                        <small class="text-muted">Kondisi ruangan, peralatan medis</small>
                    </li>
                    <li><strong>Administrasi</strong><br>
                        <small class="text-muted">Proses pendaftaran, pembayaran</small>
                    </li>
                    <li><strong>Kebersihan</strong><br>
                        <small class="text-muted">Kebersihan ruangan dan lingkungan</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
