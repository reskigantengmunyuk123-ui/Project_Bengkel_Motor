<?php
// Jalankan sekali untuk membuat atau mereset akun mechanic, lalu HAPUS file ini.
require_once __DIR__ . '/app/Core/Database.php';
try {
    $pdo = App\Core\Database::connect();

    // Siapkan tabel aplikasi saat setup pertama kali dijalankan.
    $schemaFile = __DIR__ . '/database/motortech_db.sql';
    $schema = file_get_contents($schemaFile);
    if ($schema === false) throw new RuntimeException('File database/motortech_db.sql tidak dapat dibaca.');
    $schema = preg_replace('/^CREATE DATABASE[^;]*;\s*USE\s+[^;]*;/mi', '', $schema, 1);
    foreach (explode(';', $schema) as $statement) {
        $statement = trim($statement);
        if ($statement === '' || preg_match('/^INSERT\s+INTO\s+services\b/i', $statement)) continue;
        $pdo->exec($statement);
    }

    // Seed layanan hanya saat tabelnya masih kosong agar setup aman diulang.
    if ((int)$pdo->query('SELECT COUNT(*) FROM services')->fetchColumn() === 0) {
        $seed = $pdo->prepare('INSERT INTO services(name, description, price) VALUES(?,?,?)');
        foreach ([
            ['Servis Rutin', 'Pemeriksaan berkala dan penyetelan umum', 50000],
            ['Ganti Oli', 'Penggantian oli mesin', 30000],
            ['Tune Up', 'Pemeriksaan performa mesin', 75000],
            ['Ganti Ban', 'Penggantian ban luar atau dalam', 100000],
            ['Servis Rem', 'Pemeriksaan kampas dan sistem rem', 35000],
            ['Perbaikan Mesin', 'Pemeriksaan dan perbaikan kendala mesin', 0],
        ] as $service) $seed->execute($service);
    }

    $name = 'Mechanic MOTORTECH';
    $username = 'mechanic';
    $hash = password_hash('mechanic123', PASSWORD_DEFAULT);
    $find = $pdo->prepare("SELECT id FROM mechanics WHERE username=? LIMIT 1");
    $find->execute([$username]);
    $row = $find->fetch();
    if ($row) {
        $q = $pdo->prepare("UPDATE mechanics SET name=?, password=? WHERE id=?");
        $q->execute([$name, $hash, $row['id']]);
        echo 'Password akun mechanic berhasil direset.';
    } else {
        $q = $pdo->prepare("INSERT INTO mechanics(name,username,password) VALUES(?,?,?)");
        $q->execute([$name, $username, $hash]);
        echo 'Akun mechanic berhasil dibuat.';
    }
    echo '<br>Username: <b>mechanic</b><br>Password: <b>mechanic123</b>';
    echo '<br><b>Hapus file setup.php sekarang demi keamanan.</b>';
} catch (Throwable $e) {
    http_response_code(500);
    error_log('MOTORTECH setup error: ' . $e->getMessage());
    echo 'Setup gagal mengakses database. Pastikan MySQL FlyEnv aktif dan kredensial di config/config.php sudah sesuai.';
}
