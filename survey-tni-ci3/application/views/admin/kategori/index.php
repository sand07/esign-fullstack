<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-folder"></i> Manajemen Kategori Pertanyaan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/kategori/create'); ?>" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th width="120">Jumlah Pertanyaan</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($kategori as $k): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $k->nama_kategori; ?></strong></td>
                        <td><?php echo $k->deskripsi ? $k->deskripsi : '<span class="text-muted">-</span>'; ?></td>
                        <td class="text-center">
                            <span class="badge badge-primary"><?php echo $k->jumlah_pertanyaan; ?> pertanyaan</span>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/kategori/edit/'.$k->id); ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if($k->jumlah_pertanyaan == 0): ?>
                            <a href="<?php echo base_url('admin/kategori/delete/'.$k->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?');" title="Hapus">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                            <?php else: ?>
                            <button class="btn btn-sm btn-secondary" disabled title="Tidak bisa dihapus karena masih ada pertanyaan">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($kategori)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada kategori. Silakan tambah kategori baru.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi</h5>
    </div>
    <div class="card-body">
        <p class="mb-1"><strong>Kategori Pertanyaan</strong> digunakan untuk mengelompokkan pertanyaan survey berdasarkan tema atau aspek tertentu.</p>
        <p class="mb-1">Contoh kategori:</p>
        <ul class="mb-0">
            <li>Pelayanan Medis</li>
            <li>Fasilitas</li>
            <li>Administrasi</li>
            <li>Kebersihan</li>
            <li>Kepuasan Umum</li>
        </ul>
    </div>
</div>
