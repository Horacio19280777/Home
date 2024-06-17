<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
        function validateForm() {
            var usuario = document.forms["loginForm"]["login"].value;
            var contraseña = document.forms["loginForm"]["pwd"].value;
            if (usuario == "" || pwd == "") {
                alert("Todos los campos son obligatorios.");
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
          <a class="nav-link" href="registro.php">Registrarse</a>
        </li>
      </ul>
    </div>
  </nav>
    <h2>Formulario de Login</h2>
    <form name="loginForm" action="login.php" onsubmit="return validateForm()" method="post">
        <label for="login">Login:</label>
        <input type="text" name="login" required><br>
        <label for="pwd">Pwd:</label>
        <input type="pwd" name="pwd" required><br>
        <input type="submit" value="Ingresar">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'conexion.php';
        $login = $conn->real_escape_string($_POST['login']);
        $pwd = $_POST['pwd'];
        if (empty($login) || empty($pwd)) {
            echo "Todos los campos son obligatorios.";
        } else {
            $sql = "SELECT * FROM usuarios WHERE login='$login'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($pwd == $row['pwd']) {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['nombres'] = $row['nombres'];
                    $_SESSION['apellidos'] = $row['apellidos'];
                    $_SESSION['rol'] = $row['rol']; // Guarda el rol en la sesión
                    echo "Login exitoso!";
                    header("Location: home.php");
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "Usuario no encontrado.";
            }
        }
        $conn->close();
    }    
    ?>
</body>
</html>