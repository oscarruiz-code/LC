<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$tablas = ['Comerciales', 'Productos', 'Ventas'];
$data = [];
$mensaje = '';

$tablaSeleccionada = $_POST['tabla'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
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
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['venta_id'])) {
    list($codComercial, $refProducto, $fecha) = explode('|', $_POST['venta_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $codComercial = $_POST['codComercial'] ?? $codComercial;
    $refProducto = $_POST['refProducto'] ?? $refProducto;
    $fecha = $_POST['fecha'] ?? $fecha;

    echo "Formulario enviado<br>"; // Línea de depuración
    var_dump($_POST); // Verificar los datos enviados

    if ($tablaSeleccionada == 'Comerciales') {
        try {
            $resultado = eliminarComercial($pdo, $codComercial);
            if ($resultado === "Comercial eliminado correctamente.") {
                $mensaje = $resultado;
            } else {
                $mensaje = $resultado;
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Productos') {
        try {
            $resultado = eliminarProducto($pdo, $refProducto);
            if ($resultado === "Producto eliminado correctamente.") {
                $mensaje = $resultado;
            } else {
                $mensaje = $resultado;
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Ventas') {
        try {
            $resultado = eliminarVenta($pdo, $codComercial, $refProducto, $fecha);
            if ($resultado) {
                $mensaje = "Registro eliminado correctamente en Ventas.";
            } else {
                $mensaje = "Error al eliminar el registro en Ventas.";
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Registro</title>
</head>
<body>
    <h1>Eliminar Registro</h1>
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
        <button type="submit">Seleccionar</button>
    </form>

    <?php if (!empty($data) && isset($tablaSeleccionada)): ?>
        <h2>Eliminar de la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codComercial">Código Comercial:</label>
                <select name="codComercial" id="codComercial" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codigo'] ?>"><?= $item['codigo'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="refProducto">Referencia Producto:</label>
                <select name="refProducto" id="refProducto" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['referencia'] ?>"><?= $item['referencia'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="venta_id">Seleccione una venta:</label>
                <select name="venta_id" id="venta_id" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codComercial'] ?>|<?= $item['refProducto'] ?>|<?= $item['fecha'] ?>">
                            <?= $item['codComercial'] ?> - <?= $item['refProducto'] ?> - <?= $item['fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <button type="submit" name="eliminar">Eliminar</button>
        </form>
    <?php endif; ?>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
