<?php


// 10 de Octubre del 2014
// Model.php
// @brief agrego la clase Model para reducir las lineas de los modelos

class Model {

	public static function exists($modelname){
		$fullpath = self::getFullpath($modelname);
		$found=false;
		if(file_exists($fullpath)){
			$found = true;
		}
		return $found;
	}

	public static function getFullpath($modelname){
		return "core/modules/".Module::$module."/model/".$modelname.".php";
	}

	public static function many($query, $aclass){
		$array = array();
		$cnt = 0;

		if($query) {
			// Configurar el modo de obtención para PDO
			$query->setFetchMode(PDO::FETCH_ASSOC);

			while($r = $query->fetch()){
				$array[$cnt] = new $aclass;
				foreach ($r as $key => $value) {
					$array[$cnt]->$key = $value;
				}
				$cnt++;
			}
		}
		return $array;
	}
	//////////////////////////////////
	public static function one($query, $aclass){
		$found = null;

		if($query) {
			// Configurar el modo de obtención para PDO
			$query->setFetchMode(PDO::FETCH_ASSOC);

			$r = $query->fetch();
			if($r){
				$found = new $aclass;
				foreach ($r as $key => $value) {
					$found->$key = $value;
				}
			}
		}
		return $found;
	}

}



?>