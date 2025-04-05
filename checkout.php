<?php
require_once 'services/User.php';

$user = new User();

$isAuthenticated = $user->is_authenticated();

if (!$isAuthenticated) {
    header("Location: login.php");
    exit;
}

$errors = [];
$data = [
    'address' => '',
    'city' => '',
    'province' => '',
    'postal_code' => '',
    'card_name' => '',
    'card_number' => '',
    'expiry' => '',
    'cvv' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // Validation
    if (empty($data['address'])) $errors['address'] = "Address is required.";
    if (empty($data['city'])) $errors['city'] = "City is required.";
    if (empty($data['province'])) $errors['province'] = "Province is required.";
    if (empty($data['postal_code'])) $errors['postal_code'] = "Postal code is required.";

    if (empty($data['card_name'])) $errors['card_name'] = "Cardholder name is required.";
    if (empty($data['card_number']) || !preg_match('/^\d{13,19}$/', str_replace(' ', '', $data['card_number']))) {
        $errors['card_number'] = "Valid card number is required.";
    }
    if (empty($data['expiry']) || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $data['expiry'])) {
        $errors['expiry'] = "Expiry date must be in MM/YY format.";
    }
    if (empty($data['cvv']) || !preg_match('/^\d{3,4}$/', $data['cvv'])) {
        $errors['cvv'] = "CVV must be 3 or 4 digits.";
    }

    if (empty($errors)) {
        header("Location: confirmation.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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

    <main class="container my-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h3 class="mb-4">Checkout</h3>

                <form method="POST">
                    <h5 class="mb-3">Shipping Address</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control <?php echo !empty($errors['address']) ? 'is-invalid' : ''; ?>" id="address" name="address" value="<?php echo htmlspecialchars($data['address']); ?>">
                            <?php if (!empty($errors['address'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['address']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control <?php echo !empty($errors['city']) ? 'is-invalid' : ''; ?>" id="city" name="city" value="<?php echo htmlspecialchars($data['city']); ?>">
                            <?php if (!empty($errors['city'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['city']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="province" class="form-label">Province</label>
                            <input type="text" class="form-control <?php echo !empty($errors['province']) ? 'is-invalid' : ''; ?>" id="province" name="province" value="<?php echo htmlspecialchars($data['province']); ?>">
                            <?php if (!empty($errors['province'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['province']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control <?php echo !empty($errors['postal_code']) ? 'is-invalid' : ''; ?>" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($data['postal_code']); ?>">
                            <?php if (!empty($errors['postal_code'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['postal_code']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Payment Information</h5>
                    <div class="mb-3">
                        <label for="card_name" class="form-label">Name on Card</label>
                        <input type="text" class="form-control <?php echo !empty($errors['card_name']) ? 'is-invalid' : ''; ?>" id="card_name" name="card_name" value="<?php echo htmlspecialchars($data['card_name']); ?>">
                        <?php if (!empty($errors['card_name'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['card_name']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" class="form-control <?php echo !empty($errors['card_number']) ? 'is-invalid' : ''; ?>" id="card_number" name="card_number" value="<?php echo htmlspecialchars($data['card_number']); ?>">
                        <?php if (!empty($errors['card_number'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['card_number']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Expiration Date</label>
                            <input type="text" class="form-control <?php echo !empty($errors['expiry']) ? 'is-invalid' : ''; ?>" id="expiry" name="expiry" placeholder="MM/YY" value="<?php echo htmlspecialchars($data['expiry']); ?>">
                            <?php if (!empty($errors['expiry'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['expiry']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control <?php echo !empty($errors['cvv']) ? 'is-invalid' : ''; ?>" id="cvv" name="cvv" maxlength="4" value="<?php echo htmlspecialchars($data['cvv']); ?>">
                            <?php if (!empty($errors['cvv'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['cvv']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>