<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Item</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { margin-top: 20px; padding: 10px 20px; background: #87ADFD; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #6B94E8; }
    </style>
</head>
<body>
    <h1>Crear Nuevo Item</h1>

    <!--
        method="POST"  → los datos van en el body del request, no en la URL
        action="/items" → a dónde se envían (POST /items en nuestro router)
    -->
    <form method="POST" action="/prog3-clase2/items">

        <label>Nombre del Item:
            <!--
                name="name" → define la clave en $_POST['name']
                required    → validación del NAVEGADOR (cliente)
                                No reemplaza la validación del servidor.
                                El usuario puede deshabilitarla con DevTools.
                -->
            <input type="text" name="name" required placeholder="Ej: Laptop HP">
        </label>

        <label>Cantidad:
            <input type="number" name="quantity" min="1" required placeholder="Ej: 5">
        </label>

        <label>Precio (opcional):
            <input type="number" name="price" min="0" step="0.01" placeholder="Ej: 999.99">
        </label>

        <button type="submit">Crear Item</button>
    </form>
</body>
</html>