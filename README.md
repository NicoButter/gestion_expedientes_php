# Gestión de Expedientes

Este proyecto, desarrollado enteramente en PHP, es una herramienta externa diseñada para administrar los expedientes almacenados en un sistema de expedientes realizado con ScriptCase.

## Descripción

La herramienta permite realizar operaciones de gestión sobre los expedientes del sistema, ofreciendo una interfaz intuitiva para la administración y consulta de los mismos.

## Características

- Gestión de expedientes: creación, edición, eliminación y consulta.
- Integración con el sistema de expedientes realizado con ScriptCase.
- Interfaz de usuario sencilla e intuitiva.

## Requisitos

- PHP 7.4 o superior
- Servidor web (Apache, Nginx, etc.)
- Acceso al sistema de expedientes realizado con ScriptCase

## Instalación

1. Clona este repositorio en tu servidor web:

    ```bash
    git clone https://github.com/tuusuario/gestion_expedientes.git
    ```

2. Configura los detalles de conexión a la base de datos en el archivo `config.php`.

3. Asegúrate de que tu servidor web tenga permisos de escritura en las carpetas necesarias.

4. Accede a la URL donde has desplegado el proyecto para comenzar a usar la herramienta.

## Configuración

Asegúrate de configurar correctamente el archivo `config.php` con los detalles de conexión a la base de datos y otros parámetros necesarios para la integración con el sistema de expedientes realizado con ScriptCase.

```php
<?php
// Ejemplo de configuración de base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'nombre_base_datos');
define('DB_USER', 'usuario_base_datos');
define('DB_PASS', 'contraseña_base_datos');
