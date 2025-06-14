<?php
// chat.php
session_start();

// Incluir las funciones de base de datos (si ya las tienes)
// require_once 'db_functions.php';

// Verificar si el usuario ha iniciado sesión (solo para demostración, puedes omitirlo por ahora)
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Estudiante de Prueba';
$id_usuario = $_SESSION['id_usuario'] ?? 'usuario-12345';

// Obtener la materia seleccionada de la URL
$materia_seleccionada = isset($_GET['materia']) ? urldecode($_GET['materia']) : 'Materia Desconocida';

// Para el chat, necesitamos un "asesor" o "profesor" asociado a la materia.
// Por ahora, lo pondremos de forma estática para la demo, simulando el "Asesor de Ciencias"
$nombre_asesor = "Asesor de " . htmlspecialchars($materia_seleccionada);
$id_asesor = "asesor-" . strtolower(str_replace(' ', '', $materia_seleccionada)); // ID simple para la demo

// Aquí iría la lógica para cargar mensajes existentes (en un sistema real)
// Por ahora, solo un mensaje de bienvenida de prueba
$mensajes_chat = [
    [
        'sender' => $nombre_asesor,
        'message' => "Hola, ¿en qué puedo ayudarte con " . htmlspecialchars($materia_seleccionada) . "?",
        'time' => '16:37' // Hora estática por ahora
    ]
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat de Asesoría - <?php echo htmlspecialchars($materia_seleccionada); ?></title>
    <link rel="stylesheet" href="css/estilos.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="contenedor-principal">
        <header class="encabezado-usuario">
            <p>Bienvenido/a.</p>
            <div class="info-usuario">
                <span class="icono-usuario">👤</span>
                <span class="nombre-usuario"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                <span class="id-usuario">ID: <?php echo htmlspecialchars($id_usuario); ?></span>
            </div>
        </header>

        <main class="contenido-chat-completo">
            <h1>Centro de Asesorías</h1>

            <div class="panel-chat-division">
                <div class="panel-conversaciones">
                    <h2>Conversaciones</h2>
                    <div class="lista-conversaciones">
                        <div class="conversacion-item conversacion-activa">
                            <div class="avatar">A</div>
                            <div class="detalle-conversacion">
                                <h3><?php echo htmlspecialchars($nombre_asesor); ?></h3>
                                <p>Hola, ¿en qué puedo ayudar...?</p>
                            </div>
                        </div>
                        </div>
                </div>

                <div class="panel-chat-principal">
                    <div class="encabezado-chat">
                        <div class="avatar-chat">A</div>
                        <div class="info-chat-destinatario">
                            <h3><?php echo htmlspecialchars($nombre_asesor); ?></h3>
                            <p>Asesor de <?php echo htmlspecialchars($materia_seleccionada); ?></p>
                        </div>
                    </div>

                    <div class="area-mensajes">
                        <?php foreach ($mensajes_chat as $mensaje): ?>
                            <div class="mensaje mensaje-tercero"> <p><?php echo htmlspecialchars($mensaje['message']); ?></p>
                                <span class="tiempo-mensaje"><?php echo htmlspecialchars($mensaje['time']); ?></span>
                            </div>
                        <?php endforeach; ?>
                        </div>

                    <div class="area-entrada-mensaje">
                        <input type="text" placeholder="Escribe un mensaje..." class="input-mensaje">
                        <button class="boton-enviar">▶</button> </div>
                </div>
            </div>
            <div class="acciones-inferiores">
                <a href="asesorias.php" class="boton-volver">Volver a Materias</a>
            </div>
        </main>
    </div>
</body>
</html>