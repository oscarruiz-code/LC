<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$tablas = ['Comerciales', 'Productos', 'Ventas', 'VentasPorComercio'];
$data = [];
$dataVentasPorComercio = [];
$mensaje = '';

$tablaSeleccionada = $_POST['tabla'] ?? '';
$comercioSeleccionado = $_POST['comercio'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($tablaSeleccionada) {
        case 'Comerciales':
            $data = obtenerComerciales($pdo);
            break;
        case 'Productos':
            $data = obtenerProductos($pdo);
            break;
        case 'Ventas':
            $data = obtenerVentas($pdo);
            break;
        case 'VentasPorComercio':
            $dataVentasPorComercio = obtenerVentasPorComercio($pdo, $comercioSeleccionado);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Tablas</title>
</head>
<body>
    <h1>Consultar Tablas</h1>
    <nav>
        <ul>
            <li><a href="consultar.php">Consultar</a></li>
            <li><a href="insertar.php">Insertar</a></li>
            <li><a href="modificar.php">Modificar</a></li>
            <li><a href="eliminar.php">Eliminar</a></li>
        </ul>
    </nav>
    <form method="POST">
        <label for="tabla">Seleccione una tabla:</label>
        <select name="tabla" id="tabla" required>
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?= $tabla ?>" <?= $tabla === $tablaSeleccionada ? 'selected' : '' ?>><?= $tabla ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($tablaSeleccionada === 'VentasPorComercio'): ?>
            <label for="comercio">Seleccione un comercio:</label>
            <select name="comercio" id="comercio">
                <?php 
                $comerciales = obtenerComerciales($pdo);
                foreach ($comerciales as $comercio): ?>
                    <option value="<?= $comercio['codigo'] ?>" <?= $comercio['codigo'] === $comercioSeleccionado ? 'selected' : '' ?>><?= $comercio['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button type="submit">Consultar</button>
    </form>

    <?php if (!empty($data)): ?>
        <h2>Datos de la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach (array_keys($data[0]) as $columna): ?>
                        <th><?= htmlspecialchars($columna) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $fila): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                            <td><?= htmlspecialchars($valor) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($dataVentasPorComercio)): ?>
        <h2>Ventas por Comercio</h2>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach (array_keys($dataVentasPorComercio[0]) as $columna): ?>
                        <th><?= htmlspecialchars($columna) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataVentasPorComercio as $fila): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                            <td><?= htmlspecialchars($valor) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
