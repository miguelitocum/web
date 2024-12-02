<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda_mascotas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los parámetros de la solicitud
$productId = $_POST['id'];
$quantity = $_POST['quantity'];

// Actualizar el stock en la base de datos
$sql = "UPDATE productos SET stock = stock - $quantity WHERE id = $productId";
if ($conn->query($sql) === TRUE) {
    echo "Stock actualizado correctamente";
} else {
    echo "Error al actualizar el stock: " . $conn->error;
}

$conn->close();
?>
