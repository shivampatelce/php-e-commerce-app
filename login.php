<?php
require_once './services/DatabaseConnection.php';
require_once './services/User.php';

$errors = [];
$data = ['email' => '', 'password' => ''];

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['email'] = trim($_POST['email'] ?? '');
    $data['password'] = trim($_POST['password'] ?? '');

    // Basic validation
    if (empty($data['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    if (empty($data['password'])) {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {
        $isAuthenticated = $user->login($data['email'], $data['password']);

        if ($isAuthenticated) {
            $isAdmin = $user->is_admin_user();
            if ($isAdmin) {
                header("Location: admin/admin.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            $errors['general'] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
                        <a class="nav-link active" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 bg-white p-4 rounded shadow-sm">
                <h2 class="text-center mb-4">Login</h2>

                <?php if (!empty($errors['general'])) : ?>
                    <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                <?php endif; ?>

                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                        <?php if (!empty($errors['email'])) : ?>
                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" value="<?php echo htmlspecialchars($data['password']); ?>">
                        <?php if (!empty($errors['password'])) : ?>
                            <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="user-registration.php">Register here</a>.</p>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>