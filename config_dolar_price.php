<?php
// Este archivo guarda el precio del dólar.
// Se recomienda proteger el acceso a este archivo.

// Si el archivo no existe o está vacío, inicializar con un valor por defecto.
$dolar_price_file = __DIR__ . '/dolar_price.json'; // Usaremos JSON para simplicidad

if (!file_exists($dolar_price_file) || filesize($dolar_price_file) == 0) {
    $current_dolar_price = ['price' => 102.22, 'last_updated' => date('Y-m-d H:i:s')];
    file_put_contents($dolar_price_file, json_encode($current_dolar_price));
} else {
    $data = json_decode(file_get_contents($dolar_price_file), true);
    $current_dolar_price = $data['price']; // Obtener solo el precio
}

// Función para actualizar el precio del dólar
function updateDolarPrice($new_price) {
    global $dolar_price_file;
    $data = ['price' => floatval($new_price), 'last_updated' => date('Y-m-d H:i:s')];
    file_put_contents($dolar_price_file, json_encode($data));
}

?>