<?php
namespace Controller;

use \Config\Constant;

Class LogController{
    private $path;
    
    public function __construct(){
        $this->path = PATH.'/app/Logs/';
    }

    public function request($message){
		$this->message($message, 'REQUEST');
    }
    
    public function response($message){
		$this->message($message, 'RESPONSE');
    }

    public function error($message){
		$this->message($message, 'ERROR');
    }
	
	public function info($message){
		$this->message($message, 'INFO');
	}

	public function warning($message){
		$this->message($message, 'WARNING');
    }
    
    private function message($message, $level){
        $tIP = $_SERVER['REMOTE_ADDR'];
		$date = date("Y-m-d h:m:s");
		$log_name = $this->path.date('Ymd').'.log';
        $message = "[{$date}] [".basename($this->path)."] [Remote IP - {$tIP}] [{$level}] {$message}".PHP_EOL;
		return file_put_contents($log_name, $message, FILE_APPEND); 
	}
    
    /*
    static public function addToLog($pConnGw,$pUser,$pRequest){
        $ip	= $_SERVER['REMOTE_ADDR'];
        try {
            $query = "
                    INSERT INTO SSB_ACCESS_LOG_WEBSERVICE 
                    (user,date,request,ip) VALUES (:uid,now(),:req,:ip);
            ";

            $stmt = $pConnGw->prepare($query);

            $stmt->bindValue(':uid', $pUser, PDO::PARAM_STR);
            $stmt->bindValue(':req', $pRequest, PDO::PARAM_STR);
            $stmt->bindValue(':ip', $ip, PDO::PARAM_STR);
            $res = $stmt->execute();
        }catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
            die();
        }
        return $res;
    }
    */    
}
?>
