<?php
require_once '../services/Products.php';
require_once '../services/User.php';

$user = new User();

$data = [
    'name' => '',
    'brand' => '',
    'description' => '',
    'available_quantity' => '',
    'price' => '',
];

$errors = [];
$success = '';
$is_edit_mode = false;


if (isset($_GET['edit_product_id'])) {
    $product_id = $_GET['edit_product_id'];

    $products = new Products();
    $product = $products->get_product_by_id($product_id);
    $is_edit_mode = true;

    if ($product) {
        $data = [
            'name' => $product['name'],
            'brand' => $product['brand'],
            'description' => $product['description'],
            'available_quantity' => $product['available_quantity'],
            'price' => $product['price'],
            'image_path' => $product['image_path']
        ];
    } else {
        echo "Product not found";
    }
} else {
    $is_edit_mode = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // Validation
    if (empty($data['name'])) {
        $errors['name'] = "Product name is required.";
    }

    if (empty($data['brand'])) {
        $errors['brand'] = "Brand is required.";
    }

    if (empty($data['description'])) {
        $errors['description'] = "Description is required.";
    }

    if (!is_numeric($data['available_quantity']) || (int)$data['available_quantity'] < 0) {
        $errors['available_quantity'] = "Quantity must be a non-negative number.";
    }

    if (!is_numeric($data['price']) || (float)$data['price'] < 0) {
        $errors['price'] = "Price must be a positive number.";
    }

    // Image validation
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageType = $_FILES['image']['type'];
        $imageSize = $_FILES['image']['size'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($imageType, $allowedTypes)) {
            $errors['image'] = "Invalid image type. Only JPG, PNG, and GIF are allowed.";
        } elseif ($imageSize > $maxFileSize) {
            $errors['image'] = "Image file size exceeds the 5 MB limit.";
        } else {
            $imageDir = 'uploads/';
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0777, true);
            }
            $imagePath = $imageDir . time() . '_' . $imageName;

            if (!move_uploaded_file($imageTmp, $imagePath)) {
                $errors['image'] = "Failed to upload image. Try again.";
            }
        }
    } elseif (!$is_edit_mode) {
        // Only required for new products
        $errors['image'] = "Product image is required.";
    }

    // If no errors, insert into DB
    if (empty($errors)) {
        $products = new Products();

        echo $is_edit_mode;

        if (!$is_edit_mode) {
            $isProductAdded = $products->add_products(
                $data['name'],
                $data['brand'],
                $data['description'],
                (int)$data['available_quantity'],
                (float)$data['price'],
                $imagePath
            );

            if ($isProductAdded) {
                $data = array_fill_keys(array_keys($data), '');
                header("Location: admin.php");
                exit;
            } else {
                $errors['general'] = "Failed to add product. Try again.";
            }
        } else {
            $isProductUpdated = $products->update_product(
                $_GET['edit_product_id'],
                $data['name'],
                $data['brand'],
                $data['description'],
                (int)$data['available_quantity'],
                (float)$data['price'],
                $imagePath
            );

            if ($isProductUpdated) {
                header("Location: admin.php");
                exit;
            } else {
                $errors['general'] = "Failed to update product. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">NextGadgets</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add-products.php">Add Product</a>
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

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white p-4 rounded shadow-sm">
                <h2 class="text-center mb-4"><?php echo $is_edit_mode ? 'Edit' : 'Add'; ?> Product</h2>

                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if (!empty($errors['general'])) : ?>
                    <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($data['name']); ?>">
                        <?php if (!empty($errors['name'])) : ?>
                            <div class="text-danger"><?php echo $errors['name']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" name="brand" id="brand" value="<?php echo htmlspecialchars($data['brand']); ?>">
                        <?php if (!empty($errors['brand'])) : ?>
                            <div class="text-danger"><?php echo $errors['brand']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"><?php echo htmlspecialchars($data['description']); ?></textarea>
                        <?php if (!empty($errors['description'])) : ?>
                            <div class="text-danger"><?php echo $errors['description']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="available_quantity" class="form-label">Available Quantity</label>
                        <input type="number" class="form-control" name="available_quantity" id="available_quantity" min="0" value="<?php echo htmlspecialchars($data['available_quantity']); ?>">
                        <?php if (!empty($errors['available_quantity'])) : ?>
                            <div class="text-danger"><?php echo $errors['available_quantity']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="text" class="form-control" name="price" id="price" value="<?php echo htmlspecialchars($data['price']); ?>">
                        <?php if (!empty($errors['price'])) : ?>
                            <div class="text-danger"><?php echo $errors['price']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>

                        <?php if ($is_edit_mode && !empty($data['image_path'])) : ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($data['image_path']); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 120px;">
                            </div>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        <?php else : ?>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                        <?php endif; ?>

                        <?php if (!empty($errors['image'])) : ?>
                            <div class="text-danger"><?php echo $errors['image']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo $is_edit_mode ? 'Update' : 'Add'; ?> Product</button>
                        <a href="admin.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>