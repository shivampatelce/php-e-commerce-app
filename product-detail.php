<?php
require_once 'services/User.php';
require_once 'services/Products.php';
require_once 'services/Cart.php';

$user = new User();

// Get product ID from query
$productId = isset($_GET['id']) ? $_GET['id'] : 0;

$productsObj = new Products();
$product = $productsObj->get_product_by_id($productId);

$cart = new Cart();

// Get quantity of the product in the cart
$quantity = $cart->get_cart_quantity_by_id($productId);

// Handle Add/Remove to/from cart
if (isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $cart->add_to_cart($product, $quantity);
    header("Location: product-detail.php?id=" . $productId);
    exit();
}

if (isset($_POST['remove_from_cart'])) {
    $cart->remove_from_cart($productId);
    header("Location: product-detail.php?id=" . $productId);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                    <li class="nav-item">
                        <?php if ($user->is_authenticated()) : ?>
                            <a class="nav-link" href="logout.php">Logout</a>
                        <?php else : ?>
                            <a class="nav-link" href="login.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container py-5">

            <?php if ($product) : ?>
                <div class="card shadow-sm p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <img src="admin/<?php echo htmlspecialchars($product['image_path']); ?>" class="img-fluid rounded" height="100%" width="100%" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <h5 class="text-muted mb-3"><?php echo htmlspecialchars($product['brand']); ?></h5>
                            <p class="mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

                            <p class="fs-4 fw-bold text-primary">$<?php echo number_format($product['price'], 2); ?></p>

                            <form method="post">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo htmlspecialchars($quantity); ?>" min="1" required>
                                </div>

                                <?php if ($cart->is_product_available_in_cart($product['product_id'])) : ?>
                                    <button type="submit" name="add_to_cart" class="btn btn-success mt-4">Update Cart</button>
                                    <button type="submit" name="remove_from_cart" class="btn btn-danger mt-4">Remove From Cart</button>
                                <?php else : ?>
                                    <button type="submit" name="add_to_cart" class="btn btn-success mt-4">Add To Cart</button>
                                <?php endif; ?>

                            </form>

                            <a href="index.php" class="btn btn-secondary mt-4">Back to Catalog</a>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-danger text-center">
                    Product not found.
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-outline-secondary">Back to Catalog</a>
                </div>
            <?php endif; ?>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>