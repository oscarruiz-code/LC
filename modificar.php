<?php
include 'funciones.inc';
$pdo = conectarDB();

// Obtener los productos existentes para modificar
$productos = $pdo->prepare("SELECT * FROM productos");
$productos->execute();
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lógica de modificación (por ejemplo, modificar un producto)
    $referencia = $_POST['referencia'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Aquí iría la función que modifica el producto
    // Por ejemplo: modificarProducto($pdo, $referencia, $nombre, $precio);
    echo "Producto modificado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Producto</title>
</head>
<body>
    <h1>Modificar Producto</h1>
    <form method="POST">
        <label for="referencia">Seleccionar Producto:</label>
        <select name="referencia" id="referencia">
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['referencia'] ?>"><?= $producto['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="nombre">Nuevo Nombre:</label>
        <input type="text" name="nombre" id="nombre">
        <br>
        <label for="precio">Nuevo Precio:</label>
        <input type="number" name="precio" id="precio" step="0.01">
        <br>
        <button type="submit">Modificar</button>
    </form>
</body>
</html>
