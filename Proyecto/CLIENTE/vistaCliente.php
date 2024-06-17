<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../LOGIN/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Carros</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .table-custom {
            background-color: #f8f9fa;
        }
        .table-custom thead {
            background-color: #343a40;
            color: white;
        }
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
        }
        .detalle-emergente {
            position: fixed;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
            max-width: 300px;
            z-index: 1000;
            display: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .detalle-carro img {
            float: left;
            margin-right: 10px;
        }
        .custom-margin {
            margin-right: 50px; 
        }
        @media (min-width: 992px) {
            .detalle-emergente {
                position: relative;
                transform: none;
                max-width: none;
                width: auto;
                box-shadow: none;
                border: none;
            }
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
                        Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="vistaCliente.php">Carros</a>
                        <a class="dropdown-item" href="datosPersonales.php">Datos personales</a>
                        <a class="dropdown-item" href="../LOGIN/cerrar.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 style="text-align: center; color:brown;">Vista de Carros</h2>

        <?php
            include '../LOGIN/conexion.php'; // Ruta de conexión


            $query = "SELECT id, UPPER(nombre) AS nombre, UPPER(marca) AS marca, UPPER(modelo) AS modelo, año, precio, detalles FROM carros";
            $result = $conn->query($query);

            if ($result) {
                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered table-custom">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Nombre</th>';
                    echo '<th>Marca</th>';
                    echo '<th>Modelo</th>';
                    echo '<th>Año</th>';
                    echo '<th>Precio</th>';
                    echo '<th>Detalle</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    while ($fila = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($fila['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($fila['marca']) . '</td>';
                        echo '<td>' . htmlspecialchars($fila['modelo']) . '</td>';
                        echo '<td>' . htmlspecialchars($fila['año']) . '</td>';
                        echo '<td>' . htmlspecialchars($fila['precio']) . '</td>';
                        echo '<td><button type="button" class="btn btn-info ver-detalle" data-detalle="' . htmlspecialchars($fila['detalles']) . '">
                                <i class="fas fa-info-circle"></i>
                              </button></td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="alert alert-info">No se encontraron resultados.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Error en la consulta: ' . $conn->error . '</div>';
            }
            $conn->close();
        ?>

        <div id="detalleEmergente" class="detalle-emergente"></div>
    </div>
    <script>
        $(document).ready(function(){
            $('.ver-detalle').on('click', function() {
                var detalle = $(this).data('detalle');
                $('#detalleEmergente').html(detalle).show();
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest('.ver-detalle').length && !$(event.target).closest('#detalleEmergente').length) {
                    $('#detalleEmergente').hide();
                }
            });

            $(window).on('resize', function(){
                $('#detalleEmergente').hide();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
