<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa — Praktikum 7 MVC</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; }
        nav { background: #2c3e50; padding: 15px 30px; }
        nav a { color: white; text-decoration: none; margin-right: 20px; font-size: 14px; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 20px; }
        h1 { color: #2c3e50; margin-bottom: 25px; }
        
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: bold; font-size: 14px; color: #2c3e50; margin-bottom: 5px; }
        .form-group small { display: block; color: #999; font-size: 12px; margin-top: 3px; }
        .form-group input, .form-group select {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 6px; font-size: 15px;
        }
        .form-group input.error { border-color: #e74c3c; }
        .pesan-error { color: #e74c3c; font-size: 13px; margin-top: 4px; display: block; }
        
        .tombol-simpan { background: #27ae60; color: white; border: none; padding: 12px 30px; border-radius: 6px; font-size: 15px; cursor: pointer; }
        .tombol-simpan:hover { background: #1e8449; }
        .tombol-batal { color: #666; text-decoration: none; margin-left: 15px; font-size: 14px; }
    </style>
</head>
<body>

<nav>
    <a href="index.php">🏠 Beranda</a>
    <a href="?halaman=tambah">➕ Tambah Mahasiswa</a>
</nav>

<div class="container">
    <h1>➕ Tambah Mahasiswa Baru</h1>

    <div class="card">
        <!-- 
            Form ini mengirim data ke controller yang sama (action kosong = halaman ini sendiri)
            Controller yang memproses POST dan validasi
        -->
        <form method="POST" action="?halaman=tambah">

            <!-- FIELD: NIM -->
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text"
                       id="nim"
                       name="nim"
                       maxlength="8"
                       value="<?= htmlspecialchars($nim ?? '') ?>"
                       class="<?= isset($errors['nim']) ? 'error' : '' ?>"
                       placeholder="Contoh: 10231001">
                <small>Harus 8 digit angka. Unik per mahasiswa.</small>
                <?php if (isset($errors['nim'])): ?>
                    <span class="pesan-error">⚠ <?= htmlspecialchars($errors['nim']) ?></span>
                <?php endif; ?>
            </div>

            <!-- FIELD: Nama -->
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text"
                       id="nama"
                       name="nama"
                       value="<?= htmlspecialchars($nama ?? '') ?>"
                       class="<?= isset($errors['nama']) ? 'error' : '' ?>"
                       placeholder="Contoh: Agus Ikhsyan">
                <?php if (isset($errors['nama'])): ?>
                    <span class="pesan-error">⚠ <?= htmlspecialchars($errors['nama']) ?></span>
                <?php endif; ?>
            </div>

            <!-- FIELD: Jurusan -->
            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select id="jurusan" name="jurusan"
                        class="<?= isset($errors['jurusan']) ? 'error' : '' ?>">
                    <option value="">-- Pilih Jurusan --</option>
                    <?php
                        // Daftar jurusan — bisa dipindah ke config/database jika makin besar
                        $jurusan_list = ["Sistem Informasi", "Teknik Informatika", "Ilmu Komputer"];
                        foreach ($jurusan_list as $j):
                    ?>
                        <option value="<?= $j ?>" <?= ($jurusan ?? '') === $j ? 'selected' : '' ?>>
                            <?= $j ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['jurusan'])): ?>
                    <span class="pesan-error">⚠ <?= htmlspecialchars($errors['jurusan']) ?></span>
                <?php endif; ?>
            </div>

            <!-- FIELD: IPK -->
            <div class="form-group">
                <label for="ipk">IPK</label>
                <input type="number"
                       id="ipk"
                       name="ipk"
                       step="0.01"
                       min="0"
                       max="4"
                       value="<?= htmlspecialchars($ipk ?? '') ?>"
                       class="<?= isset($errors['ipk']) ? 'error' : '' ?>"
                       placeholder="Contoh: 3.75">
                <small>Rentang 0.00 – 4.00</small>
                <?php if (isset($errors['ipk'])): ?>
                    <span class="pesan-error">⚠ <?= htmlspecialchars($errors['ipk']) ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="tombol-simpan">💾 Simpan ke Database</button>
            <a href="index.php" class="tombol-batal">Batal</a>

        </form>
    </div>
</div>

</body>
</html>
