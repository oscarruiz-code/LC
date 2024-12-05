<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'funciones.inc.php';

$pdo = conectarDB();

$tablas = ['Comerciales', 'Productos', 'Ventas'];
$mensaje = '';

$tablaSeleccionada = $_POST['tabla'] ?? '';
$codigo = $_POST['comercial'] ?? '';
$referencia = $_POST['producto'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$fecha = $_POST['fecha'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    $comerciales = obtenerComerciales($pdo);
    $productos = obtenerProductos($pdo);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    echo "Formulario enviado<br>"; // Línea de depuración
    var_dump($_POST); // Verificar los datos enviados

    if ($tablaSeleccionada == 'Comerciales' && $codigo && $nombre && $salario !== '' && $hijos !== '' && $fNacimiento) {
        try {
            $stmt = $pdo->prepare('INSERT INTO Comerciales (codigo, nombre, salario, hijos, fNacimiento) VALUES (?, ?, ?, ?, ?)');
            if ($stmt->execute([$codigo, $nombre, $salario, $hijos, $fNacimiento])) {
                $mensaje = "Registro insertado correctamente en Comerciales.";
            } else {
                $mensaje = "Error al insertar el registro en Comerciales.";
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Productos' && $referencia && $nombre && $precio !== '' && $descuento !== '') {
        try {
            $stmt = $pdo->prepare('INSERT INTO Productos (referencia, nombre, descripcion, precio, descuento) VALUES (?, ?, ?, ?, ?)');
            if ($stmt->execute([$referencia, $nombre, $descripcion, $precio, $descuento])) {
                $mensaje = "Registro insertado correctamente en Productos.";
            } else {
                $mensaje = "Error al insertar el registro en Productos.";
                var_dump($pdo->errorInfo()); // Mostrar error de PDO
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Ventas' && $codigo && $referencia && $cantidad !== '' && $fecha) {
        try {
            $stmt = $pdo->prepare('INSERT INTO Ventas (codComercial, refProducto, cantidad, fecha) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$codigo, $referencia, $cantidad, $fecha])) {
                $mensaje = "Registro insertado correctamente en Ventas.";
            } else {
                $mensaje = "Error al insertar el registro en Ventas.";
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
    <title>Insertar Registro</title>
</head>
<body>
    <h1>Insertar Registro</h1>
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

    <?php if (isset($tablaSeleccionada) && $tablaSeleccionada): ?>
        <h2>Insertar en la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codigo">Código:</label>
                <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>" required>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                <label for="salario">Salario:</label>
                <input type="number" step="0.01" name="salario" id="salario" value="<?= htmlspecialchars($_POST['salario'] ?? '') ?>" required>
                <label for="hijos">Hijos:</label>
                <input type="number" name="hijos" id="hijos" value="<?= htmlspecialchars($_POST['hijos'] ?? '') ?>" required>
                <label for="fNacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fNacimiento" id="fNacimiento" value="<?= htmlspecialchars($_POST['fNacimiento'] ?? '') ?>" required>
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="referencia">Referencia:</label>
                <input type="text" name="referencia" id="referencia" value="<?= htmlspecialchars($_POST['referencia'] ?? '') ?>" required>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($_POST['descripcion'] ?? '') ?>">
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>" required>
                <label for="descuento">Descuento:</label>
                <input type="number" name="descuento" id="descuento" value="<?= htmlspecialchars($_POST['descuento'] ?? '') ?>" required>
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="comercial">Comercial:</label>
                <select name="comercial" id="comercial" required>
                    <?php foreach ($comerciales as $comercial): ?>
                        <option value="<?= $comercial['codigo'] ?>" <?= $comercial['codigo'] == $codigo ? 'selected' : '' ?>>
                            <?= $comercial['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="producto">Producto:</label>
                <select name="producto" id="producto" required>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['referencia'] ?>" <?= $producto['referencia'] == $referencia ? 'selected' : '' ?>>
                            <?= $producto['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" value="<?= htmlspecialchars($cantidad) ?>" required>
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($fecha) ?>" required>
            <?php endif; ?>

            <button type="submit">Insertar</button>
        </form>
    <?php endif; ?>

    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
