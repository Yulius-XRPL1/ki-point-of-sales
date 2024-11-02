<?php
include 'config.php';
include 'header.php';

$categories = mysqli_query($conn, "SELECT * FROM categories");
$products = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_order'])) {
    $customer_id = $_POST['customer_id'];
    $total_payment = 0;
    $total_product = 0;

    $query = "INSERT INTO orders (admin_id, customer_id, total_payment, total_product) VALUES (1, '$customer_id', '$total_payment', '$total_product')";
    mysqli_query($conn, $query);
    $order_id = mysqli_insert_id($conn);

    $products_exist = false;

    foreach ($_POST['products'] as $product_id => $quantity) {
        if (!empty($quantity) && $quantity > 0) {
            $products_exist = true;
            $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$product_id'"));
            if ($product['stock'] >= $quantity) {
                $total_price = $product['price'] * $quantity;
                $total_payment += $total_price;
                $total_product += $quantity;

                $new_stock = $product['stock'] - $quantity;
                mysqli_query($conn, "UPDATE products SET stock='$new_stock' WHERE id='$product_id'");

                mysqli_query($conn, "INSERT INTO order_products (order_id, product_id, quantity, total_price) VALUES ('$order_id', '$product_id', '$quantity', '$total_price')");
            } else {
                echo "Stok produk tidak cukup untuk: " . htmlspecialchars($product['name']);
            }
        }
    }

    if ($products_exist) {
        mysqli_query($conn, "UPDATE orders SET total_payment='$total_payment', total_product='$total_product' WHERE id='$order_id'");
        header('Location: transactions.php');
        exit;
    } else {
        echo "Tidak ada produk yang dipilih untuk transaksi.";
    }
}
?>

<div class="container">
    <h1>Transaksi</h1>
    <form method="POST" class="transaction-form">
        <label>Customer:</label>
        <select name="customer_id" required>
            <?php
            $customers = mysqli_query($conn, "SELECT * FROM customers");
            while ($customer = mysqli_fetch_assoc($customers)) {
                echo "<option value='{$customer['id']}'>{$customer['name']}</option>";
            }
            ?>
        </select>

        <label>Produk:</label>
        <?php while ($product = mysqli_fetch_assoc($products)): ?>
            <div>
                <input type='number' name='products[<?php echo $product['id']; ?>]' placeholder='Jumlah untuk <?php echo htmlspecialchars($product['name']); ?>' min='0' max='<?php echo $product['stock']; ?>' value='0'>
                <small>Masukkan jumlah untuk <?php echo htmlspecialchars($product['name']); ?>. Kosongkan jika tidak ingin membeli.</small>
            </div>
        <?php endwhile; ?>
        <button type="submit" name="submit_order">Submit Order</button>
    </form>
</div>

</body>
</html>
            