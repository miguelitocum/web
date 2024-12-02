<!-- revisar_compra.php -->
<?php
// Incluir conexión a la base de datos
include('db.php');

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el número de tarjeta
    $numero_tarjeta = $_POST['numero_tarjeta'];

    // Verificar si el CAPTCHA fue completado correctamente
    $captcha_secret = "tu_clave_secreta"; // Tu clave secreta de Google reCAPTCHA
    $captcha_response = $_POST['g-recaptcha-response'];
    $captcha_verify_url = "https://www.google.com/recaptcha/api/siteverify?secret=$captcha_secret&response=$captcha_response";

    $response = file_get_contents($captcha_verify_url);
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $error_message = "Por favor, completa el CAPTCHA correctamente.";
    } else {
        // Si el CAPTCHA es válido, continuar con la búsqueda del número de tarjeta
        $query = "SELECT * FROM pagos WHERE numero_tarjeta = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $numero_tarjeta);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $success_message = "Historial de compras encontrado.";
        } else {
            $error_message = "No se encontraron compras con ese número de tarjeta.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisar Compras - Tienda de Mascotas</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Script de reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Revisa tus Compras</h1>
        </div>
    </header>

    <main class="container">
        <form action="revisar_compra.php" method="POST" class="form-container" onsubmit="return confirmarCaptcha()">
            <label for="numero_tarjeta">Número de tarjeta de crédito:</label>
            <input type="text" name="numero_tarjeta" required maxlength="16" pattern="\d{16}" placeholder="1234 5678 9012 3456" title="Ingrese un número de tarjeta de 16 dígitos" class="input-field">

            <!-- CAPTCHA -->
            <div class="g-recaptcha" data-sitekey="tu_clave_publica"></div><br>

            <button type="submit" class="btn-submit">Ver mis compras</button>
        </form>

        <!-- Mensajes de estado -->
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
            <!-- Aquí podrías mostrar la lista de compras con más detalles -->
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Tienda de Mascotas</p>
        </div>
    </footer>
</body>
</html>
