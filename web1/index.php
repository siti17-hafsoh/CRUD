<?php
include 'config/database.php';
include 'templates/header.php';

// Ambil data mahasiswa dan jurusan
$result = $conn->query("
    SELECT m.id, m.nama, m.umur, j.nama AS jurusan
    FROM mahasiswa m
    LEFT JOIN jurusan j ON m.jurusan_id = j.id
    ORDER BY m.id DESC
");
?>

<h2>📋 Daftar Mahasiswa</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Umur</th>
            <th>Jurusan</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= $row['umur']; ?></td>
                <td><?= $row['jurusan'] ?: '-'; ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Belum ada data mahasiswa.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<footer>
    <p>© 2025 | Sistem Data Mahasiswa by Hapsoh 💜</p>
</footer>

<?php include 'templates/footer.php'; ?>
