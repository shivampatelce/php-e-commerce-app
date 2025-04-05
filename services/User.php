<?php
session_start();

class User
{
    function register_user($first_name, $last_name, $email, $password, $user_type_id = 2)
    {

        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $first_name = $db->prepare_string($dbc, $first_name);
        $last_name = $db->prepare_string($dbc, $last_name);
        $email = $db->prepare_string($dbc, $email);
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $id = uniqid('', true);

        $query = "INSERT INTO user (id, first_name, last_name, email, password, user_type_id)
              VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "sssssi", $id, $first_name, $last_name, $email, $password_hashed, $user_type_id);

            if (mysqli_stmt_execute($stmt)) {
                return true;
            } else {
                return false;
            }

            mysqli_stmt_close($stmt);
        } else {
            return false;
        }
    }

    function login($email, $password)
    {
        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $email = $db->prepare_string($dbc, $email);

        $query = "SELECT id, first_name, last_name, email, password, user_type_id FROM user WHERE email = ?";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                if ($user['user_type_id'] == 1 && $password == $user['password']) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'user_type_id' => $user['user_type_id']
                    ];
                    return true;
                }

                if (password_verify($password, $user['password'])) {
                    // Password matches, store user info in session
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'user_type_id' => $user['user_type_id']
                    ];
                    return true;
                }
            }
        }

        return false;
    }

    function is_admin_user()
    {
        return $_SESSION['user']['user_type_id'] == 1;
    }

    function is_authenticated()
    {
        return isset($_SESSION['user']);
    }

    function logout()
    {
        session_destroy();
        return true;
    }
}
