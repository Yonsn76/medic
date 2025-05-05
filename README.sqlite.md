# Citas Médicas con SQLite

Sistema de Citas Médicas usando PHP, SQLite y Bootstrap.

## Características

- Gestión de Pacientes y Médicos
- Creación de Citas: Asunto, Paciente, Médico, Fecha, Hora, Enfermedad, Síntomas, Medicamentos, Costo
- Vista de Calendario
- Integración de Áreas con Médicos
- Integración de estado de cita y tipo de pago
- Reportes por paciente, médico, rango de fecha, estado, tipo de pago

## Requisitos

- PHP 7.0 o superior
- Extensión PDO de PHP habilitada
- Extensión SQLite3 de PHP habilitada

## Instalación

1. Clona o descarga este repositorio en tu servidor web
2. Asegúrate de que el directorio `db` tenga permisos de escritura
3. Inicializa la base de datos usando uno de estos métodos:

   **Método 1: A través del navegador web (recomendado)**
   - Accede a `http://tu-servidor/init_db_web.php`
   - Haz clic en el botón "Inicializar Base de Datos"

   **Método 2: Desde la línea de comandos (si tienes acceso)**
   ```
   php init_db.php
   ```

4. Accede a la aplicación a través de tu navegador web

## Credenciales por defecto

- Usuario: admin
- Contraseña: admin

## Migrado a SQLite

Esta versión ha sido migrada para usar SQLite en lugar de MySQL, lo que facilita su instalación y uso sin necesidad de configurar un servidor de base de datos separado.

## Notas de la migración

- Se ha actualizado el código para usar PDO en lugar de mysqli
- Se han adaptado las consultas SQL para ser compatibles con SQLite
- Se ha creado un esquema de base de datos específico para SQLite
- Se ha implementado un sistema de inicialización automática de la base de datos

## Basado en el proyecto original

Sistema de Citas Médicas original desarrollado por Evilnapsis.
