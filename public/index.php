<?php
// =============================================================================
// public/index.php — Punto de entrada único de la aplicación
// =============================================================================
// Todo request llega acá gracias al .htaccess.
// Este archivo SOLO hace tres cosas:
//   1. Carga dependencias (DB, modelos, validadores)
//   2. Limpia y normaliza la URI
//   3. Despacha a la ruta correcta
//
// NO valida datos. NO consulta la DB. NO arma respuestas.
// Esa lógica vive en src/routes/ y src/validators/.
// =============================================================================

declare(strict_types=1);

// =============================================================================
// CARGA DE DEPENDENCIAS
// =============================================================================

// NUEVO en Clase 6: conexión a MySQL vía Eloquent
// Debe ser lo primero — todo lo demás depende de la conexión.
require_once __DIR__ . '/../config/db.php';

// NUEVO en Clase 6: modelo Item (mapea la tabla items de MySQL)
require_once __DIR__ . '/../models/Item.php';

// Viene de Clase 3/4: validador separado de la lógica de rutas
require_once __DIR__ . '/../src/validators/ItemValidator.php';

// =============================================================================
// LEER Y LIMPIAR LA PETICIÓN HTTP
// =============================================================================

$method     = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestUri = $_SERVER['REQUEST_URI']    ?? '/';
$path       = parse_url($requestUri, PHP_URL_PATH);

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir  = str_replace('\\', '/', dirname($scriptName));

// Limpiar prefijo del proyecto: /prog3-clase2/public/items → /items
$path = preg_replace('#^/prog3-clase2(/public)?#', '', $path);
$path = '/' . ltrim((string)$path, '/');
if ($path === '//') $path = '/';

// =============================================================================
// DESPACHO DE RUTAS
// =============================================================================

// ----------------------------------------------------------------------------
// Ruta: GET /health — Estado del servidor (Clase 2, sin cambios)
// ----------------------------------------------------------------------------
if ($method === 'GET' && $path === '/health') {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode([
        'status'      => 'ok',
        'timestamp'   => date('Y-m-d H:i:s'),
        'php_version' => phpversion(),
        'server'      => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// ----------------------------------------------------------------------------
// Ruta: GET / — Raíz (Clase 2, actualizada con nuevas rutas)
// ----------------------------------------------------------------------------
if ($method === 'GET' && $path === '/') {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode([
        'message' => 'API funcionando',
        'rutas'   => [
            'GET  /health'     => 'Estado del servidor',
            'GET  /items/new'  => 'Formulario para crear item',
            'GET  /items'      => 'Listar todos los items',
            'POST /items'      => 'Crear un item nuevo',
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// ----------------------------------------------------------------------------
// Rutas: /items/* — Delegamos a src/routes/items.php
// ----------------------------------------------------------------------------
// El router no sabe nada de items. Solo detecta el prefijo y delega.
// Dentro de items.php, $method y $path siguen disponibles.
if (str_starts_with($path, '/items')) {
    require __DIR__ . '/../src/routes/items.php';
    exit;
}

// ----------------------------------------------------------------------------
// Fallback: 404 Not Found
// ----------------------------------------------------------------------------
header('Content-Type: application/json; charset=utf-8');
http_response_code(404);
echo json_encode([
    'error' => 'Not Found',
    'path'  => $path
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);