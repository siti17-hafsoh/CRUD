<?php
include '../../config/database.php';
include '../../templates/header.php';

// Ambil data jurusan untuk dropdown
$jurusan = $conn->query("SELECT * FROM jurusan");

// --- Tambah Data Mahasiswa ---
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jurusan_id = $_POST['jurusan_id'];

    $conn->query("INSERT INTO mahasiswa (nama, umur, jurusan_id) VALUES ('$nama', '$umur', '$jurusan_id')");
    header("Location: index.php?sukses=1");
    exit;
}

// --- Update Data Mahasiswa ---
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $jurusan_id = $_POST['jurusan_id'];

    $conn->query("UPDATE mahasiswa SET nama='$nama', umur='$umur', jurusan_id='$jurusan_id' WHERE id='$id'");
    header("Location: index.php?update_sukses=1");
    exit;
}

// --- Hapus Data Mahasiswa ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM mahasiswa WHERE id='$id'");
    header("Location: index.php?hapus_sukses=1");
    exit;
}

// --- Ambil Data untuk Edit ---
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = $conn->query("SELECT * FROM mahasiswa WHERE id='$id'")->fetch_assoc();
}

// --- Ambil Semua Data Mahasiswa + Nama Jurusan ---
$result = $conn->query("
    SELECT m.id, m.nama, m.umur, j.nama AS jurusan
    FROM mahasiswa m
    LEFT JOIN jurusan j ON m.jurusan_id = j.id
    ORDER BY m.id ASC
");
?>

<h2>Form Biodata Mahasiswa</h2>

<?php if (isset($_GET['sukses'])): ?>
    <div id="notif">Data berhasil disimpan!</div>
<?php elseif (isset($_GET['update_sukses'])): ?>
    <div id="notif">Data berhasil diperbarui!</div>
<?php elseif (isset($_GET['hapus_sukses'])): ?>
    <div id="notif">Data berhasil dihapus!</div>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

    <input type="text" name="nama" placeholder="Masukkan nama" required
           value="<?= $editData['nama'] ?? '' ?>">

    <input type="number" name="umur" placeholder="Masukkan umur" required
           value="<?= $editData['umur'] ?? '' ?>">

    <select name="jurusan_id" required>
        <option value="">-- Pilih Jurusan --</option>
        <?php
        $jurusan->data_seek(0);
        while ($j = $jurusan->fetch_assoc()):
            $selected = isset($editData['jurusan_id']) && $editData['jurusan_id'] == $j['id'] ? 'selected' : '';
            echo "<option value='{$j['id']}' $selected>{$j['nama']}</option>";
        endwhile;
        ?>
    </select>

    <?php if ($editData): ?>
        <button type="submit" name="update">Update</button>
        <a href="index.php" class="batal-btn">Batal</a>
    <?php else: ?>
        <button type="submit" name="tambah">Simpan</button>
    <?php endif; ?>
</form>

<h3>List Mahasiswa</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Umur</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= $row['umur'] ?></td>
            <td><?= $row['jurusan'] ?: '-' ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>" style="color:green; text-decoration:none;">Edit</a> |
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus data ini?')"
                   style="color:red; text-decoration:none;">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    const notif = document.getElementById('notif');
    if (notif) {
        setTimeout(() => {
            notif.style.opacity = '0';
            notif.style.transition = 'opacity 0.5s ease';
            setTimeout(() => notif.remove(), 500);
            const url = new URL(window.location);
            url.searchParams.delete('sukses');
            url.searchParams.delete('update_sukses');
            url.searchParams.delete('hapus_sukses');
            window.history.replaceState({}, document.title, url);
        }, 3000);
    }
</script>

<style>
.batal-btn {
    display: inline-block;
    background: #ccc;
    color: #333;
    padding: 10px;
    border-radius: 8px;
    text-decoration: none;
}
.batal-btn:hover {
    background: #aaa;
}
</style>

<?php include '../../templates/footer.php'; ?>
