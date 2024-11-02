<?php
include 'config.php';
include 'header.php';

if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $id = intval($_POST['product_id']);
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $old_image = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image_url FROM products WHERE id='$id'"))['image_url'];
            if (file_exists($old_image)) {
                unlink($old_image);
            }

            $image_url = 'uploads/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
                $update_query = "UPDATE products SET name='$name', category_id='$category_id', price='$price', stock='$stock', image_url='$image_url' WHERE id='$id'";
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            $update_query = "UPDATE products SET name='$name', category_id='$category_id', price='$price', stock='$stock' WHERE id='$id'";
        }

        if (mysqli_query($conn, $update_query)) {
            header('Location: products.php');
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_url = 'uploads/' . basename($_FILES['image']['name']);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_url)) {
                $query = "INSERT INTO products (name, category_id, price, stock, image_url) VALUES ('$name', '$category_id', '$price', '$stock', '$image_url')";
                if (mysqli_query($conn, $query)) {
                    header('Location: products.php');
                    exit;
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading file.";
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $old_image = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image_url FROM products WHERE id='$id'"))['image_url'];
    if (file_exists($old_image)) {
        unlink($old_image);
    }

    if (mysqli_query($conn, "DELETE FROM products WHERE id='$id'")) {
        header('Location: products.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$categories = mysqli_query($conn, "SELECT * FROM categories");
$products = mysqli_query($conn, "SELECT products.*, categories.name AS category_name FROM products JOIN categories ON products.category_id = categories.id");

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$id'"));
}
?>

<div class="container">
    <h1>Kelola Produk</h1>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo isset($product) ? $product['id'] : ''; ?>">

        <label>Nama Produk:</label>
        <input type="text" name="product_name" value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>" required>
        
        <label>Kategori:</label>
        <select name="category_id" required>
            <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                <option value="<?php echo $row['id']; ?>" <?php echo isset($product) && $product['category_id'] == $row['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <label>Harga:</label>
        <input type="number" name="price" value="<?php echo isset($product) ? htmlspecialchars($product['price']) : ''; ?>" required>
        
        <label>Stok:</label>
        <input type="number" name="stock" value="<?php echo isset($product) ? htmlspecialchars($product['stock']) : ''; ?>" required>
        
        <label>Gambar:</label>
        <input type="file" name="image" accept="image/*">
        <?php if (isset($product)): ?>
            <p>Gambar saat ini:</p>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Gambar Produk" style="width:100px;height:auto;">
        <?php endif; ?>
        
        <button type="submit" name="add_product"><?php echo isset($product) ? 'Update Produk' : 'Tambah Produk'; ?></button>
    </form>

    <h2>Daftar Produk</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($products)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Gambar Produk" style="width:100px;height:auto;"></td>
                <td>
                    <a href="products.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
