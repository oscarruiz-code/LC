<?php
include 'funciones.inc';
$pdo = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $referencia = $_POST['referencia'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    insertarProducto($pdo, $referencia, $nombre, $precio);
    echo "Producto insertado correctamente.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Producto</title>
</head>
<body>
    <h1>Insertar Producto</h1>
    <form method="POST">
        <label for="referencia">Referencia:</label>
        <input type="text" name="referencia" id="referencia" required>
        <br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" required step="0.01">
        <br>
        <button type="submit">Insertar</button>
    </form>
</body>
</html>
