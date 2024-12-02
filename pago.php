<?php
session_start();

// Verificar si el carrito tiene productos
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "No hay productos en tu carrito.";
    exit;
}

// Definir la ciudad y estado para el cálculo de costos de envío
$ciudadCapital = "San Luis Potosí"; // Ciudad Capital
$estadoSLP = "San Luis Potosí"; // Estado

// Función para calcular el costo de envío
function calcularCostoEnvio($estado, $ciudad) {
    global $estadoSLP, $ciudadCapital;
    $costoEnvio = 0;
    
    // Si no está en la ciudad capital o estado de SLP, agregar costo de envío
    if ($estado !== $estadoSLP || $ciudad !== $ciudadCapital) {
        $costoEnvio = 100; // Puedes ajustar este valor
    }

    return $costoEnvio;
}

// Calcular el total del carrito
$totalCarrito = array_sum(array_map(function($item) {
    return $item['quantity'] * $item['price'];
}, $_SESSION['cart']));

// Si se proporciona información de la dirección (como ciudad y estado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoTarjeta = $_POST['tipo_tarjeta'];
    $numeroTarjeta = $_POST['numero_tarjeta'];
    $nombreTitular = $_POST['nombre_titular'];
    $fechaVencimiento = $_POST['fecha_vencimiento'];
    $cvv = $_POST['cvv'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $pais = $_POST['pais'];
    $codigoPostal = $_POST['codigo_postal'];
    
    // Calcular costo de envío
    $costoEnvio = calcularCostoEnvio($estado, $ciudad);
    $totalFinal = $totalCarrito + $costoEnvio; // Total con envío

    // Aquí podrías guardar la información de pago y dirección, y procesar el pedido
    // Ejemplo: insertar en la base de datos
    // Asegúrate de realizar la lógica de procesamiento de pago aquí.
    
    // Redirigir a una página de confirmación de compra
    header("Location: confirmacion.php?total=" . $totalFinal);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <h1>Detalles de Pago</h1>

    <!-- Muestra los productos y el total -->
    <h2>Resumen del Carrito</h2>
    <ul>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <li>
                <h3><?php echo $item['name']; ?></h3>
                <p>Cantidad: <?php echo $item['quantity']; ?></p>
                <p>Precio: $<?php echo $item['price']; ?></p>
                <p>Total: $<?php echo $item['quantity'] * $item['price']; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><strong>Total Carrito: $<?php echo $totalCarrito; ?></strong></p>
    
    <!-- Si el costo de envío es mayor a 0, se muestra -->
    <?php if ($costoEnvio > 0): ?>
        <p><strong>Costo de Envío: $<?php echo $costoEnvio; ?></strong></p>
    <?php endif; ?>
    
    <p><strong>Total a Pagar: $<?php echo $totalCarrito + $costoEnvio; ?></strong></p>

    <!-- Formulario de Pago -->
    <form action="pago.php" method="POST">
        <h2>Información de la Tarjeta</h2>
        <label for="tipo_tarjeta">Tipo de tarjeta:</label>
        <select name="tipo_tarjeta" required>
            <option value="Visa">Visa</option>
            <option value="MasterCard">MasterCard</option>
            <option value="American Express">American Express</option>
        </select><br>

        <label for="numero_tarjeta">Número de tarjeta:</label>
        <input type="text" name="numero_tarjeta" required pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456"><br>

        <label for="nombre_titular">Nombre del titular:</label>
        <input type="text" name="nombre_titular" required><br>

        <label for="fecha_vencimiento">Fecha de vencimiento:</label>
        <input type="month" name="fecha_vencimiento" required><br>

        <label for="cvv">Código de seguridad (CVV):</label>
        <input type="text" name="cvv" required pattern="\d{3}" maxlength="3" placeholder="123"><br>

        <h2>Dirección de Envío</h2>
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" required><br>

        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" required><br>

        <label for="estado">Estado:</label>
        <input type="text" name="estado" required><br>

        <label for="pais">País:</label>
        <input type="text" name="pais" required><br>

        <label for="codigo_postal">Código Postal:</label>
        <input type="text" name="codigo_postal" required><br>

        <button type="submit">Confirmar Pago</button>
    </form>

</body>
</html>
