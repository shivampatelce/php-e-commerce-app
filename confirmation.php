<?php
require_once './services/User.php';
require_once './services/Cart.php';

$user = new User();
$cart = new Cart();

$cart->empty_cart();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>confirmation</title>
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

    <main class="container mt-5">
        <div class="text-center">
            <h2>Order Confirmation</h2>
            <p>Your order has been successfully placed. Thank you for shopping with us!</p>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-1">&copy; <?php echo date('Y'); ?> NextGadgets. All rights reserved.</p>
            <ul class="list-inline">
                <li class="list-inline-item">Created By:</li>
                <li class="list-inline-item">Shivam Patel</li>
                <li class="list-inline-item">Krish Lavani</li>
                <li class="list-inline-item">Diksha Samotra</li>
                <li class="list-inline-item">Jiten Shreshtha</li>
            </ul>
        </div>
    </footer>
</body>