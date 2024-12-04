<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$ventas = obtenerVentas($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codComercial = $_POST['codComercial'];
    $refProducto = $_POST['refProducto'];
    $fecha = $_POST['fecha'];

    if (eliminarVenta($pdo, $codComercial, $refProducto, $fecha)) {
        $mensaje = "Venta eliminada correctamente.";
    } else {
        $mensaje = "Error al eliminar la venta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Venta</title>
</head>
<body>
    <h1>Eliminar Venta</h1>
    <form method="POST">
        <label for="codComercial">Seleccione un comercial:</label>
        <select name="codComercial" id="codComercial" required>
            <?php foreach ($ventas as $venta): ?>
                <option value="<?= $venta['codComercial'] ?>"><?= $venta['codComercial'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="refProducto">Seleccione un producto:</label>
        <select name="refProducto" id="refProducto" required>
            <?php foreach ($ventas as $venta): ?>
                <option value="<?= $venta['refProducto'] ?>"><?= $venta['refProducto'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>

        <button type="submit">Eliminar</button>
    </form>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
