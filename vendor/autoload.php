<?php
	include 'lib/json.php';

	spl_autoload_register(function ($class) {		
		$vdir = PATH.'/vendor/';
		$cdir = PATH.'/app/';
		$ldir = PATH.'/lib/';
				
		$vfile = $vdir . str_replace('\\', '/', $class) . '.php';
		$cfile = $cdir . str_replace('\\', '/', $class) . '.php';
		$lfile = $ldir . str_replace('\\', '/', $class) . '.php';

		if (file_exists($vfile)){
			require $vfile;			
		} 
		elseif(file_exists($cfile)){
			require $cfile;
		} 		
		elseif(file_exists($lfile)){
			require $lfile;
		}
	});
?>
