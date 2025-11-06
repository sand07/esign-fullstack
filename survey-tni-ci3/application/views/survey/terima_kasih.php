<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>

                <h2 class="text-success mb-3">Survey Berhasil Dikirim!</h2>

                <p class="lead">Terima kasih atas partisipasi Bapak/Ibu dalam mengisi survey kepuasan pasien.</p>

                <hr class="my-4">

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Hasil Survey Anda:</h5>
                            <div class="mt-3">
                                <h3 class="display-4 text-primary"><?php echo number_format($rata_rata, 2); ?>/5</h3>
                                <p class="mb-0">
                                    Kategori: <strong class="text-success"><?php echo $kategori_kepuasan; ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-muted">
                        Masukan Anda sangat berharga bagi kami untuk terus meningkatkan kualitas pelayanan kesehatan TNI.
                    </p>
                </div>

                <div class="mt-4">
                    <a href="<?php echo base_url(); ?>" class="btn btn-primary">
                        <i class="fas fa-home"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="text-center text-muted mb-3">
                    <i class="fas fa-quote-left"></i> Kepuasan Anda adalah Prioritas Kami <i class="fas fa-quote-right"></i>
                </h6>
                <p class="text-center text-muted small mb-0">
                    Jika ada pertanyaan atau keluhan lebih lanjut, silakan hubungi bagian layanan informasi kami.
                </p>
            </div>
        </div>
    </div>
</div>
