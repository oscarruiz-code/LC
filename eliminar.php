<?php
include 'funciones.inc';
$pdo = conectarDB();

// Obtener los productos existentes para eliminar
$productos = $pdo->prepare("SELECT * FROM productos");
$productos->execute();
$productos = $productos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $referencia = $_POST['referencia'];
    $mensaje = eliminarProducto($pdo, $referencia);
    echo $mensaje ? $mensaje : "Producto eliminado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Producto</title>
</head>
<body>
    <h1>Eliminar Producto</h1>
    <form method="POST">
        <label for="referencia">Seleccionar Producto:</label>
        <select name="referencia" id="referencia">
            <?php foreach ($productos as $producto): ?>
                <option value="<?= $producto['referencia'] ?>"><?= $producto['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Eliminar</button>
    </form>
</body>
</html>
