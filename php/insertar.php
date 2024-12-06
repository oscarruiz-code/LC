<?php

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de funciones para manejar la base de datos
include_once 'funciones.inc.php';

// Conectar a la base de datos
$pdo = conectarDB();

// Definir las tablas disponibles para inserción
$tablas = ['Comerciales', 'Productos', 'Ventas'];
$mensaje = '';

// Obtener la tabla seleccionada del formulario
$tablaSeleccionada = $_POST['tabla'] ?? '';

// Si se seleccionó una tabla, obtener los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    $comerciales = obtenerComerciales($pdo);
    $productos = obtenerProductos($pdo);
}

// Procesar el formulario de inserción si se recibe una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar'])) {
    // Obtener los datos del formulario
    $codigo = $_POST['codigo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $salario = $_POST['salario'] ?? '';
    $hijos = $_POST['hijos'] ?? '';
    $fNacimiento = $_POST['fNacimiento'] ?? '';
    $referencia = $_POST['referencia'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $descuento = $_POST['descuento'] ?? '';
    $comercial = $_POST['comercial'] ?? '';
    $producto = $_POST['producto'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    // Insertar datos según la tabla seleccionada
    if ($tablaSeleccionada == 'Comerciales' && $codigo && $nombre && $salario !== '' && $hijos !== '' && $fNacimiento) {
        if (insertarComercial($pdo, $codigo, $nombre, $salario, $hijos, $fNacimiento)) {
            $mensaje = "Registro insertado correctamente en Comerciales.";
        } else {
            $mensaje = "Error al insertar el registro en Comerciales.";
        }
    } elseif ($tablaSeleccionada == 'Productos' && $referencia && $nombre && $precio !== '' && $descuento !== '') {
        if (insertarProducto($pdo, $referencia, $nombre, $descripcion, $precio, $descuento)) {
            $mensaje = "Registro insertado correctamente en Productos.";
        } else {
            $mensaje = "Error al insertar el registro en Productos.";
        }
    } elseif ($tablaSeleccionada == 'Ventas' && $comercial && $producto && $cantidad !== '' && $fecha) {
        // Verificar existencia de comercial y producto
        $existeComercial = obtenerComercial($pdo, $comercial);
        $existeProducto = obtenerProducto($pdo, $producto);
        
        if ($existeComercial && $existeProducto) {
            if (insertarVenta($pdo, $comercial, $producto, $cantidad, $fecha)) {
                $mensaje = "Registro insertado correctamente en Ventas.";
            } else {
                $mensaje = "Error al insertar el registro en Ventas.";
            }
        } else {
            $mensaje = "Comercial o Producto no existen.";
        }
    } else {
        $mensaje = "Por favor, complete todos los campos obligatorios.";
    }
}

// Pasar las variables a la vista para mostrar el resultado
include '../vistas/vistainsertar.php';
?>
