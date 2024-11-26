?php
$host = 'localhost';
$db = 'shop_db';
$user = 'root';
$password = 'your_password';

try {
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Database connection failed: " . $e->getMessage());
}
?>

<?php
require_once 'db.php';

function getAllProducts($pdo) {
$stmt = $pdo->prepare("SELECT * FROM product");
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($pdo, $id) {
$stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
$stmt->execute(['id' => $id]);
return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createProduct($pdo, $name, $description, $price, $stock) {
$stmt = $pdo->prepare("INSERT INTO product (name, description, price, stock) VALUES (:name, :description, :price, :stock)");
$stmt->execute([
'name' => $name,
'description' => $description,
'price' => $price,
'stock' => $stock
]);
return $pdo->lastInsertId();
}

function updateProduct($pdo, $id, $name, $description, $price, $stock) {
$stmt = $pdo->prepare("UPDATE product SET name = :name, description = :description, price = :price, stock = :stock WHERE id = :id");
return $stmt->execute([
'id' => $id,
'name' => $name,
'description' => $description,
'price' => $price,
'stock' => $stock
]);
}

function deleteProduct($pdo, $id) {
$stmt = $pdo->prepare("DELETE FROM product WHERE id = :id");
return $stmt->execute(['id' => $id]);
}
?>

<?php
require_once 'product_model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['action'])) {
if ($_POST['action'] === 'create') {
createProduct(
$pdo,
$_POST['name'],
$_POST['description'],
$_POST['price'],
$_POST['stock']
);
} elseif ($_POST['action'] === 'update') {
updateProduct(
$pdo,
$_POST['id'],
$_POST['name'],
$_POST['description'],
$_POST['price'],
$_POST['stock']
);
} elseif ($_POST['action'] === 'delete') {
deleteProduct($pdo, $_POST['id']);
}
}
header('Location: products.php');
exit;
}
?>

<?php
require_once 'product_model.php';
$products = getAllProducts($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products Management</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
<h1>Products Management</h1>
</header>

<div class="container">
<h2>All Products</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Description</th>
<th>Price</th>
<th>Stock</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach ($products as $product): ?>
<tr>
<td><?= htmlspecialchars($product['id']) ?></td>
<td><?= htmlspecialchars($product['name']) ?></td>
<td><?= htmlspecialchars($product['description']) ?></td>
<td><?= htmlspecialchars($product['price']) ?></td>
<td><?= htmlspecialchars($product['stock']) ?></td>
<td>
<a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a>
<form action="product_controller.php" method="POST" style="display:inline;">
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= $product['id'] ?>">
<button type="submit">Delete</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>Add Product</h2>
<form action="product_controller.php" method="POST">
<input type="hidden" name="action" value="create">
<label for="name">Name:</label>
<input type="text" id="name" name="name" required>
<label for="description">Description:</label>
<textarea id="description" name="description" required></textarea>
<label for="price">Price:</label>
<input type="number" id="price" name="price" step="0.01" required>
<label for="stock">Stock:</label>
<input type="number" id="stock" name="stock" required>
<button type="submit">Add Product</button>
</form>
</div>
</body>
</html>
<?php

require_once 'product_model.php';



// Vérifiez si le formulaire a été soumis

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'create') {

        // Vérifiez si l'image a été téléchargée avec succès

        $image_url = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

            // Obtenez le nom du fichier

            $image_name = basename($_FILES['image']['name']);

            // Créez un chemin pour l'image

            $image_url = 'images/' . $image_name;

            

            // Vérifiez que le fichier est bien une image

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            $file_type = $_FILES['image']['type'];

            

            if (in_array($file_type, $allowed_types)) {

                // Déplacez l'image vers le dossier

                move_uploaded_file($_FILES['image']['tmp_name'], $image_url);

            } else {

                echo "Seules les images JPEG, PNG et GIF sont autorisées.";

                exit;

            }

        }



        // Ajoutez le produit à la base de données

        createProduct(

            $pdo,

            $_POST['name'],

            $_POST['description'],

            $_POST['price'],

            $_POST['stock'],

            $image_url

        );



        // Redirigez vers la page des produits après succès

        header('Location: products.php');

        exit;

    }

}

?>
chown -R www-data:www-data images/
<?php foreach ($products as $product): ?>

<tr>

    <td><?= htmlspecialchars($product['id']) ?></td>

    <td><?= htmlspecialchars($product['name']) ?></td>

    <td><?= htmlspecialchars($product['description']) ?></td>

    <td><?= htmlspecialchars($product['price']) ?></td>

    <td><?= htmlspecialchars($product['stock']) ?></td>

    <td>

        <?php if ($product['image_url']): ?>

            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100px; height: auto;">

        <?php else: ?>

            <span>Pas d'image</span>

        <?php endif; ?>

    </td>

    <td>

        <a href="edit_product.php?id=<?= $product['id'] ?>">Modifier</a>

        <form action="product_controller.php" method="POST" style="display:inline;">

            <input type="hidden" name="action" value="delete">

            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <button type="submit">Supprimer</button>

        </form>

    </td>

</tr>

<?php endforeach; ?>

if ($_FILES['image']['size'] > 2000000) {

echo "La taille du fichier ne doit pas dépasser 2 Mo.";

exit;

}

$image_name = uniqid() . '-' . basename($_FILES['image']['name']);