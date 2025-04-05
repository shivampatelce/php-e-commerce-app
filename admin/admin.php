<?php
require_once '../services/Products.php';
require_once '../services/User.php';

$user = new User();
$products = new Products();
$productsList = $products->get_products();

if (isset($_GET['delete_product_id'])) {
    $product_id = $_GET['delete_product_id'];

    $is_deleted = $products->delete_product_by_id($product_id);

    if ($is_deleted) {
        header("Location: admin.php");
    } else {
        echo "Error deleting watch.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add-products.php">Add Product</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($user->is_authenticated()) : ?>
                            <a class="nav-link" href="../logout.php">Logout</a>
                        <?php else : ?>
                            <a class="nav-link" href="../login.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h2 class="text-center mb-4">Product List</h2>

                <?php if (count($productsList) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Price ($)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productsList as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['brand']); ?></td>
                                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                                        <td><?php echo (int)$product['available_quantity']; ?></td>
                                        <td><?php echo number_format((float)$product['price'], 2); ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href='add-products.php?edit_product_id=<?php echo urlencode($product['product_id']); ?>' class="btn btn-success btn-sm">
                                                    Edit
                                                </a>
                                                <a href='admin.php?delete_product_id=<?php echo urlencode($product['product_id']); ?>' class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center mt-4">
                        No products found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>