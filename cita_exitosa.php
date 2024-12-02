<?php
// Incluir el archivo de conexión a la base de datos
include('db.php');

// Recibir los datos de la cita
$nombre_cliente = $_GET['nombre_cliente'];
$telefono_cliente = $_GET['telefono_cliente'];
$fecha_cita = $_GET['fecha_cita'];
$servicio_id = $_GET['servicio_id'];
$nombre_veterinario = $_GET['nombre_veterinario'];

// Obtener el nombre del servicio
$servicio_query = "SELECT nombre FROM servicios WHERE id = '$servicio_id'";
$servicio_result = mysqli_query($conn, $servicio_query);
$servicio_data = mysqli_fetch_assoc($servicio_result);
$nombre_servicio = $servicio_data['nombre'];

// Obtener la hora de la cita
$hora = date('H', strtotime($fecha_cita)); // Obtenemos solo la hora

// Determinamos si es AM o PM
if ($hora < 12) {
    $periodo = "AM (mañana)";
} else {
    $periodo = "PM (tarde)";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Agendada Exitosamente - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos generales */
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

        h2 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        .details {
            font-size: 1.2em;
            margin-bottom: 20px;
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

        /* Responsividad */
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }

            .content {
                padding: 20px;
            }

            .back-btn {
                width: 180px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>CITA AGENDADA EXITOSAMENTE</h1>
    </header>

    <!-- Detalles de la cita -->
    <div class="content">
        <h2>Detalles de la Cita</h2>

        <div class="details">
            <p><strong>Cliente:</strong> <?php echo $nombre_cliente; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $telefono_cliente; ?></p>

            <!-- Mostrar la Fecha y Hora con AM/PM -->
            <p><strong>Fecha de la Cita:</strong> <?php echo date('d-m-Y', strtotime($fecha_cita)); ?></p>
            <p><strong>Hora de la Cita:</strong> <?php echo date('H:i', strtotime($fecha_cita)) . " " . $periodo; ?></p>

            <p><strong>Servicio:</strong> <?php echo $nombre_servicio; ?></p>
            <p><strong>Veterinario:</strong> <?php echo $nombre_veterinario; ?></p>
        </div>

        <a href="index.php" class="back-btn">Volver a la Página Principal</a>
    </div>

</body>
</html>
