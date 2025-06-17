<?php
session_start();
date_default_timezone_set('America/Caracas'); // Ajusta según tu ubicación
include 'conexion.php';
include 'config_dolar_price.php'; // Incluir el archivo de configuración del dólar

$mensaje_feedback = "";

// --- Lógica para AGREGAR EQUIPO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_equipo'])) {
    $nombre_equipo = $_POST['nombre_equipo'];
    $tipo = $_POST['tipo'];
    $caracteristicas = $_POST['caracteristicas_tecnicas'];
    $estado = $_POST['estado'];
$$costo_por_minuto = floatval($_POST['costo_por_minuto']) * 60;
    // El costo_por_minuto del equipo solo es relevante para 'consola' y 'juegos'
    $costo_por_minuto = 0.00; // Valor por defecto
    if ($tipo == 'consola' || $tipo == 'juegos') {
        $costo_por_minuto = floatval($_POST['costo_por_minuto'])* 60;
    }

    $imagen_ruta_equipo = "";
    // ... Lógica de subida de imagen de equipo ...

    try {
        $sql_insert = "INSERT INTO equipos (nombre_equipo, tipo, caracteristicas_tecnicas, estado, imagen, costo_por_minuto)
                       VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql_insert)) {
            $stmt->bind_param("sssssd", $nombre_equipo, $tipo, $caracteristicas, $estado, $imagen_ruta_equipo, $costo_por_minuto);
            if ($stmt->execute()) {
                $_SESSION['feedback_message'] = "<div class='alert success'>Equipo '$nombre_equipo' agregado correctamente.</div>";
            } else {
                throw new Exception($stmt->error);
            }
            $stmt->close();
        }
    } catch (mysqli_sql_exception $e) {
        $_SESSION['feedback_message'] = "<div class='alert error'>Error al agregar equipo. Por favor, inténtelo de nuevo.</div>";
    }
    
    header("Location: administrar_cyber.php");
    exit();
}

// Resto del código (para iniciar sesión, gestionar equipos, etc.) sigue aquí...

// --- Lógica para INICIAR SESIÓN (MÁS IMPORTANTE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['iniciar_sesion'])) {
    $id_equipo = $_POST['id_equipo'];
    $cedula_cliente = $_POST['cedula_cliente'];
    $nombre_cliente_form = $_POST['nombre_cliente'];
    $telefono_cliente_form = $_POST['telefono_cliente'];
    $tipo_sesion = $_POST['tipo_sesion']; // 'Internet', 'Juegos', 'Impresion', etc.
    $duracion_estimada_minutos = intval($_POST['duracion_estimada_minutos']);

    // Obtener el tipo de equipo y costo_por_minuto del equipo
    $sql_equipo_info = "SELECT tipo, costo_por_minuto FROM equipos WHERE id_equipo = ?";
    $stmt_equipo_info = $conn->prepare($sql_equipo_info);
    $stmt_equipo_info->bind_param("i", $id_equipo);
    $stmt_equipo_info->execute();
    $result_equipo_info = $stmt_equipo_info->get_result();
    $equipo_info = $result_equipo_info->fetch_assoc();
    $stmt_equipo_info->close();

    $tipo_equipo_db = $equipo_info['tipo'];
    $costo_por_minuto_equipo_db = $equipo_info['costo_por_minuto'];

    $current_time = date('Y-m-d H:i:s');
    $precio_dolar_actual_para_sesion = $precio_dolar_bs; // El precio del dólar al momento de iniciar la sesión

    // Determinar el costo inicial y el costo_por_minuto para esta sesión
    // Determinar el costo inicial y el costo_por_minuto para esta sesión
$costo_sesion_usd = 0.00;
$costo_por_hora_sesion = 0.00; // Cambiado a por hora

if ($tipo_equipo_db == 'consola' || $tipo_equipo_db == 'juegos') {
    // Para consolas/juegos, el costo es por minuto
    $costo_por_hora_sesion = $costo_por_minuto_equipo_db * 60; // Convertir a costo por hora
    $costo_sesion_usd = 0.00; // Se acumulará con el tiempo
} elseif ($tipo_equipo_db == 'principal' || $tipo_equipo_db == 'oficina') {
    // Para computadoras, es un costo fijo de $1 USD por sesión
    $costo_sesion_usd = 1.00; 
    $costo_por_hora_sesion = 0.00; // No se cobra por hora
} elseif ($tipo_equipo_db == 'impresora') {
    // Para impresoras, el costo es por copia, no se define al inicio de sesión
    $costo_sesion_usd = 0.00; // Se actualizará cuando se registren las copias
    $costo_por_hora_sesion = 0.00; // No se cobra por hora
}
    
    // NOTA: Para las impresoras, la lógica de registro de copias y actualización del costo
    // de la sesión deberá ser un paso ADICIONAL. Por ahora, el inicio de sesión para
    // impresora simplemente registra el uso sin costo inicial.

    $sql_insert_uso = "INSERT INTO uso_equipos (id_equipo, cedula_usuario, nombre_cliente, telefono_cliente, fecha_hora_inicio, estado_equipo, tipo_sesion, costo, precio_dolar_sesion, duracion_estimada_minutos, costo_por_minuto_sesion)
                       VALUES (?, ?, ?, ?, ?, 'Ocupado', ?, ?, ?, ?, ?)";
            
    if ($stmt_insert_uso = $conn->prepare($sql_insert_uso)) {
        $stmt_insert_uso->bind_param("isssisddd", 
                                     $id_equipo, 
                                     $cedula_cliente, 
                                     $nombre_cliente_form, 
                                     $telefono_cliente_form, 
                                     $current_time, 
                                     $tipo_sesion, // Esta 's' es para tipo_sesion
                                     $costo_sesion_usd, // Esta 'd' es para costo
                                     $precio_dolar_actual_para_sesion, 
                                     $duracion_estimada_minutos,
                                     $costo_por_minuto_sesion // Asegúrate de que este campo exista en tu tabla uso_equipos
                                    );
        if ($stmt_insert_uso->execute()) {
            $id_uso_reciente = $conn->insert_id;
            
            // Actualizar el estado del equipo y la última sesión
            $sql_update_equipo = "UPDATE equipos SET estado = 'Ocupado', ultima_sesion_id = ? WHERE id_equipo = ?";
            if ($stmt_update_equipo = $conn->prepare($sql_update_equipo)) {
                $stmt_update_equipo->bind_param("ii", $id_uso_reciente, $id_equipo);
                $stmt_update_equipo->execute();
                $stmt_update_equipo->close();
            } else {
                $mensaje_feedback = "<div class='alert error'>Error de preparación (actualizar equipo): " . $conn->error . "</div>";
            }
            $_SESSION['feedback_message'] = "<div class='alert success'>Sesión iniciada correctamente en equipo ID: $id_equipo.</div>";

        } else {
            $mensaje_feedback = "<div class='alert error'>Error al iniciar sesión: " . $stmt_insert_uso->error . "</div>";
        }
        $stmt_insert_uso->close();
    } else {
        $mensaje_feedback = "<div class='alert error'>Error de preparación (iniciar sesión): " . $conn->error . "</div>";
    }
    header("Location: administrar_cyber.php");
    exit();
}


// --- Lógica para GESTIONAR USO DE EQUIPO (Iniciar/Finalizar Sesión, Cambiar Estado) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion_equipo'])) {
    $id_equipo = intval($_POST['id_equipo']);
    $accion = $_POST['accion_equipo'];
    $current_time = date('Y-m-d H:i:s');
    
    global $current_dolar_price; 
    $precio_dolar_actual_para_sesion = $current_dolar_price; 

    switch ($accion) {
        case 'iniciar_sesion':
            $cedula_cliente = $_POST['cedula_cliente_form'];
            $nombre_cliente_form = $_POST['nombre_cliente_form'];
            $telefono_cliente_form = $_POST['telefono_cliente_form'];
            $tipo_sesion = $_POST['tipo_sesion'];
            // duracion_estimada_minutos puede ser nulo o 0 si no se establece una duración
            $duracion_estimada_minutos = isset($_POST['duracion_estimada_minutos']) && $_POST['duracion_estimada_minutos'] !== '' ? intval($_POST['duracion_estimada_minutos']) : NULL;
            
            $costo_por_minuto_equipo = floatval($_POST['costo_por_minuto_equipo']); // Este viene del campo oculto

            // Insertar nueva sesión de uso con la nueva información del cliente
            $sql_insert_uso = "INSERT INTO uso_equipos (id_equipo, cedula_usuario, nombre_cliente, telefono_cliente, fecha_hora_inicio, estado_equipo, tipo_sesion, costo, precio_dolar_sesion, duracion_estimada_minutos)
                               VALUES (?, ?, ?, ?, ?, 'Ocupado', ?, 0.00, ?, ?)";
            
            if ($stmt_insert_uso = $conn->prepare($sql_insert_uso)) {
                $stmt_insert_uso->bind_param("issssdsi", // i:id_equipo, s:cedula, s:nombre, s:telefono, s:inicio, s:tipo, d:costo_dolar, i:duracion_min
                                             $id_equipo, $cedula_cliente, $nombre_cliente_form, $telefono_cliente_form, $current_time, $tipo_sesion, $precio_dolar_actual_para_sesion, $duracion_estimada_minutos);
                if ($stmt_insert_uso->execute()) {
                    $last_insert_id = $conn->insert_id; 

                    // Actualizar estado del equipo y asignar ultima_sesion_id
                    $sql_update_equipo = "UPDATE equipos SET estado = 'Ocupado', ultima_sesion_id = ? WHERE id_equipo = ?";
                    if ($stmt_update_equipo = $conn->prepare($sql_update_equipo)) {
                        $stmt_update_equipo->bind_param("ii", $last_insert_id, $id_equipo);
                        if ($stmt_update_equipo->execute()) {
                            $_SESSION['feedback_message'] = "<div class='alert success'>Sesión iniciada para el equipo ID $id_equipo con $nombre_cliente_form.</div>";
                        } else {
                            $_SESSION['feedback_message'] = "<div class='alert error'>Error al actualizar estado del equipo: " . $stmt_update_equipo->error . "</div>";
                        }
                        $stmt_update_equipo->close();
                    } else {
                        $_SESSION['feedback_message'] = "<div class='alert error'>Error de preparación (actualizar equipo iniciar): " . $conn->error . "</div>";
                    }
                } else {
                    $_SESSION['feedback_message'] = "<div class='alert error'>Error al iniciar sesión de uso: " . $stmt_insert_uso->error . "</div>";
                }
                $stmt_insert_uso->close();
            } else {
                $_SESSION['feedback_message'] = "<div class='alert error'>Error de preparación (iniciar_sesion): " . $conn->error . "</div>";
            }
            break;

            
        case 'finalizar_sesion':
    $sesion_id = intval($_POST['sesion_id_activa']);

    // Obtener información de la sesión activa
    $sql_get_sesion = "SELECT fecha_hora_inicio FROM uso_equipos WHERE id_uso = ?";
    $stmt_get_sesion = $conn->prepare($sql_get_sesion);
    $stmt_get_sesion->bind_param("i", $sesion_id);
    $stmt_get_sesion->execute();
    $result_get_sesion = $stmt_get_sesion->get_result();

    if ($row = $result_get_sesion->fetch_assoc()) {
        $current_time = new DateTime(); // Hora actual
        $inicio_sesion = new DateTime($row['fecha_hora_inicio']); // Asegúrate de que se use el formato correcto

        $tiempo_transcurrido = $current_time->diff($inicio_sesion); // Calcula la diferencia

        // Calcular el costo total basado en horas
        $tiempo_transcurrido_horas = $tiempo_transcurrido->h + ($tiempo_transcurrido->i / 60); // Convertir a horas
        $costo_por_hora_equipo = floatval($_POST['costo_por_minuto_equipo_fin']) * 60; // Convertir de costo por minuto a costo por hora
        $costo_total_usd = $tiempo_transcurrido_horas * $costo_por_hora_equipo; // Cambiar a costo por hora

        // Actualizar registro de uso
        $sql_update_uso = "UPDATE uso_equipos SET fecha_hora_fin = ?, estado_equipo = 'Finalizado', costo = ? WHERE id_uso = ?";
        if ($stmt_update_uso = $conn->prepare($sql_update_uso)) {
            $stmt_update_uso->bind_param("sdi", $current_time->format('Y-m-d H:i:s'), $costo_total_usd, $sesion_id);
            if ($stmt_update_uso->execute()) {
                // Limpiar cualquier alerta de tiempo excedido para esta sesión
                if (isset($_SESSION['expired_session_alerts'][$sesion_id])) {
                    unset($_SESSION['expired_session_alerts'][$sesion_id]);
                }

                // Actualizar estado del equipo y limpiar ultima_sesion_id
                $sql_update_equipo = "UPDATE equipos SET estado = 'Disponible', ultima_sesion_id = NULL WHERE id_equipo = (SELECT id_equipo FROM uso_equipos WHERE id_uso = ?)";
                if ($stmt_update_equipo = $conn->prepare($sql_update_equipo)) {
                    $stmt_update_equipo->bind_param("i", $sesion_id);
                    $stmt_update_equipo->execute();
                    $stmt_update_equipo->close();
                } else {
                    $_SESSION['feedback_message'] = "<div class='alert error'>Error de preparación (actualizar equipo a Disponible): " . $conn->error . "</div>";
                }

                $_SESSION['feedback_message'] = "<div class='alert success'>Sesión finalizada para equipo ID: " . $sesion_id . ". Costo: $" . number_format($costo_total_usd, 2) . ".</div>";
            } else {
                $_SESSION['feedback_message'] = "<div class='alert error'>Error al finalizar sesión de uso: " . $stmt_update_uso->error . "</div>";
            }
            $stmt_update_uso->close();
        } else {
            $_SESSION['feedback_message'] = "<div class='alert error'>Error de preparación (finalizar_sesion): " . $conn->error . "</div>";
        }
    } else {
        $_SESSION['feedback_message'] = "<div class='alert error'>No se encontró la sesión activa.</div>";
    }
    break;

        case 'cambiar_estado':
            $nuevo_estado = $_POST['nuevo_estado_equipo'];
            $sql_update_equipo = "UPDATE equipos SET estado = ? WHERE id_equipo = ?";
            if ($stmt = $conn->prepare($sql_update_equipo)) {
                $stmt->bind_param("si", $nuevo_estado, $id_equipo);
                if ($stmt->execute()) {
                    // Si el estado cambia y hay una sesión activa, finalizarla como "Interrumpida"
                    if (($nuevo_estado == 'En reparación' || $nuevo_estado == 'No disponible') && isset($_POST['current_sesion_id']) && $_POST['current_sesion_id'] !== 'null') {
                         $active_ses_id = intval($_POST['current_sesion_id']);
                         $sql_close_session = "UPDATE uso_equipos SET fecha_hora_fin = ?, estado_equipo = 'Interrumpido', costo = 0.00 WHERE id_uso = ?";
                         if ($stmt_close_ses = $conn->prepare($sql_close_session)) {
                             $stmt_close_ses->bind_param("si", $current_time, $active_ses_id);
                             $stmt_close_ses->execute();
                             $stmt_close_ses->close();
                             // Limpiar cualquier alerta de tiempo excedido para esta sesión
                             if (isset($_SESSION['expired_session_alerts'][$active_ses_id])) {
                                unset($_SESSION['expired_session_alerts'][$active_ses_id]);
                             }
                         }
                    }
                    // Siempre limpiar ultima_sesion_id del equipo si cambia de estado a no disponible/reparacion o disponible manualmente
                    $sql_clear_last_session = "UPDATE equipos SET ultima_sesion_id = NULL WHERE id_equipo = ?";
                    if ($stmt_clear = $conn->prepare($sql_clear_last_session)) {
                        $stmt_clear->bind_param("i", $id_equipo);
                        $stmt_clear->execute();
                        $stmt_clear->close();
                    }
                    $_SESSION['feedback_message'] = "<div class='alert success'>Estado del equipo ID $id_equipo actualizado a '$nuevo_estado'.</div>";
                } else {
                    $_SESSION['feedback_message'] = "<div class='alert error'>Error al cambiar estado del equipo: " . $stmt->error . "</div>";
                }
                $stmt->close();
            } else {
                $_SESSION['feedback_message'] = "<div class='alert error'>Error de preparación (cambiar_estado): " . $conn->error . "</div>";
            }
            break;
    }
    header("Location: administrar_cyber.php");
    exit();
}

// *** Recuperar el mensaje de feedback de la sesión si existe ***
if (isset($_SESSION['feedback_message'])) {
    $mensaje_feedback = $_SESSION['feedback_message'];
    unset($_SESSION['feedback_message']);
}

// --- Lógica para obtener datos de Equipos (Incluyendo datos de sesión activa si la hay) ---
$equipos = [];
$sql_equipos = "SELECT e.id_equipo, e.nombre_equipo, e.tipo, e.caracteristicas_tecnicas, e.estado, e.imagen, e.costo_por_minuto, e.ultima_sesion_id,
                        ue.id_uso AS sesion_activa_id, ue.fecha_hora_inicio, ue.tipo_sesion, ue.nombre_cliente, ue.telefono_cliente, ue.cedula_usuario, ue.duracion_estimada_minutos
                 FROM equipos e
                 LEFT JOIN uso_equipos ue ON e.ultima_sesion_id = ue.id_uso AND ue.fecha_hora_fin IS NULL 
                 ORDER BY e.id_equipo DESC"; 
$result_equipos = $conn->query($sql_equipos);

// Inicializar el array de alertas si no existe
if (!isset($_SESSION['expired_session_alerts'])) {
    $_SESSION['expired_session_alerts'] = [];
}

if ($result_equipos->num_rows > 0) {
    while($row = $result_equipos->fetch_assoc()) {
        $equipos[] = $row;

        // Lógica para verificar sesiones con tiempo excedido
        if ($row['estado'] == 'Ocupado' && !empty($row['ultima_sesion_id']) && $row['duracion_estimada_minutos'] !== NULL && $row['duracion_estimada_minutos'] > 0) {
            $inicio_sesion = new DateTime($row['fecha_hora_inicio']);
            $duracion_intervalo = new DateInterval('PT' . $row['duracion_estimada_minutos'] . 'M');
            $fin_estimado_sesion = $inicio_sesion->add($duracion_intervalo);
            $ahora = new DateTime();

            if ($ahora > $fin_estimado_sesion) {
                // Solo agregar la alerta si no existe ya para esta sesión
                if (!isset($_SESSION['expired_session_alerts'][$row['sesion_activa_id']])) {
                    $tiempo_excedido_diff = $ahora->diff($fin_estimado_sesion);
                    $_SESSION['expired_session_alerts'][$row['sesion_activa_id']] = [
                        'equipo_nombre' => $row['nombre_equipo'],
                        'cliente_nombre' => $row['nombre_cliente'],
                        'tiempo_excedido' => $tiempo_excedido_diff->format('%H:%I:%S')
                    ];
                }
            }
        }
    }
}

// --- Lógica para obtener datos de Uso de Equipos (Historial) ---
$uso_equipos = [];
$sql_uso_equipos = "
    SELECT ue.id_uso, ue.fecha_hora_inicio, ue.fecha_hora_fin, ue.estado_equipo, ue.costo, ue.tipo_sesion, ue.precio_dolar_sesion,
           ue.nombre_cliente, ue.telefono_cliente, ue.cedula_usuario, ue.duracion_estimada_minutos,
           e.nombre_equipo
    FROM uso_equipos ue
    JOIN equipos e ON ue.id_equipo = e.id_equipo
    ORDER BY ue.fecha_hora_inicio DESC
    LIMIT 10"; // Mostrar los últimos 10 usos
$result_uso_equipos = $conn->query($sql_uso_equipos);

if ($result_uso_equipos->num_rows > 0) {
    while($row = $result_uso_equipos->fetch_assoc()) {
        $uso_equipos[] = $row;
    }
}

// --- Lógica para obtener datos de Juegos (Manteniendo la estructura anterior) ---
$juegos = [];
$sql_juegos = "SELECT id_juego, nombre, descripcion, consola, id_equipo, imagen, disponibilidad FROM juegos";
$result_juegos = $conn->query($sql_juegos);

if ($result_juegos->num_rows > 0) {
    while($row = $result_juegos->fetch_assoc()) {
        $juegos[] = $row;
    }
}

// Precio del dólar actual para mostrar en la interfaz
global $current_dolar_price; 
$precio_dolar_para_mostrar = $current_dolar_price; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoRed - Administrar Cyber</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <link rel="stylesheet" href="css/administrador_ciber.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            TecnoRed
        </div>
        <ul>
            <li><a href="homeadmin.php">Home Admin</a></li>
            <li><a href="administrar_cyber.php" class="active">Administrar Cyber</a></li>
            <li><a href="javascript:void(0);" onclick="alert('Funcionalidad de Asesores por desarrollar');">Asesores</a></li>
            <li><a href="javascript:void(0);" onclick="alert('Funcionalidad de Inventario por desarrollar');">Inventario</a></li>
            <li><a href="javascript:void(0);" onclick="alert('Funcionalidad de Reporte de Finanzas por desarrollar');">Reporte de Finanzas</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="user-info">
                <?php
                    $nombre_usuario = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Administrador"; 
                    echo "Bienvenido, " . $nombre_usuario;
                ?>
            </span>
            <div class="dolar-price-container">
                <span class="dolar-price-display" id="dolarPriceDisplay">
                    USD/VES: Bs. <?php echo number_format($precio_dolar_para_mostrar, 2); ?> <i class="fas fa-edit"></i>
                </span>
                <form id="dolarPriceEditForm" class="dolar-price-edit" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="number" step="0.01" name="new_dolar_price_value" id="newDolarPriceValue" value="<?php echo htmlspecialchars(number_format($precio_dolar_para_mostrar, 2, '.', '')); ?>">
                    <button type="submit" name="update_dolar_price">Guardar</button>
                    <button type="button" id="cancelDolarEdit">Cancelar</button>
                </form>
            </div>
        </div>

        <div class="content-section">
            <h1>Gestión de Cyber</h1>

            <?php if (!empty($mensaje_feedback)): ?>
                <?php echo $mensaje_feedback; ?>
            <?php endif; ?>

            <h2>Equipos del Cyber</h2>
            <div class="card-container">
                <?php if (!empty($equipos)): ?>
                    <?php foreach ($equipos as $equipo): ?>
                        <div class="card" 
                             data-id="<?php echo htmlspecialchars($equipo['id_equipo']); ?>"
                             data-nombre="<?php echo htmlspecialchars($equipo['nombre_equipo']); ?>"
                             data-tipo="<?php echo htmlspecialchars($equipo['tipo']); ?>"
                             data-caracteristicas="<?php echo htmlspecialchars($equipo['caracteristicas_tecnicas']); ?>"
                             data-estado="<?php echo htmlspecialchars($equipo['estado']); ?>"
                             data-imagen="<?php echo htmlspecialchars($equipo['imagen']); ?>"
                             data-costo-por-minuto="<?php echo htmlspecialchars($equipo['costo_por_minuto']); ?>"
                             data-ultima-sesion-id="<?php echo htmlspecialchars($equipo['ultima_sesion_id']); ?>"
                             data-sesion-activa-id="<?php echo htmlspecialchars($equipo['sesion_activa_id'] ?? ''); ?>"
                             data-fecha-hora-inicio="<?php echo htmlspecialchars($equipo['fecha_hora_inicio'] ?? ''); ?>"
                             data-duracion-estimada-minutos="<?php echo htmlspecialchars($equipo['duracion_estimada_minutos'] ?? ''); ?>"
                             data-cliente-nombre="<?php echo htmlspecialchars($equipo['nombre_cliente'] ?? ''); ?>"
                             data-cliente-cedula="<?php echo htmlspecialchars($equipo['cedula_usuario'] ?? ''); ?>"
                             data-cliente-telefono="<?php echo htmlspecialchars($equipo['telefono_cliente'] ?? ''); ?>"
                             data-tipo-sesion="<?php echo htmlspecialchars($equipo['tipo_sesion'] ?? ''); ?>"
                             onclick="openManageEquipoModal(this)">
                            <?php if (!empty($equipo['imagen'])): ?>
                                <div class="card-image"><img src="<?php echo htmlspecialchars($equipo['imagen']); ?>" alt="<?php echo htmlspecialchars($equipo['nombre_equipo']); ?>"></div>
                            <?php else: ?>
                                <div class="card-image">No hay imagen</div>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h3>
                            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($equipo['tipo']); ?></p>
                            <p><strong>Características:</strong> <?php echo nl2br(htmlspecialchars($equipo['caracteristicas_tecnicas'])); ?></p>
                            <span class="status <?php echo str_replace(' ', '-', htmlspecialchars($equipo['estado'])); ?>">
                                <?php echo htmlspecialchars($equipo['estado']); ?>
                            </span>

                            <?php if ($equipo['estado'] == 'Ocupado' && $equipo['ultima_sesion_id']): ?>
                                <div class="card-timer" 
                                     id="timer-card-<?php echo htmlspecialchars($equipo['id_equipo']); ?>"
                                     data-equipo-id="<?php echo htmlspecialchars($equipo['id_equipo']); ?>"
                                     data-start-time="<?php echo htmlspecialchars($equipo['fecha_hora_inicio']); ?>"
                                     data-cost-per-minute="<?php echo htmlspecialchars($equipo['costo_por_minuto']); ?>"
                                     data-duration-minutes="<?php echo htmlspecialchars($equipo['duracion_estimada_minutos'] ?? ''); ?>">
                                    <i class="far fa-clock"></i> <span class="time-display">00:00:00</span>
                                    <br>
                                    <span class="cost-display-small">$0.00 (Bs. 0.00)</span>
                                </div>
                                <div class="card-client-info">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($equipo['nombre_cliente']); ?><br>
                                    <i class="fas fa-id-card"></i> <?php echo htmlspecialchars($equipo['cedula_usuario']); ?><br>
                                    <i class="fas fa-phone"></i> <?php echo htmlspecialchars($equipo['telefono_cliente']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay equipos registrados en la base de datos.</p>
                <?php endif; ?>
            </div>

            <h2>Últimos Usos de Equipos</h2>
            <?php if (!empty($uso_equipos)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Equipo</th>
                            <th>Cliente</th>
                            <th>Teléfono</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Duración Estimada</th>
                            <th>Costo ($)</th>
                            <th>Costo (Bs)</th>
                            <th>Tipo Sesión</th>
                            <th>Estado Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($uso_equipos as $uso): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($uso['nombre_equipo']); ?></td>
                                <td><?php echo htmlspecialchars($uso['nombre_cliente']); ?> (CI: <?php echo htmlspecialchars($uso['cedula_usuario']); ?>)</td>
                                <td><?php echo htmlspecialchars($uso['telefono_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($uso['fecha_hora_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($uso['fecha_hora_fin'] ?: 'En curso'); ?></td>
                                <td><?php echo ($uso['duracion_estimada_minutos'] !== NULL ? htmlspecialchars($uso['duracion_estimada_minutos']) . ' min' : 'Ilimitado'); ?></td>
                                <td><?php echo htmlspecialchars(number_format($uso['costo'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($uso['costo'] * $uso['precio_dolar_sesion'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($uso['tipo_sesion']); ?></td>
                                <td><?php echo htmlspecialchars($uso['estado_equipo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay registros de uso de equipos.</p>
            <?php endif; ?>

            <h2>Juegos Disponibles</h2>
            <div class="card-container">
                <?php if (!empty($juegos)): ?>
                    <?php foreach ($juegos as $juego): ?>
                        <div class="card">
                            <?php if (!empty($juego['imagen'])): ?>
                                <div class="card-image"><img src="<?php echo htmlspecialchars($juego['imagen']); ?>" alt="<?php echo htmlspecialchars($juego['nombre']); ?>"></div>
                            <?php else: ?>
                                <div class="card-image">No hay imagen</div>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($juego['nombre']); ?></h3>
                            <p><strong>Consola:</strong> <?php echo htmlspecialchars($juego['consola']); ?></p>
                            <p><strong>Descripción:</strong> <?php echo nl2br(htmlspecialchars($juego['descripcion'])); ?></p>
                            <span class="status <?php echo str_replace(' ', '-', htmlspecialchars($juego['disponibilidad'])); ?>">
                                <?php echo htmlspecialchars($juego['disponibilidad']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay juegos registrados en la base de datos.</p>
                <?php endif; ?>
            </div>

        </div>

        <div class="add-button" id="addEquipoBtn">
            <i class="fas fa-plus"></i>
        </div>

        <div id="addEquipoModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal('addEquipoModal')">&times;</span>
                <h2>Agregar Nuevo Equipo</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                    <div>
                        <label for="nombre_equipo">Nombre del Equipo:</label>
                        <input type="text" id="nombre_equipo" name="nombre_equipo" required>
                    </div>
                    <div>
                        <label for="tipo">Tipo de Equipo:</label>
                        <select id="tipo" name="tipo" required>
                            <option value="principal">Principal</option>
                            <option value="oficina">Oficina</option>
                            <option value="juegos">Juegos</option>
                            <option value="consola">Consola</option>
                            <option value="impresora">Impresora</option>
                        </select>
                    </div>
                    <div style="grid-column: span 2;">
                        <label for="caracteristicas_tecnicas">Características Técnicas:</label>
                        <textarea id="caracteristicas_tecnicas" name="caracteristicas_tecnicas" rows="4"></textarea>
                    </div>
                    <div>
                        <label for="estado">Estado Inicial:</label>
                        <select id="estado" name="estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Ocupado">Ocupado</option>
                            <option value="En reparación">En reparación</option>
                            <option value="No disponible">No disponible</option>
                        </select>
                    </div>
                    <div>
                        <label for="costo_por_minuto">Costo por hora ($):</label>
                        <input type="number" step="0.0001" id="costo_por_minuto" name="costo_por_minuto" value="0.00">
                    </div>
                    <div>
                        <label for="imagen_equipo">Seleccionar Imagen:</label>
                        <input type="file" id="imagen_equipo" name="imagen_equipo" accept="image/*">
                    </div>
                    <button type="submit" name="agregar_equipo">Agregar Equipo</button>
                </form>
            </div>
        </div>

        <div id="manageEquipoModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="closeModal('manageEquipoModal')">&times;</span>
                <h2>Gestión de Equipo: <span id="manageEquipoNombre"></span></h2>
                <div class="equipo-details">
                    <p>ID: <strong><span id="manageEquipoId"></span></strong></p>
                    <p>Tipo: <strong><span id="manageEquipoTipo"></span></strong></p>
                    <p>Estado Actual: <strong id="manageEquipoEstado"></strong></p>
                    <p>Costo por Hora: <strong>$<span id="manageEquipoCostoMinuto"></span></strong></p>
                </div>

                <div class="action-section">
                    <h3>Estado de Sesión</h3>
                    <div id="sesionActiveSection" style="display: none;">
                        <p>Cliente: <strong><span id="sesionClienteNombre"></span></strong></p>
                        <p>Cédula: <strong><span id="sesionClienteCedula"></span></strong></p>
                        <p>Teléfono: <strong><span id="sesionClienteTelefono"></span></strong></p>
                        <p>Tipo de Sesión: <strong><span id="sesionTipoActual"></span></strong></p>
                        <p>Duración Estimada: <strong><span id="sesionDuracionEstimada"></span></strong></p>

                        <div class="timer-display" id="sessionTimerModal">00:00:00</div>
                        <div class="cost-display">
                            Costo Actual: <span id="sessionCostUsdModal">$0.00</span> (<span id="sessionCostBsModal">Bs. 0.00</span>)
                            <p style="font-size: 0.8em; color: #666;">Precio Dólar (actual): Bs. <span id="currentDolarPriceInModal"><?php echo number_format($precio_dolar_para_mostrar, 2); ?></span></p>
                        </div>
                        <div class="action-buttons">
                            <button class="stop-button" onclick="finalizarSesion()">Finalizar Sesión</button>
                        </div>
                    </div>

                    <form id="iniciarSesionForm" class="iniciar-sesion-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: none;">
                        <h3>Iniciar Nueva Sesión</h3>
                        <input type="hidden" name="accion_equipo" value="iniciar_sesion">
                        <input type="hidden" name="id_equipo" id="iniciarIdEquipo">
                        <input type="hidden" name="costo_por_minuto_equipo" id="iniciarCostoPorMinutoEquipo">
                        
                        <label for="cedula_cliente_form">Cédula del Cliente:</label>
                        <input type="text" id="cedula_cliente_form" name="cedula_cliente_form" placeholder="V-12345678" required>

                        <label for="nombre_cliente_form">Nombre del Cliente:</label>
                        <input type="text" id="nombre_cliente_form" name="nombre_cliente_form" placeholder="Nombre Apellido" required>

                        <label for="telefono_cliente_form">Teléfono del Cliente:</label>
                        <input type="text" id="telefono_cliente_form" name="telefono_cliente_form" placeholder="04XX-XXXXXXX" pattern="[0-9]{4}-[0-9]{7}" title="Formato: 04XX-XXXXXXX" required>

                        <label for="tipo_sesion">Tipo de Sesión:</label>
                        <select id="tipo_sesion" name="tipo_sesion" required>
                            <option value="Internet">Internet</option>
                            <option value="Juegos">Juegos</option>
                            <option value="Oficina">Oficina</option>
                        </select>

                        <label for="duracion_estimada_minutos_form">Duración Estimada (minutos, 0 para ilimitado):</label>
                        <input type="number" id="duracion_estimada_minutos_form" name="duracion_estimada_minutos" min="0" value="0">

                        <button type="submit">Iniciar Sesión</button>
                    </form>

                    <form id="cambiarEstadoForm" class="estado-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <h3>Cambiar Estado del Equipo</h3>
                        <input type="hidden" name="accion_equipo" value="cambiar_estado">
                        <input type="hidden" name="id_equipo" id="cambiarEstadoIdEquipo">
                        <input type="hidden" name="current_sesion_id" id="currentSesionIdForEstadoChange"> <label for="nuevo_estado_equipo">Nuevo Estado:</label>
                        <select id="nuevo_estado_equipo" name="nuevo_estado_equipo" required>
                            <option value="Disponible">Disponible</option>
                            <option value="En reparación">En reparación</option>
                            <option value="No disponible">No disponible</option>
                        </select>
                        <button type="submit">Guardar Estado</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales para el cálculo de costos
        const precioDolarBs = <?php echo $precio_dolar_para_mostrar; ?>; // Precio del dólar actual
        let modalTimerInterval; // Intervalo para el cronómetro del modal

        // Objeto para mantener los cronómetros de las tarjetas
        let cardTimers = {}; // { equipoId: { startTime: Date, durationMinutes: int, costPerMinute: float, elements: { timerDisplay, costSmall } } }

        // Función para abrir cualquier modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "flex";
        }

        // Función para cerrar cualquier modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
            if (modalId === 'manageEquipoModal') {
                clearInterval(modalTimerInterval); // Detener el cronómetro del modal
            }
        }

        // --- Lógica del Modal "Agregar Equipo" ---
        var addEquipoModal = document.getElementById("addEquipoModal");
        var addEquipoBtn = document.getElementById("addEquipoBtn");

        addEquipoBtn.onclick = function() {
            openModal('addEquipoModal');
        }

        // --- Lógica para el precio del dólar editable ---
        const dolarPriceDisplay = document.getElementById('dolarPriceDisplay');
        const dolarPriceEditForm = document.getElementById('dolarPriceEditForm');
        const newDolarPriceValueInput = document.getElementById('newDolarPriceValue');
        const cancelDolarEditButton = document.getElementById('cancelDolarEdit');

        dolarPriceDisplay.onclick = function() {
            dolarPriceDisplay.style.display = 'none';
            dolarPriceEditForm.style.display = 'flex';
            newDolarPriceValueInput.focus();
        };

        cancelDolarEditButton.onclick = function() {
            dolarPriceDisplay.style.display = 'block';
            dolarPriceEditForm.style.display = 'none';
            newDolarPriceValueInput.value = precioDolarBs.toFixed(2);
        };

        <?php if (!empty($mensaje_feedback) && strpos($mensaje_feedback, 'dólar') !== false): ?>
            dolarPriceDisplay.style.display = 'none';
            dolarPriceEditForm.style.display = 'flex';
        <?php endif; ?>

        // --- Funciones de cronómetro y cálculo de costo ---
      function formatTime(seconds) {
    const absSeconds = Math.abs(seconds);
    const hours = Math.floor(absSeconds / 3600);
    const minutes = Math.floor((absSeconds % 3600) / 60);
    const secs = absSeconds % 60;

    return String(hours).padStart(2, '0') + ':' +
           String(minutes).padStart(2, '0') + ':' +
           String(secs).padStart(2, '0');
}

        function calculateCost(elapsedMinutes, costPerMinute) {
            if (elapsedMinutes < 0) return 0; // No cobrar por tiempo negativo (antes de iniciar)
            return elapsedMinutes * costPerMinute;
        }

        function updateTimerAndCost(data, timerElement, costUsdElement, costBsElement, isModal = false) {
    const now = new Date();
    const startTime = data.startTime;
    const durationMinutes = data.durationMinutes; // Duración total en minutos
    const costPerMinute = data.costPerMinute;

    // Total en segundos
    const totalDurationSeconds = durationMinutes * 60;

    // Calcula el tiempo transcurrido
    const elapsedMilliseconds = now - startTime;
    const elapsedSeconds = Math.floor(elapsedMilliseconds / 1000);
    
    // Calcula el tiempo restante
    const remainingSeconds = totalDurationSeconds - elapsedSeconds;

    // Si el tiempo restante es menor que 0, se establece en 0
    const displayTime = remainingSeconds >= 0 ? remainingSeconds : 0;

    // Actualizar display de tiempo
    timerElement.textContent = formatTime(displayTime);

    // Lógica de costo
    const totalCostUsd = calculateCost(elapsedSeconds / 60, costPerMinute);
    const totalCostBs = totalCostUsd * precioDolarBs;

    costUsdElement.textContent = `$${totalCostUsd.toFixed(2)}`;
    costBsElement.textContent = `Bs. ${totalCostBs.toFixed(2)}`;
}
        // --- Lógica para los cronómetros en las tarjetas ---
        function initCardTimers() {
            const timerCards = document.querySelectorAll('.card-timer');
            timerCards.forEach(cardTimerElement => {
                const equipoId = cardTimerElement.dataset.equipoId;
                const startTimeStr = cardTimerElement.dataset.startTime;
                const durationMinutesStr = cardTimerElement.dataset.durationMinutes;
                const costPerMinuteStr = cardTimerElement.dataset.costPerMinute;

                // Si startTime es inválido (ej. '0000-00-00 00:00:00'), salta
                if (!startTimeStr || startTimeStr === '0000-00-00 00:00:00') {
                    console.warn(`Invalid startTime for equipoId ${equipoId}: ${startTimeStr}`);
                    cardTimerElement.style.display = 'none'; // Ocultar si la data es inconsistente
                    return;
                }
                
                const startTime = new Date(startTimeStr.replace(/-/g, '/'));
                const durationMinutes = durationMinutesStr ? parseInt(durationMinutesStr, 10) : null;
                const costPerMinute = parseFloat(costPerMinuteStr);

                cardTimers[equipoId] = {
                    startTime: startTime,
                    durationMinutes: durationMinutes,
                    costPerMinute: costPerMinute,
                    timerElement: cardTimerElement.querySelector('.time-display'),
                    costUsdElement: cardTimerElement.querySelector('.cost-display-small'),
                    costBsElement: cardTimerElement.querySelector('.cost-display-small') // Usar el mismo para ambos
                };
            });

            // Iniciar el intervalo para actualizar todos los cronómetros de las tarjetas
            setInterval(updateAllCardTimers, 1000);
            updateAllCardTimers(); // Llamar una vez al inicio para evitar retraso inicial
        }

        function updateAllCardTimers() {
            for (const equipoId in cardTimers) {
                if (cardTimers.hasOwnProperty(equipoId)) {
                    const data = cardTimers[equipoId];
                    updateTimerAndCost(data, data.timerElement, data.costUsdElement, data.costBsElement);
                }
            }
        }

        // Llama a esto cuando el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', initCardTimers);


        // --- Lógica del Modal "Gestionar Equipo" ---
        let currentEquipoData = {}; // Guarda los datos del equipo y sesión para el modal
        let currentModalCostPerMinute;
        let currentModalSesionId;

        function openManageEquipoModal(card) {
            // Cargar todos los datos desde los data-attributes de la tarjeta
            currentEquipoData = {
                id_equipo: card.dataset.id,
                nombre: card.dataset.nombre,
                tipo: card.dataset.tipo,
                estado: card.dataset.estado,
                costoPorMinuto: parseFloat(card.dataset.costoPorMinuto),
                ultimaSesionId: card.dataset.ultimaSesionId,
                sesionActivaId: card.dataset.sesionActivaId, // El ID de uso de la sesión activa
                fechaHoraInicio: card.dataset.fechaHoraInicio,
                duracionEstimadaMinutos: card.dataset.duracionEstimadaMinutos ? parseInt(card.dataset.duracionEstimadaMinutos, 10) : null,
                clienteNombre: card.dataset.clienteNombre,
                clienteCedula: card.dataset.clienteCedula,
                clienteTelefono: card.dataset.clienteTelefono,
                tipoSesion: card.dataset.tipoSesion
            };

            // Actualizar el contenido del modal
            document.getElementById('manageEquipoNombre').textContent = currentEquipoData.nombre;
            document.getElementById('manageEquipoId').textContent = currentEquipoData.id_equipo;
            document.getElementById('manageEquipoTipo').textContent = currentEquipoData.tipo;
            document.getElementById('manageEquipoEstado').textContent = currentEquipoData.estado;
            document.getElementById('manageEquipoCostoMinuto').textContent = currentEquipoData.costoPorMinuto.toFixed(4);
            document.getElementById('currentDolarPriceInModal').textContent = precioDolarBs.toFixed(2);

            // Actualizar campos ocultos en los formularios del modal
            document.getElementById('iniciarIdEquipo').value = currentEquipoData.id_equipo;
            document.getElementById('iniciarCostoPorMinutoEquipo').value = currentEquipoData.costoPorMinuto;
            document.getElementById('cambiarEstadoIdEquipo').value = currentEquipoData.id_equipo;
            document.getElementById('currentSesionIdForEstadoChange').value = currentEquipoData.sesionActivaId; // Para pasar ID de sesión al cambiar estado

            // Mostrar/Ocultar secciones según el estado del equipo
            const iniciarSesionForm = document.getElementById('iniciarSesionForm');
            const sesionActiveSection = document.getElementById('sesionActiveSection');
            const cambiarEstadoForm = document.getElementById('cambiarEstadoForm');

            clearInterval(modalTimerInterval); // Asegurarse de que no haya un cronómetro del modal corriendo

            if (currentEquipoData.estado === 'Ocupado' && currentEquipoData.sesionActivaId && currentEquipoData.sesionActivaId !== 'null') { 
                // Equipo está Ocupado y tiene un ID de sesión válido
                iniciarSesionForm.style.display = 'none';
                // El formulario de cambiar estado es visible incluso si está ocupado
                // cambiarEstadoForm.style.display = 'none'; // Se deja visible para permitir cambio a "En reparación", etc.
                sesionActiveSection.style.display = 'block';

                // Cargar detalles de la sesión activa en el modal
                document.getElementById('sesionClienteNombre').textContent = currentEquipoData.clienteNombre;
                document.getElementById('sesionClienteCedula').textContent = currentEquipoData.clienteCedula;
                document.getElementById('sesionClienteTelefono').textContent = currentEquipoData.clienteTelefono;
                document.getElementById('sesionTipoActual').textContent = currentEquipoData.tipoSesion;
                document.getElementById('sesionDuracionEstimada').textContent = currentEquipoData.duracionEstimadaMinutos !== null && currentEquipoData.duracionEstimadaMinutos > 0 ? `${currentEquipoData.duracionEstimadaMinutos} minutos` : 'Ilimitado';

                currentModalSesionId = currentEquipoData.sesionActivaId;
                currentModalCostPerMinute = currentEquipoData.costoPorMinuto; // Usar el costo por minuto del equipo

                // Iniciar o reanudar el cronómetro del modal
             const modalTimerData = {
    startTime: new Date(currentEquipoData.fechaHoraInicio.replace(/-/g, '/')),
    durationMinutes: currentEquipoData.duracionEstimadaMinutos,
    costPerMinute: currentEquipoData.costoPorMinuto
};

// Inicia el intervalo para actualizar el cronómetro
modalTimerInterval = setInterval(() => {
    updateTimerAndCost(modalTimerData, document.getElementById('sessionTimerModal'), document.getElementById('sessionCostUsdModal'), document.getElementById('sessionCostBsModal'), true);
}, 1000);
                // Llamar una vez inmediatamente para actualizar el display
                updateTimerAndCost(
                    modalTimerData, 
                    document.getElementById('sessionTimerModal'), 
                    document.getElementById('sessionCostUsdModal'), 
                    document.getElementById('sessionCostBsModal'),
                    true // Es el modal
                );

            } else { 
                // Equipo Disponible, En reparación, No disponible, o Ocupado sin sesion_id válido
                sesionActiveSection.style.display = 'none';
                iniciarSesionForm.style.display = 'block'; // Mostrar formulario para iniciar sesión
                cambiarEstadoForm.style.display = 'block'; // Mostrar formulario para cambiar estado
                document.getElementById('nuevo_estado_equipo').value = currentEquipoData.estado; // Establecer el estado actual
                
                // Limpiar campos del formulario de inicio de sesión
                document.getElementById('cedula_cliente_form').value = '';
                document.getElementById('nombre_cliente_form').value = '';
                document.getElementById('telefono_cliente_form').value = '';
                document.getElementById('tipo_sesion').value = 'Internet';
                document.getElementById('duracion_estimada_minutos_form').value = '0';
            }

            openModal('manageEquipoModal');
        }

        function finalizarSesion() {
            const confirmar = confirm("¿Estás seguro de que quieres finalizar esta sesión?");
            if (confirmar) {
                clearInterval(modalTimerInterval); // Detener el cronómetro del modal

                const now = new Date();
                const startTimeModal = new Date(currentEquipoData.fechaHoraInicio.replace(/-/g, '/'));
                const elapsedMilliseconds = now - startTimeModal;
                const tiempoTranscurridoMinutos = elapsedMilliseconds / (1000 * 60);

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>';

                const inputAccion = document.createElement('input');
                inputAccion.type = 'hidden';
                inputAccion.name = 'accion_equipo';
                inputAccion.value = 'finalizar_sesion';
                form.appendChild(inputAccion);

                const inputIdEquipo = document.createElement('input');
                inputIdEquipo.type = 'hidden';
                inputIdEquipo.name = 'id_equipo';
                inputIdEquipo.value = currentEquipoData.id_equipo;
                form.appendChild(inputIdEquipo);

                const inputSesionId = document.createElement('input');
                inputSesionId.type = 'hidden';
                inputSesionId.name = 'sesion_id_activa';
                inputSesionId.value = currentModalSesionId;
                form.appendChild(inputSesionId);

                const inputTiempoTranscurrido = document.createElement('input');
                inputTiempoTranscurrido.type = 'hidden';
                inputTiempoTranscurrido.name = 'tiempo_transcurrido_minutos';
                inputTiempoTranscurrido.value = tiempoTranscurridoMinutos;
                form.appendChild(inputTiempoTranscurrido);

                const inputCostoPorMinutoFin = document.createElement('input');
                inputCostoPorMinutoFin.type = 'hidden';
                inputCostoPorMinutoFin.name = 'costo_por_minuto_equipo_fin';
                inputCostoPorMinutoFin.value = currentModalCostPerMinute;
                form.appendChild(inputCostoPorMinutoFin);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            if (event.target == addEquipoModal) {
                closeModal('addEquipoModal');
            }
            if (event.target == manageEquipoModal) {
                closeModal('manageEquipoModal');
            }
        }

        // Si hay un mensaje de feedback, muéstralo y luego oculta el modal de agregar si fue un error
        <?php if (!empty($mensaje_feedback) && strpos($mensaje_feedback, 'error') !== false && isset($_POST['agregar_equipo'])): ?>
            openModal('addEquipoModal');
        <?php endif; ?>
    </script>
</body>
</html>
<?php
$conn->close();
?>