<?php
require_once 'services/Products.php';
require_once 'services/User.php';

$user = new User();

$productsObj = new Products();

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchTerm !== '') {
    $products = $productsObj->get_products($searchTerm);
} else {
    $products = $productsObj->get_products();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Product List</title>
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
                        <a class="nav-link active" href="index.php">Home</a>
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
            <h1 class="text-center mb-5">Product Catalog</h1>

            <form method="GET" class="mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($products)) : ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($products as $product) : ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <img src="admin/<?php echo htmlspecialchars($product['image_path']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="object-fit: cover; height: 200px;">

                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($product['brand']); ?></h6>
                                    <p class="mb-2 text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                                    <a href="product-detail.php?id=<?php echo urlencode($product['product_id']); ?>" class="btn btn-outline-primary mt-auto">More Details</a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-warning text-center" role="alert">
                    No products available.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>