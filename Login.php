<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Tecnored</title>
</head>
<body>
    <h2>Login Tecnored</h2>

    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form method="POST" action="login.php">
        <label for="cedula">Cédula:</label><br>
        <input type="text" name="cedula" required><br><br>

        <label for="clave">Contraseña:</label><br>
        <input type="password" name="clave" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>
</body>

</html>
<?php
session_start();

// Configuración base de datos
$host = "localhost";
$user = "root";
$password = "";
$db = "tecnored";

// Crear conexión
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula']);
    $clave = trim($_POST['clave']);

    if (empty($cedula) || empty($clave)) {
        $_SESSION['error'] = "Por favor, completa todos los campos.";
        header("Location: login.html");
        exit();
    }

    $stmt = $conn->prepare("SELECT cedula, nombre, apellido, nivel, clave FROM usuarios WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña (hashed)
        if (password_verify($clave, $usuario['clave'])) {
            $_SESSION['cedula'] = $usuario['cedula'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido'] = $usuario['apellido'];
            $_SESSION['nivel'] = $usuario['nivel'];

            // Redireccionar según nivel
            switch ($usuario['nivel']) {
                case 1:
                    header("Location: admin/dashboard.php");
                    break;
                case 2:
                    header("Location: asesor/dashboard.php");
                    break;
                case 3:
                    header("Location: usuario/dashboard.php");
                    break;
                default:
                    $_SESSION['error'] = "Nivel de usuario no reconocido.";
                    header("Location: login.html");
            }
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: login.html");
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado.";
        header("Location: login.html");
    }

    $stmt->close();
}

$conn->close();
?>
