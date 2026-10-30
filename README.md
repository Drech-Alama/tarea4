# Nexo Inventario

Sistema web de inventario para accesorios informáticos, desarrollado con Laravel 12, PHP 8.2, MySQL y vistas Blade. Incluye CRUD de productos, categorías, proveedores y secciones, movimientos de entrada y salida de stock con historial, dashboard con estadísticas reales, búsqueda, filtros y paginación responsive.

## Requisitos

- PHP 8.2 o superior con `pdo_mysql`.
- Composer 2.
- MySQL iniciado y la base de datos `crud_laravel` creada.
- Bootstrap 5.3 y Bootstrap Icons se cargan desde CDN, por lo que la interfaz completa necesita conexión a Internet.

## Iniciar el proyecto

La configuración local de `.env` apunta a la base `crud_laravel` de XAMPP en `127.0.0.1:3306` con el usuario `root` sin contraseña, según la configuración local de phpMyAdmin. Si tu instalación usa otros datos, actualiza los valores `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.

Desde la carpeta del proyecto, ejecuta:

```powershell
composer install
php artisan config:clear
php artisan migrate
php artisan serve
```

Abre <http://127.0.0.1:8000>. Las migraciones son incrementales; no uses `migrate:fresh` ni `db:wipe` si necesitas conservar datos.

## Acceso de usuarios

El panel requiere iniciar sesión y no permite registro público. Para crear una cuenta de forma segura, ejecuta:

```powershell
php artisan app:user-create
```

El comando solicita nombre, correo y contraseña de forma interactiva; la contraseña no se muestra en pantalla. Luego inicia sesión en <http://127.0.0.1:8000/login>. Las rutas privadas redirigen allí si no has iniciado sesión.

## Datos de demostración

Las migraciones no insertan datos de muestra. Para cargar categorías, proveedores, secciones y productos de ejemplo de forma intencional, ejecuta:

```powershell
php artisan db:seed --class="Database\Seeders\DemoInventorySeeder"
```

El seeder utiliza `firstOrCreate`, por lo que puedes volver a ejecutarlo sin duplicar sus registros de demostración.

## Pruebas

```powershell
php artisan test
```

Las pruebas utilizan una base SQLite en memoria y no modifican la base de datos MySQL del proyecto.

## Módulos

- **Dashboard:** estadísticas de productos, categorías, proveedores, secciones y stock bajo.
- **Acceso:** inicio y cierre de sesión, rutas privadas, protección CSRF, regeneración de sesión, opción «Recordarme» y límite de intentos.
- **Perfil:** edición del nombre y correo, validación de correo único y cambio de contraseña con verificación de la contraseña actual.
- **Productos:** registro, edición y eliminación; cada producto requiere categoría, proveedor y sección.
- **Movimientos de stock:** registra entradas y salidas con validación de existencias, saldo anterior/nuevo y detalle. Los cambios directos de stock al crear o editar productos también quedan en el historial.
- **Trazabilidad:** los productos existentes reciben un saldo inicial al habilitar la migración. El historial se conserva aunque se elimine un producto.
- **Secciones:** administración de ubicaciones de inventario y protección contra eliminación cuando hay productos asociados.
- **Productos:** búsqueda por nombre, filtros por categoría/sección/stock y alertas de inventario.
- **Categorías:** administración y protección contra eliminación cuando hay productos asociados.
- **Proveedores:** administración y protección contra eliminación cuando hay productos asociados.
