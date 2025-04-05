<?php
require_once 'DatabaseConnection.php';

class Products
{
    function add_products($product_name, $brand, $description, $available_quantity, $price, $image_path)
    {

        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $product_name = $db->prepare_string($dbc, $product_name);
        $brand = $db->prepare_string($dbc, $brand);
        $description = $db->prepare_string($dbc, $description);
        $available_quantity = $db->prepare_string($dbc, $available_quantity);
        $price = $db->prepare_string($dbc, $price);

        $product_id = uniqid('', true);

        $query = "INSERT INTO products (product_id, name, brand, description, available_quantity, price, image_path)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "ssssiis", $product_id, $product_name, $brand, $description, $available_quantity, $price, $image_path);

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

    function get_products()
    {
        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $query = "SELECT * FROM products";

        $result = mysqli_query($dbc, $query);

        $products = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }

        return $products;
    }

    function get_product_by_id($product_id)
    {
        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $product_id = $db->prepare_string($dbc, $product_id);

        $query = "SELECT * FROM products WHERE product_id = ?";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $product_id);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    mysqli_stmt_close($stmt);
                    return $row;
                } else {
                    mysqli_stmt_close($stmt);
                    return null;
                }
            } else {
                mysqli_stmt_close($stmt);
                return false;
            }
        } else {
            return false;
        }
    }

    function delete_product_by_id($product_id)
    {
        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $product_id = $db->prepare_string($dbc, $product_id);

        $query = "DELETE FROM products WHERE product_id = ?";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $product_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                return false;
            }
        } else {
            return false;
        }
    }

    function update_product($product_id, $product_name, $brand, $description, $available_quantity, $price, $image_path)
    {
        $db = new DatabaseConnection();
        $dbc = $db->get_dbc();

        $product_id = $db->prepare_string($dbc, $product_id);
        $product_name = $db->prepare_string($dbc, $product_name);
        $brand = $db->prepare_string($dbc, $brand);
        $description = $db->prepare_string($dbc, $description);
        $available_quantity = $db->prepare_string($dbc, $available_quantity);
        $price = $db->prepare_string($dbc, $price);

        $query = "UPDATE products 
                  SET name = ?, brand = ?, description = ?, available_quantity = ?, price = ?, image_path = ? 
                  WHERE product_id = ?";

        if ($stmt = mysqli_prepare($dbc, $query)) {
            mysqli_stmt_bind_param($stmt, "sssidss", $product_name, $brand, $description, $available_quantity, $price, $image_path, $product_id);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                mysqli_stmt_close($stmt);
                return false;
            }
        } else {
            return false;
        }
    }
}
