<?php

class Executor {

	public static function doit($sql){
		try {
			$con = Database::getCon();
			$result = $con->query($sql);

			// Para consultas INSERT, obtenemos el último ID insertado
			if (stripos($sql, 'INSERT') === 0) {
				$lastId = $con->lastInsertId();
				return array($result, $lastId);
			}

			return array($result, null);
		} catch (PDOException $e) {
			echo "Error en la consulta SQL: " . $e->getMessage();
			return array(false, null);
		}
	}

	// Método para ejecutar consultas preparadas (más seguro para datos de usuario)
	public static function doitWithParams($sql, $params = array()){
		try {
			$con = Database::getCon();
			$stmt = $con->prepare($sql);
			$stmt->execute($params);

			// Para consultas INSERT, obtenemos el último ID insertado
			if (stripos($sql, 'INSERT') === 0) {
				$lastId = $con->lastInsertId();
				return array($stmt, $lastId);
			}

			return array($stmt, null);
		} catch (PDOException $e) {
			echo "Error en la consulta SQL preparada: " . $e->getMessage();
			return array(false, null);
		}
	}
}
?>