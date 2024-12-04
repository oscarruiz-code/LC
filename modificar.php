<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$ventas = obtenerVentas($pdo);
$comerciales = obtenerComerciales($pdo);
$productos = obtenerProductos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['venta_id'])) {
    list($codComercial, $refProducto, $fecha) = explode('|', $_POST['venta_id']);
    $venta = obtenerVenta($pdo, $codComercial, $refProducto, $fecha);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    $codComercial = $_POST['codComercial'];
    $refProducto = $_POST['refProducto'];
    $fecha = $_POST['fecha'];
    $cantidad = $_POST['cantidad'];

    if (modificarVenta($pdo, $codComercial, $refProducto, $fecha, $cantidad)) {
        $mensaje = "Venta modificada correctamente.";
    } else {
        $mensaje = "Error al modificar la venta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Venta</title>
</head>
<body>
    <h1>Modificar Venta</h1>
    <nav>
        <ul>
            <li><a href="consultar.php">Consultar Ventas</a></li>
            <li><a href="insertar.php">Insertar</a></li>
            <li><a href="modificar.php">Modificar</a></li>
            <li><a href="eliminar.php">Eliminar</a></li>
        </ul>
    </nav>
    <form method="POST">
        <label for="venta_id">Seleccione una venta:</label>
        <select name="venta_id" id="venta_id" required>
            <?php foreach ($ventas as $venta): ?>
                <option value="<?= $venta['codComercial'] ?>|<?= $venta['refProducto'] ?>|<?= $venta['fecha'] ?>">
                    <?= $venta['codComercial'] ?> - <?= $venta['refProducto'] ?> - <?= $venta['fecha'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Seleccionar</button>
    </form>

    <?php if (isset($venta)): ?>
    <form method="POST">
        <input type="hidden" name="codComercial" value="<?= $venta['codComercial'] ?>">
        <input type="hidden" name="refProducto" value="<?= $venta['refProducto'] ?>">
        <input type="hidden" name="fecha" value="<?= $venta['fecha'] ?>">

        <label for="comercial">Comercial:</label>
        <select name="codComercial" id="comercial" required>
            <?php foreach ($comerciales as $comercial): ?>
                <option value="<?= $comercial['codigo'] ?>" <?= $venta['codComercial'] == $comercial['codigo'] ? 'selected' : '' ?>>
                    <?= $comercial['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="producto">Producto:</label>
        <select name="refProducto" id="producto" required>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['referencia'] ?>" <?= $venta['refProducto'] == $producto['referencia'] ? 'selected' : '' ?>>
                    <?= $producto['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" value="<?= $venta['cantidad'] ?>" required>

        <button type="submit" name="modificar">Modificar</button>
    </form>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
    <?php endif; ?>
</body>
</html>
