<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../LOGIN/login.php");
    exit;
}

include '../LOGIN/conexion.php';

$usuario = $_SESSION['usuario'];
$correo = $pwd = $confirmar_contraseña = "";
$correo_err = $pwd_err = $confirmar_contraseña_err = "";

// Obtener los datos actuales del usuario
$sql = "SELECT usuario, correo, pwd FROM usuarios WHERE usuario = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_actual, $correo_actual, $pwd_actual);
    if ($stmt->fetch()) {
        // Datos obtenidos correctamente
    } else {
        // Manejar el caso en que no se obtuvieron datos
        echo "<div class='alert alert-danger mt-3'>No se encontraron datos para el usuario especificado.</div>";
    }
    $stmt->close();
} else {
    // Manejar error en la preparación de la declaración
    echo "<div class='alert alert-danger mt-3'>Error en la preparación de la declaración: " . htmlspecialchars($conn->error) . "</div>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor, ingrese un correo.";
    } elseif (!filter_var(trim($_POST["correo"]), FILTER_VALIDATE_EMAIL)) {
        $correo_err = "Por favor, ingrese un correo válido.";
    } else {
        $correo = trim($_POST["correo"]);
    }

    // Validar contraseña
    if (!empty(trim($_POST["pwd"])) && strlen(trim($_POST["pwd"])) < 6) {
        $pwd_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $pwd = trim($_POST["pwd"]);
    }

    // Validar confirmar contraseña
    if (!empty(trim($_POST["pwd"])) && empty(trim($_POST["confirmar_contraseña"]))) {
        $confirmar_contraseña_err = "Por favor, confirme la contraseña.";
    } else {
        $confirmar_contraseña = trim($_POST["confirmar_contraseña"]);
        if (!empty($pwd) && ($pwd != $confirmar_contraseña)) {
            $confirmar_contraseña_err = "Las contraseñas no coinciden.";
        }
    }

    // Comprobar errores de entrada antes de actualizar la base de datos
    if (empty($correo_err) && empty($pwd_err) && empty($confirmar_contraseña_err)) {
        if (!empty($pwd)) {
            $pwd_hashed = password_hash($pwd, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET correo = ?, pwd = ? WHERE usuario = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sss", $correo, $pwd_hashed, $usuario);
            }
        } else {
            $sql = "UPDATE usuarios SET correo = ? WHERE usuario = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $correo, $usuario);
            }
        }

        if ($stmt->execute()) {
            echo "<div class='alert alert-success mt-3'>Datos actualizados correctamente.</div>";
            // Actualizar los valores mostrados
            $correo_actual = $correo;
            if (!empty($pwd)) {
                $pwd_actual = $pwd_hashed;
            }
        } else {
            echo "<div class='alert alert-danger mt-3'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</div>";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Personales</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#togglePassword').click(function() {
                const passwordField = $('#pwd');
                const passwordFieldType = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', passwordFieldType);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
            $('#toggleConfirmPassword').click(function() {
                const confirmPasswordField = $('#confirmar_contraseña');
                const confirmPasswordFieldType = confirmPasswordField.attr('type') === 'password' ? 'text' : 'password';
                confirmPasswordField.attr('type', confirmPasswordFieldType);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });
        });
    </script>
    <style>
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="fas fa-home" href="#">Programación Web</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto custom-margin">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"] ?? ''); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="home.php">Carros</a>
                        <a class="dropdown-item" href="datosPersonales.php">Datos personales</a>
                        <a class="dropdown-item" href="../LOGIN/cerrar.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Actualizar Datos Personales</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="usuario" class="form-control" value="<?php echo htmlspecialchars($usuario_actual); ?>" readonly>
            </div>
            <div class="form-group <?php echo (!empty($correo_err)) ? 'has-error' : ''; ?>">
                <label>Correo</label>
                <input type="text" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo_actual); ?>">
                <span class="help-block"><?php echo $correo_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($pwd_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <div class="input-group">
                    <input type="password" id="pwd" name="pwd" class="form-control" value="">
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
                <span class="help-block"><?php echo $pwd_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirmar_contraseña_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" class="form-control" value="">
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggleConfirmPassword"><i class="fa fa-eye"></i></span>
                    </div>
                </div>
                <span class="help-block"><?php echo $confirmar_contraseña_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Guardar">
            </div>
        </form>
    </div>
</body>
</html
