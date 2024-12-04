<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$comerciales = obtenerComerciales($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codComercial = $_POST['comercial'];
    $ventas = obtenerVentasDeComercial($pdo, $codComercial);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Ventas</title>
</head>
<body>
    <h1>Consultar Ventas</h1>
    <form method="POST">
        <label for="comercial">Seleccione un comercial:</label>
        <select name="comercial" id="comercial" required>
            <?php foreach ($comerciales as $comercial): ?>
                <option value="<?= $comercial['codigo'] ?>"><?= $comercial['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Consultar</button>
    </form>

    <?php if (isset($ventas)): ?>
        <h2>Ventas realizadas</h2>
        <table border="1">
            <tr>
                <th>Código Comercial</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= $venta['codComercial'] ?></td>
                    <td><?= $venta['fecha'] ?></td>
                    <td><?= $venta['refProducto'] ?></td>
                    <td><?= $venta['cantidad'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
