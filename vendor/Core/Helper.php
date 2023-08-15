<?php
namespace Core;

use \DateTime;

class Helper{
	// echo result from service request
	public static function echoResponse($pResult){
		header('Content-type: application/json; charset=utf-8');
		echo $pResult; 		
	}
	
	// convert date string to date formated
	public static function convertDate($date){
		$date = DateTime::createFromFormat('dmY',$date);
		return $date->format('d/m/Y');
	}	

	// generate unique id
	public static function UUID($sDelim = ''){
		return sprintf('%04x%04x%s%04x%s%03x4%s%04x%s%04x%04x%04x',
			mt_rand(0, 65535), mt_rand(0, 65535),$sDelim,mt_rand(0, 65535),$sDelim,mt_rand(0, 4095),
			$sDelim,bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)),$sDelim,
			mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
		);
	}

	// convert second to hour minute second
	public static function convertTime($t,$f=':'){
		return ($t< 0 ? '-' : '') . sprintf("%02d%s%02d%s%02d", floor(abs($t)/3600), $f, (abs($t)/60)%60, $f, abs($t)%60); 
	}
}
?>
