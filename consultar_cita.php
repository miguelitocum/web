<?php
// Incluir el archivo de conexión a la base de datos
include('db.php');

// Variables para almacenar la cita
$mensaje = '';
$cita = null;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir el teléfono o nombre del cliente
    $telefono_cliente = $_POST['telefono_cliente'];

    // Buscar la cita correspondiente al teléfono sin importar el estado
    $consulta_cita_query = "SELECT * FROM citas_agendadas WHERE telefono_cliente = '$telefono_cliente'";
    $consulta_cita_result = mysqli_query($conn, $consulta_cita_query);

    // Si se encuentra la cita, mostrarla
    if (mysqli_num_rows($consulta_cita_result) > 0) {
        $cita = mysqli_fetch_assoc($consulta_cita_result);

        // Obtener los detalles del servicio y veterinario
        $servicio_query = "SELECT nombre FROM servicios WHERE id = '{$cita['servicio_id']}'";
        $servicio_result = mysqli_query($conn, $servicio_query);
        $servicio_data = mysqli_fetch_assoc($servicio_result);
        $cita['servicio'] = $servicio_data['nombre'];

        $veterinario_query = "SELECT nombre FROM veterinarios WHERE id = '{$cita['veterinario_id']}'";
        $veterinario_result = mysqli_query($conn, $veterinario_query);
        $veterinario_data = mysqli_fetch_assoc($veterinario_result);
        $cita['veterinario'] = $veterinario_data['nombre'];
    } else {
        $mensaje = 'No se encontraron citas para este número de teléfono.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Cita - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos para la página de consulta */
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
            max-width: 700px;
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

        input {
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
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .message {
            font-size: 1.2em;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        /* Detalles de la cita */
        .details {
            font-size: 1.2em;
            margin-top: 20px;
        }

        .details p {
            margin: 10px 0;
        }

        .details strong {
            color: #ff66cc;
        }

        .back-btn {
            background-color: #ff66cc;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.2em;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            display: block;
            width: 200px;
            margin: 20px auto;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #ffcc00;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Consultar Cita</h1>
    </header>

    <!-- Formulario para consultar la cita -->
    <div class="content">
        <form method="POST" action="consultar_cita.php">
            <div class="form-group">
                <label for="telefono_cliente">Número de Teléfono</label>
                <input type="tel" name="telefono_cliente" id="telefono_cliente" required>
            </div>

            <button type="submit">Consultar Cita</button>
        </form>

        <!-- Mensaje si no se encuentra la cita -->
        <?php if (!empty($mensaje)) { ?>
            <div class="message"><?php echo $mensaje; ?></div>
        <?php } ?>

        <!-- Si se encuentra la cita, mostrar detalles -->
        <?php if ($cita) { ?>
            <div class="details">
                <h2>Detalles de la Cita</h2>
                <p><strong>Cliente:</strong> <?php echo $cita['nombre_cliente']; ?></p>
                <p><strong>Teléfono:</strong> <?php echo $cita['telefono_cliente']; ?></p>
                <p><strong>Fecha de la Cita:</strong> <?php echo date('d-m-Y', strtotime($cita['fecha_cita'])); ?></p>
                <p><strong>Hora de la Cita:</strong> <?php echo date('H:i', strtotime($cita['fecha_cita'])); ?></p>
                <p><strong>Servicio:</strong> <?php echo $cita['servicio']; ?></p>
                <p><strong>Veterinario:</strong>  <?php echo $cita['veterinario']; ?></p>
            </div>
        <?php } ?>

        <a href="index.php" class="back-btn">Volver a la Página Principal</a>
    </div>

</body>
</html>