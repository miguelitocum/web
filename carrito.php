<?php
session_start(); // Iniciar sesión

// Verificar si hay productos en el carrito
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
} else {
    $cartItems = [];
}

// Calcular el total del carrito
$totalCarrito = 0;
foreach ($cartItems as $item) {
    $totalCarrito += $item['quantity'] * $item['price'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <h1>Carrito de Compras</h1>

    <?php if (!empty($cartItems)): ?>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <h3><?php echo $item['name']; ?></h3>
                    <p>Cantidad: <?php echo $item['quantity']; ?></p>
                    <p>Precio: $<?php echo $item['price']; ?></p>
                    <p>Total: $<?php echo $item['quantity'] * $item['price']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Total: $<?php echo $totalCarrito; ?></strong></p>

        <!-- Botón para proceder con el pago -->
        <form action="detalles_pago.php" method="POST">
            <input type="hidden" name="total" value="<?php echo $totalCarrito; ?>">
            <button type="submit">Realizar Compra</button>
        </form>

    <?php else: ?>
        <p>No hay productos en tu carrito.</p>
    <?php endif; ?>

</body>
</html>
