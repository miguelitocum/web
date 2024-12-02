<?php
// Incluir el archivo de conexión a la base de datos
include('db.php');

// Obtener los servicios desde la base de datos
$servicios_query = "SELECT * FROM servicios";
$servicios_result = mysqli_query($conn, $servicios_query);

// Si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $nombre_cliente = $_POST['nombre_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];
    $fecha_cita = $_POST['fecha_cita'];
    $servicio_id = $_POST['servicio_id'];  // Se obtiene el id del servicio seleccionado
    $estado = 'agendada'; // Estado inicial de la cita

    // Verificar si ya hay citas programadas en esa fecha y hora
    $verificar_cita_query = "SELECT * FROM citas_agendadas WHERE fecha_cita = '$fecha_cita' AND servicio_id = '$servicio_id'";
    $verificar_cita_result = mysqli_query($conn, $verificar_cita_query);

    if (mysqli_num_rows($verificar_cita_result) > 0) {
        // Si ya existe una cita en esa fecha y hora para ese servicio
        $mensaje = "¡Error! Ya hay una cita agendada para esa fecha y hora con este servicio.";
    } else {
        // Asignar automáticamente un veterinario disponible
        // Buscar un veterinario disponible para esa fecha y hora
        $asignar_veterinario_query = "SELECT id, nombre FROM veterinarios WHERE id NOT IN (SELECT veterinario_id FROM citas_agendadas WHERE fecha_cita = '$fecha_cita') LIMIT 1";
        $veterinario_result = mysqli_query($conn, $asignar_veterinario_query);
        $veterinario_data = mysqli_fetch_assoc($veterinario_result);

        if ($veterinario_data) {
            $veterinario_id = $veterinario_data['id'];
            $nombre_veterinario = $veterinario_data['nombre'];

            // Insertar la cita en la base de datos
            $insert_cita = "INSERT INTO citas_agendadas (veterinario_id, nombre_cliente, telefono_cliente, fecha_cita, servicio_id, estado) 
                            VALUES ('$veterinario_id', '$nombre_cliente', '$telefono_cliente', '$fecha_cita', '$servicio_id', '$estado')";

            if (mysqli_query($conn, $insert_cita)) {
                // Redirigir a la página de éxito, pasando los datos necesarios
                header("Location: cita_exitosa.php?nombre_cliente=$nombre_cliente&telefono_cliente=$telefono_cliente&fecha_cita=$fecha_cita&servicio_id=$servicio_id&nombre_veterinario=$nombre_veterinario");
                exit();
            } else {
                $mensaje = "Error al agendar la cita: " . mysqli_error($conn);
            }
        } else {
            $mensaje = "No hay veterinarios disponibles para esa fecha y hora.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos generales de la página */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff4e6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ff66cc;
            padding: 20px;
            text-align: center;
            color: white;
        }

        h1 {
            font-size: 2.5em;
        }

        .content {
            padding: 40px;
            margin: 20px auto;
            max-width: 600px;
            background-color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 1.2em;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            font-size: 1.1em;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #ff66cc;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #ffcc00;
        }

        .message {
            font-size: 1.2em;
            color: green;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2em;
            }
        }

        /* Botón adicional para volver a la página principal */
        .back-btn {
            background-color: #ff3399;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.1em;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            margin-top: 20px;
            display: block;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #ff66cc;
        }

        /* Estilo del mensaje de pagos en la tienda */
        .pago-info {
            margin-top: 30px;
            background-color: #ffcc00;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.2em;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Agendar Cita</h1>
    </header>

    <!-- Formulario para agendar cita -->
    <div class="content">
        <form action="agendar_cita.php" method="POST">
            <div class="form-group">
                <label for="servicio_id">Selecciona Servicio</label>
                <select name="servicio_id" id="servicio_id" required>
                    <?php while ($servicio = mysqli_fetch_assoc($servicios_result)) { ?>
                        <option value="<?php echo $servicio['id']; ?>">
                            <?php echo $servicio['nombre']; ?> - $<?php echo number_format($servicio['precio'], 2); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente" required>
            </div>

            <div class="form-group">
                <label for="telefono_cliente">Teléfono del Cliente</label>
                <input type="tel" name="telefono_cliente" id="telefono_cliente" required>
            </div>

            <div class="form-group">
                <label for="fecha_cita">Fecha y Hora de la Cita</label>
                <input type="datetime-local" name="fecha_cita" id="fecha_cita" required>
            </div>

            <button type="submit">Agendar Cita</button>
        </form>

        <!-- Mensaje de confirmación o error -->
        <?php if (isset($mensaje)) { ?>
            <div class="message">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>

        <!-- Botón para volver a la página principal -->
        <a href="index.php" class="back-btn">Volver a la Página Principal</a>
        <a href="consultar_cita.php" class="back-btn">CONSULTAR CITA</a>

        <!-- Mensaje de Pago en la tienda -->
        <div class="pago-info">
            <p><strong>Importante:</strong> Los pagos por las citas y servicios se realizan directamente en nuestra tienda, en efectivo o tarjeta de crédito.</p>
        </div>
    </div>

</body>
</html>
