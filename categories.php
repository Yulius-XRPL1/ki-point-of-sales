<?php
include 'config.php';
include 'header.php';

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $category = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories WHERE id='$id'"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_category'])) {
    $id = $_POST['id'];
    $name = $_POST['category_name'];
    mysqli_query($conn, "UPDATE categories SET name='$name' WHERE id='$id'");
    header('Location: categories.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $name = $_POST['category_name'];
    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id='$id'");
    header('Location: categories.php');
    exit;
}

$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<div class="container">
    <h1>Kelola Kategori</h1>

    <?php if (isset($category)): ?>
        <h2>Edit Kategori</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <label>Nama Kategori:</label>
            <input type="text" name="category_name" value="<?php echo $category['name']; ?>" required>
            <button type="submit" name="update_category">Update Kategori</button>
        </form>
    <?php else: ?>
        <h2>Tambah Kategori</h2>
        <form method="POST">
            <label>Nama Kategori:</label>
            <input type="text" name="category_name" required>
            <button type="submit" name="add_category">Tambah Kategori</button>
        </form>
    <?php endif; ?>

    <h2>Daftar Kategori</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>
                    <a href="categories.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="categories.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
