<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Registro</title>
    <!-- El elemento meta charset define la codificación de caracteres utilizada por el documento -->
</head>
<body>
    <!-- El encabezado principal de la página -->
    <h1>Modificar Registro</h1>

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

    <!-- Formulario para seleccionar la tabla a modificar registros -->
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

    <!-- Mostrar formulario de modificación si hay datos disponibles -->
    <?php if (!empty($data) && isset($tablaSeleccionada)): ?>
        <h2>Modificar la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <!-- Formulario de modificación específico según la tabla seleccionada -->
            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codComercial">Código Comercial:</label>
                <select name="codComercial" id="codComercial" required>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codigo'] ?>"><?= $item['codigo'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select></br></br>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($data[0]['nombre'] ?? '') ?>" required></br></br>
                <label for="salario">Salario:</label>
                <input type="number" step="0.01" name="salario" id="salario" value="<?= htmlspecialchars($data[0]['salario'] ?? '') ?>" required></br></br>
                <label for="hijos">Hijos:</label>
                <input type="number" name="hijos" id="hijos" value="<?= htmlspecialchars($data[0]['hijos'] ?? '') ?>" required></br></br>
                <label for="fNacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fNacimiento" id="fNacimiento" value="<?= htmlspecialchars($data[0]['fNacimiento'] ?? '') ?>" required></br></br>
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="referencia">Referencia:</label>
                <select name="referencia" id="referencia" required></br></br>
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['referencia'] ?>"><?= $item['referencia'] ?> - <?= $item['nombre'] ?></option>
                    <?php endforeach; ?>
                </select></br></br>
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($data[0]['nombre'] ?? '') ?>" required></br></br>
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($data[0]['descripcion'] ?? '') ?>"></br></br>
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($data[0]['precio'] ?? '') ?>" required></br></br>
                <label for="descuento">Descuento:</label>
                <input type="number" name="descuento" id="descuento" value="<?= htmlspecialchars($data[0]['descuento'] ?? '') ?>" required></br></br>
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="venta_id">Seleccione una venta:</label>
                <select name="venta_id" id="venta_id" required>
                    <!-- Generar opciones dinámicamente en base a las ventas disponibles -->
                    <?php foreach ($data as $item): ?>
                        <option value="<?= $item['codComercial'] ?>|<?= $item['refProducto'] ?>|<?= $item['fecha'] ?>">
                            <?= $item['codComercial'] ?> - <?= $item['refProducto'] ?> - <?= $item['fecha'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Mostrar campos adicionales para la venta seleccionada -->
                <?php if (isset($venta)): ?>
                    <input type="hidden" name="codComercial" value="<?= htmlspecialchars($venta['codComercial'] ?? '') ?>">
                    <input type="hidden" name="refProducto" value="<?= htmlspecialchars($venta['refProducto'] ?? '') ?>">
                    <input type="hidden" name="fecha" value="<?= htmlspecialchars($venta['fecha'] ?? '') ?>"></br></br>

                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidad" value="<?= htmlspecialchars($venta['cantidad'] ?? '') ?>" required></br></br>
                <?php endif; ?>
            <?php endif; ?>

            <button type="submit" name="modificar">Modificar</button>
        </form>
    <?php endif; ?>

    <!-- Mostrar mensaje si existe -->
    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
