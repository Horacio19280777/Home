<!DOCTYPE html>
<html>
<head>
    <title>Registro de ususarios</title>
    <script>
        function validateForm() {
            var usuario = document.forms["registerForm"]["usuario"].value;
            var contraseña = document.forms["registerForm"]["contraseña"].value;
            var confirmContraseña = document.forms["registerForm"]["confirm_contraseña"].value;
            if (usuario == "" || contraseña == "" || confirmContraseña == "") {
                alert("Todos los campos son obligatorios.");
                return false;
            }
            if (contraseña != confirmContraseña) {
                alert("Las contraseñas no coinciden.");
                return false;
            }
            if (contraseña.length < 6) {
                alert("La contraseña debe tener al menos 6 caracteres.");
                return false;
            }
            return true;
        }
    </script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/diseño.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="../index.html">Home</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="login.php">Iniciar sesión</a>
        </li>
      </ul>
    </div>
  </nav>
    <h2>Formulario de Registro</h2>
    <form name="registerForm" action="registro.php" onsubmit="return validateForm()" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required><br>
        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required><br>
        <label for="confirm_contraseña">Confirmar Contraseña:</label>
        <input type="password" name="confirm_contraseña" required><br>
        <input type="submit" value="Registrar">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'conexion.php';
        $usuario = $conn->real_escape_string($_POST['usuario']);
        $contraseña = $_POST['contraseña'];
        $confirm_contraseña = $_POST['confirm_contraseña'];
        if (empty($usuario) || empty($contraseña) || empty($confirm_contraseña)) {
            echo "Todos los campos son obligatorios.";
        } elseif ($contraseña != $confirm_contraseña) {
            echo "Las contraseñas no coinciden.";
        } elseif (strlen($contraseña) < 6) {
            echo "La contraseña debe tener al menos 6 caracteres.";
        } else {
            $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "El nombre de usuario ya está en uso.";
            } else {
                $sql = "INSERT INTO usuarios (usuario, contraseña,puesto) VALUES ('$usuario', '$contraseña','Cliente')";

                if ($conn->query($sql) === TRUE) {
                    echo "Registro exitoso!";
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['usuario'] = $usuario;
                    header("Location: proteccion.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
        $conn->close();
    }
    ?>
</body>
</html>
