<?php
$host = "localhost";
$db = "tecnored";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>



<?php


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $clave = password_hash($_POST["clave"], PASSWORD_DEFAULT);
    $tipo = $_POST["tipo"];

    if (!empty($nombre) && !empty($correo) && !empty($_POST["clave"])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, clave, tipo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $correo, $clave, $tipo]);
            $mensaje = "✅ Usuario registrado correctamente.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensaje = "⚠️ Este correo ya está registrado.";
            } else {
                $mensaje = "❌ Error: " . $e->getMessage();
            }
        }
    } else {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        form {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mensaje {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<form method="POST" action="">
    <h2>Registro de Usuario</h2>

    <label for="nombre">Nombre completo:</label>
    <input type="text" name="nombre" required>

    <label for="correo">Correo electrónico:</label>
    <input type="email" name="correo" required>

    <label for="clave">Contraseña:</label>
    <input type="password" name="clave" required>

    <label for="tipo">Tipo de usuario:</label>
    <select name="tipo" required>
        <option value="">Seleccionar...</option>
        <option value="usuario">Usuario</option>
        <option value="profesor">Profesor</option>
    </select>

    <button type="submit">Registrar</button>

    <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
</form>

<?php include 'footer.php'; ?>

</body>
</html>
