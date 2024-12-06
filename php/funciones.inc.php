<?php

// Función para conectar a la base de datos
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

// Función para obtener todos los comerciales
function obtenerComerciales($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM Comerciales');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener comerciales: ' . $e->getMessage();
        return [];
    }
}

// Función para obtener todos los productos
function obtenerProductos($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM Productos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener productos: ' . $e->getMessage();
        return [];
    }
}

// Función para obtener todas las ventas
function obtenerVentas($pdo) {
    try {
        $stmt = $pdo->query('SELECT * FROM Ventas');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener ventas: ' . $e->getMessage();
        return [];
    }
}

// Función para obtener ventas por comerciante
function obtenerVentasPorComercio($pdo, $comercioId) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Ventas WHERE codComercial = :codComercial');
        $stmt->execute(['codComercial' => $comercioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener ventas por comercio: ' . $e->getMessage();
        return [];
    }
}

// Función para obtener un comercial específico
function obtenerComercial($pdo, $codigo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Comerciales WHERE codigo = ?');
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener el comercial: ' . $e->getMessage();
        return false;
    }
}

// Función para obtener un producto específico
function obtenerProducto($pdo, $referencia) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Productos WHERE referencia = ?');
        $stmt->execute([$referencia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error al obtener el producto: ' . $e->getMessage();
        return false;
    }
}

// Función para obtener una venta específica
function obtenerVenta($pdo, $codComercial, $refProducto, $fecha) { 
    try { 
        $stmt = $pdo->prepare('SELECT * FROM Ventas WHERE codComercial = ? AND refProducto = ? AND fecha = ?'); 
        $stmt->execute([$codComercial, $refProducto, $fecha]); 
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
         echo 'Error al obtener la venta: ' . $e->getMessage(); 
         return false; 
    } 
}

// Función para insertar un comercial en la base de datos
function insertarComercial($pdo, $codigo, $nombre, $salario, $hijos, $fNacimiento) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Comerciales (codigo, nombre, salario, hijos, fNacimiento) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$codigo, $nombre, $salario, $hijos, $fNacimiento]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para insertar un producto en la base de datos
function insertarProducto($pdo, $referencia, $nombre, $descripcion, $precio, $descuento) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Productos (referencia, nombre, descripcion, precio, descuento) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$referencia, $nombre, $descripcion, $precio, $descuento]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para insertar una venta en la base de datos
function insertarVenta($pdo, $codComercial, $refProducto, $cantidad, $fecha) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Ventas (codComercial, refProducto, cantidad, fecha) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$codComercial, $refProducto, $cantidad, $fecha]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para modificar un comercial en la base de datos
function modificarComercial($pdo, $codigo, $nombre, $salario, $hijos, $fNacimiento) {
    try {
        $stmt = $pdo->prepare('UPDATE Comerciales SET nombre = ?, salario = ?, hijos = ?, fNacimiento = ? WHERE codigo = ?');
        return $stmt->execute([$nombre, $salario, $hijos, $fNacimiento, $codigo]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para modificar un producto en la base de datos
function modificarProducto($pdo, $referencia, $nombre, $descripcion, $precio, $descuento) {
    try {
        $stmt = $pdo->prepare('UPDATE Productos SET nombre = ?, descripcion = ?, precio = ?, descuento = ? WHERE referencia = ?');
        return $stmt->execute([$nombre, $descripcion, $precio, $descuento, $referencia]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para modificar una venta en la base de datos
function modificarVenta($pdo, $codComercial, $refProducto, $fecha, $cantidad) {
    try {
        $stmt = $pdo->prepare('UPDATE Ventas SET cantidad = ? WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
        return $stmt->execute([$cantidad, $codComercial, $refProducto, $fecha]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función para eliminar un comercial de la base de datos
function eliminarComercial($pdo, $codigo) {
    if (tieneVentasDeComercial($pdo, $codigo)) {
        return "No se puede eliminar el comercial porque tiene ventas asociadas.";
    }
    try {
        $stmt = $pdo->prepare('DELETE FROM Comerciales WHERE codigo = ?');
        if ($stmt->execute([$codigo])) {
            return "Comercial eliminado correctamente.";
        }
        return "Error al eliminar el comercial.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return "Error al eliminar el comercial.";
    }
}

// Función para eliminar un producto de la base de datos
function eliminarProducto($pdo, $referencia) {
    if (tieneVentasAsociadas($pdo, $referencia)) {
        return "No se puede eliminar el producto porque tiene ventas asociadas.";
    }
    try {
        $stmt = $pdo->prepare('DELETE FROM Productos WHERE referencia = ?');
        if ($stmt->execute([$referencia])) {
            return "Producto eliminado correctamente.";
        }
        return "Error al eliminar el producto.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return "Error al eliminar el producto.";
    }
}

// Función para eliminar una venta de la base de datos
function eliminarVenta($pdo, $codComercial, $refProducto, $fecha) {
    try {
        $stmt = $pdo->prepare('DELETE FROM Ventas WHERE codComercial = ? AND refProducto = ? AND fecha = ?');
        if ($stmt->execute([$codComercial, $refProducto, $fecha])) {
            return "Venta eliminada correctamente.";
        }
        return "Error al eliminar la venta.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función auxiliar para comprobar si un producto tiene ventas asociadas
function tieneVentasAsociadas($pdo, $referencia) {
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Ventas WHERE refProducto = ?');
        $stmt->execute([$referencia]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

// Función auxiliar para comprobar si un comercial tiene ventas asociadas
function tieneVentasDeComercial($pdo, $codigo) {
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Ventas WHERE codComercial = ?');
        $stmt->execute([$codigo]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

?>
