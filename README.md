# Advertencia
Software legacy.

# Acerca de SISSET

Sistema integral de Servicios para Secretarías de Tránsito, desarrollado en PHP usando Laravel Framework v5 y actualizada a la V6.

Este software está enfocado para las secretarías de tránsito y transporte que requieran implementar soluciones digitales orientadas a los servicios en línea.

Actualmente dispone de los siguiente módulos:

-   Tarjetas de operación.
-   Comparendos.
-   Turnos (laravel echo).
-   Trámites.
-   Correspondencia.
-   Gestión de usuarios.

## Variables de entorno

Antes de proceder a instalar y configurar la aplicación, se deben configurar las siguientes variables de entorno en el archivo .env:

`APP_URL`

`RECAPTCHA_SITE_KEY`

`RECAPTCHA_SECRET_KEY`

También se deben configurar las variables de entorno para la cache, bases de datos y sesión de acuerdo a la [documentación](https://laravel.com/docs/6.x/) de Laravel.

## Instalación

Se requiere PHP 7.2, composer, node js, npm y librerías requeridas por Laravel Framework y libsodium para la encriptación de datos.

```bash
  composer install
  npm install
  npm run build
  php artisan migrate
  php artisan db:seed
```

# Licencia
SISSET es un software de código abierto licenciado bajo la [GNU Affero General Public License version 3](https://opensource.org/license/agpl-v3).
