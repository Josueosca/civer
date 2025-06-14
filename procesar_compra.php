<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recoger datos del cliente
    $nombre_cliente = htmlspecialchars(trim($_POST['nombre_cliente']));
    $email_cliente = htmlspecialchars(trim($_POST['email_cliente']));
    $telefono_cliente = htmlspecialchars(trim($_POST['telefono_cliente']));
    $direccion_cliente = htmlspecialchars(trim($_POST['direccion_cliente']));

    // 2. Validar que haya productos en el carrito
    if (empty($_SESSION['carrito'])) {
        header("Location: finalizar_compra.php?status=error&message=" . urlencode("El carrito está vacío."));
        exit();
    }

    // 3. Simular el procesamiento de la compra
    // En un sistema real, aquí iría la lógica de:
    // - Registrar la orden en una tabla de pedidos (ej. `pedidos`, `detalles_pedido`)
    // - Descontar el stock de los productos
    // - Procesar el pago con una pasarela de pago (ej. Stripe, PayPal, local bank transfer)
    // - Enviar correos de confirmación al cliente y al administrador
    // - Etc.

    // Para este ejemplo, simplemente vamos a simular éxito y vaciar el carrito.
    
    // Calcular el total final (por seguridad, recalcularlo aquí también)
    $total_compra = 0;
    $detalle_compra = []; // Para guardar los detalles que podríamos almacenar en una tabla de pedidos
    foreach ($_SESSION['carrito'] as $item) {
        $total_item = $item['precio'] * $item['quantity'];
        $total_compra += $total_item;
        $detalle_compra[] = [
            'id_producto' => $item['id_producto'],
            'tipo_producto' => $item['tipo_producto'],
            'precio_unitario' => $item['precio'],
            'cantidad' => $item['quantity'],
            'subtotal' => $total_item
        ];
    }

    // --- Aquí iría la lógica para guardar la orden en la base de datos ---
    // Ejemplo de inserción en una tabla hipotética `pedidos` y `detalles_pedido`
    /*
    $conn->begin_transaction(); // Iniciar transacción para asegurar la consistencia

    try {
        // Insertar el pedido principal
        $stmt_pedido = $conn->prepare("INSERT INTO pedidos (nombre_cliente, email_cliente, telefono_cliente, direccion_cliente, total, fecha_pedido, estado) VALUES (?, ?, ?, ?, ?, NOW(), 'Pendiente')");
        $stmt_pedido->bind_param("sssds", $nombre_cliente, $email_cliente, $telefono_cliente, $direccion_cliente, $total_compra);
        $stmt_pedido->execute();
        $id_pedido = $conn->insert_id; // Obtener el ID del pedido recién insertado
        $stmt_pedido->close();

        // Insertar los detalles de los ítems del pedido
        $stmt_detalle = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, nombre_producto, precio_unitario, cantidad, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($detalle_compra as $item) {
            $stmt_detalle->bind_param("iisddd", $id_pedido, $item['id_producto'], $item['tipo_producto'], $item['precio_unitario'], $item['cantidad'], $item['subtotal']);
            $stmt_detalle->execute();
        }
        $stmt_detalle->close();

        $conn->commit(); // Confirmar la transacción
        $_SESSION['carrito'] = []; // Vaciar el carrito después de la compra exitosa
        header("Location: finalizar_compra.php?status=success");
        exit();

    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); // Revertir la transacción si algo falla
        header("Location: finalizar_compra.php?status=error&message=" . urlencode("Error al guardar el pedido en la base de datos: " . $e->getMessage()));
        exit();
    }
    */

    // --- Simulación de éxito sin guardar en DB por ahora ---
    $_SESSION['carrito'] = []; // Vaciar el carrito después de la "compra"
    header("Location: finalizar_compra.php?status=success");
    exit();

} else {
    // Si se accede directamente sin POST, redirigir al inicio
    header("Location: tienda.php");
    exit();
}
$conn->close();
?>