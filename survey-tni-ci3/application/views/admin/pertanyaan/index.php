<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="fas fa-question-circle"></i> Manajemen Pertanyaan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('admin/pertanyaan/create'); ?>" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Tambah Pertanyaan
            </a>
            <a href="<?php echo base_url('admin/pertanyaan/import'); ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-file-excel"></i> Import Excel
            </a>
            <a href="<?php echo base_url('admin/pertanyaan/export'); ?>" class="btn btn-sm btn-info">
                <i class="fas fa-download"></i> Export Excel
            </a>
            <a href="<?php echo base_url('admin/pertanyaan/template'); ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-file-download"></i> Template Excel
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="form-row mb-3">
            <div class="col-md-4">
                <label>Filter Kategori:</label>
                <select id="filter-kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    <?php foreach($kategori_list as $k): ?>
                        <option value="<?php echo $k->id; ?>"><?php echo $k->nama_kategori; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>Filter Status:</label>
                <select id="filter-status" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Pertanyaan</th>
                        <th width="150">Kategori</th>
                        <th width="120">Tipe Jawaban</th>
                        <th width="80">Urutan</th>
                        <th width="80">Wajib</th>
                        <th width="80">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($pertanyaan as $p): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $p->pertanyaan; ?></td>
                        <td><?php echo $p->nama_kategori ? $p->nama_kategori : '-'; ?></td>
                        <td>
                            <?php
                            $tipe_labels = [
                                'skala' => '<span class="badge badge-primary">Skala</span>',
                                'pilihan_ganda' => '<span class="badge badge-info">Pilihan Ganda</span>',
                                'text' => '<span class="badge badge-secondary">Text</span>',
                                'ya_tidak' => '<span class="badge badge-warning">Ya/Tidak</span>'
                            ];
                            echo $tipe_labels[$p->tipe_jawaban];
                            ?>
                        </td>
                        <td><?php echo $p->urutan; ?></td>
                        <td>
                            <?php if($p->is_wajib): ?>
                                <span class="badge badge-danger">Ya</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Tidak</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($p->is_active): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo base_url('admin/pertanyaan/edit/'.$p->id); ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo base_url('admin/pertanyaan/delete/'.$p->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pertanyaan ini?');" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                            <a href="<?php echo base_url('admin/pertanyaan/toggle_status/'.$p->id); ?>" class="btn btn-sm btn-info" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('.table').DataTable({
        "order": [[ 4, "asc" ]],
        "pageLength": 25
    });

    $('#filter-kategori, #filter-status').on('change', function() {
        var kategori = $('#filter-kategori').val();
        var status = $('#filter-status').val();

        table.columns(2).search(kategori).draw();
        if(status !== '') {
            var statusText = status == '1' ? 'Aktif' : 'Nonaktif';
            table.columns(6).search(statusText).draw();
        } else {
            table.columns(6).search('').draw();
        }
    });
});
</script>
