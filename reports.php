<?php
include 'config.php';
include 'header.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM orders WHERE id='$id'");
    header('Location: reports.php');
    exit;
}

$reports = mysqli_query($conn, "SELECT orders.*, admins.email AS admin_email, customers.name AS customer_name FROM orders JOIN admins ON orders.admin_id = admins.id JOIN customers ON orders.customer_id = customers.id");

$detail_report = null;
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $detail_report = mysqli_fetch_assoc(mysqli_query($conn, "SELECT orders.*, admins.email AS admin_email, customers.name AS customer_name FROM orders JOIN admins ON orders.admin_id = admins.id JOIN customers ON orders.customer_id = customers.id WHERE orders.id='$order_id'"));
    $order_products = mysqli_query($conn, "SELECT order_products.*, products.name AS product_name FROM order_products JOIN products ON order_products.product_id = products.id WHERE order_products.order_id='$order_id'");
}

?>

<div class="container">
    <h1>Laporan Transaksi</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Admin</th>
            <th>Customer</th>
            <th>Tanggal</th>
            <th>Total Pembayaran</th>
            <th>Total Produk</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($reports)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['admin_email']); ?></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['total_payment']); ?></td>
                <td><?php echo htmlspecialchars($row['total_product']); ?></td>
                <td>
                    <a href="reports.php?id=<?php echo $row['id']; ?>">Detail</a>
                    <a href="reports.php?delete=<?php echo $row['id']; ?>" style="color: red; margin-left: 10px;" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($detail_report): ?>
        <h2>Detail Laporan Transaksi ID: <?php echo htmlspecialchars($detail_report['id']); ?></h2>
        <p><strong>Admin:</strong> <?php echo htmlspecialchars($detail_report['admin_email']); ?></p>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($detail_report['customer_name']); ?></p>
        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($detail_report['created_at']); ?></p>
        <p><strong>Total Pembayaran:</strong> <?php echo htmlspecialchars($detail_report['total_payment']); ?></p>
        <p><strong>Total Produk:</strong> <?php echo htmlspecialchars($detail_report['total_product']); ?></p>

        <h3>Detail Produk yang Dipesan</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order_product = mysqli_fetch_assoc($order_products)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order_product['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($order_product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($order_product['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order_product['total_price']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
