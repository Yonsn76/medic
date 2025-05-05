<?php
class Database {
	public static $db;
	public static $con;
	public static $sqlite_db_path;

	function __construct(){
		// Define la ruta al archivo de base de datos SQLite
		self::$sqlite_db_path = 'db/bookmedik.sqlite';

		// Asegúrate de que el directorio exista
		if (!file_exists('db')) {
			mkdir('db', 0777, true);
		}
	}

	function connect(){
		try {
			// Crear conexión SQLite
			$con = new PDO('sqlite:' . self::$sqlite_db_path);

			// Configurar PDO para lanzar excepciones en caso de error
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Habilitar claves foráneas
			$con->exec('PRAGMA foreign_keys = ON;');

			return $con;
		} catch(PDOException $e) {
			die("Error de conexión a la base de datos: " . $e->getMessage());
		}
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();

			// Inicializar la base de datos si no existe
			self::initDatabase();
		}
		return self::$con;
	}

	// Método para inicializar la base de datos si no existe
	private static function initDatabase(){
		if (!file_exists(self::$sqlite_db_path) || filesize(self::$sqlite_db_path) == 0) {
			try {
				// Si el archivo no existe o está vacío, ejecuta el script de creación
				$sql_path = __DIR__ . '/../../schema.sqlite.sql';
				if (!file_exists($sql_path)) {
					$sql_path = 'schema.sqlite.sql';
				}

				if (file_exists($sql_path)) {
					$sql = file_get_contents($sql_path);
					self::$con->exec($sql);
					echo "Base de datos inicializada correctamente.";
				} else {
					die("Error: No se pudo encontrar el archivo schema.sqlite.sql");
				}
			} catch (PDOException $e) {
				die("Error al inicializar la base de datos: " . $e->getMessage());
			}
		}
	}
}
?>
