<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Tablas</title>
    <!-- El elemento meta charset define la codificación de caracteres utilizada por el documento -->
</head>
<body>
    <!-- El encabezado principal de la página -->
    <h1>Consultar Tablas</h1>

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

    <!-- Formulario para seleccionar la tabla a consultar -->
    <form method="POST">
        <label for="tabla">Seleccione una tabla:</label>
        <select name="tabla" id="tabla" required>
            <!-- Generar opciones dinámicamente en base a las tablas disponibles -->
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?= $tabla ?>" <?= $tabla === $tablaSeleccionada ? 'selected' : '' ?>><?= $tabla ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Campo adicional si la tabla seleccionada es VentasPorComercio -->
        <?php if ($tablaSeleccionada === 'VentasPorComercio'): ?>
            <label for="comercio">Seleccione un comercio:</label>
            <select name="comercio" id="comercio">
                <!-- Generar opciones dinámicamente en base a los comerciales disponibles -->
                <?php 
                $comerciales = obtenerComerciales($pdo);
                foreach ($comerciales as $comercio): ?>
                    <option value="<?= $comercio['codigo'] ?>" <?= $comercio['codigo'] === $comercioSeleccionado ? 'selected' : '' ?>><?= $comercio['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button type="submit">Consultar</button>
    </form>

    <!-- Mostrar los datos de la tabla seleccionada si existen -->
    <?php if (!empty($data)): ?>
        <h2>Datos de la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <table border="1">
            <thead>
                <tr>
                    <!-- Generar encabezados de la tabla dinámicamente en base a las columnas -->
                    <?php foreach (array_keys($data[0]) as $columna): ?>
                        <th><?= htmlspecialchars($columna) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Generar filas de la tabla dinámicamente en base a los datos -->
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

    <!-- Mostrar los datos de ventas por comercio si existen -->
    <?php if (!empty($dataVentasPorComercio)): ?>
        <h2>Ventas por Comercio</h2>
        <table border="1">
            <thead>
                <tr>
                    <!-- Generar encabezados de la tabla dinámicamente en base a las columnas -->
                    <?php foreach (array_keys($dataVentasPorComercio[0]) as $columna): ?>
                        <th><?= htmlspecialchars($columna) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Generar filas de la tabla dinámicamente en base a los datos -->
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
