<?php
session_start();

// Verifica si hay productos en el carrito
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "No hay productos en el carrito.";
    exit;
}

// Establece los estados de México (puedes ajustarlos según tu necesidad)
$estadosMexico = [
    'Ciudad de México', 'Jalisco', 'Nuevo León', 'Puebla', 'Guanajuato', 'Zacatecas', 'San Luis Potosí',
    'Sinaloa', 'Chihuahua', 'Coahuila', 'Yucatán', 'Querétaro', 'Baja California', 'Sonora', 'Tamaulipas',
    'Durango', 'Oaxaca', 'Hidalgo', 'Morelos', 'Chiapas', 'Veracruz', 'Tabasco', 'Tlaxcala', 'Colima', 'Michoacán',
    'Nayarit', 'Baja California Sur', 'Campeche', 'Guerrero'
];

// Calcular el total del carrito
$totalCarrito = array_sum(array_map(function($item) {
    return $item['price'] * $item['quantity'];
}, $_SESSION['cart']));

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pago</title>
    <script>
        // Función para calcular el costo de envío y el tiempo de entrega
        function calcularEnvio() {
            var estado = document.getElementById("estado").value;
            var costoEnvio = 0;
            var tiempoEnvio = "3-5 días hábiles";

            // Si el estado no es la capital, agregar costo extra de envío
            if (estado !== "Ciudad de México") {
                costoEnvio = 100; // Ejemplo de costo adicional de envío fuera de la CDMX
                tiempoEnvio = "5-7 días hábiles"; // Ajustar el tiempo de entrega fuera de la capital
            }

            // Si el total del carrito supera los 1000, el envío es gratis
            var totalCompra = <?php echo $totalCarrito; ?>;
            if (totalCompra > 1000) {
                costoEnvio = 0;
                tiempoEnvio = "3-5 días hábiles"; // El envío es gratuito y rápido
            }

            // Actualizar el costo de envío y el tiempo estimado de entrega
            document.getElementById("costo_envio").innerText = "$" + costoEnvio;
            document.getElementById("tiempo_envio").innerText = tiempoEnvio;

            // Actualizar el total final
            var totalFinal = totalCompra + costoEnvio;
            document.getElementById("total_final").innerText = "$" + totalFinal;
        }

        // Confirmar si el usuario quiere realizar la compra
        function confirmarCompra() {
            var totalCompra = <?php echo $totalCarrito; ?>;
            var costoEnvio = document.getElementById("costo_envio").innerText.replace('$', '');
            var totalFinal = totalCompra + parseFloat(costoEnvio);

            return confirm("¿Estás seguro de que deseas realizar la compra por un total de " + totalFinal + " pesos?");
        }
    </script>
</head>
<body>

    <h1>Detalles de Pago</h1>

    <form action="procesar_pago.php" method="POST" onsubmit="return confirmarCompra()">
        <label for="tipo_tarjeta">Tipo de tarjeta:</label>
        <select name="tipo_tarjeta" required>
            <option value="Credito">Tarjeta de Crédito</option>
            <option value="Debito">Tarjeta de Débito</option>
        </select><br>

        <label for="numero_tarjeta">Número de tarjeta:</label>
        <input type="text" name="numero_tarjeta" required maxlength="16" pattern="\d{16}" placeholder="1234567812345678" title="Ingrese un número de tarjeta de 16 dígitos"><br>

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
        <select id="estado" name="estado" required onchange="calcularEnvio()">
            <?php foreach ($estadosMexico as $estado): ?>
                <option value="<?php echo $estado; ?>"><?php echo $estado; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="pais">País:</label>
        <input type="text" name="pais" value="México" readonly><br>

        <label for="codigo_postal">Código Postal:</label>
        <input type="text" name="codigo_postal" required><br>

        <h3>Tiempo de Entrega</h3>
        <p>Envío estimado: <span id="tiempo_envio">3-5 días hábiles</span></p>
        <p>Costo de Envío: <span id="costo_envio">$0</span></p>
        <p>Total a Pagar: <span id="total_final">$<?php echo $totalCarrito; ?></span></p>

        <button type="submit">Confirmar Pago</button>
    </form>

</body>
</html>
