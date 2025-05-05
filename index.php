<?php
// Iniciar sesión
session_start();

// Incluir autoload
include "core/autoload.php";

// Asegurarse de que la base de datos existe y está inicializada
$db_path = 'db/bookmedik.sqlite';

// Verificar si el directorio db existe
if (!file_exists('db')) {
    mkdir('db', 0777, true);
}

// Si la base de datos no existe, inicializarla
if (!file_exists($db_path) || filesize($db_path) == 0) {
    try {
        // Crear una nueva conexión a la base de datos
        $pdo = new PDO('sqlite:' . $db_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Habilitar claves foráneas
        $pdo->exec('PRAGMA foreign_keys = ON;');

        // Leer y ejecutar el script SQL
        $sql_init = file_get_contents('schema.sqlite.sql');
        if ($sql_init) {
            $pdo->exec($sql_init);
        } else {
            die("Error: No se pudo leer el archivo schema.sqlite.sql");
        }
    } catch (PDOException $e) {
        die("Error al inicializar la base de datos: " . $e->getMessage());
    }
}

// Cargar el módulo principal
$lb = new Lb();
$lb->loadModule("index");
?>