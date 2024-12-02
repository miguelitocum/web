<?php
session_start();

// Comprobar si los datos del producto han sido enviados
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['quantity'])) {
    // Obtener los datos del producto
    $productId = $_POST['id'];
    $productName = $_POST['name'];
    $productPrice = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Verificar si ya hay un carrito de compras en la sesión
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Si no existe, crear el carrito
    }

    // Verificar si el producto ya está en el carrito
    $productFound = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $productId) {
            // Si ya está, incrementar la cantidad
            $_SESSION['cart'][$key]['quantity'] += $quantity; // Incrementar la cantidad seleccionada
            $productFound = true;
            break;
        }
    }

    // Si el producto no está en el carrito, agregarlo
    if (!$productFound) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'name' => $productName,
            'price' => $productPrice,
            'quantity' => $quantity
        ];
    }

    echo "Producto agregado al carrito";
} else {
    echo "Error: Falta información del producto";
}
?>
