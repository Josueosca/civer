<?php
// asesorias.php

session_start();

// Datos de prueba para el usuario (en un sistema real, vienen del login)
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Estudiante de Prueba';
$id_usuario = $_SESSION['id_usuario'] ?? 'usuario-12345';

// Materias que se mostrarán en los botones
$materias = [
    'Matemáticas',
    'Ciencias',
    'Historia',
    'Literatura',
    'Inglés',
    'Programación'
];

// Estas variables PHP no se usarán para mostrar el chat al cargar la página por defecto
// pero se mantienen por si se decide usar en el futuro (ej. URL directa a un chat).
$materia_chat_activa = '';
$nombre_asesor_chat = '';
$id_asesor_chat = '';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Asesorías</title>
    <link rel="stylesheet" href="css/asesorias.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

        <main class="contenido-asesorias" id="main-content">
            <div id="seccion-materias" class="seccion-visible">
                <h1>Centro de Asesorías</h1>
                <p class="instruccion">Selecciona una materia para asesoría:</p>

                <div class="grid-materias">
                    <?php foreach ($materias as $materia): ?>
                        <a href="#" class="tarjeta-materia" data-materia="<?php echo htmlspecialchars($materia); ?>">
                            <span class="icono-materia">◯</span>
                            <p><?php echo htmlspecialchars($materia); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="seccion-chat" class="seccion-oculta">
                <h1 id="chat-h1">Asesoría de <span id="materia-chat-titulo"></span></h1>

                <div class="panel-chat-division">
                    <div class="panel-conversaciones">
                        <h2>Conversaciones</h2>
                        <div class="lista-conversaciones">
                            </div>
                    </div>

                    <div class="panel-chat-principal">
                        <div class="encabezado-chat">
                            <div class="avatar-chat">A</div>
                            <div class="info-chat-destinatario">
                                <h3 id="nombre-asesor-chat"></h3>
                                <p id="especialidad-asesor-chat"></p>
                            </div>
                        </div>

                        <div class="area-mensajes" id="chat-messages">
                            </div>

                        <div class="area-entrada-mensaje">
                            <input type="text" id="message-input" placeholder="Escribe un mensaje..." class="input-mensaje" autocomplete="off">
                            <button id="send-message-btn" class="boton-enviar">▶</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="acciones-inferiores">
                <a href="#" id="boton-volver-materias" class="boton-volver seccion-oculta">Volver a Materias</a>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            const userId = '<?php echo $id_usuario; ?>'; // ID del estudiante logueado
            const userName = '<?php echo $nombre_usuario; ?>';

            let currentMateria = '';
            let currentAsesorName = '';
            let currentAsesorId = '';

            // *** CRÍTICO: Asegurarse de que al cargar la página, siempre se muestre la sección de materias ***
            mostrarMaterias();


            // Función para mostrar la sección de chat y ocultar la de materias
            function mostrarChat(materia, asesorNombre, asesorId) {
                currentMateria = materia;
                currentAsesorName = asesorNombre;
                currentAsesorId = asesorId;

                $('#seccion-materias').removeClass('seccion-visible').addClass('seccion-oculta');
                $('#seccion-chat').removeClass('seccion-oculta').addClass('seccion-visible');
                $('#boton-volver-materias').removeClass('seccion-oculta').addClass('seccion-visible');

                // Actualizar los títulos del chat
                $('#materia-chat-titulo').text(materia);
                $('#nombre-asesor-chat').text(asesorNombre);
                $('#especialidad-asesor-chat').text('Asesor de ' + materia);

                // Limpiar y agregar la conversación activa al panel de conversaciones
                $('.lista-conversaciones').empty(); // Limpiar conversaciones previas
                $('.lista-conversaciones').append(
                    `<div class="conversacion-item conversacion-activa" data-materia="${htmlspecialchars(materia)}">
                        <div class="avatar">A</div>
                        <div class="detalle-conversacion">
                            <h3>${htmlspecialchars(asesorNombre)}</h3>
                            <p>Asesoría de ${htmlspecialchars(materia)}</p>
                        </div>
                    </div>`
                );

                // Cargar mensajes específicos para esta conversación
                cargarMensajes();
            }

            // Función para mostrar la sección de materias y ocultar la de chat
            function mostrarMaterias() {
                $('#seccion-chat').removeClass('seccion-visible').addClass('seccion-oculta');
                $('#seccion-materias').removeClass('seccion-oculta').addClass('seccion-visible');
                $('#boton-volver-materias').removeClass('seccion-visible').addClass('seccion-oculta');
                currentMateria = '';
                currentAsesorName = '';
                currentAsesorId = '';
                $('#chat-messages').empty(); // Limpiar mensajes al volver
                $('.lista-conversaciones').empty(); // Limpiar conversaciones al volver a la sección de materias
            }

            // Manejar clic en las tarjetas de materia
            $('.tarjeta-materia').on('click', function(e) {
                e.preventDefault(); // Previene la navegación por defecto
                const materia = $(this).data('materia');

                const asesorNombre = "Asesor de " + materia;
                const asesorId = "asesor-" + materia.toLowerCase().replace(/\s/g, '');

                mostrarChat(materia, asesorNombre, asesorId);
            });

            // Manejar clic en el botón "Volver a Materias"
            $('#boton-volver-materias').on('click', function(e) {
                e.preventDefault();
                mostrarMaterias();
            });


            // --- Lógica del Chat (AJAX para simular tiempo real) ---

            function cargarMensajes() {
                 $('#chat-messages').empty(); // Limpiar antes de cargar
                const mensajeBienvenida = {
                    sender: currentAsesorName,
                    message: "Hola, ¿en qué puedo ayudarte con " + currentMateria + "?",
                    time: new Date().toLocaleTimeString('es-VE', {hour: '2-digit', minute: '2-digit'})
                };
                 agregarMensajeAlChat(mensajeBienvenida, 'tercero');

                // Si tuvieras un backend PHP que devuelve JSON de mensajes:
                /*
                $.ajax({
                    url: 'get_chat_messages.php', // Un nuevo archivo PHP para obtener mensajes
                    method: 'GET',
                    data: {
                        materia: currentMateria,
                        profesor_id: currentAsesorId, // El ID del profesor real
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(mensajes) {
                        $('#chat-messages').empty();
                        mensajes.forEach(function(msg) {
                            const tipoMensaje = (msg.sender_id === userId) ? 'propio' : 'tercero';
                            agregarMensajeAlChat(msg, tipoMensaje);
                        });
                        scrollChatToBottom();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar mensajes:", error);
                    }
                });
                */
            }

            function enviarMensaje() {
                const message = $('#message-input').val().trim();
                if (message !== '') {
                    const nuevoMensaje = {
                        sender: userName,
                        message: message,
                        time: new Date().toLocaleTimeString('es-VE', {hour: '2-digit', minute: '2-digit'})
                    };
                    agregarMensajeAlChat(nuevoMensaje, 'propio');
                    $('#message-input').val(''); // Limpiar el input

                    // Enviar el mensaje al servidor (a un script PHP)
                    /*
                    $.ajax({
                        url: 'send_chat_message.php', // Un nuevo archivo PHP para enviar mensajes
                        method: 'POST',
                        data: {
                            materia: currentMateria,
                            profesor_id: currentAsesorId,
                            user_id: userId,
                            message: message
                        },
                        success: function(response) {
                            console.log("Mensaje enviado:", response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al enviar mensaje:", error);
                        }
                    });
                    */
                }
            }

            function agregarMensajeAlChat(msg, tipo) {
                const claseMensaje = (tipo === 'propio') ? 'mensaje-propio' : 'mensaje-tercero';
                const nombreEmisor = (tipo === 'propio') ? 'Tú' : msg.sender;
                $('#chat-messages').append(
                    `<div class="mensaje ${claseMensaje}">
                        <p><strong>${htmlspecialchars(nombreEmisor)}:</strong> ${htmlspecialchars(msg.message)}</p>
                        <span class="tiempo-mensaje">${msg.time}</span>
                    </div>`
                );
                scrollChatToBottom();
            }

            function scrollChatToBottom() {
                const chatArea = $('#chat-messages')[0];
                if (chatArea) {
                    chatArea.scrollTop = chatArea.scrollHeight;
                }
            }

            // Event listeners para enviar mensaje
            $('#send-message-btn').on('click', enviarMensaje);
            $('#message-input').on('keypress', function(e) {
                if (e.which === 13) { // Tecla Enter
                    enviarMensaje();
                    e.preventDefault();
                }
            });

            // Función para escapar HTML para seguridad (evitar XSS)
            function htmlspecialchars(str) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return str.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
        });
    </script>
</body>
</html>