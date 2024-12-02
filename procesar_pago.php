<?php
// Incluir la conexión a la base de datos
include('db.php');
session_start();

// Verificar que el carrito de compras y los datos de pago estén disponibles
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    // Obtener los datos del formulario
    $tipo_tarjeta = $_POST['tipo_tarjeta'];
    $numero_tarjeta = $_POST['numero_tarjeta'];
    $nombre_titular = $_POST['nombre_titular'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $cvv = $_POST['cvv'];

    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigo_postal = $_POST['codigo_postal'];
    $pais = $_POST['pais'];
    $telefono = $_POST['telefono'];

    // Obtener el total de la compra desde la sesión
    $totalCarrito = array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $_SESSION['cart']));

    // Opción 1: Asignar un usuario_id predeterminado
    $usuario_id = 1;  // Si no tienes sistema de login, asigna un ID de usuario predeterminado.

    // Opción 2: Obtener el usuario_id desde la sesión (si tienes un sistema de login)
    // if (isset($_SESSION['user_id'])) {
    //     $usuario_id = $_SESSION['user_id'];  // Obtener el usuario_id de la sesión
    // } else {
    //     echo "Error: No estás logueado.";
    //     exit();
    // }

    // Generar un nuevo ID para el pedido (asegurarse de que es autoincremental en la base de datos)
    $query = "INSERT INTO pedidos (usuario_id, total, fecha, estado) VALUES ('$usuario_id', '$totalCarrito', NOW(), 'Pendiente')";
    if ($conn->query($query) === TRUE) {
        // Obtener el ID del pedido recién insertado
        $pedido_id = $conn->insert_id; // Esto obtiene el ID autoincrementado del último pedido insertado

        // Insertar en la tabla pagos
        $query_pago = "INSERT INTO pagos (pedido_id, metodo_pago, numero_tarjeta, fecha_pago, nombre_titular, cvv) 
                       VALUES ('$pedido_id', '$tipo_tarjeta', '$numero_tarjeta', NOW(), '$nombre_titular', '$cvv')";
        $conn->query($query_pago);

        // Insertar la dirección de envío
        $query_direccion = "INSERT INTO direccion_envio (pedido_id, direccion, ciudad, estado, codigo_postal, telefono) 
                            VALUES ('$pedido_id', '$direccion', '$ciudad', '$estado', '$codigo_postal', '$telefono')";
        $conn->query($query_direccion);

        // Insertar los detalles del pedido
        foreach ($_SESSION['cart'] as $item) {
            $producto_id = $item['id'];
            $cantidad = $item['quantity'];
            $precio = $item['price'];

            $query_detalle = "INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) 
                              VALUES ('$pedido_id', '$producto_id', '$cantidad', '$precio')";
            $conn->query($query_detalle);
        }

        // Vaciar el carrito después de procesar el pago
        unset($_SESSION['cart']);

        // Redirigir al usuario a la página de pago exitoso
        header("Location: pago_exitoso.php?pedido_id=$pedido_id");
        exit();
    } else {
        echo "Error al procesar el pedido: " . $conn->error;
    }
} else {
    echo "No se ha encontrado el carrito o los datos del pago.";
}
?>
