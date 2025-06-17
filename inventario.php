<?php
// conexion.php
// Asegúrate de que 'conexion.php' esté en el mismo directorio que 'homeadmin.php'
include 'conexion.php';

$message = ""; // Variable para almacenar mensajes de éxito o error

// Directorio donde se guardarán las imágenes de inventario
// Cambiado a 'jpginventario/' según la solicitud del usuario
$upload_directory = "jpginventario/";

// Crea el directorio de carga si no existe
if (!is_dir($upload_directory)) {
    // Crea el directorio con permisos 0755 (lectura/escritura/ejecución para propietario, lectura/ejecución para grupo y otros)
    mkdir($upload_directory, 0755, true); 
}

// ========================================================================
// Manejo para agregar un nuevo producto
// ========================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    // Sanitizar y escapar los datos de entrada
    $tipo_producto = $conn->real_escape_string($_POST['tipo_producto']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $precio = $conn->real_escape_string($_POST['precio']);
    $cantidad = (int)$_POST['cantidad']; // Convertir cantidad a entero

    $imagen_ruta_db = ""; // Inicializar la ruta de la imagen en la BD

    // Manejo de la carga de la imagen
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_name = $_FILES['product_image']['tmp_name'];
        $file_name = basename($_FILES['product_image']['name']);
        // Usar finfo_open para una detección más robusta del tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file_tmp_name);
        finfo_close($finfo);
        $file_size = $_FILES['product_image']['size'];

        // Generar un nombre de archivo único para evitar colisiones
        // Limpiar el nombre del archivo para asegurar compatibilidad
        $unique_file_name = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $file_name); 
        $upload_path = $upload_directory . $unique_file_name;

        // Validar tipo de archivo (recomendado para seguridad)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file_type, $allowed_types)) {
            $message = "<p class='error-message'>Error: Tipo de archivo no permitido. Solo se permiten imágenes JPEG, PNG, GIF o WebP.</p>";
        }
        // Validar tamaño del archivo (ej. máximo 5MB)
        else if ($file_size > 5 * 1024 * 1024) { // 5 MB
            $message = "<p class='error-message'>Error: La imagen es demasiado grande (máximo 5MB).</p>";
        }
        else if (move_uploaded_file($file_tmp_name, $upload_path)) {
            $imagen_ruta_db = $upload_path; // Guardar la ruta relativa en la base de datos
        } else {
            $message = "<p class='error-message'>Error al subir la imagen al servidor.</p>";
        }
    } else if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] != UPLOAD_ERR_NO_FILE) {
        // Manejar otros errores de carga (ej. tamaño máximo excedido por php.ini)
        $message = "<p class='error-message'>Error de carga de imagen: " . $_FILES['product_image']['error'] . "</p>";
    }

    // Si no hubo errores en la carga de la imagen (o si no se subió ninguna imagen)
    if (empty($message)) {
        // Determinar la disponibilidad en función de la cantidad
        $disponibilidad = ($cantidad > 0) ? 'Disponible' : 'No disponible';

        // SQL para insertar un nuevo producto usando una sentencia preparada para seguridad
        $sql = "INSERT INTO inventario_venta (tipo_producto, descripcion, precio, imagen, cantidad, disponibilidad) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // 's' para string, 'd' para double (decimal), 'i' para integer
        // La variable $imagen_ruta_db contendrá la ruta del archivo subido o estará vacía
        $stmt->bind_param("ssdsis", $tipo_producto, $descripcion, $precio, $imagen_ruta_db, $cantidad, $disponibilidad);

        // Ejecutar la sentencia y verificar el éxito
        if ($stmt->execute()) {
            $message = "<p class='success-message'>Producto agregado exitosamente!</p>";
        } else {
            $message = "<p class='error-message'>Error al agregar producto: " . $stmt->error . "</p>";
        }
        $stmt->close(); // Cerrar la sentencia preparada
    }
}

// ========================================================================
// Manejo para actualizar la cantidad de productos existentes
// ========================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $id_producto = (int)$_POST['id_producto']; // Obtener ID del producto
    $new_cantidad = (int)$_POST['new_cantidad']; // Obtener nueva cantidad

    // Determinar la nueva disponibilidad en función de la cantidad actualizada
    $new_disponibilidad = ($new_cantidad > 0) ? 'Disponible' : 'No disponible';

    // SQL para actualizar cantidad y disponibilidad usando una sentencia preparada
    $sql_update = "UPDATE inventario_venta SET cantidad = ?, disponibilidad = ? WHERE id_producto = ?";
    $stmt_update = $conn->prepare($sql_update);
    
    // 'i' para entero, 's' para cadena, 'i' para entero (para id_producto)
    $stmt_update->bind_param("isi", $new_cantidad, $new_disponibilidad, $id_producto);

    // Ejecutar la sentencia de actualización y verificar el éxito
    if ($stmt_update->execute()) {
        $message = "<p class='success-message'>Cantidad y disponibilidad actualizadas!</p>";
    } else {
        $message = "<p class='error-message'>Error al actualizar: " . $stmt_update->error . "</p>";
    }
    $stmt_update->close(); // Cerrar la sentencia preparada
}

// ========================================================================
// Obtener productos existentes de la base de datos para mostrar
// ========================================================================
// Obtener el campo 'imagen' que contendrá la ruta del archivo (local)
$sql_select = "SELECT id_producto, tipo_producto, descripcion, precio, imagen, cantidad, disponibilidad FROM inventario_venta ORDER BY tipo_producto ASC";
$result = $conn->query($sql_select);

$conn->close(); // Cerrar la conexión a la base de datos después de todas las operaciones
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoRed - Panel de Administrador</title>
    <style>
        /* General Body and Layout Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            background-color: #f0f8f0; /* Light greenish-gray */
            color: #333; /* Dark gray for text */
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2e8b57; /* Dark green */
            color: white;
            padding-top: 20px;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: sticky; /* Keeps sidebar fixed when scrolling */
            top: 0;
            left: 0;
        }
        .sidebar .logo {
            font-size: 28px;
            font-weight: bold;
            color: #ecf0f1; /* Off-white */
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            width: 100%;
        }
        .sidebar ul li {
            width: 100%;
        }
        .sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li a:hover, .sidebar ul li a.active {
            background-color: #3cb371; /* Lighter green on hover/active */
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            max-width: calc(100% - 250px); /* Adjusts width based on sidebar */
            box-sizing: border-box; /* Includes padding in the element's total width and height */
        }

        /* Header Styles */
        .header {
            background-color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border-radius: 8px; /* Rounded corners */
        }
        .user-info {
            font-weight: bold;
            margin-right: 10px;
            color: #2e8b57; /* Dark green for user info */
        }

        /* Generic Content Section Styles */
        .content-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow */
            margin-bottom: 20px;
        }
        .content-section h1 {
            color: #2e8b57;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee; /* Separator line */
            padding-bottom: 10px;
        }

        /* Inventory Specific Styles */
        .inventory-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }
        .add-product-btn {
            background-color: #2e8b57;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Button shadow */
        }
        .add-product-btn:hover {
            background-color: #3cb371;
            transform: translateY(-1px); /* Slight lift effect */
        }

        .inventory-form-container {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            margin-bottom: 30px;
            display: none; /* Hidden by default, toggled by JS */
            transition: all 0.3s ease-in-out; /* Smooth transition for display */
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); /* Inner shadow */
        }
        .inventory-form-container.active {
            display: block; /* Shown when active */
        }
        .inventory-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsive grid */
            gap: 20px;
        }
        .inventory-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #555;
        }
        .inventory-form input[type="text"],
        .inventory-form input[type="number"],
        .inventory-form textarea,
        .inventory-form select,
        .inventory-form input[type="file"] { /* Added file input styling */
            width: 100%; /* Full width within its grid column */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .inventory-form input[type="text"]:focus,
        .inventory-form input[type="number"]:focus,
        .inventory-form textarea:focus,
        .inventory-form select:focus,
        .inventory-form input[type="file"]:focus { /* Added file input focus styling */
            border-color: #2e8b57; /* Green border on focus */
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.2); /* Light green glow */
            outline: none;
        }
        .inventory-form textarea {
            resize: vertical; /* Allow vertical resizing */
            min-height: 80px;
        }
        .inventory-form button[type="submit"] {
            grid-column: 1 / -1; /* Spans all columns for the submit button */
            background-color: #2e8b57;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .inventory-form button[type="submit"]:hover {
            background-color: #3cb371;
            transform: translateY(-1px);
        }

        /* Product Table Styles */
        .product-table {
            width: 100%;
            border-collapse: collapse; /* Remove double borders */
            margin-top: 20px;
            font-size: 0.95rem; /* Slightly smaller font for table */
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            vertical-align: middle;
        }
        .product-table th {
            background-color: #e8f5e9; /* Light green for header */
            color: #2e8b57;
            font-weight: bold;
            white-space: nowrap; /* Prevent wrapping in headers */
        }
        .product-table tr:nth-child(even) {
            background-color: #f9f9f9; /* Zebra striping */
        }
        .product-table tr:hover {
            background-color: #f0f0f0; /* Hover effect */
        }
        .product-image-thumb {
            width: 60px; /* Small thumbnail size */
            height: auto;
            border-radius: 4px;
            margin-right: 10px;
            vertical-align: middle;
            object-fit: cover; /* Ensure image covers the area without distortion */
        }
        .product-cell-content {
            display: flex;
            align-items: center;
        }

        /* Availability Status Colors */
        .disponibilidad-disponible {
            color: #28a745; /* Green */
            font-weight: bold;
        }
        .disponibilidad-no-disponible {
            color: #dc3545; /* Red */
            font-weight: bold;
        }

        /* Message Boxes (Success/Error) */
        .message-container {
            margin-bottom: 20px;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .success-message {
            background-color: #d4edda; /* Light green background */
            color: #155724; /* Dark green text */
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb;
        }

        /* Quantity Update Form in Table */
        .quantity-input {
            width: 70px; /* Wider input for quantity */
            text-align: center;
            margin-right: 8px;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 44px;
            box-sizing: border-box;
        }
        .update-quantity-form {
            display: flex;
            align-items: center;
        }
        .update-quantity-btn {
            background-color: #007bff; /* Blue for update button */
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background-color 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .update-quantity-btn:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            body {
                flex-direction: column; /* Stack sidebar and main content */
            }
            .sidebar {
                width: 100%;
                height: auto;
                padding-top: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                position: relative; /* No longer sticky on small screens */
            }
            .sidebar ul {
                display: flex; /* Make sidebar links horizontal */
                flex-wrap: wrap;
                justify-content: center;
            }
            .sidebar ul li {
                width: auto;
            }
            .sidebar ul li a {
                padding: 10px 15px;
            }
            .main-content {
                max-width: 100%;
                padding: 15px;
            }
            .inventory-form {
                grid-template-columns: 1fr; /* Single column on small screens */
            }
            .product-table, .product-table thead, .product-table tbody, .product-table th, .product-table td, .product-table tr {
                display: block; /* Make table responsive */
            }
            .product-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .product-table tr {
                border: 1px solid #ddd;
                margin-bottom: 10px;
                border-radius: 8px;
                overflow: hidden; /* For rounded corners */
            }
            .product-table td {
                border: none;
                position: relative;
                padding-left: 50%; /* Space for pseudo-element labels */
                text-align: right;
            }
            .product-table td::before {
                content: attr(data-label); /* Use data-label for mobile headers */
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
                white-space: nowrap;
                color: #2e8b57;
            }
            .product-cell-content {
                justify-content: flex-end; /* Align content to the right on mobile */
            }
            .product-image-thumb {
                margin-right: 0;
                margin-left: 10px;
            }
            .update-quantity-form {
                justify-content: flex-end;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener referencias al botón de agregar producto y al contenedor del formulario
            const addProductBtn = document.getElementById('toggleAddProductForm');
            const addProductFormContainer = document.getElementById('addProductFormContainer');

            // Añadir un listener de evento para alternar la visibilidad del formulario
            if (addProductBtn && addProductFormContainer) {
                addProductBtn.addEventListener('click', function() {
                    addProductFormContainer.classList.toggle('active'); // Alterna la clase 'active'
                    // Desplazarse al formulario si se vuelve visible
                    if (addProductFormContainer.classList.contains('active')) {
                        addProductFormContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            }

            // Desplazamiento suave a la sección de inventario si la página se carga con el hash #inventory
            if (window.location.hash === '#inventory') {
                const inventorySection = document.getElementById('inventory');
                if (inventorySection) {
                    inventorySection.scrollIntoView({ behavior: 'smooth' });
                }
            }

            // Añadir etiquetas para tablas en móvil (para tabla responsiva)
            const table = document.querySelector('.product-table');
            if (table) {
                const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
                table.querySelectorAll('tr:not(:first-child)').forEach(row => {
                    Array.from(row.querySelectorAll('td')).forEach((cell, index) => {
                        if (headers[index]) {
                            cell.setAttribute('data-label', headers[index] + ":");
                        }
                    });
                });
            }
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            TecnoRed
        </div>
        <ul>
            <li><a href="homeadmin.php">Home Admin</a></li>
            <li><a href="administrar_cyber.php" onclick="console.log('Funcionalidad de Administrar Cyber por desarrollar');">Administrar Cyber</a></li>
            <li><a href="javascript:void(0);" onclick="console.log('Funcionalidad de Asesores por desarrollar');">Asesores</a></li>
            <li><a href="#inventory" class="active">Inventario</a></li> <!-- Marcar Inventario como activo -->
            <li><a href="javascript:void(0);" onclick="console.log('Funcionalidad de Reporte de Finanzas por desarrollar');">Reporte de Finanzas</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="user-info">
                <?php
                    // Simulación de usuario logeado
                    // En un entorno real, esto vendría de una sesión o autenticación
                    $nombre_usuario = "Administrador"; // O desde la base de datos
                    echo "Bienvenido, " . htmlspecialchars($nombre_usuario);
                ?>
            </span>
        </div>

        <div class="content-section">
            <h1>Panel de Control Principal</h1>
            <p>Bienvenido al panel de administración de TecnoRed. Desde aquí podrás gestionar todos los aspectos del cyber, desde los equipos hasta las finanzas.</p>
            <p>Usa el menú de la izquierda para navegar entre las diferentes secciones.</p>
        </div>

        <div class="content-section" id="inventory">
            <div class="inventory-header">
                <h1>Gestión de Inventario</h1>
                <!-- Botón para alternar la visibilidad del formulario de agregar producto -->
                <button type="button" id="toggleAddProductForm" class="add-product-btn">+ Producto</button>
            </div>

            <?php if (!empty($message)): ?>
                <!-- Mostrar mensaje de éxito o error aquí -->
                <div class="message-container <?php echo strpos($message, 'exitosamente') !== false ? 'success-message' : 'error-message'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Contenedor para el formulario de agregar producto, inicialmente oculto -->
            <div id="addProductFormContainer" class="inventory-form-container">
                <h2>Agregar Nuevo Producto</h2>
                <!-- El atributo enctype es CRUCIAL para la carga de archivos -->
                <form class="inventory-form" method="POST" action="homeadmin.php#inventory" enctype="multipart/form-data">
                    <div>
                        <label for="tipo_producto">Tipo de Producto:</label>
                        <input type="text" id="tipo_producto" name="tipo_producto" required>
                    </div>
                    <div>
                        <label for="precio">Precio:</label>
                        <input type="number" id="precio" name="precio" step="0.01" required>
                    </div>
                    <div>
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" min="0" value="1" required>
                    </div>
                    <div>
                        <label for="product_image">Seleccionar Imagen:</label>
                        <input type="file" id="product_image" name="product_image" accept="image/jpeg, image/png, image/gif, image/webp">
                    </div>
                    <div style="grid-column: span 2;"> <!-- La descripción abarca ambas columnas -->
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion"></textarea>
                    </div>
                    <button type="submit" name="add_product">Agregar Producto</button>
                </form>
            </div>

            <h2>Inventario Actual</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Disponibilidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-label="ID"><?php echo htmlspecialchars($row['id_producto']); ?></td>
                                <td data-label="Producto">
                                    <div class="product-cell-content">
                                        <?php 
                                        // Display image from URL if available, or placeholder if not
                                        if (!empty($row['imagen'])): ?>
                                            <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['tipo_producto']); ?>" class="product-image-thumb" onerror="this.onerror=null;this.src='https://placehold.co/60x60/cccccc/333333?text=No+Img';">
                                        <?php else: ?>
                                            <!-- Placeholder if no image URL -->
                                            <img src="https://placehold.co/60x60/cccccc/333333?text=No+Img" alt="No Image" class="product-image-thumb">
                                        <?php endif; ?>
                                        <span><?php echo htmlspecialchars($row['tipo_producto']); ?></span>
                                    </div>
                                </td>
                                <td data-label="Descripción"><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td data-label="Precio">$<?php echo number_format($row['precio'], 2); ?></td>
                                <td data-label="Cantidad">
                                    <!-- Form to update quantity directly from the table -->
                                    <form class="update-quantity-form" method="POST" action="homeadmin.php#inventory">
                                        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($row['id_producto']); ?>">
                                        <input type="number" name="new_cantidad" class="quantity-input" value="<?php echo htmlspecialchars($row['cantidad']); ?>" min="0">
                                        <button type="submit" name="update_quantity" class="update-quantity-btn">Actualizar</button>
                                    </form>
                                </td>
                                <td data-label="Disponibilidad" class="<?php echo ($row['disponibilidad'] == 'Disponible' ? 'disponibilidad-disponible' : 'disponibilidad-no-disponible'); ?>">
                                    <?php echo htmlspecialchars($row['disponibilidad']); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay productos en el inventario.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
