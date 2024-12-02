<?php
// Incluir la conexión a la base de datos
include('db.php');
session_start();

// Verificar si se ha pasado un ID de pedido por la URL
if (isset($_GET['pedido_id'])) {
    $pedido_id = $_GET['pedido_id'];

    // Obtener los detalles del pedido desde la base de datos
    $query = "SELECT * FROM pedidos WHERE id = '$pedido_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $pedido = $result->fetch_assoc();
        $totalCarrito = $pedido['total'];
    } else {
        echo "No se encontró el pedido.";
        exit();
    }
} else {
    echo "No se encontró el ID del pedido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>¡Compra Exitosa!</h1>
    <p>Gracias por tu compra, Cliente.</p>
    <p>El total de tu compra es: $<?php echo number_format($totalCarrito, 2); ?></p>
    <p>Te hemos enviado un correo con los detalles de tu pedido (simulado).</p>
    <p>El pedido se procesará y llegará en aproximadamente 3-5 días hábiles.</p>
    
    <h2>Detalles de la compra:</h2>
    <p><strong>Nombre del cliente:</strong> Cliente</p>
    <p><strong>Total de la compra:</strong> $<?php echo number_format($totalCarrito, 2); ?></p>
    <p><strong>Estado del pago:</strong> Aprobado</p>
    <p><strong>Fecha estimada de entrega:</strong> 3-5 días hábiles.</p>
    
    <a href="productos.php">Regresar a la tienda</a>
</body>
</html>
