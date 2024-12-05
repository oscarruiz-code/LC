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
    $venta = obtenerVenta($pdo, $codComercial, $refProducto, $fecha);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    $codComercial = $_POST['codComercial'] ?? '';
    $refProducto = $_POST['refProducto'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';

    if ($tablaSeleccionada == 'Comerciales') {
        $codigo = $_POST['codComercial'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $salario = $_POST['salario'] ?? '';
        $hijos = $_POST['hijos'] ?? '';
        $fNacimiento = $_POST['fNacimiento'] ?? '';

        try {
            $stmt = $pdo->prepare('UPDATE Comerciales SET nombre = ?, salario = ?, hijos = ?, fNacimiento = ? WHERE codigo = ?');
            if ($stmt->execute([$nombre, $salario, $hijos, $fNacimiento, $codigo])) {
                $mensaje = "Registro modificado correctamente en Comerciales.";
            } else {
                $mensaje = "Error al modificar el registro en Comerciales.";
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Productos') {
        $referencia = $_POST['referencia'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $descuento = $_POST['descuento'] ?? '';

        try {
            $stmt = $pdo->prepare('UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, descuento = ? WHERE referencia = ?');
            if ($stmt->execute([$nombre, $descripcion, $precio, $descuento, $referencia])) {
                $mensaje = "Registro modificado correctamente en Productos.";
            } else {
                $mensaje = "Error al modificar el registro en Productos.";
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Ventas') {
        try {
            $stmt = $pdo->prepare('UPDATE Ventas SET cantidad = ? WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
            if ($stmt->execute([$cantidad, $codComercial, $refProducto, $fecha])) {
                $mensaje = "Registro modificado correctamente en Ventas.";
            } else {
                $mensaje = "Error al modificar el registro en Ventas.";
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
    <title>Modificar Registro</title>
</head>
<body>
    <h1>Modificar Registro</h1>
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
        <h2>Modificar la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codComercial">Código Comercial:</label>
                <select name="codComercial" id="codComercial" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codigo'] ?>"><?= $item['codigo'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($data[0]['nombre'] ?? '') ?>" required>
                <label for="salario">Salario:</label>
                <input type="number" step="0.01" name="salario" id="salario" value="<?= htmlspecialchars($data[0]['salario'] ?? '') ?>" required>
                <label for="hijos">Hijos:</label>
                <input type="number" name="hijos" id="hijos" value="<?= htmlspecialchars($data[0]['hijos'] ?? '') ?>" required>
                <label for="fNacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fNacimiento" id="fNacimiento" value="<?= htmlspecialchars($data[0]['fNacimiento'] ?? '') ?>" required>
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="referencia">Referencia:</label>
                <select name="referencia" id="referencia" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['referencia'] ?>"><?= $item['referencia'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($data[0]['nombre'] ?? '') ?>" required>
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($data[0]['descripcion'] ?? '') ?>">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($data[0]['precio'] ?? '') ?>" required>
                <label for="descuento">Descuento:</label>
                <input type="number" name="descuento" id="descuento" value="<?= htmlspecialchars($data[0]['descuento'] ?? '') ?>" required>
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="venta_id">Seleccione una venta:</label>
                <select name="venta_id" id="venta_id" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codComercial'] ?>|<?= $item['refProducto'] ?>|<?= $item['fecha'] ?>">
                            <?= $item['codComercial'] ?> - <?= $item['refProducto'] ?> - <?= $item['fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if (isset($venta)): ?>
                    <input type="hidden" name="codComercial" value="<?= htmlspecialchars($venta['codComercial'] ?? '') ?>">
                    <input type="hidden" name="refProducto" value="<?= htmlspecialchars($venta['refProducto'] ?? '') ?>">
                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($venta['fecha'] ?? '') ?>">

                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidad" value="<?= htmlspecialchars($venta['cantidad'] ?? '') ?>" required>
                <?php endif; ?>
            <?php endif; ?>

            <button type="submit" name="modificar">Modificar</button>
        </form>
    <?php endif; ?>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
  
</body>
</html>
