<?php

class Cart
{
    // Add product to the cart
    function add_to_cart($product, $quantity)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        if ($product) {
            $found = false;

            foreach ($cart as &$item) {
                if ($item['id'] == $product['product_id']) {
                    $item['quantity'] = $quantity;
                    $found = true;
                    break;
                }
            }

            // If the product wasn't found, add it to the cart
            if (!$found) {
                $cart[] = [
                    'id' => $product['product_id'],
                    'name' => $product['name'],
                    'brand' => $product['brand'],
                    'price' => $product['price'],
                    'image_path' => $product['image_path'],
                    'quantity' => $quantity
                ];
            }

            // Update the cart in the session
            $_SESSION['cart'] = $cart;
        }
    }

    function get_cart()
    {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    function get_cart_quantity_by_id($productId)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        foreach ($cart as $item) {
            if ($item['id'] == $productId) {
                return $item['quantity'];
            }
        }

        return 1;
    }

    function remove_from_cart($productId)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        foreach ($cart as $index => $item) {
            if ($item['id'] == $productId) {
                unset($cart[$index]);
                $_SESSION['cart'] = array_values($cart);
                var_dump($_SESSION['cart']);
                return true;
            }
        }
        return false;
    }

    function is_product_available_in_cart($productId)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

        foreach ($cart as $item) {
            if ($item['id'] == $productId) {
                return true;
            }
        }

        return false;
    }

    function empty_cart()
    {
        $_SESSION['cart'] = [];
    }
}
