<?php
namespace Controller;

use \Core\Helper;
use \Core\Request;
use \Model\SurveillanceModel;
use \Controller\AuthController;
use \Controller\LogController;
use \Validate\SurveillanceValidate;
use \Services_JSON;

class SurveillanceController{
	private $json;
	private $auth;
	private $svModel;
	private $log;
	
    public function __construct(){		
		$this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->auth = new AuthController;
		$this->svModel = new SurveillanceModel;		
		$this->log = new LogController;
    }
	    
	public function checkConnectionMobilePOS() {
		header('Access-Control-Allow-Origin:*'); 
		echo json_encode(array("status"=>"ok"));
	}
     
    public function receiveTransMobilePOS(){	
        header('Access-Control-Allow-Origin:*'); 
		
		$result = array('success' => 0, 'message' => 'Data gagal disimpan.');		
		if (Request::type() === 'POST') {		
			$tParams = explode(',',$_POST['A1']);			
			$masa_pajak1= explode(' ',$tParams['9']);
			$masa_pajak1 = explode('-',$masa_pajak1['0']);
			$masa_pajak2 = substr($masa_pajak1['0'],2);
			
			// build fields and values
			$data = array(
				'TransactionNumber'		=>	Helper::UUID(),
				'NPWPD'					=>	$tParams['0'],
				'NOP'					=>	$tParams['11'],
				'TaxType'				=>	(int) $tParams['1'],
				'DeviceId'				=>	'',
				'TransactionType'		=>	$tParams['2'],
				'BranchCode'			=>	$tParams['3'],
				'TaxPeriode'			=>	$masa_pajak1['2'].$masa_pajak2,
				'BillNumber'			=>	$tParams['4'],
				'Quantity'				=>	$tParams['5'],
				'invoice'				=>	$tParams['6'],
				'TransactionDescription'=>	$tParams['7'],
				'TransactionAmount'		=>	$tParams['8'],
				'TaxAmount'				=>	($tParams['10']/100)*$tParams['8'],
				'TransactionDate'		=>	$tParams['9'],
				'TransactionSource'		=>	'5',
				'NotAdmitReason'		=>	'',
				'TaxInfo'				=>	''
			);		
			
			$fields = array_keys($data);
			$values = array();
			foreach($data as $val){
				$values[] = str_replace("'","",sprintf("%s",$val));
			}
			
			// insert transaction to db surveillance
			$tValidate = $this->json->decode($this->svModel->getCountTrans($tParams['0'],$tParams['4'],$tParams['9'],$tParams['8']));			
			// data doesn't exist
			if($tValidate->exist == 0){
				$save = $this->svModel->insertTransaction($fields, $values);
				if($save){
					$result['success'] = 1;				
					$result['message'] = 'Data berhasil di simpan';				
				}else{
					$result['success'] = 0;				
					$result['message'] = 'Data gagal disimpan';											
				}			
			}else{
				//$result['success'] = 0;				
				//$result['message'] = 'Data sudah ada';							
				
				$save = $this->svModel->insertDuplicateTransaction($fields, $values);
				if($save){
					$result['success'] = 1;				
					$result['message'] = 'Data duplicate berhasil di simpan';				
				}else{
					$result['success'] = 0;				
					$result['message'] = 'Data gagal disimpan';							
				}			
			}
		}else{
			$result['message'] = 'Bad request, use POST type';
		}		
		
		Helper::echoResponse($this->json->encode($result));					
	}

    public function receiveTransMobilePOSJson(){	
        header('Access-Control-Allow-Origin: *'); 
                
		$pJsonReq	= file_get_contents('php://input');			
		$tJsonReq	= $this->json->decode($pJsonReq);		
		$result		= array();		
		
		
		$result = array('success' => false, 'message' => 'Data gagal disimpan.');	
			
		$masa_pajak1= explode(' ',$tJsonReq->TransactionDate);
		$masa_pajak1 = explode('-',$masa_pajak1['0']);
		$masa_pajak2 = substr($masa_pajak1['0'],2);
		
		// build fields and values
		$data = array(
			'TransactionNumber'		=>	Helper::UUID(),
			'NPWPD'					=>	$tJsonReq->NPWPD,
			'NOP'					=>	$tJsonReq->NOP,
			'TaxType'				=>	(int) $tJsonReq->TaxType,
			'DeviceId'				=>	'',
			'TransactionType'		=>	$tJsonReq->TransactionType,
			'BranchCode'			=>	$tJsonReq->BranchCode,
			'TaxPeriode'			=>	$masa_pajak1['2'].$masa_pajak2,
			'BillNumber'			=>	$tJsonReq->BillNumber,
			'Quantity'				=>	$tJsonReq->Quantity,
			'invoice'				=>	$tJsonReq->Invoice,
			'TransactionDescription'=>	$tJsonReq->TransactionDescription,
			'TransactionAmount'		=>	$tJsonReq->TransactionAmount,
			'TaxAmount'				=>	($tJsonReq->TaxAmount/100)*$tJsonReq->TransactionAmount,
			'TransactionDate'		=>	$tJsonReq->TransactionDate,
			'TransactionSource'		=>	'5',
			'NotAdmitReason'		=>	'',
			'TaxInfo'				=>	''
		);		
		
		$fields = array_keys($data);
		$values = array();
		foreach($data as $val){
			$values[] = str_replace("'","",sprintf("%s",$val));
		}
		
		// insert transaction to db surveillance
		$tValidate = $this->json->decode($this->svModel->getCountTrans($tJsonReq->NPWPD,$tJsonReq->BillNumber,$tJsonReq->TransactionDate,$tJsonReq->TransactionAmount));
		// data doesn't exist
		if($tValidate->exist == 0){
			$save = $this->svModel->insertTransaction($fields, $values);
			if($save){
				$result['success'] = true;				
				$result['message'] = 'Data berhasil di simpan';				
			}else{
				$result['success'] = false;				
				$result['message'] = 'Data gagal disimpan';											
			}			
		}else{
			//$result['success'] = 0;				
			//$result['message'] = 'Data sudah ada';							
			
			$save = $this->svModel->insertDuplicateTransaction($fields, $values);
			if($save){
				$result['success'] = true;				
				$result['message'] = 'Data duplicate berhasil di simpan';				
			}else{
				$result['success'] = false;				
				$result['message'] = 'Data gagal disimpan';							
			}			
		}
		
		Helper::echoResponse($this->json->encode($result));					
	}

	/**
     * 
     * @api {post} /data/transaction Send Transaction
     * @apiName PostTransaction
     * @apiGroup Surveillance
     * @apiVersion  0.1.2
     * @apiPermission public
     * 
     * @apiDescription Mengirimkan data transaksi.
     * 
     * @apiExample Contoh Penggunaan :
     * http://119.252.160.220/vtax-web-service/data/transaction 
     * 
     * @apiParam {String} Token Kode autentikasi.
     * @apiParam {String} NPWPD NPWPD.
     * @apiParam {String} NOP NOP.
     * @apiParam {Number} TransactionType [0] TransctionAmount sudah termasuk pajak. 
	 * <br/>[1] TransactionAmount belum termasuk pajak.
     * @apiParam {Number} TaxType Jenis Pajak <br/>
	 * [4] Hotel.<br/>
	 * [5] Restoran.<br/>
	 * [6] Hiburan.<br/>
	 * [7] Reklame.<br/>
	 * [8] Penerangan Jalan.<br/>
	 * [9] Mineral Non Logam dan Batuan 10 = Parkir.<br/>
	 * [11] Air Bawah Tanah.<br/>
	 * [12] Sarang Burung Walet.<br/>
	 * [24] Hotel (NonReg).<br/>
	 * [25] Restoran (NonReg).<br/>
	 * [26] Hiburan (NonReg).<br/>
	 * [27] Reklame (NonReg).<br/>
	 * [28] Penerangan Jalan (NonReg).<br/>
	 * [29] Mineral Non Logam dan Batuan (NonReg).<br/>
	 * [30] Parkir (NonReg).<br/>
	 * [31] Air Bawah Tanah (NonReg).<br/>
	 * [32] Sarang Burung Walet (NonReg)
     * @apiParam {String} BranchCode Branch Code.
     * @apiParam {String} BillNumber Nomor bill/struk.
     * @apiParam {Number} Quantity Quantity.
     * @apiParam {String} Invoice Invoice.
     * @apiParam {Number} TransactionSource 
	 * [1] Tapbox. <br/> 
	 * [2] Cash Register. <br/>
	 * [3] File Transfer. <br/>
	 * [4] Manual. <br/>
	 * [5] Mobile POS. <br/>
	 * [6] Web Service. <br/>
	 * [7] Tap Server. <br/>
     * @apiParam {String} TransactionDescription Deskripsi transaksi.
     * @apiParam {Number} TransactinAmount Nilai transaksi.
     * @apiParam {Date} TransactionDate Tanggal transaksi (yyyy-mm-dd H:i:s).
     * @apiParam {String} Tax Nilai persentasi pajak.
     * @apiParam {String} [DeviceId] Kode/Nomor device (tapbox).
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"Token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 *		"NPWPD" : "PTES001",
	 *		"NOP" : "0989098",
	 *		"TaxType" : 8,
	 *		"TransactionType" : 1,
	 *		"BranchCode" : "2",
	 *		"BillNumber" : "15544372669314619838",
	 *		"Quantity" : 2,
	 *		"Invoice" : "15544372669314619838",
	 *		"TransactionDescription" : "kosong", 
	 *		"TransactionDate" : "2019-04-20 11:07:46",
	 *		"TransactionAmount" : 169500,
	 *		"Tax" : "13%",
	 *		"DeviceId" : "dv001",
	 *		"TransactionSource" : 5
	 * }      
     * @apiSuccess {boolean} status Status response, jika berhasil bernilai true dan jika gagal bernilai false.
     * @apiSuccess {String} message Deskripsi response.
     * 
     * @apiSuccessExample {json} Success-Respon: 
     * {
     *       "status": true,
     *       "message": "Insert Success!"
     * }
     * 
     * @apiError {String} Parameter-Invalid Parameter tidak sesuai.
     * @apiError {String} Token-Invalid Token salah, expired, dll.
     * @apiError {String} TransactionType-Ivalid TransactionType tidak sesuai.
     * @apiError {String} TaxType-Invalid TaxType tidak sesuai.
     * @apiError {String} TransactionSource-Invalid TransactionSource tidak sesuai.
     * @apiError {String} Insert-Failed Gagal insert data.
     * @apiError {String} Insert-Duplicated Data yang dikirim sudah ada.
     * 
     * @apiErrorExample {json} Error-Response (contoh):
     *  {
     *       "status": false,
     *       "message": "Parameter invalid!",
     *  }
     */
    public function saveTransaction(){	
		header('Access-Control-Allow-Origin: *'); 
		
		$tRequest	= Request::json();
		$result = array('status' => false, 'message' => null);	

		// save log request
		$this->log->request($this->json->encode($tRequest));
		
		// validasi parameter
		if(!SurveillanceValidate::issetSaveTransaction($tRequest)){
			$result['message'] = "Parameters invalid!";

			// save log response
			$this->log->response($this->json->encode($result));

			echo $this->json->encode($result);
			die;
		}

		//validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->Token));

		if($auth->status){		

			// validasi TransactionType
			if(!SurveillanceValidate::isTransactionType($tRequest)){
				$result['message'] = "TransactionType invalid!";

				// save log response
				$this->log->response($this->json->encode($result));

				echo $this->json->encode($result);
				die;
			}
	
			// validasi TaxType
			if(!SurveillanceValidate::isTaxType($tRequest)){
				$result['message'] = "TaxType invalid!";

				// save log response
				$this->log->response($this->json->encode($result));

				echo $this->json->encode($result);
				die;
			}

			// validasi TransactionSource
			if(!SurveillanceValidate::isTransactionSource($tRequest)){
				$result['message'] = "TransactionSource invalid!";

				// save log response
				$this->log->response($this->json->encode($result));

				echo $this->json->encode($result);
				die;
			}

			// get masa pajak			
			$masa_pajak1= explode(' ',$tRequest->TransactionDate);
			$masa_pajak1 = explode('-',$masa_pajak1['0']);
			$masa_pajak2 = substr($masa_pajak1['0'],2);
			
			//get fix value for transAmount & taxAmount
			$fixValue = $this->getTransAndTaxAmount($tRequest->TransactionType,$tRequest->TransactionAmount,$tRequest->Tax);

			// build fields and values
			$data = array(
				'TransactionNumber'		=>	Helper::UUID(),
				'NPWPD'					=>	$tRequest->NPWPD,
				'NOP'					=>	$tRequest->NOP,
				'TaxType'				=>	$tRequest->TaxType,
				'DeviceId'				=>	$tRequest->DeviceId,
				'TransactionType'		=>	$tRequest->TransactionType,
				'BranchCode'			=>	$tRequest->BranchCode,
				'TaxPeriode'			=>	$masa_pajak1['2'].$masa_pajak2,
				'BillNumber'			=>	$tRequest->BillNumber,
				'Quantity'				=>	$tRequest->Quantity,
				'invoice'				=>	$tRequest->Invoice,
				'TransactionDescription'=>	$tRequest->TransactionDescription,
				'TransactionAmount'		=>	$fixValue['transAmount'],
				'TaxAmount'				=>	$fixValue['taxAmount'],
				'TransactionDate'		=>	$tRequest->TransactionDate,
				'TransactionSource'		=>	$tRequest->TransactionSource,
				'NotAdmitReason'		=>	'',
				'TaxInfo'				=>	''
			);		
			
			$fields = array_keys($data);
			$values = array();
			foreach($data as $val){
				$values[] = str_replace("'","",sprintf("%s",$val));
			}
			
			// insert transaction to db surveillance
			$tValidate = $this->json->decode($this->svModel->getCountTrans($tRequest->NPWPD,$tRequest->BillNumber,$tRequest->TransactionDate,$tRequest->TransactionAmount));
			// data doesn't exist
			if($tValidate->exist == 0){
				$save = $this->svModel->insertTransaction($fields, $values);
				if($save){
					$result['status'] = true;				
					$result['message'] = 'Insert success!';				
				}else{
					$result['status'] = false;				
					$result['message'] = 'Insert failed!';											
				}			
			}else{
				$save = $this->svModel->insertDuplicateTransaction($fields, $values);
				if($save){
					$result['status'] = true;				
					$result['message'] = 'Insert duplicated!';				
				}else{
					$result['status'] = false;				
					$result['message'] = 'Insert failed';							
				}			
			}
		}else{
			$result['message'] = $auth->message;
		}	

		// save log response
		$this->log->response($this->json->encode($result));
		
		echo $this->json->encode($result);					
		
	}

	/**
     * 
     * @api {post} /data/alarm Send Alarm
     * @apiName PostAlarm
     * @apiGroup Surveillance
     * @apiVersion  0.1.2
     * @apiPermission public
     * 
     * @apiDescription Mengirimkan data alarm device (tapbox).
     * 
     * @apiExample Contoh Penggunaan :
     * http://119.252.160.220/vtax-web-service/data/alarm 
     * 
     * @apiParam {String} Token Kode autentikasi.
     * @apiParam {String} DeviceId Kode/Nomor device (tapbox).
     * @apiParam {DateTime} ServerTimeStamp Waktu server (yyyy-mm-dd H:i:s).
     * @apiParam {DateTime} DeviceTimeStamp Waktu device (yyyy-mm-dd H:i:s). 
     * @apiParam {String} AlarmCode Kode alarm.
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"Token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 *   	"DeviceId" : "dv001",
	 *   	"ServerTimeStamp" : "2019-04-20 10:10:10",
	 * 		"DeviceTimeStamp" : "2019-04-20 11:11:11",
	 * 		"AlarmCode" : "1"
	 * }      
     * @apiSuccess {boolean} status Status response, jika berhasil bernilai true dan jika gagal bernilai false.
     * @apiSuccess {String} message Deskripsi response.
     * 
     * @apiSuccessExample {json} Success-Respon: 
     * {
     *       "status": true,
     *       "message": "Insert Success!"
     *  }
     * 
     * @apiError {String} Parameter-Invalid Parameter tidak sesuai.
     * @apiError {String} Token-Invalid Token salah, expired, dll.
     * @apiError {String} Insert-Failed Gagal insert data.
     * 
     * @apiErrorExample {json} Error-Response (contoh):
     *  {
     *       "status": false,
     *       "message": "Parameter invalid!",
     *  }
     */
    public function saveAlarm(){	
        header('Access-Control-Allow-Origin: *'); 
                
		$tRequest	= Request::json();
		$result = array('status' => false, 'message' => null);	
		
		// save log request
		$this->log->request($this->json->encode($tRequest));

		// validasi parameter
		if(!SurveillanceValidate::issetSaveAlarm($tRequest)){
			$result['message'] = "Parameters invalid!";

			// save log response
			$this->log->response($this->json->encode($result));

			echo $this->json->encode($result);
			die;
		}

		$auth = $this->json->decode($this->auth->validateToken($tRequest->Token));

		if($auth->status){
			// build fields and values
			$data = array(
				'DeviceId'			=>	$tRequest->DeviceId,
				'ServerTimeStamp'	=>	$tRequest->ServerTimeStamp,
				'TimeStamp'			=>	$tRequest->DeviceTimeStamp,
				'JenisAlarm'		=>	$tRequest->AlarmCode
			);		
			
			$fields = array_keys($data);
			$values = array();
			foreach($data as $val){
				$values[] = str_replace("'","",sprintf("%s",$val));
			}

			$save = $this->svModel->insertAlarm($fields, $values);
			if($save){
				$result['status'] = true;				
				$result['message'] = 'Insert success!';				
			}else{
				$result['status'] = false;				
				$result['message'] = 'Insert failed!';															
			}			

		}else{
			$result['message'] = $auth->message;
		}
					
		// save log response
		$this->log->response($this->json->encode($result));

		echo $this->json->encode($result);							
	}

	public function getTransAndTaxAmount($tranType, $amount, $tax=0){
		$taxAmount = $transAmount = 0;
		$tax = str_replace('%','',$tax);

		switch($tranType){
			// include tax
			case '0'	:
				$tmpTotal = $amount;
						if($tax == 0){
							$transAmount =  round(($tmpTotal / 1.1),2) ;
						}else{
							$transAmount =  round(($tmpTotal / ($tax/100+1)),2) ;
						}
						$taxAmount = round(($tmpTotal - $transAmount),2);							
					break;
			// exclude tax
			case '1'	:
				$transAmount = $amount;
				if($tax == 0){
					$taxAmount = round($amount * 0.1,2);
				}else{
					$taxAmount = round($amount * ($tax/100),2);
				}
				break;
		}
		
		$result = array('taxAmount' => $taxAmount, 'transAmount' => $transAmount);

		return $result;
	}		

}
