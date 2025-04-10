<?php
require_once './services/DatabaseConnection.php';
require_once 'services/User.php';

$data = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    if (empty($data['first_name'])) {
        $errors['first_name'] = "First name is required.";
    }

    if (empty($data['last_name'])) {
        $errors['last_name'] = "Last name is required.";
    }

    if (empty($data['email'])) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($data['password'])) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($data['password']) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if (empty($data['confirm_password'])) {
        $errors['confirm_password'] = "Please confirm your password.";
    } elseif ($data['password'] !== $data['confirm_password']) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $user = new User();
        $isRegistered = $user->register_user($data['first_name'], $data['last_name'], $data['email'], $data['password']);

        if ($isRegistered) {
            header("Location: login.php");
            exit;
        } else {
            $errors['general'] = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center mb-4">Register</h3>

                        <?php if (!empty($errors['general'])) : ?>
                            <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                        <?php endif; ?>

                        <form method="POST" id="registerForm" novalidate>
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control <?php echo !empty($errors['first_name']) ? 'is-invalid' : ''; ?>" name="first_name" id="first_name" value="<?php echo htmlspecialchars($data['first_name']); ?>">
                                <div class="invalid-feedback"><?php echo $errors['first_name'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control <?php echo !empty($errors['last_name']) ? 'is-invalid' : ''; ?>" name="last_name" id="last_name" value="<?php echo htmlspecialchars($data['last_name']); ?>">
                                <div class="invalid-feedback"><?php echo $errors['last_name'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                                <div class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control <?php echo !empty($errors['password']) ? 'is-invalid' : ''; ?>" name="password" id="password" value="<?php echo htmlspecialchars($data['password']); ?>">
                                <div class="invalid-feedback"><?php echo $errors['password'] ?? ''; ?></div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control <?php echo !empty($errors['confirm_password']) ? 'is-invalid' : ''; ?>" name="confirm_password" id="confirm_password" value="<?php echo htmlspecialchars($data['confirm_password']); ?>">
                                <div class="invalid-feedback"><?php echo $errors['confirm_password'] ?? ''; ?></div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>

                            <div class="text-center">
                                <p class="mb-0">Already have an account? <a href="login.php">Login here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('email').addEventListener('blur', function() {
            let email = this.value.trim();
            let emailExists = false;

            if (email === '') return;

            let xhr = new XMLHttpRequest();

            xhr.open('POST', 'check_email.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            let data = JSON.stringify({
                email: email
            });

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    // Check if the email exists
                    if (response.exists) {
                        // If exists, show error message
                        document.getElementById('email').classList.add('is-invalid');
                        document.getElementById('email').nextElementSibling.textContent = 'Email already exists.';
                        emailExists = true;
                    } else {
                        // If does not exist, remove error message
                        document.getElementById('email').classList.remove('is-invalid');
                        document.getElementById('email').nextElementSibling.textContent = '';
                        emailExists = false;
                    }

                    // Disable or enable submit button based on email existence
                    document.getElementById('registerForm').querySelector('button[type="submit"]').disabled = emailExists;
                }
            };

            // Send the request with the email data
            xhr.send(data);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>