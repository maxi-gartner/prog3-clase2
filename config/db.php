<?php
// =============================================================================
// config/db.php — Bootstrap de Eloquent ORM (conexión a MySQL)
// =============================================================================
// Este archivo hace UNA sola cosa: decirle a Eloquent cómo conectarse a MySQL.
// Se ejecuta una vez al arrancar la app (lo incluimos desde public/index.php).
// =============================================================================

// PASO 1: Cargar el autoloader de Composer
// Composer genera vendor/autoload.php — sabe dónde está cada clase instalada.
// Sin esto PHP no conoce "Capsule", "Model", ni ninguna clase de Eloquent.
require_once __DIR__ . '/../vendor/autoload.php';

// PASO 2: Importar Capsule con su namespace completo
// Capsule es el "contenedor" de Eloquent standalone.
// En Laravel completo esto ya viene configurado; acá lo hacemos a mano.
use Illuminate\Database\Capsule\Manager as Capsule;

// PASO 3: Crear la instancia de Capsule
$capsule = new Capsule;

// PASO 4: Agregar la configuración de conexión
// Credenciales por defecto en XAMPP Windows:
//   host: localhost | database: items_db | username: root | password: ''
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'items_db',
    'username'  => 'root',
    'password'  => '',            // vacío en XAMPP por defecto
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// PASO 5: Hacer la instancia accesible globalmente
// Permite usar los modelos en cualquier parte del código.
$capsule->setAsGlobal();

// PASO 6: Activar Eloquent ORM
// Sin esta línea las clases que extiendan Model no funcionan.
$capsule->bootEloquent();