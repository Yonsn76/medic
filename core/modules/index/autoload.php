<?php
// autoload.php
// 10 octubre del 2014
// esta funcion elimina el hecho de estar agregando los modelos manualmente
// Updated for PHP 7.2+ compatibility

spl_autoload_register(function($modelname) {
	if(Model::exists($modelname)){
		include Model::getFullPath($modelname);
	}

	if(Form::exists($modelname)){
		include Form::getFullPath($modelname);
	}
});

?>