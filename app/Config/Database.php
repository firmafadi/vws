<?php
namespace Config;

use \Core\DbAccess;

class Database extends DbAccess{
	private $hostGwBPHTB = '127.0.0.1';
	private $userGwBPHTB = 'root';
	private $passGwBPHTB = 'rahasiav51';
	private $dbGwBPHTB = 'GW_SSB_DEMO';

	private $hostGwPATDA = '127.0.0.1';
	private $userGwPATDA = 'root';
	private $passGwPATDA = 'rahasiav51';
	private $dbGwPATDA = 'GW_PATDA_PALEMBANG';
	
	private $hostSwPATDA = '127.0.0.1';
	private $userSwPATDA = 'root';
	private $passSwPATDA = 'rahasiav51';
	private $dbSwPATDA = 'SW_PATDA_TEGAL';

	private $hostSwBPHTB = '127.0.0.1';
	private $userSwBPHTB = 'root';
	private $passSwBPHTB = 'rahasiav51';
	private $dbSwBPHTB = 'GW_SSB_DEMO';
	
	private $hostSurveillance = '192.168.26.112';
	private $userSurveillance = 'root';
	private $passSurveillance = 'rahasiav51';
	private $dbSurveillance = 'SURVEILLANCE_DB';
	
	private $hostGwPBB = '127.0.0.1';
	private $userGwPBB= 'root';
	private $passGwPBB = 'rahasiav51';
	private $dbGwPBB = 'GW_PBB_PALEMBANG';	
	
	protected $connGW;
	protected $connSW;
	protected $connSV;
	
	protected function getConnGwBPHTB(){										  
		$this->connGW =  $this->connectDB($this->hostGwBPHTB, 
										  $this->dbGwBPHTB, 
										  $this->userGwBPHTB, 
										  $this->passGwBPHTB);
	}
	
	protected function getConnSwBPHTB(){
		$this->connSW =  $this->connectDB($this->hostSwBPHTB, 
										  $this->dbSwBPHTB, 
										  $this->userSwBPHTB, 
										  $this->passSwBPHTB);
	}			
	
	protected function getConnSurveillance(){
		$this->connSV =  $this->connectDB($this->hostSurveillance, 
										  $this->dbSurveillance, 
										  $this->userSurveillance, 
										  $this->passSurveillance);
	}
	
	protected function getConnGwPBB(){
		$this->connGW =  $this->connectDB($this->hostGwPBB, 
										  $this->dbGwPBB, 
										  $this->userGwPBB, 
										  $this->passGwPBB);
	}
	protected function getConnGwPATDA(){										  
		$this->connGW =  $this->connectDB($this->hostGwPATDA, 
										  $this->dbGwPATDA, 
										  $this->userGwPATDA, 
										  $this->passGwPATDA);
	}			
	protected function getConnSwPATDA(){										  
		$this->connSW =  $this->connectDB($this->hostSwPATDA, 
										  $this->dbSwPATDA, 
										  $this->userSwPATDA, 
										  $this->passSwPATDA);
	}				
}
?>
