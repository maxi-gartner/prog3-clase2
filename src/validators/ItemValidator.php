<?php
// =============================================================================
// src/validators/ItemValidator.php — Validaciones del recurso Item
// =============================================================================
// Separar la validación en su propia clase tiene un propósito claro:
// el router no valida, la ruta no valida — este archivo es el único
// responsable de las reglas de negocio de Item.
// Si cambian las reglas, cambia UN solo archivo.
// =============================================================================

class ItemValidator
{
    // -------------------------------------------------------------------------
    // validate() — Valida los datos de entrada para crear/editar un Item
    // -------------------------------------------------------------------------
    // Recibe un array con los datos crudos del request.
    // Devuelve un array de errores (vacío = todo OK).
    //
    // Campos validados:
    //   name     → obligatorio, string, 3-100 caracteres
    //   quantity → obligatorio, entero positivo (> 0)
    //   price    → OPCIONAL, si viene debe ser numérico y >= 0
    // -------------------------------------------------------------------------
    public static function validate(array $data): array
    {
        $errors = [];

        // ---- Validar name ----
        $name = trim($data['name'] ?? '');

        if ($name === '') {
            $errors['name'] = 'El campo name es obligatorio';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'El campo name debe tener al menos 3 caracteres';
        } elseif (strlen($name) > 100) {
            $errors['name'] = 'El campo name no puede superar 100 caracteres';
        }

        // ---- Validar quantity ----
        // ctype_digit() — SOLO acepta strings de dígitos positivos.
        // Más estricto que is_numeric() que acepta decimales, negativos,
        // notación científica ("3.14", "-5", "2e3").
        $quantity = trim($data['quantity'] ?? '');

        if ($quantity === '') {
            $errors['quantity'] = 'El campo quantity es obligatorio';
        } elseif (!ctype_digit($quantity)) {
            $errors['quantity'] = 'El campo quantity debe ser un número entero positivo';
        } elseif ((int)$quantity <= 0) {
            $errors['quantity'] = 'El campo quantity debe ser mayor que 0';
        }

        // ---- Validar price (opcional) ----
        $price = trim($data['price'] ?? '');

        if ($price !== '') {
            if (!is_numeric($price)) {
                $errors['price'] = 'El campo price debe ser un número';
            } elseif ((float)$price < 0) {
                $errors['price'] = 'El campo price no puede ser negativo';
            }
        }

        return $errors;
    }
}