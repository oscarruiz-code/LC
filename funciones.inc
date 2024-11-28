<?php

// Conexión a la base de datos
function conectarDB() {
    $host = 'localhost';
    $dbname = 'ventas_comerciales';
    $user = 'dam';
    $pass = 'hlc';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error al conectar con la base de datos: " . $e->getMessage());
    }
}

// Obtener todos los comerciales
function obtenerComerciales($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM comerciales");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener todas las ventas de un comercial
function obtenerVentasDeComercial($pdo, $idComercial) {
    $stmt = $pdo->prepare("SELECT v.*, p.nombre AS producto, c.nombre AS comercial
                           FROM ventas v
                           JOIN productos p ON v.producto = p.referencia
                           JOIN comerciales c ON v.comercial = c.id
                           WHERE v.comercial = :comercial");
    $stmt->bindParam(':comercial', $idComercial);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Insertar un producto
function insertarProducto($pdo, $referencia, $nombre, $precio) {
    $stmt = $pdo->prepare("INSERT INTO productos (referencia, nombre, precio) VALUES (:referencia, :nombre, :precio)");
    $stmt->bindParam(':referencia', $referencia);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':precio', $precio);
    $stmt->execute();
}

// Insertar una venta
function insertarVenta($pdo, $comercial, $producto, $cantidad, $total, $fecha) {
    $stmt = $pdo->prepare("INSERT INTO ventas (comercial, producto, cantidad, total, fecha) 
                           VALUES (:comercial, :producto, :cantidad, :total, :fecha)");
    $stmt->bindParam(':comercial', $comercial);
    $stmt->bindParam(':producto', $producto);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
}

// Eliminar un producto
function eliminarProducto($pdo, $referencia) {
    $stmt = $pdo->prepare("DELETE FROM productos WHERE referencia = :referencia");
    try {
        $stmt->bindParam(':referencia', $referencia);
        $stmt->execute();
    } catch (PDOException $e) {
        return "Error: No se puede eliminar este producto porque está asociado a una venta.";
    }
}

?>

