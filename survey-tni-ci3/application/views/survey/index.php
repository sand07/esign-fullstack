<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center">
                <h3><i class="fas fa-hospital"></i> Survey Kepuasan Pasien</h3>
                <h5>TNI Angkatan Darat</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-clipboard-check fa-5x text-success mb-3"></i>
                    <h4>Selamat Datang di Survey Kepuasan Pasien</h4>
                    <p class="lead">Kami mengundang Bapak/Ibu untuk mengisi survey kepuasan terhadap pelayanan kesehatan yang telah diberikan.</p>
                </div>

                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Informasi Penting:</h5>
                    <ul class="mb-0">
                        <li>Survey ini akan memakan waktu sekitar <strong>5-10 menit</strong></li>
                        <li>Semua jawaban Anda akan dijaga kerahasiaannya</li>
                        <li>Masukan Anda sangat berharga untuk meningkatkan kualitas pelayanan</li>
                        <li>Mohon jawab semua pertanyaan dengan jujur sesuai pengalaman Anda</li>
                    </ul>
                </div>

                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h6 class="text-primary"><i class="fas fa-question-circle"></i> Yang akan Anda nilai:</h6>
                        <?php if(!empty($kategori_list)): ?>
                        <div class="row">
                            <?php foreach($kategori_list as $k): ?>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="fas fa-check text-success"></i> <?php echo $k->nama_kategori; ?>
                                    <small class="text-muted">(<?php echo $k->jumlah_pertanyaan; ?> pertanyaan)</small>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <p class="mb-0 mt-2">
                            <strong>Total: <?php echo $total_pertanyaan; ?> pertanyaan</strong>
                        </p>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo base_url('survey/form'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-play-circle"></i> Mulai Survey
                    </a>
                </div>

                <hr class="my-4">

                <div class="text-center text-muted">
                    <p class="small mb-0">Terima kasih atas partisipasi Anda dalam meningkatkan kualitas pelayanan kesehatan TNI</p>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="text-primary"><i class="fas fa-shield-alt"></i> Privasi & Keamanan Data</h6>
                <p class="small text-muted mb-0">
                    Semua data yang Anda berikan akan digunakan hanya untuk keperluan evaluasi dan peningkatan kualitas pelayanan.
                    Data pribadi Anda akan dijaga kerahasiaannya dan tidak akan disebarluaskan kepada pihak ketiga.
                </p>
            </div>
        </div>
    </div>
</div>
