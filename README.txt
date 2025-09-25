README - Proyecto de Gestión de Tickets (Actividad 3 / Parcial 1)

1. ¿Qué se hizo?
----------------
Se desarrolló un sistema web de gestión de tickets con soporte para tres roles de usuario: Cliente, Administrador y Técnico.
El sistema permite el registro y login de usuarios, la creación y administración de tickets, asignación a técnicos, notificaciones,
y dashboards diferenciados según el rol de cada usuario.

2. ¿Cómo se hizo?
-----------------
- Se utilizó PHP (con MySQLi) como lenguaje de programación para la lógica de servidor.
- Se implementaron sentencias preparadas y uso de password_hash para mayor seguridad en contraseñas y consultas.
- Se estructuró la base de datos en MySQL con las tablas principales:
  * usuarios: almacena la información de clientes, administradores y técnicos.
  * tickets: almacena los tickets creados, incluyendo estado, técnico asignado y referencias a usuarios.
  * notificaciones: almacena los mensajes o avisos dirigidos a los usuarios cuando un ticket cambia de estado o se asigna.
- Se creó un sistema de login que dirige al dashboard correspondiente según el perfil del usuario.
- Se desarrollaron dashboards diferenciados:
  * Cliente: puede crear, ver y eliminar sus tickets, y recibir notificaciones.
  * Administrador: puede ver todos los tickets y usuarios, crear técnicos, asignar tickets y cambiar estados.
  * Técnico: puede ver solo los tickets asignados y cambiar su estado.
- Se implementó un archivo CSS (styles.css) común para dar estética uniforme a todas las vistas.
- Se empaquetó todo en un archivo parcial_1.zip junto con el script SQL parcial_1.sql que crea/actualiza las tablas.

3. ¿Para qué?
-------------
El objetivo del sistema es servir como una herramienta de mesa de ayuda (help desk) o soporte técnico,
donde clientes pueden reportar incidencias, técnicos darles seguimiento y administradores gestionar tanto a usuarios como a tickets.
Este proyecto ejemplifica un CRUD avanzado con roles diferenciados y control de permisos, siendo útil como práctica de desarrollo web fullstack.

