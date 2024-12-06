<?php

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de funciones
include_once 'funciones.inc.php';

// Conectar a la base de datos
$pdo = conectarDB();

// Definir las tablas disponibles
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
}

// Procesar el formulario de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    // Verificar que venta_id está definida
    if (!isset($_POST['venta_id'])) {
        $mensaje = "Por favor, seleccione una venta.";
    } else {
        list($codComercial, $refProducto, $fecha) = explode('|', $_POST['venta_id']);

        // Verificar existencia de comercial y producto
        $existeComercial = obtenerComercial($pdo, $codComercial);
        $existeProducto = obtenerProducto($pdo, $refProducto);

        if ($existeComercial && $existeProducto) {
            if (eliminarVenta($pdo, $codComercial, $refProducto, $fecha)) {
                $mensaje = "Registro eliminado correctamente en Ventas.";
            } else {
                $mensaje = "Error al eliminar el registro en Ventas.";
            }
        } else {
            $mensaje = "Comercial o Producto no existen.";
        }
    }
}

// Incluir la vista correspondiente
include '../vistas/vistaeliminar.php';
?>
