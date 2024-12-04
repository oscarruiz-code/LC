<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$comerciales = obtenerComerciales($pdo);
$productos = obtenerProductos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codComercial = $_POST['comercial'];
    $refProducto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];

    if (insertarVenta($pdo, $codComercial, $refProducto, $cantidad, $fecha)) {
        $mensaje = "Venta insertada correctamente.";
    } else {
        $mensaje = "Error al insertar la venta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Venta</title>
</head>
<body>
    <h1>Insertar Venta</h1>
    <nav>
        <ul>
            <li><a href="consultar.php">Consultar Ventas</a></li>
            <li><a href="insertar.php">Insertar</a></li>
            <li><a href="modificar.php">Modificar</a></li>
            <li><a href="eliminar.php">Eliminar</a></li>
        </ul>
    </nav>
    <form method="POST">
        <label for="comercial">Comercial:</label>
        <select name="comercial" id="comercial" required>
            <?php foreach ($comerciales as $comercial): ?>
                <option value="<?= $comercial['codigo'] ?>"><?= $comercial['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="producto">Producto:</label>
        <select name="producto" id="producto" required>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['referencia'] ?>"><?= $producto['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required>

        <label for="fecha">Fecha:</label>
        <input type="date" name="fecha" id="fecha" required>

        <button type="submit">Insertar</button>
    </form>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
