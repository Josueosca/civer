<?php
// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión a la base de datos
// ASUMIMOS que 'conexion.php' está en la misma carpeta que 'detalle_producto.php'
require_once 'conexion.php';

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Verificar si se recibió el ID del producto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = "ID de producto no proporcionado.";
    echo json_encode($response);
    exit();
}

$id_producto = intval($_GET['id']); // Convertir el ID a entero para seguridad

// Verificar que el ID sea un número válido
if ($id_producto <= 0) {
    $response['message'] = "ID de producto inválido.";
    echo json_encode($response);
    exit();
}

try {
    // Preparar la consulta SQL para obtener los detalles del producto
    // Asegúrate de que los nombres de las columnas coincidan con tu base de datos
    $sql = "SELECT id_producto, tipo_producto, descripcion, precio, imagen FROM inventario_venta WHERE id_producto = ? AND disponibilidad = 'Disponible'";
    
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta falló
    if ($stmt === false) {
        $response['message'] = "Error al preparar la consulta: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    // Vincular el parámetro ID a la consulta
    $stmt->bind_param("i", $id_producto); // "i" indica que es un entero

    // Ejecutar la consulta
    $stmt->execute();
    
    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    // Verificar si se encontró el producto
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $response['success'] = true;
        $response['product'] = $product;
    } else {
        $response['message'] = "Producto no encontrado o no disponible.";
    }

    $stmt->close(); // Cerrar la sentencia preparada
    $conn->close(); // Cerrar la conexión a la base de datos

} catch (Exception $e) {
    // Capturar cualquier excepción inesperada (por ejemplo, problemas de conexión)
    $response['message'] = "Error interno del servidor: " . $e->getMessage();
}

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>