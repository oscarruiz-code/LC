<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Registro</title>
    <!-- El elemento meta charset define la codificación de caracteres utilizada por el documento -->
</head>
<body>
    <!-- El encabezado principal de la página -->
    <h1>Eliminar Registro</h1>

    <!-- Navegación principal de la aplicación -->
    <nav>
        <ul>
            <!-- Enlace a la página de consultar registros -->
            <li><a href="../php/consultar.php">Consultar</a></li>

            <!-- Enlace a la página de insertar registros -->
            <li><a href="../php/insertar.php">Insertar</a></li>

            <!-- Enlace a la página de modificar registros -->
            <li><a href="../php/modificar.php">Modificar</a></li>

            <!-- Enlace a la página de eliminar registros -->
            <li><a href="../php/eliminar.php">Eliminar</a></li>
        </ul>
    </nav>

    <!-- Formulario para seleccionar la tabla a eliminar registros -->
    <form method="POST">
        <label for="tabla">Seleccione una tabla:</label>
        <select name="tabla" id="tabla" required>
            <!-- Generar opciones dinámicamente en base a las tablas disponibles -->
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?= $tabla ?>" <?= $tabla === $tablaSeleccionada ? 'selected' : '' ?>><?= $tabla ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Seleccionar</button>
    </form>

    <!-- Mostrar formulario de eliminación si hay datos disponibles -->
    <?php if (!empty($data) && isset($tablaSeleccionada)): ?>
        <h2>Eliminar de la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <!-- Formulario de eliminación específico según la tabla seleccionada -->
            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codComercial">Código Comercial:</label>
                <select name="codComercial" id="codComercial" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codigo'] ?>"><?= $item['codigo'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select></br></br>
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="refProducto">Referencia Producto:</label>
                <select name="refProducto" id="refProducto" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['referencia'] ?>"><?= $item['referencia'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select></br></br>
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="venta_id">Seleccione una venta:</label>
                <select name="venta_id" id="venta_id" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codComercial'] ?>|<?= $item['refProducto'] ?>|<?= $item['fecha'] ?>">
                            <?= $item['codComercial'] ?> - <?= $item['refProducto'] ?> - <?= $item['fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select></br></br>
            <?php endif; ?>

            <button type="submit" name="eliminar">Eliminar</button>
        </form>
    <?php endif; ?>

    <!-- Mostrar mensaje si existe -->
    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
