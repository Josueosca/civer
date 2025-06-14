<?php
session_start();
require_once 'conexion.php';

// Redirigir si el carrito está vacío
if (empty($_SESSION['carrito'])) {
    header("Location: tienda.php");
    exit();
}

$total_compra = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - TecnoRed Shop</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .checkout-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .checkout-container h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 25px;
            font-size: 2em;
        }
        .order-summary {
            margin-bottom: 30px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .order-summary h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .order-summary ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .order-summary ul li {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        .order-summary ul li:last-child {
            border-bottom: none;
        }
        .order-summary .item-name {
            font-weight: bold;
            color: #555;
        }
        .order-summary .item-price-qty {
            color: #777;
            font-size: 0.9em;
        }
        .order-summary .total-price {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
        }

        .client-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .client-form input[type="text"],
        .client-form input[type="email"] {
            width: calc(100% - 22px);
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .client-form button {
            background-color: #007bff;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .client-form button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 1em;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #badbcc; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Finalizar Tu Compra</h2>

        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<p class="message success">¡Compra realizada con éxito! Pronto nos contactaremos contigo.</p>';
            } elseif ($_GET['status'] == 'error') {
                echo '<p class="message error">Error al procesar la compra: ' . htmlspecialchars($_GET['message']) . '</p>';
            }
        }
        ?>

        <div class="order-summary">
            <h3>Resumen de Tu Pedido</h3>
            <ul>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <li>
                        <span class="item-name"><?php echo htmlspecialchars($item['tipo_producto']); ?></span>
                        <span class="item-price-qty">
                            <?php echo number_format($item['precio'], 2, ',', '.') . ' Bs. x ' . $item['quantity']; ?>
                        </span>
                    </li>
                    <?php $total_compra += $item['precio'] * $item['quantity']; ?>
                <?php endforeach; ?>
            </ul>
            <p class="total-price">Total a Pagar: <?php echo number_format($total_compra, 2, ',', '.') . ' Bs.'; ?></p>
        </div>

        <form action="procesar_compra.php" method="POST" class="client-form">
            <h3>Tus Datos de Envío</h3>
            <label for="nombre_cliente">Nombre Completo:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" required>

            <label for="email_cliente">Correo Electrónico:</label>
            <input type="email" id="email_cliente" name="email_cliente" required>

            <label for="telefono_cliente">Teléfono:</label>
            <input type="text" id="telefono_cliente" name="telefono_cliente" required>

            <label for="direccion_cliente">Dirección de Envío:</label>
            <input type="text" id="direccion_cliente" name="direccion_cliente" required>

            <button type="submit">Confirmar Compra</button>
        </form>
        <a href="index.php" class="back-link" style="text-align: center; margin-top: 20px;">← Seguir Comprando</a>
    </div>
</body>
</html>