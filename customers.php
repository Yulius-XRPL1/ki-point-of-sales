<?php
include 'config.php';
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);

    if ($id) {
        $update_query = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id='$id'";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
        mysqli_query($conn, $insert_query);
    }

    header('Location: customers.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM customers WHERE id='$id'");
    header('Location: customers.php');
    exit;
}

$customers = mysqli_query($conn, "SELECT * FROM customers");

$edit_customer = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE id='$id'"));
}
?>

<div class="container">
    <h1>Kelola Pelanggan</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit_customer['id'] ?? ''; ?>">
        <label>Nama Pelanggan:</label>
        <input type="text" name="customer_name" value="<?php echo $edit_customer['name'] ?? ''; ?>" required>

        <label>Email Pelanggan:</label>
        <input type="email" name="customer_email" value="<?php echo $edit_customer['email'] ?? ''; ?>" required>

        <label>Nomor Telepon Pelanggan:</label>
        <input type="text" name="customer_phone" value="<?php echo $edit_customer['phone'] ?? ''; ?>" required>

        <button type="submit"><?php echo $edit_customer ? 'Update Pelanggan' : 'Tambah Pelanggan'; ?></button>
    </form>

    <h2>Daftar Pelanggan</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($customers)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td>
                    <a href="customers.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="customers.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
