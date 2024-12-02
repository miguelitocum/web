<?php
session_start(); // Iniciar sesión

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

// Obtener los productos de la base de datos
$sql = "SELECT p.id, p.nombre, p.descripcion, p.imagen, p.precio, p.existencia, c.nombre AS categoria
        FROM productos p
        JOIN categorias c ON p.categoria_id = c.id";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Barking and More</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilo para el contenedor de los productos */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            padding: 20px;
        }

        .product {
            width: 200px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product h3 {
            margin: 10px 0;
            font-size: 1.1em;
        }

        .product p {
            font-size: 1.2em;
            color: #f39c12;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        #searchInput {
            padding: 8px;
            width: 60%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #categoryFilter {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 35%;
        }

        /* Modal */
        #productModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        #productModalContent {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        #productModal h2 {
            font-size: 1.6em;
            margin-bottom: 15px;
        }

        #modalImage {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        #modalQuantity {
            padding: 5px;
            font-size: 1em;
            width: 50%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
        }

        #addToCartModal {
            background-color: #f39c12;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        #addToCartModal:hover {
            background-color: #e67e22;
        }

        #closeModal {
            margin-top: 15px;
            font-size: 1.2em;
            color: #f39c12;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Botón flotante de carrito */
        .cart-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f39c12;
            color: white;
            padding: 15px;
            border-radius: 50%;
            font-size: 1.5em;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .cart-button:hover {
            background-color: #e67e22;
        }

    </style>
</head>
<body>

<!-- Barra de búsqueda y filtro por categoría -->
<div class="filter-container">
    <input type="text" id="searchInput" placeholder="Buscar productos..." onkeyup="searchProducts()">
    <select id="categoryFilter" onchange="filterCategory()">
        <option value="">Selecciona una categoría</option>
        <option value="Camas">Camas</option>
        <option value="Juguetes">Juguetes</option>
        <option value="Alimentos">Alimentos</option>
        <option value="Productos de limpieza">Productos de limpieza</option>
    </select>
</div>

<!-- Contenedor de productos -->
<div class="product-container" id="productContainer">
    <?php foreach ($products as $product): ?>
        <div class="product" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['nombre']; ?>" data-description="<?php echo $product['descripcion']; ?>" data-price="<?php echo $product['precio']; ?>" data-image="<?php echo $product['imagen']; ?>" data-category="<?php echo $product['categoria']; ?>" data-stock="<?php echo $product['existencia']; ?>">
            <img src="<?php echo $product['imagen']; ?>" alt="<?php echo $product['nombre']; ?>" class="product-image">
            <h3><?php echo $product['nombre']; ?></h3>
            <p>$<?php echo $product['precio']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal para ver más detalles del producto -->
<div id="productModal">
    <div id="productModalContent">
        <h2 id="modalTitle"></h2>
        <img id="modalImage" src="" alt="Imagen del producto">
        <p id="modalDescription"></p>
        <p><strong>Precio: $<span id="modalPrice"></span></strong></p>
        <p><strong>Existencia disponible: <span id="modalStock"></span></strong></p>
        <label for="modalQuantity">Cantidad:</label>
        <input type="number" id="modalQuantity" value="1" min="1" />
        <button id="addToCartModal">Agregar al carrito</button>
        <p id="closeModal">Cerrar</p>
    </div>
</div>

<!-- Botón flotante de carrito -->
<button class="cart-button" onclick="window.location.href='carrito.php'">🛒</button>

<script>
    // Mostrar detalles del producto al hacer clic en el recuadro completo
    document.querySelectorAll('.product').forEach(product => {
        product.addEventListener('click', function() {
            const productId = product.getAttribute('data-id');
            const productName = product.getAttribute('data-name');
            const productDesc = product.getAttribute('data-description');
            const productPrice = product.getAttribute('data-price');
            const productImage = product.getAttribute('data-image');
            const productStock = parseInt(product.getAttribute('data-stock'));

            // Mostrar el modal con los detalles
            document.getElementById('modalTitle').textContent = productName;
            document.getElementById('modalImage').src = productImage;
            document.getElementById('modalDescription').textContent = productDesc;
            document.getElementById('modalPrice').textContent = productPrice;
            document.getElementById('modalStock').textContent = productStock;

            // Ajustar la cantidad máxima al stock disponible
            const quantityInput = document.getElementById('modalQuantity');
            quantityInput.max = productStock;
            quantityInput.value = 1;  // Establecer la cantidad inicial a 1

            // Mostrar el modal
            document.getElementById('productModal').style.display = "flex";

            // Agregar al carrito desde el modal
            document.getElementById('addToCartModal').addEventListener('click', function() {
                const quantity = parseInt(quantityInput.value);

                // Verificar si la cantidad es mayor al stock disponible
                if (quantity > productStock) {
                    alert('No hay suficiente stock disponible. Solo hay ' + productStock + ' unidades.');
                } else {
                    addToCart(productId, productName, productPrice, quantity);
                    closeModal();
                }
            });
        });
    });

    // Cerrar el modal
    document.getElementById('closeModal').addEventListener('click', closeModal);

    function closeModal() {
        document.getElementById('productModal').style.display = "none";
    }

    function addToCart(productId, productName, productPrice, quantity) {
        // Usar AJAX para agregar el producto al carrito sin recargar la página
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "agregar_carrito.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Producto agregado al carrito: ' + productName);
                // Actualizar stock en la base de datos
                updateStock(productId, quantity);
            }
        };
        xhr.send("id=" + productId + "&name=" + productName + "&price=" + productPrice + "&quantity=" + quantity);
    }

    function updateStock(productId, quantity) {
        // Actualizar el stock en la base de datos después de agregar al carrito
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_stock.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + productId + "&quantity=" + quantity);
    }

    // Funciones para filtrar productos por búsqueda y categoría
    function searchProducts() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const products = document.querySelectorAll('.product');
        products.forEach(product => {
            const productName = product.querySelector('h3').textContent.toLowerCase();
            if (productName.includes(searchInput)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    function filterCategory() {
        const categoryFilter = document.getElementById('categoryFilter').value;
        const products = document.querySelectorAll('.product');
        products.forEach(product => {
            const productCategory = product.getAttribute('data-category');
            if (categoryFilter === '' || productCategory === categoryFilter) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
