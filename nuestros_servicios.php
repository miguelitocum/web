<?php
// Incluir la conexión a la base de datos
include('db.php');

// Obtener los servicios desde la base de datos
$servicios_query = "SELECT * FROM servicios";
$servicios_result = mysqli_query($conn, $servicios_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios</title>
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
            max-width: 800px;
            background-color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .service-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .service-item {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }

        .service-item:hover {
            transform: scale(1.05);
        }

        .service-item h3 {
            font-size: 1.5em;
            color: #ff66cc;
        }

        .service-item p {
            font-size: 1.1em;
            color: #666;
        }

        .service-item a {
            background-color: #ff66cc;
            color: white;
            padding: 10px 20px;
            font-size: 1.1em;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .service-item a:hover {
            background-color: #ff3399;
        }

        .back-btn {
            background-color: #4CAF50;
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
            background-color: #ff3399; /* Color rosa más intenso para el hover */
        }

        /* Responsividad */
        @media (max-width: 768px) {
            header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Nuestros Servicios</h1>
    </header>

    <!-- Contenido -->
    <div class="content">
        <div class="service-list">
            <?php while ($servicio = mysqli_fetch_assoc($servicios_result)) { ?>
                <div class="service-item">
                    <h3><?php echo $servicio['nombre']; ?></h3>
                    <p>Precio: $<?php echo number_format($servicio['precio'], 2); ?></p>
                    <p><?php echo $servicio['descripcion']; ?></p>
                    <a href="agendar_cita.php?servicio_id=<?php echo $servicio['id']; ?>">Agendar Cita</a>
                </div>
            <?php } ?>
        </div>

        <!-- Botón para volver a la página principal -->
        <a href="index.php" class="back-btn">Volver a la Página Principal</a>
    </div>

</body>
</html>
