<?php

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de funciones para manejar la base de datos
include_once 'funciones.inc.php';

// Conectar a la base de datos
$pdo = conectarDB();

// Definir las tablas disponibles para modificación
$tablas = ['Comerciales', 'Productos', 'Ventas'];
$data = [];
$mensaje = '';

// Obtener la tabla seleccionada del formulario
$tablaSeleccionada = $_POST['tabla'] ?? '';

// Si se seleccionó una tabla, obtener los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    switch ($tablaSeleccionada) {
        case 'Comerciales':
            $data = obtenerComerciales($pdo);
            break;
        case 'Productos':
            $data = obtenerProductos($pdo);
            break;
        case 'Ventas':
            $data = obtenerVentas($pdo);
            break;
    }
}

// Si se seleccionó una venta específica, obtener los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['venta_id'])) {
    list($codComercial, $refProducto, $fecha) = explode('|', $_POST['venta_id']);
    $venta = obtenerVenta($pdo, $codComercial, $refProducto, $fecha);
}

// Procesar el formulario de modificación si se recibe una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar'])) {
    // Obtener los datos del formulario
    $codComercial = $_POST['codComercial'] ?? '';
    $refProducto = $_POST['refProducto'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';

    // Modificar datos según la tabla seleccionada
    if ($tablaSeleccionada == 'Comerciales') {
        $codigo = $_POST['codComercial'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $salario = $_POST['salario'] ?? '';
        $hijos = $_POST['hijos'] ?? '';
        $fNacimiento = $_POST['fNacimiento'] ?? '';

        try {
            if (modificarComercial($pdo, $codigo, $nombre, $salario, $hijos, $fNacimiento)) {
                $mensaje = "Registro modificado correctamente en Comerciales.";
            } else {
                $mensaje = "Error al modificar el registro en Comerciales.";
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Productos') {
        $referencia = $_POST['referencia'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $precio = $_POST['precio'] ?? '';
        $descuento = $_POST['descuento'] ?? '';

        try {
            if (modificarProducto($pdo, $referencia, $nombre, $descripcion, $precio, $descuento)) {
                $mensaje = "Registro modificado correctamente en Productos.";
            } else {
                $mensaje = "Error al modificar el registro en Productos.";
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } elseif ($tablaSeleccionada == 'Ventas') {
        // Verificar existencia de comercial y producto
        $existeComercial = obtenerComercial($pdo, $codComercial);
        $existeProducto = obtenerProducto($pdo, $refProducto);
        
        if ($existeComercial && $existeProducto) {
            try {
                if (modificarVenta($pdo, $codComercial, $refProducto, $fecha, $cantidad)) {
                    $mensaje = "Registro modificado correctamente en Ventas.";
                } else {
                    $mensaje = "Error al modificar el registro en Ventas.";
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            $mensaje = "Comercial o Producto no existen.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    }
}

// Incluir la vista correspondiente para mostrar el resultado
include '../vistas/vistamodificar.php';
?>
