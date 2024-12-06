<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Registro</title>
    <!-- El elemento meta charset define la codificación de caracteres utilizada por el documento -->
</head>
<body>
    <!-- El encabezado principal de la página -->
    <h1>Insertar Registro</h1>

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

    <!-- Formulario para seleccionar la tabla a insertar registros -->
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

    <!-- Mostrar formulario de inserción si se seleccionó una tabla -->
    <?php if (isset($tablaSeleccionada) && $tablaSeleccionada): ?>
        <h2>Insertar en la tabla <?= htmlspecialchars($tablaSeleccionada) ?></h2>
        <form method="POST">
            <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">

            <!-- Formulario de inserción específico según la tabla seleccionada -->
            <?php if ($tablaSeleccionada == 'Comerciales'): ?>
                <label for="codigo">Código:</label>
                <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($_POST['codigo'] ?? '') ?>" required></br></br>
                
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required></br></br>
                
                <label for="salario">Salario:</label>
                <input type="number" step="0.01" name="salario" id="salario" value="<?= htmlspecialchars($_POST['salario'] ?? '') ?>" required></br></br>
                
                <label for="hijos">Hijos:</label>
                <input type="number" name="hijos" id="hijos" value="<?= htmlspecialchars($_POST['hijos'] ?? '') ?>" required></br></br>
                
                <label for="fNacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fNacimiento" id="fNacimiento" value="<?= htmlspecialchars($_POST['fNacimiento'] ?? '') ?>" required></br></br>
            
            <?php elseif ($tablaSeleccionada == 'Productos'): ?>
                <label for="referencia">Referencia:</label>
                <input type="text" name="referencia" id="referencia" value="<?= htmlspecialchars($_POST['referencia'] ?? '') ?>" required></br></br>
                
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required></br></br>
                
                <label for="descripcion">Descripción:</label>
                <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($_POST['descripcion'] ?? '') ?>"></br></br>
                
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" name="precio" id="precio" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>" required></br></br>
                
                <label for="descuento">Descuento:</label>
                <input type="number" name="descuento" id="descuento" value="<?= htmlspecialchars($_POST['descuento'] ?? '') ?>" required></br></br>
            
            <?php elseif ($tablaSeleccionada == 'Ventas'): ?>
                <label for="comercial">Comercial:</label>
                <select name="comercial" id="comercial" required>
                    <?php foreach ($comerciales as $comercial): ?>
                        <option value="<?= $comercial['codigo'] ?>" <?= $comercial['codigo'] == ($_POST['comercial'] ?? '') ? 'selected' : '' ?>>
                            <?= $comercial['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="producto">Producto:</label>
                <select name="producto" id="producto" required>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= $producto['referencia'] ?>" <?= $producto['referencia'] == ($_POST['producto'] ?? '') ? 'selected' : '' ?>>
                            <?= $producto['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select></br></br>
                
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" value="<?= htmlspecialchars($_POST['cantidad'] ?? '') ?>" required></br></br>
                
                <label for="fecha">Fecha:</label>
                <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required></br></br>
            <?php endif; ?>

            <button type="submit" name="insertar">Insertar</button>
        </form>
    <?php endif; ?>

    <!-- Mostrar mensaje si existe -->
    <?php if (isset($mensaje)): ?>
        <p><?= $mensaje ?></p>
    <?php endif; ?>
</body>
</html>
