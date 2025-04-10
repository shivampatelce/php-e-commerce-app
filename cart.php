<?php
require_once 'services/Cart.php';
require_once 'services/User.php';

$user = new User();
$cartObj = new Cart();

// Remove item from cart if the remove button is clicked
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $cartObj->remove_from_cart($productId);
    header("Location: cart.php");
    exit;
}

// Get the current cart
$cart = $cartObj->get_cart();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">NextGadgets</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="cart.php">Cart</a>
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
    <div class="container py-5">
        <h2 class="text-center mb-4">Cart</h2>

        <?php if (empty($cart)) : ?>
            <div class="alert alert-warning text-center">
                No products in the cart.<br /> <a href="index.php" class="btn btn-primary mt-2">Back to Catalog</a>
            </div>
        <?php else : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0;
                    foreach ($cart as $item) :
                        $itemTotal = $item['price'] * $item['quantity'];
                        $totalAmount += $itemTotal;
                    ?>
                        <tr>
                            <td><img src="admin/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="img-fluid" style="max-width: 100px;"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['brand']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($itemTotal, 2); ?></td>
                            <td>
                                <a href="cart.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-danger mt-4">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <h4>Total: $<?php echo number_format($totalAmount, 2); ?></h4>
                <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>