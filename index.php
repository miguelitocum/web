<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos Generales */
        body {
            font-family: 'Comic Sans MS', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff4e6;
            color: #333;
        }

        /* Header Section */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff66cc;
            padding: 40px 60px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 4.5em;
            margin: 0;
            color: #fff;
            font-family: 'Impact', sans-serif;
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.4);
        }

        .logo {
            max-width: 140px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        /* Slogan Section */
        .slogan {
            background-color: #ffcc00;
            color: #fff;
            font-size: 2.5em;
            font-weight: bold;
            padding: 20px 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            border-radius: 15px;
            text-transform: uppercase;
            letter-spacing: 3px;
            animation: pulse 1.5s infinite alternate;
            background: linear-gradient(45deg, #ff66cc, #ffcc00);
            background-size: 300% 300%;
            animation: gradientAnimation 6s linear infinite;
        }

        /* Animación de fondo de gradiente */
        @keyframes gradientAnimation {
            0% { background-position: 100% 0; }
            50% { background-position: 0 100%; }
            100% { background-position: 100% 0; }
        }

        /* Efecto de "pulso" en el slogan */
        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        /* Main Content Section */
        .content {
            padding: 60px 20px;
            background-color: #fff;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            margin-top: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            border: 5px dashed #ff66cc;
        }

        .content p {
            font-size: 1.4em;
            color: #333;
            line-height: 1.8;
            margin-bottom: 30px;
            max-width: 750px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Botones con estilo 2000s */
        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
        }

        .button {
            background-color: #ff66cc;
            color: white;
            padding: 15px 50px;
            border: 2px solid #fff;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1.3em;
            transition: transform 0.3s ease, background-color 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .button:hover {
            background-color: #ffcc00;
            transform: scale(1.1);
        }

        /* Footer Section */
        .about-section {
            font-size: 1.2em;
            color: #333;
            padding: 40px;
            text-align: center;
            background-color: #ff66cc;
            margin-top: 30px;
            border-top: 4px dashed #fff;
            border-bottom: 4px dashed #fff;
            color: #fff;
        }

        /* Responsividad para móviles */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 20px 40px;
                text-align: center;
            }

            h1 {
                font-size: 3.5em;
                margin-bottom: 10px;
            }

            .logo {
                max-width: 120px;
                margin-top: 20px;
            }

            .button {
                padding: 12px 30px;
                font-size: 1.1em;
            }

            .slogan {
                font-size: 2em;
                padding: 15px 30px;
            }

            .content p {
                font-size: 1.2em;
            }

            .button-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1>Barking and More</h1>
        <img src="logo.jpeg" alt="Barking and More Logo" class="logo"> <!-- Espacio para tu logo -->
    </header>

    <!-- Slogan Section -->
    <div class="slogan">
        <p>Más que ladridos, sonrisas y bienestar</p>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <p>Bienvenidos a Barking and More, donde cada producto y servicio está diseñado para hacer la vida de tu mascota más feliz y saludable. Nos especializamos en ofrecer productos de alta calidad para el cuidado de tu perro y otros amigos peludos, así como servicios que garantizan su bienestar.</p>

        <!-- Botones de navegación -->
        <div class="button-container">
            <a href="productos.php" class="button">Productos para tu mascota</a>
            <a href="nuestros_servicios.php" class="button">Nuestros Servicios</a>
            <a href="agendar_cita.php" class="button">Agendar Cita</a>
            <a href="acerca-de-nosotros.php" class="button">Acerca de Nosotros</a>
            <a href="revisar_compra.php" class="button">Compras realizadas</a>
            
        </div>
    </div>

    <!-- About Section -->
    <div class="about-section">
        <p>En Barking and More, creemos que tu mascota merece lo mejor. Descubre nuestros productos y servicios que ayudarán a mantener a tu compañero peludo feliz y saludable. ¡Conoce lo que tenemos preparado para ellos!</p>
    </div>

    <!-- Carrito de compras -->
    <button class="cart-button" onclick="window.location.href='carrito.php'">🛒 Ver carrito</button>

    <script>
        // Función para agregar productos al carrito con confirmación
        function addToCart(productId, productName, productPrice) {
            alert("Producto agregado al carrito: " + productName);
            // Aquí puedes enviar la información al carrito usando AJAX o una sesión PHP.
        }
    </script>
</body>
</html>

