<?php
namespace Model;

use \PDO;
use \Config\Database;
use \Services_JSON;

class SurveillanceModel extends Database{	 
	private $json;
	
	public function __construct(){
		$this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->getConnSurveillance();
	}
	
	public function insertTransaction($fields, $values){
		$query = "INSERT INTO `TRANSACTION_TES_BALIKPAPAN` ";
		//$query = "INSERT INTO `TRANSACTION` ";
		$query.= "(".implode(',',$fields).") VALUES";
		$query.= "('".implode("','",$values)."')";
		
		$result = false;
		try {
			$stmt	= $this->connSV->prepare($query);
			$result = $stmt->execute();		
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}      					
	
	public function insertDuplicateTransaction($fields, $values){
		$query = "INSERT INTO `TRANSACTION_DUPLICATE_LOG` ";
		$query.= "(".implode(',',$fields).") VALUES";
		$query.= "('".implode("','",$values)."')";
		
		$result = false;
		try {
			$stmt	= $this->connSV->prepare($query);
			$result = $stmt->execute();		
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}      					
	
	public function getCountTrans($npwpd,$bill,$date,$amount){
		$result = array('exist' => 0, 'data' => null);
		/*
		$query = "
			SELECT * FROM TRANSACTION_TES_BALIKPAPAN 
			WHERE
			NPWPD = '{$npwpd}' AND 
			BillNumber = '{$bill}' AND
			TransactionDate='{$date}' AND 
			TransactionAmount='{$amount}'";
		*/
		
		$query = "
			SELECT * FROM TRANSACTION 
			WHERE
			NPWPD = '{$npwpd}' AND 
			BillNumber = '{$bill}' AND
			TransactionDate='{$date}' AND 
			TransactionAmount='{$amount}'";
		
		
		try {
			$stmt = $this->connSV->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				$result['data'] = $stmt->fetch(PDO::FETCH_OBJ);								
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $this->json->encode($result);
	}   	
	
	public function insertAlarm($fields, $values){
		$query = "INSERT INTO `ALARM` ";
		$query.= "(".implode(',',$fields).") VALUES";
		$query.= "('".implode("','",$values)."')";
		
		$result = false;
		try {
			$stmt	= $this->connSV->prepare($query);
			$result = $stmt->execute();		
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}      					
	
}
?>
