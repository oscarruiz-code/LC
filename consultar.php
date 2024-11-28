<?php
include 'funciones.inc';
$pdo = conectarDB();
$comerciales = obtenerComerciales($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idComercial = $_POST['comercial'];
    $ventas = obtenerVentasDeComercial($pdo, $idComercial);
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
                <option value="<?= $comercial['id'] ?>"><?= $comercial['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Consultar</button>
    </form>

    <?php if (isset($ventas)): ?>
        <h2>Ventas realizadas</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Total</th>
            </tr>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= $venta['id'] ?></td>
                    <td><?= $venta['fecha'] ?></td>
                    <td><?= $venta['producto'] ?></td>
                    <td><?= $venta['cantidad'] ?></td>
                    <td><?= $venta['total'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
