<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}
$nombres = htmlspecialchars($_SESSION['nombres']);
$apellidos = htmlspecialchars($_SESSION['apellidos']);
$rol = $_SESSION['rol']; // Obtén el rol de la sesión
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel de Usuario</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/719186e97d.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../CSS/diseño.css">
    <style>
        .dropdown-menu {
            background-color: #007bff;
        }
        .dropdown-item {
            color: white;
        }
        .dropdown-item:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.html">Home</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo "Bienvenido: $nombres $apellidos"; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <?php if ($rol == 'alumno'): ?>
                            <a class="dropdown-item" href="areas_interes.php">Áreas de interés</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="cerrar.php">Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        <h2>Listado de Vehículos</h2>
        <?php
        if (isset($_GET['msg'])) {
            echo "<div class='alert alert-info'>" . htmlspecialchars($_GET['msg']) . "</div>";
        }
        ?>
        <button class='btn btn-success' onclick="openModal('agregar')"> <i class='fa-solid fa-square-plus'></i> </button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Kilometraje</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <?php
                include 'conexion.php';
                $sql = "SELECT * FROM carros";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['marca']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['modelo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['año']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kilometraje']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['precio']) . "</td>";   
                        echo "<td>
                            
                            <button class='btn btn-primary' onclick=\"openModal('modificar', this, " . $row['id'] . ")\"> <i class='fa-solid fa-pen'></i> </button>
                            <button class='btn btn-danger' onclick=\"openModal('eliminar', this, " . $row['id'] . ")\"> <i class='fa-solid fa-trash'></i> </button>
                            
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron vehículos</td></tr>";
                }
                $conn->close();
                ?>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="crud" tabindex="-1" aria-labelledby="e_crud" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="e_crud">Vehiculo:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="crudForm" action="crud.php" method="post">
                        <div class="form-group">
                            <label for="marca">Marca:</label>
                            <input type="text" class="form-control" id="marca" name="marca" required>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo:</label>
                            <input type="text" class="form-control" id="modelo" name="modelo" required>
                        </div>
                        <div class="form-group">
                            <label for="anio">Año:</label>
                            <input type="number" class="form-control" id="anio" name="anio" required>
                        </div>
                        <div class="form-group">
                            <label for="kilometraje">Kilometraje:</label>
                            <input type="number" class="form-control" id="kilometraje" name="kilometraje" required>
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="number" class="form-control" id="precio" name="precio" required>
                        </div>
                        <input type="hidden" name="accion" id="accion">
                        <input type="hidden" name="id" id="carroId">
                        <button type="submit" class="btn btn-primary" id="crudButton">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/719186e97d.js" crossorigin="anonymous"></script>
    <script>
        function openModal(action, button, id = null) {
            $('#crudForm')[0].reset();
            $('#accion').val(action);
            $('#carroId').val(id);
            $('#crudButton').text(action.charAt(0).toUpperCase() + action.slice(1));

            if (action === 'modificar' || action === 'eliminar') {
                var row = $(button).closest('tr');
                $('#marca').val(row.find('td:eq(0)').text());
                $('#modelo').val(row.find('td:eq(1)').text());
                $('#anio').val(row.find('td:eq(2)').text());
                $('#kilometraje').val(row.find('td:eq(3)').text());
                $('#precio').val(row.find('td:eq(4)').text());
            }

            if (action === 'eliminar') {
                $('#crudForm input').prop('readonly', true);
            } else {
                $('#crudForm input').prop('readonly', false);
            }

            $('#crud').modal('show');
        }
    </script>
</body>
</html>

