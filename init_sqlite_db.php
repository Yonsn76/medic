<?php
// Script para inicializar la base de datos SQLite
require_once 'core/autoload.php';

// Asegurarse de que el directorio db existe
if (!file_exists('db')) {
    mkdir('db', 0777, true);
}

$db_path = 'db/bookmedik.sqlite';

// Si el archivo de base de datos ya existe, lo eliminamos para recrearlo
if (file_exists($db_path)) {
    unlink($db_path);
}

// Crear una nueva conexión a la base de datos
$pdo = new PDO('sqlite:' . $db_path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Habilitar claves foráneas
$pdo->exec('PRAGMA foreign_keys = ON;');

// Leer y ejecutar el script SQL
$sql = file_get_contents('schema.sqlite.sql');
$pdo->exec($sql);

echo "Base de datos SQLite inicializada correctamente.\n";
?>
