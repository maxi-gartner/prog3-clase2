<?php
// =============================================================================
// models/Item.php — Modelo Eloquent que representa la tabla "items"
// =============================================================================
// Un modelo en Eloquent = una clase PHP que mapea a una tabla MySQL.
// Cada INSTANCIA del modelo  = una FILA de la tabla.
// Los MÉTODOS ESTÁTICOS      = CONSULTAS SQL (sin escribir SQL).
// =============================================================================

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Nombre exacto de la tabla en MySQL
    // Por convención Eloquent asumiría "items" (plural de Item),
    // pero es buena práctica declararlo explícitamente.
    protected $table = 'items';

    // Whitelist de campos asignables masivamente
    // Item::create(['name'=>'X', 'quantity'=>5]) SOLO acepta estos campos.
    // Esto previene que alguien mande campos extra como 'id' o campos admin.
    protected $fillable = [
        'name',
        'quantity',
        'price',
    ];

    // Eloquent maneja created_at y updated_at automáticamente.
    // Solo funciona si la tabla tiene esas dos columnas (las creamos en el SQL).
    public $timestamps = true;

    // Conversión automática de tipos
    // MySQL devuelve todo como strings; $casts convierte al tipo PHP correcto.
    protected $casts = [
        'quantity' => 'integer',  // "5"    → 5
        'price'    => 'float',    // "9.99" → 9.99  (null si es NULL en DB)
    ];
}

// =============================================================================
// MÉTODOS DISPONIBLES (los usamos en src/routes/items.php):
//
//   Item::all()           → SELECT * FROM items
//   Item::find($id)       → SELECT * FROM items WHERE id = $id
//   Item::create([...])   → INSERT INTO items (...) VALUES (...)
//   $item->save()         → UPDATE items SET ... WHERE id = $item->id
//   $item->delete()       → DELETE FROM items WHERE id = $item->id
//
// Todo sin escribir una sola línea de SQL. Eso es el ORM.
// =============================================================================