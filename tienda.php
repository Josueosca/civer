<?php session_start(); // Iniciar sesión al principio para usar el carrito ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoRed Shop - Tu Tienda de Tecnología</title>
    <link rel="stylesheet" href="css/compras.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>Bienvenido a TecnoRed Shop</h1>
        <p>Tu destino para la última tecnología en Venezuela.</p>
        </div>

    <div class="cart-icon-container">
        <i class="fas fa-shopping-cart cart-icon" id="cartIcon"></i>
        <span class="cart-count" id="cartCount">0</span>
    </div>

    <div class="product-grid">
        <?php
        require_once 'conexion.php';

        $sql = "SELECT id_producto, tipo_producto, descripcion, precio, imagen FROM inventario_venta WHERE disponibilidad = 'Disponible' ORDER BY tipo_producto ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagen_url = htmlspecialchars($row['imagen']);
                $precio_formateado = number_format($row['precio'], 2, ',', '.') . ' Bs.';

                echo '<div class="product-card" data-product-id="' . htmlspecialchars($row['id_producto']) . '">';
                echo '<img src="' . $imagen_url . '" alt="' . htmlspecialchars($row['tipo_producto']) . '" class="product-image">';
                echo '<div class="product-info">';
                echo '<h3>' . htmlspecialchars($row['tipo_producto']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '<p class="product-price">' . $precio_formateado . '</p>';
                echo '</div>'; // product-info
                echo '</div>'; // product-card
            }
        } else {
            echo '<div class="no-products"><p>¡Ups! No hay productos disponibles en este momento.</p><p>Pronto tendremos más novedades.</p></div>';
        }
        $conn->close();
        ?>
    </div>

    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div class="modal-body">
                <div class="modal-image-container">
                    <img id="modalProductImage" src="" alt="Imagen del Producto" class="modal-image">
                </div>
                <div class="modal-details">
                    <h2 id="modalProductTitle"></h2>
                    <p id="modalProductDescription"></p>
                    <p class="price" id="modalProductPrice"></p>
                    <div class="modal-buttons">
                        <button id="buyNowButton" class="modal-button buy">Comprar Ahora</button>
                        <button id="addToCartButton" class="modal-button cart">Agregar al Carrito</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Tu Carrito de Compras</h2>
            <ul id="cartItems">
                </ul>
            <p class="cart-total" id="cartTotal">Total: Bs. 0.00</p>
            <button id="checkoutButtonCart" class="checkout-button-cart">Finalizar Compra</button>
        </div>
    </div>

    <script src="js/modalescomp.js"></script>
</body>
</html>