<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-file-excel"></i> Import Pertanyaan dari Excel</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/pertanyaan'); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-upload"></i> Upload File Excel</h5>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('admin/pertanyaan/do_import'); ?>
                    <div class="form-group">
                        <label>Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file_excel" class="form-control-file" accept=".xls,.xlsx" required>
                        <small class="form-text text-muted">
                            Format file: .xls atau .xlsx (Maksimal 2MB)
                        </small>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="hapus_existing" class="form-check-input" id="hapus_existing" value="1">
                        <label class="form-check-label" for="hapus_existing">
                            <strong>Hapus semua pertanyaan existing sebelum import</strong>
                            <br><small class="text-danger">Hati-hati! Ini akan menghapus semua pertanyaan yang ada.</small>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload & Import
                    </button>
                <?php echo form_close(); ?>
            </div>
        </div>

        <?php if(isset($import_result)): ?>
        <div class="card mt-3">
            <div class="card-header bg-<?php echo $import_result['success'] ? 'success' : 'danger'; ?> text-white">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $import_result['success'] ? 'check-circle' : 'times-circle'; ?>"></i>
                    Hasil Import
                </h5>
            </div>
            <div class="card-body">
                <p><strong>Total Diproses:</strong> <?php echo $import_result['total']; ?> baris</p>
                <p><strong>Berhasil:</strong> <?php echo $import_result['inserted']; ?> pertanyaan</p>
                <p><strong>Gagal:</strong> <?php echo $import_result['failed']; ?> baris</p>

                <?php if(!empty($import_result['errors'])): ?>
                <div class="alert alert-warning">
                    <strong>Error Details:</strong>
                    <ul class="mb-0">
                        <?php foreach($import_result['errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Panduan Import</h5>
            </div>
            <div class="card-body">
                <h6>Format Excel:</h6>
                <ol class="small">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Isi data sesuai kolom yang tersedia:
                        <ul>
                            <li><strong>kategori_id:</strong> ID kategori (kosongkan jika tidak ada)</li>
                            <li><strong>pertanyaan:</strong> Isi pertanyaan (wajib)</li>
                            <li><strong>tipe_jawaban:</strong> skala/pilihan_ganda/text/ya_tidak</li>
                            <li><strong>pilihan_jawaban:</strong> Untuk pilihan ganda (pisahkan dengan koma)</li>
                            <li><strong>is_wajib:</strong> 1 atau 0</li>
                            <li><strong>urutan:</strong> Angka urutan</li>
                            <li><strong>is_active:</strong> 1 atau 0</li>
                        </ul>
                    </li>
                    <li>Simpan file dalam format .xlsx atau .xls</li>
                    <li>Upload file melalui form di samping</li>
                </ol>

                <hr>

                <div class="text-center">
                    <a href="<?php echo base_url('admin/pertanyaan/template'); ?>" class="btn btn-success btn-block">
                        <i class="fas fa-download"></i> Download Template Excel
                    </a>
                </div>

                <hr>

                <h6 class="text-danger">Perhatian:</h6>
                <ul class="small text-danger">
                    <li>Baris pertama adalah header (akan diabaikan)</li>
                    <li>Pastikan tipe_jawaban sesuai: skala, pilihan_ganda, text, atau ya_tidak</li>
                    <li>Untuk pilihan_ganda, wajib mengisi kolom pilihan_jawaban</li>
                    <li>Jika hapus existing dicentang, SEMUA pertanyaan lama akan terhapus</li>
                </ul>
            </div>
        </div>
    </div>
</div>
