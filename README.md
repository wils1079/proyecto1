# Proyecto CRUD -- Mesa de Ayuda (PHP + PostgreSQL)

##  Descripción

Este proyecto es un sistema de **Mesa de Ayuda** desarrollado en **PHP**
con base de datos **PostgreSQL**, que permite gestionar tickets de
soporte. Fue adaptado y desplegado en la nube para funcionar sin
necesidad de usar XAMPP en local.

##  Servicio de despliegue elegido

El servicio de despliegue utilizado fue **Replit**, ya que permite
correr aplicaciones PHP con PostgreSQL de manera sencilla y generar una
URL pública para acceder al CRUD.

URL del despliegue:https://cb9929d9-9bc7-4d00-9164-df4f9de1e54f-00-39nrtju2y6owy.riker.replit.dev/

##  Configuración de variables de entorno

En Replit, se configuraron las siguientes variables de entorno para la
conexión a PostgreSQL:

-   `DB_HOST` → Host de la base de datos proporcionado por Replit.\
-   `DB_USER` → Usuario de la base de datos.\
-   `DB_PASS` → Contraseña del usuario.\
-   `DB_NAME` → Nombre de la base de datos.

El archivo `conexion.php` fue modificado para conectarse a PostgreSQL en
lugar de MySQLi.

##  Workflow de despliegue (CI/CD)

El repositorio se configuró con un workflow para que, al hacer **git
push**, se compile y despliegue automáticamente el CRUD en Replit.

Ejemplo de configuración de servidor en Replit:

``` bash
php -S 0.0.0.0:5000 -t parcial1ws
```

Esto indica que el servidor PHP debe correr en el puerto 5000 y servir
la carpeta `parcial1ws`.

##  Dificultades encontradas y solución

-   **Conversión de MySQL a PostgreSQL**: el CRUD original estaba hecho
    con MySQLi. Se tuvieron que convertir las consultas y adaptarlas a
    PostgreSQL (ejemplo: uso de `SERIAL` en lugar de `AUTO_INCREMENT`).\
-   **Configuración en Replit**: al inicio hubo un error con la creación
    de la base de datos, pero se solucionó usando las variables de
    entorno que ya proporciona Replit.\
-   **Archivos faltantes (favicon 404)**: se presentó un error 404 menor
    al no encontrar el favicon, pero no afecta el funcionamiento del
    CRUD.

##  Estado actual

 El CRUD está funcionando en línea.\
 Se pueden crear usuarios, iniciar sesión, generar y gestionar
tickets.\
 URL accesible públicamente.
