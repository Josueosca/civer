<?php
// agregar_al_carrito.php
session_start(); // Iniciar la sesión al principio del script

require_once 'conexion.php';

header('Content-Type: application/json'); // Siempre responder en JSON

$response = ['success' => false, 'message' => ''];

// Inicializar el carrito en la sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = []; // Array asociativo: [id_producto => ['data_producto', 'quantity']]
}

$action = $_REQUEST['action'] ?? ''; // Obtener la acción (add, remove, get_count, get_items, clear_and_add)

switch ($action) {
    case 'add':
        $product_id = $_POST['id'] ?? null;
        $quantity = intval($_POST['quantity'] ?? 1);

        if ($product_id && $quantity > 0) {
            // Buscar el producto en la base de datos para obtener sus detalles y precio
            $stmt = $conn->prepare("SELECT id_producto, tipo_producto, precio FROM inventario_venta WHERE id_producto = ? AND disponibilidad = 'Disponible'");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();

            if ($product) {
                if (isset($_SESSION['carrito'][$product_id])) {
                    $_SESSION['carrito'][$product_id]['quantity'] += $quantity;
                    $response['message'] = "Cantidad de '" . $product['tipo_producto'] . "' actualizada en el carrito.";
                } else {
                    $_SESSION['carrito'][$product_id] = [
                        'id_producto' => $product['id_producto'],
                        'tipo_producto' => $product['tipo_producto'],
                        'precio' => $product['precio'],
                        'quantity' => $quantity
                    ];
                    $response['message'] = "'" . $product['tipo_producto'] . "' agregado al carrito.";
                }
                $response['success'] = true;
            } else {
                $response['message'] = "Producto no encontrado o no disponible.";
            }
        } else {
            $response['message'] = "Datos de producto inválidos.";
        }
        break;

    case 'remove':
        $product_id = $_POST['id'] ?? null;
        if ($product_id && isset($_SESSION['carrito'][$product_id])) {
            unset($_SESSION['carrito'][$product_id]);
            $response['success'] = true;
            $response['message'] = "Producto eliminado del carrito.";
        } else {
            $response['message'] = "Producto no encontrado en el carrito.";
        }
        break;

    case 'get_count':
        $count = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $count += $item['quantity'];
        }
        $response['success'] = true;
        $response['count'] = $count;
        break;

    case 'get_items':
        $response['success'] = true;
        $response['items'] = array_values($_SESSION['carrito']); // Devolver como array indexado
        break;

    case 'clear_and_add': // Usado para "Comprar Ahora"
        $product_id = $_POST['id'] ?? null;
        $quantity = intval($_POST['quantity'] ?? 1);

        if ($product_id && $quantity > 0) {
            $stmt = $conn->prepare("SELECT id_producto, tipo_producto, precio FROM inventario_venta WHERE id_producto = ? AND disponibilidad = 'Disponible'");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            $stmt->close();

            if ($product) {
                $_SESSION['carrito'] = []; // Limpiar el carrito
                $_SESSION['carrito'][$product_id] = [ // Agregar solo este producto
                    'id_producto' => $product['id_producto'],
                    'tipo_producto' => $product['tipo_producto'],
                    'precio' => $product['precio'],
                    'quantity' => $quantity
                ];
                $response['success'] = true;
                $response['message'] = "Carrito limpiado y producto añadido para compra directa.";
            } else {
                $response['message'] = "Producto no encontrado o no disponible para compra directa.";
            }
        } else {
            $response['message'] = "Datos de producto inválidos para compra directa.";
        }
        break;

    default:
        $response['message'] = "Acción no válida.";
        break;
}

$conn->close();
echo json_encode($response);
?>