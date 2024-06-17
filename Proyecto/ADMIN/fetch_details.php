<?php
include '../LOGIN/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "SELECT * FROM carros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'nombre' => $row['nombre'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'anio' => $row['año'],
            'precio' => $row['precio'],
            'detalles' => $row['detalles']
        ]);
    } else {
        echo json_encode(['error' => 'No se encontró el vehículo']);
    }
    $stmt->close();
}
$conn->close();
?>
