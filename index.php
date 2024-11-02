<?php
include 'config.php';
include 'header.php';


$categories = mysqli_query($conn, "
    SELECT DISTINCT categories.id, categories.name 
    FROM categories 
    JOIN products ON products.category_id = categories.id 
    WHERE products.stock > 0
");

$products = mysqli_query($conn, "
    SELECT products.*, categories.name AS category_name 
    FROM products 
    JOIN categories ON products.category_id = categories.id 
    WHERE products.stock > 0
");
?>

<div class="container">
    <h1>Toko Sepatu</h1>

    <h2>Daftar Kategori</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Daftar Produk</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Gambar Produk" style="width:100px;height:auto;"></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
