<?php
include '../../config/database.php';
include '../../templates/header.php';

// --- TAMBAH DATA JURUSAN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Jika form tambah ditekan
    if (isset($_POST['tambah'])) {
        $nama = trim($_POST['nama']);
        if (!empty($nama)) {
            $sql = "INSERT INTO jurusan (nama) VALUES ('$nama')";
            if ($conn->query($sql)) {
                header("Location: index.php?sukses=1");
                exit;
            }
        }
    }

    // Jika form update ditekan
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nama = trim($_POST['nama']);
        if (!empty($id) && !empty($nama)) {
            $sql = "UPDATE jurusan SET nama='$nama' WHERE id='$id'";
            if ($conn->query($sql)) {
                header("Location: index.php?update_sukses=1");
                exit;
            }
        }
    }
}

// --- HAPUS DATA JURUSAN ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM jurusan WHERE id='$id'");
    header("Location: index.php?hapus_sukses=1");
    exit;
}

// --- AMBIL DATA UNTUK EDIT ---
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM jurusan WHERE id='$id'");
    if ($result && $result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
}

// --- AMBIL SEMUA DATA JURUSAN ---
$result = $conn->query("SELECT * FROM jurusan ORDER BY id ASC");
?>

<h2>Manajemen Data Jurusan</h2>

<?php if (isset($_GET['sukses'])): ?>
    <div id="notif">Data jurusan berhasil ditambahkan!</div>
<?php elseif (isset($_GET['update_sukses'])): ?>
    <div id="notif">Data jurusan berhasil diperbarui!</div>
<?php elseif (isset($_GET['hapus_sukses'])): ?>
    <div id="notif">Data jurusan berhasil dihapus!</div>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
    <input type="text" name="nama" placeholder="Masukkan Nama Jurusan" required
           value="<?= $editData['nama'] ?? '' ?>">

    <?php if ($editData): ?>
        <button type="submit" name="update">Update</button>
        <a href="index.php" class="batal-btn">Batal</a>
    <?php else: ?>
        <button type="submit" name="tambah">Tambah</button>
    <?php endif; ?>
</form>

<h3>List Jurusan</h3>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Jurusan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td>
                <a href="?edit=<?= $row['id']; ?>" style="color:green; text-decoration:none;">Edit</a> |
                <a href="?hapus=<?= $row['id']; ?>"
                   onclick="return confirm('Yakin mau hapus data ini?')"
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
