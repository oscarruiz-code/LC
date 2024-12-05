<?php

function conectarDB() {
    $dsn = 'mysql:host=localhost;dbname=ventas_comerciales';
    $usuario = 'dam';
    $contrasena = 'hlc';

    try {
        $pdo = new PDO($dsn, $usuario, $contrasena);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
        exit();
    }
}

function obtenerComerciales($pdo) {
    $stmt = $pdo->query('SELECT * FROM Comerciales');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerProductos($pdo) {
    $stmt = $pdo->query('SELECT * FROM Productos');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerVentas($pdo) {
    $stmt = $pdo->query('SELECT * FROM Ventas');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerVentasPorComercio($pdo, $comercioId) {
    $stmt = $pdo->prepare('SELECT * FROM Ventas WHERE codComercial = :codComercial');
    $stmt->execute(['codComercial' => $comercioId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertarVenta($pdo, $codComercial, $refProducto, $cantidad, $fecha) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Ventas (codComercial, refProducto, cantidad, fecha) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$codComercial, $refProducto, $cantidad, $fecha]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function obtenerVenta($pdo, $codComercial, $refProducto, $fecha) {
    $stmt = $pdo->prepare('SELECT * FROM Ventas WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
    $stmt->execute([$codComercial, $refProducto, $fecha]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function modificarVenta($pdo, $codComercial, $refProducto, $fecha, $cantidad) {
    $stmt = $pdo->prepare('UPDATE Ventas SET cantidad = ? WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
    return $stmt->execute([$cantidad, $codComercial, $refProducto, $fecha]);
}

function eliminarVenta($pdo, $codComercial, $refProducto, $fecha) {
    try {
        $stmt = $pdo->prepare('DELETE FROM Ventas WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
        $stmt->execute([$codComercial, $refProducto, $fecha]);
        return $stmt->rowCount() > 0; // Asegura que se eliminó al menos un registro
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


function tieneVentasAsociadas($pdo, $refProducto) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM Ventas WHERE refProducto = ?');
    $stmt->execute([$refProducto]);
    return $stmt->fetchColumn() > 0;
}

function eliminarProducto($pdo, $refProducto) {
    if (tieneVentasAsociadas($pdo, $refProducto)) {
        return "No se puede eliminar el producto porque tiene ventas asociadas.";
    }
    try {
        $stmt = $pdo->prepare('DELETE FROM Productos WHERE referencia = ?');
        if ($stmt->execute([$refProducto])) {
            return "Producto eliminado correctamente.";
        }
        return "Error al eliminar el producto.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return "Error al eliminar el producto.";
    }
}


function tieneVentasDeComercial($pdo, $codComercial) {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM Ventas WHERE codComercial = ?');
    $stmt->execute([$codComercial]);
    return $stmt->fetchColumn() > 0;
}

function eliminarComercial($pdo, $codComercial) {
    if (tieneVentasDeComercial($pdo, $codComercial)) {
        return "No se puede eliminar el comercial porque tiene ventas asociadas.";
    }
    $stmt = $pdo->prepare('DELETE FROM Comerciales WHERE codigo = ?');
    if ($stmt->execute([$codComercial])) {
        return "Comercial eliminado correctamente.";
    }
    return "Error al eliminar el comercial.";
}

?>
