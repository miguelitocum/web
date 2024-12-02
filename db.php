<?php
// Configuración de la base de datos
$servername = "localhost"; // O la dirección de tu servidor de base de datos
$username = "root";        // Tu usuario de MySQL
$password = "";            // Tu contraseña de MySQL (si no tienes contraseña, déjalo vacío)
$dbname = "tienda_mascotas";  // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
