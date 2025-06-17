<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoRed - Panel de Administrador</title>
    <link rel="stylesheet" href="css/homeadmin.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            TecnoRed
        </div>
        <ul>
            <li><a href="homeadmin.php">Home Admin</a></li>
            <li><a href="administrar_cyber.php" onclick="alert('Funcionalidad de Administrar Cyber por desarrollar');">Administrar Cyber</a></li>
            <li><a href="inventario.php" onclick="alert('Funcionalidad de Asesores por desarrollar');">Asesores</a></li>
            <li><a href="inventario.php" onclick="alert('Funcionalidad de Inventario por desarrollar');">Inventario</a></li>
            <li><a href="inventario.php" onclick="alert('Funcionalidad de Reporte de Finanzas por desarrollar');">Reporte de Finanzas</a></li>
            </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="user-info">
                <?php
                    // Simulación de usuario logeado
                    // En un entorno real, esto vendría de una sesión
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
    </div>
</body>
</html>