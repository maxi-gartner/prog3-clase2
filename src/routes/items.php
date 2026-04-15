<?php
// =============================================================================
// src/routes/items.php — Rutas del recurso Item
// =============================================================================
// Este archivo es incluido por public/index.php cuando la ruta empieza con /items.
// Maneja tres rutas:
//   GET  /items/new  → muestra el formulario HTML
//   GET  /items      → lista todos los items desde MySQL
//   POST /items      → crea un nuevo item en MySQL
//
// Depende de:
//   - models/Item.php          (cargado en index.php)
//   - src/validators/ItemValidator.php (cargado en index.php)
//   - config/db.php            (cargado en index.php — conexión Eloquent)
// =============================================================================

// ----------------------------------------------------------------------------
// RUTA A: GET /items/new — Mostrar el formulario de creación
// ----------------------------------------------------------------------------
// Esta ruta viene de la Clase 4. No cambió nada en Clase 6.
// Solo sirve el HTML del formulario; no toca la base de datos.
if ($method === 'GET' && $path === '/items/new') {
    include __DIR__ . '/../../views/items_form.php';
    exit;
}

// ----------------------------------------------------------------------------
// RUTA B: GET /items — Listar todos los items desde MySQL
// ----------------------------------------------------------------------------
// NUEVO en Clase 6: antes devolvíamos un array estático.
// Ahora Item::all() ejecuta SELECT * FROM items y devuelve los registros reales.
// Si no hay items, devuelve un array vacío [] — eso es correcto, no es error.
if ($method === 'GET' && $path === '/items') {
    header('Content-Type: application/json; charset=utf-8');

    // Item::all() = SELECT * FROM items
    // Devuelve una Collection de objetos Item.
    // json_encode() la convierte automáticamente a un array JSON.
    $items = Item::all();

    http_response_code(200);
    echo json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ----------------------------------------------------------------------------
// RUTA C: POST /items — Crear un nuevo item en MySQL
// ----------------------------------------------------------------------------
// NUEVO en Clase 6: antes solo validábamos y devolvíamos el array.
// Ahora después de validar usamos Item::create() para persistir en MySQL.
if ($method === 'POST' && $path === '/items') {
    header('Content-Type: application/json; charset=utf-8');

    // ---- Paso 1: Leer datos del body ----
    // Los formularios HTML envían datos en $_POST.
    // Pasamos los datos crudos al validador.
    $data = [
        'name'     => $_POST['name']     ?? '',
        'quantity' => $_POST['quantity'] ?? '',
        'price'    => $_POST['price']    ?? '',
    ];

    // ---- Paso 2: Validar usando ItemValidator ----
    // La lógica de validación vive en su propia clase (clase 3/4).
    // Acá solo llamamos al método y recibimos los errores si los hay.
    $errors = ItemValidator::validate($data);

    // ---- Paso 3: Si hay errores → 400 y cortar ----
    // NUNCA llegamos a la DB si hay errores de validación.
    // exit; es fundamental: sin él PHP seguiría ejecutando el código de abajo.
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'ok'     => false,
            'errors' => $errors
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ---- Paso 4: Persistir en MySQL con Eloquent ----
    // Si llegamos acá, los datos pasaron todas las validaciones.
    // Item::create() ejecuta el INSERT y devuelve el objeto creado,
    // incluyendo el id asignado por AUTO_INCREMENT y los timestamps.
    $price = trim($data['price']);

    $item = Item::create([
        'name'     => htmlspecialchars(trim($data['name'])),
        'quantity' => (int)$data['quantity'],
        'price'    => $price !== '' ? (float)$price : null,
    ]);

    // ---- Paso 5: Responder 201 Created con el item creado ----
    // Devolvemos el item completo (con id y timestamps) para que el cliente
    // sepa el ID asignado sin tener que hacer otro request.
    http_response_code(201);
    echo json_encode([
        'ok'   => true,
        'item' => $item
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}