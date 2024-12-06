<?php

// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir el archivo de funciones para manejar la base de datos
include_once 'funciones.inc.php';

// Conectar a la base de datos
$pdo = conectarDB();

// Definir las tablas disponibles para consulta
$tablas = ['Comerciales', 'Productos', 'Ventas', 'VentasPorComercio'];
$data = [];
$dataVentasPorComercio = [];
$mensaje = '';

// Obtener la tabla seleccionada del formulario
$tablaSeleccionada = $_POST['tabla'] ?? '';
$comercioSeleccionado = $_POST['comercio'] ?? '';

// Procesar el formulario de consulta si se recibe una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($tablaSeleccionada) {
        case 'Comerciales':
            // Obtener datos de la tabla Comerciales
            $data = obtenerComerciales($pdo);
            break;
        case 'Productos':
            // Obtener datos de la tabla Productos
            $data = obtenerProductos($pdo);
            break;
        case 'Ventas':
            // Obtener datos de la tabla Ventas
            $data = obtenerVentas($pdo);
            break;
        case 'VentasPorComercio':
            // Obtener datos de las ventas por comercio seleccionado
            $dataVentasPorComercio = obtenerVentasPorComercio($pdo, $comercioSeleccionado);
            break;
    }
}

// Incluir la vista correspondiente para mostrar los datos obtenidos
include '../vistas/vistaconsultar.php';
?>
