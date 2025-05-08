SISTEMA DE CITAS MÉDICAS CON SQLITE
=====================================

Sistema de gestión de citas médicas usando PHP, SQLite y Bootstrap. Esta aplicación permite administrar pacientes, médicos, citas y generar reportes.

CARACTERÍSTICAS
--------------
- Gestión de Pacientes y Médicos
- Creación de Citas: Asunto, Paciente, Médico, Fecha, Hora, Enfermedad, Síntomas, Medicamentos, Costo
- Vista de Calendario 
- Integración de Áreas con Médicos
- Integración de estado de cita y tipo de pago 
- Reportes por paciente, médico, rango de fecha, estado, tipo de pago

REQUISITOS
---------
- XAMPP 7.0 o superior (incluye PHP, Apache)
- Extensión PDO de PHP habilitada (incluida en XAMPP por defecto)
- Extensión SQLite3 de PHP habilitada (incluida en XAMPP por defecto)

INSTALACIÓN EN XAMPP
-------------------
1. Instalar XAMPP:
   - Descarga e instala XAMPP desde https://www.apachefriends.org/
   - Asegúrate de instalar al menos PHP 7.0 o superior

2. Descargar el código:
   - Descarga el código de este repositorio
   - Descomprime el archivo en la carpeta 'htdocs' de XAMPP
     - Ubicación típica en Windows: C:\xampp\htdocs\Citas-Medicas
     - Ubicación típica en macOS: /Applications/XAMPP/htdocs/Citas-Medicas
     - Ubicación típica en Linux: /opt/lampp/htdocs/Citas-Medicas

3. Iniciar XAMPP:
   - Abre el Panel de Control de XAMPP
   - Inicia el servicio de Apache
   - No es necesario iniciar MySQL ya que usamos SQLite

4. Configurar la aplicación:
   - Abre tu navegador web
   - Accede a http://localhost/Citas-Medicas/
   - Sigue los pasos de configuración inicial

PASOS PARA CONFIGURAR LA APLICACIÓN
----------------------------------
1. Inicializar la base de datos:
   - Accede a http://localhost/Citas-Medicas/init_db_web.php
   - Haz clic en el botón "Inicializar Base de Datos"
   - Esto creará la estructura de la base de datos SQLite

2. Verificar el estado de la base de datos (opcional):
   - Accede a http://localhost/Citas-Medicas/check_db.php
   - Esta página te mostrará si la base de datos está correctamente configurada

3. Agregar un usuario administrador:
   - Accede a http://localhost/Citas-Medicas/add_admin_user.php
   - Completa el formulario con los datos del usuario administrador
   - Por defecto, se sugiere:
     - Usuario: admin
     - Contraseña: admin
   - Haz clic en "Agregar Usuario"

4. Acceder a la aplicación:
   - Accede a http://localhost/Citas-Medicas/
   - Inicia sesión con las credenciales que creaste

SOLUCIÓN DE PROBLEMAS COMUNES
----------------------------
No se puede acceder a la aplicación:
- Asegúrate de que el servicio de Apache esté iniciado en XAMPP
- Verifica que la URL sea correcta: http://localhost/Citas-Medicas/

Error al inicializar la base de datos:
- Asegúrate de que PHP tenga permisos de escritura en la carpeta 'db'
- Verifica que las extensiones PDO y SQLite3 estén habilitadas en PHP

No puedo iniciar sesión:
- Verifica que hayas creado un usuario usando add_admin_user.php
- Asegúrate de usar las credenciales correctas
- Si olvidaste la contraseña, puedes crear un nuevo usuario administrador

La página muestra errores de PHP:
- Asegúrate de que estás usando PHP 7.0 o superior
- Verifica que todas las extensiones requeridas estén habilitadas

ESTRUCTURA DE ARCHIVOS IMPORTANTES
--------------------------------
- index.php: Punto de entrada principal de la aplicación
- init_db_web.php: Script para inicializar la base de datos
- add_admin_user.php: Script para agregar usuarios administradores
- check_db.php: Script para verificar el estado de la base de datos
- db/bookmedik.sqlite: Archivo de base de datos SQLite (se crea automáticamente)

NOTAS ADICIONALES
---------------
- Esta aplicación usa SQLite en lugar de MySQL, lo que facilita su instalación y uso sin necesidad de configurar un servidor de base de datos separado.
- Los datos se almacenan en el archivo db/bookmedik.sqlite dentro de la carpeta de la aplicación.
- Para hacer una copia de seguridad, simplemente copia este archivo.

BASADO EN EL PROYECTO ORIGINAL
-----------------------------
Sistema de Citas Médicas original desarrollado por Grupo Aizen, migrado a SQLite para facilitar su instalación y uso.
