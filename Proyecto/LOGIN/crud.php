
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

include 'conexion.php';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $marca = htmlspecialchars($_POST['marca']);
    $modelo = htmlspecialchars($_POST['modelo']);
    $anio = intval($_POST['anio']);
    $kilometraje = intval($_POST['kilometraje']);
    $precio = intval($_POST['precio']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($accion == 'agregar') {
        $sql = "INSERT INTO carros (marca, modelo, año, kilometraje, precio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $marca, $modelo, $anio, $kilometraje, $precio);
        if ($stmt->execute()) {
            header("Location: home.php?msg=Carro agregado con éxito");
        } else {
            header("Location: home.php?msg=Error al agregar el carro");
        }
    } elseif ($accion == 'modificar') {
        $sql = "UPDATE carros SET marca = ?, modelo = ?, año = ?, kilometraje = ?, precio = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiiii", $marca, $modelo, $anio, $kilometraje, $precio, $id);
        if ($stmt->execute()) {
            header("Location: home.php?msg=Carro modificado con éxito");
        } else {
            header("Location: home.php?msg=Error al modificar el carro");
        }
    } elseif ($accion == 'eliminar') {
        $sql = "DELETE FROM carros WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: home.php?msg=Carro eliminado con éxito");
        } else {
            header("Location: home.php?msg=Error al eliminar el carro");
        }
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: home.php");
    exit;
}
?>
