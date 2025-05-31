<?php
setlocale(LC_ALL, 'id_ID');
include 'config.php';

// Hitung selisih hari hingga pengumuman
$today = new DateTime();
$pengumuman = new DateTime($tanggal_pengumuman);
$interval = $today->diff($pengumuman);
$days_remaining = $interval->days;

// Tentukan apakah sudah saatnya pengumuman
$is_announcement_day = ($today >= $pengumuman);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Kelulusan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hasil-kelulusan {
            display: none;
        }

        .lulus {
            color: #28a745;
        }

        .tidak-lulus {
            color: #dc3545;
        }

        #downloadSurat {
            transition: all 0.3s;
        }

        #downloadSurat:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Tambahkan style untuk counter */
        .counter-container {
            background: linear-gradient(135deg, #1e5799 0%, #207cca 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .counter-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .counter-time {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .time-box {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 10px 15px;
            min-width: 80px;
        }

        .time-value {
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
        }

        .time-label {
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .announcement-info {
            font-size: 1.2rem;
            margin-top: 15px;
            font-weight: bold;
        }

        .form-disabled {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }

        .form-disabled::after {
            content: "Form akan dibuka pada tanggal <?= tgl_indo(date('Y-m-d',strtotime($tanggal_pengumuman))) ?>";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            color: #dc3454;
            font-weight: bold;
            text-align: center;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        body {
            padding-bottom: 60px;
            /* Sesuaikan dengan tinggi footer */
        }

        @media (max-width: 768px) {
            .footer {
                position: static;
            }

            body {
                padding-bottom: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Counter Section -->
                <div class="counter-container">
                    <div class="counter-title">PENGUMUMAN KELULUSAN SISWA KELAS IX <br /> SMP NEGERI 6 SUDIMORO</div>
                    <?php if (!$is_announcement_day): ?>
                        <div class="counter-time">
                            <div class="time-box">
                                <div class="time-value" id="countdown-days"><?= $days_remaining ?></div>
                                <div class="time-label">HARI</div>
                            </div>
                            <div class="time-box">
                                <div class="time-value" id="countdown-hours">00</div>
                                <div class="time-label">JAM</div>
                            </div>
                            <div class="time-box">
                                <div class="time-value" id="countdown-minutes">00</div>
                                <div class="time-label">MENIT</div>
                            </div>
                            <div class="time-box">
                                <div class="time-value" id="countdown-seconds">00</div>
                                <div class="time-label">DETIK</div>
                            </div>
                        </div>
                        <div class="announcement-info">
                            Pengumuman dibuka pada: <b style="color:yellow"><?= tgl_indo(date('Y-m-d',strtotime($tanggal_pengumuman))).' Jam '.jam_indo(date('H',strtotime($tanggal_pengumuman))).' :'.jam_indo(date('i',strtotime($tanggal_pengumuman))) ?></b> WIB
                        </div>
                    <?php else: ?>
                        <div class="time-value" style="font-size: 2.5rem; margin: 20px 0;">
                            PENGUMUMAN TELAH DIBUKA!
                        </div>
                        <div class="announcement-info">
                            Selamat melihat hasil kelulusan!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card <?= !$is_announcement_day ? 'form-disabled' : '' ?>">
                    <!-- Form kelulusan tetap sama -->
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0 text-center">CEK KELULUSAN SISWA</h4>
                    </div>
                    <div class="card-body">
                        <form id="formCekKelulusan" method="POST">
                            <div class="mb-3">
                                <label for="nis" class="form-label">Masukkan NIS (10 Digit)</label>
                                <input type="text" class="form-control" id="nis" name="nis"
                                    placeholder="Contoh: 1234567890" maxlength="10" required>
                                <div id="nisHelp" class="form-text">Nomor Induk Siswa terdiri dari 10 digit angka.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Cek Kelulusan</button>
                        </form>

                        <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>

                        <div id="hasilKelulusan" class="hasil-kelulusan mt-4">
                            <h5 class="text-center">HASIL KELULUSAN</h5>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">NIS</div>
                                <div class="col-8" id="hasilNis">-</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Nama</div>
                                <div class="col-8" id="hasilNama">-</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Kelas</div>
                                <div class="col-8" id="hasilKelas">-</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 fw-bold">Status</div>
                                <div class="col-8 fw-bold" id="hasilStatus">-</div>
                            </div>
                            <div class="row">
                                <div class="col-4 fw-bold">Tanggal Pengumuman</div>
                                <div class="col-8" id="hasilTanggal">-</div>
                            </div>
                            <!-- Di dalam div hasilKelulusan, tambahkan setelah row terakhir -->
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <a href="surat_keterangan.php?nis=' + data.nis + '" class="btn btn-success" id="downloadSurat">
                                        <i class="bi bi-download"></i> Download Surat Keterangan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer mt-5 py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-muted mb-0">&copy; <?= date('Y') ?> IT <?= htmlspecialchars($nama_sekolah) ?> - All rights reserved</p>
                    <p class="text-muted small">Sistem Informasi Kelulusan Siswa</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Countdown timer
        <?php if (!$is_announcement_day): ?>
            // Set the date we're counting down to
            const countDownDate = new Date("<?= $tanggal_pengumuman ?>").getTime();

            // Update the count down every 1 second
            const countdownFunction = setInterval(function() {
                // Get today's date and time
                const now = new Date().getTime();

                // Find the distance between now and the count down date
                const distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result
                document.getElementById("countdown-days").innerHTML = days;
                document.getElementById("countdown-hours").innerHTML = hours.toString().padStart(2, '0');
                document.getElementById("countdown-minutes").innerHTML = minutes.toString().padStart(2, '0');
                document.getElementById("countdown-seconds").innerHTML = seconds.toString().padStart(2, '0');

                // If the count down is finished
                if (distance < 0) {
                    clearInterval(countdownFunction);
                    document.getElementById("countdown-days").innerHTML = "0";
                    document.getElementById("countdown-hours").innerHTML = "00";
                    document.getElementById("countdown-minutes").innerHTML = "00";
                    document.getElementById("countdown-seconds").innerHTML = "00";
                    setTimeout(() => {
                        location.reload(); // Reload page when it's time
                    }, 1000);
                }
            }, 1000);
        <?php endif; ?>
        document.getElementById('formCekKelulusan').addEventListener('submit', function(e) {
            e.preventDefault();

            const nis = document.getElementById('nis').value.trim();
            const errorMessage = document.getElementById('errorMessage');

            // Validasi panjang NIS
            if (nis.length !== 10) {
                errorMessage.textContent = 'NIS harus terdiri dari tepat 10 digit angka.';
                errorMessage.classList.remove('d-none');
                document.getElementById('hasilKelulusan').style.display = 'none';
                return;
            }

            // Validasi hanya angka
            if (!/^\d+$/.test(nis)) {
                errorMessage.textContent = 'NIS hanya boleh berisi angka.';
                errorMessage.classList.remove('d-none');
                document.getElementById('hasilKelulusan').style.display = 'none';
                return;
            }

            errorMessage.classList.add('d-none');

            // Kirim data ke server
            fetch('cek_kelulusan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'nis=' + encodeURIComponent(nis)
                })
                .then(response => response.json())
                .then(data => {
                    const hasilDiv = document.getElementById('hasilKelulusan');
                    if (data.error) {
                        errorMessage.textContent = data.error;
                        errorMessage.classList.remove('d-none');
                        hasilDiv.style.display = 'none';
                    } else {
                        document.getElementById('hasilNis').textContent = data.nis;
                        document.getElementById('hasilNama').textContent = data.nama;
                        document.getElementById('hasilKelas').textContent = data.kelas;

                        const statusElement = document.getElementById('hasilStatus');
                        statusElement.textContent = data.status_kelulusan;
                        statusElement.className = 'col-8 fw-bold ' +
                            (data.status_kelulusan === 'Lulus' ? 'lulus' : 'tidak-lulus');

                        document.getElementById('hasilTanggal').textContent = data.tanggal_pengumuman;
                        hasilDiv.style.display = 'block';
                        document.getElementById('downloadSurat').href = 'surat_keterangan.php?nis=' + data.nis;
                    }
                    // Di dalam .then(data => { ... } setelah menampilkan hasil

                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMessage.textContent = 'Terjadi kesalahan saat memproses data.';
                    errorMessage.classList.remove('d-none');
                    document.getElementById('hasilKelulusan').style.display = 'none';
                });
        });
    </script>
</body>

</html>