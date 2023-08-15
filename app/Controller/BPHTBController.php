<?php
namespace Controller;

use \Core\Helper;
use \Model\BPHTBModel;
use \Core\Request;
use \Services_JSON;
use \Validate\BPHTBValidate;
use \Controller\AuthController;
use \Controller\AppController;

class BPHTBController{
	private $json;
	private $bphtbModel;
	
    public function __construct(){		
        $this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
        $this->bphtbModel = new BPHTBModel;
		$this->auth = new AuthController;
		$this->appController = new AppController;
    }
    
    public function inquery(){	
        header('Access-Control-Allow-Origin:*'); 
		$result		= array();
		$tagihan = $this->bphtbModel->getTagihan($_POST['NOP'],$_POST['CODE']);
		// $listTagihan = $this->pbbModel->getListTagihan($_POST['NOP']);

		$tmp = array();
		// $tmp['profile'] = $profil
		$tmp['tagihan'] = $tagihan;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataDashboardBPHTB(){
		header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;

		$result = array('result' => NULL, 'response_code' => NULL);
		
		$ketetapan = $this->bphtbModel->getKetetapanPerTahun($thn);
		$penerimaan = $this->bphtbModel->getPenerimaanPerTahun($thn);
		$tunggakan = $this->bphtbModel->getTunggakanPerTahun($thn);
		
		$tmp = array();
		$tmp['ketetapan'] = $ketetapan;
		$tmp['penerimaan'] = $penerimaan;
		$tmp['tunggakan'] = $tunggakan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataDetailDashboardBPHTB(){
		header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;

		$result = array('result' => NULL, 'response_code' => NULL);
		
		$ketetapan = $this->bphtbModel->getKetetapan($thn);
		$penerimaan = $this->bphtbModel->getPenerimaanPerTahun($thn);
		$tunggakan = $this->bphtbModel->getTunggakanPerTahun($thn);
		
		$tmp = array();
		$tmp['ketetapan'] = $ketetapan;
		$tmp['penerimaan'] = $penerimaan;
		$tmp['tunggakan'] = $tunggakan;

		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataTunggakan(){
		header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;

		$result = array('result' => NULL, 'response_code' => NULL);
		$tunggakan = $this->bphtbModel->getTunggakanPerKecamatanPerTahun($thn);
	
		$tmp = array();
		$tmp['tunggakan'] = $tunggakan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataPenerimaan(){
		header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;

		$result = array('result' => NULL, 'response_code' => NULL);
		$penerimaan = $this->bphtbModel->getPenerimaanPerKecamatanPerTahun($thn);
	
		$tmp = array();
		$tmp['penerimaan'] = $penerimaan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function filter(){
		header('Access-Control-Allow-Origin:*'); 
		$result		= array();

		// var_dump($_POST);exit;
		if($_POST['jns'] == "tunggakan"){
			$filtered = $this->bphtbModel->getTunggakanPerKelurahan($_POST['thn'],$_POST['kec'],'',$_POST['kel']);		
		}elseif ($_POST['jns'] == "penerimaan") {
			$filtered = $this->bphtbModel->getPenerimaanPerKelurahan($_POST['thn'],$_POST['kec'],'',$_POST['kel']);		
		}elseif ($_POST['jns'] == "ketetapan"){
			$filtered = $this->bphtbModel->getKetetapanPerKelurahan($_POST['thn'],$_POST['kec'],'',$_POST['kel']);
		}

		$tmp = array();
		$tmp['filtered'] = $filtered;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
	}
	
	public function get_profile($NTPD, $NOP){
		$result = array('result' => NULL, 'response_code' => NULL);
		
		$tSwitching = $this->json->decode($this->bphtbModel->getSwitchingID($NTPD));		
		if($tSwitching->exist){
			$tValidate = $this->json->decode($this->bphtbModel->getValidate($tSwitching->data->ID));		
			if($tValidate->exist > 0){			
				$tProfile = $this->json->decode($this->bphtbModel->getProfile($NOP, $tSwitching->data->ID));			
				$tObjekPajak = $this->json->decode($this->bphtbModel->getDataOP($tSwitching->data->ID));			
				
				$tmp = array();		
				if($tProfile->exist){
					$tmp['NOP'] = $NOP;
					$tmp['NIK'] = $tProfile->data->NIK;
					$tmp['NAMA'] = $tProfile->data->NAMA;
					$tmp['ALAMAT'] = $tProfile->data->ALAMAT;
					$tmp['KELURAHAN_OP'] = $tProfile->data->KELURAHAN_OP;
					$tmp['KECAMATAN_OP'] = $tProfile->data->KECAMATAN_OP;
					$tmp['KOTA_OP'] = $tProfile->data->KOTA_OP;
					$tmp['LUASTANAH'] = ($tObjekPajak->exist ? $tObjekPajak->data->LUASTANAH : 0);
					$tmp['LUASBANGUNAN'] = ($tObjekPajak->exist ? $tObjekPajak->data->LUASBANGUNAN : 0);
					$tmp['PEMBAYARAN'] = ($tProfile->data->FLAG ? $tProfile->data->PEMBAYARAN : 0) ;
					$tmp['STATUS'] = ($tProfile->data->FLAG ? 'Y' : 'T') ;
					if($tProfile->data->FLAG &&$tProfile->data->TANGGAL_PEMBAYARAN != null){
						$tmp['TANGGAL_PEMBAYARAN'] = Helper::convertDate($tProfile->data->TANGGAL_PEMBAYARAN);
					}else{
						$tmp['TANGGAL_PEMBAYARAN'] = '00/00/0000';
					}
					$tmp['NTPD'] = $NTPD;
					$tmp['JENISBAYAR'] = ($tProfile->data->FLAG ? 'L' : 'H') ;
					
					$result['response_code'] = 'OK';		
					$result['result'] = $tmp;		
				}
			}									
		}
						
		return $result;
	}

	// NEW GILAR


    
    /**
	 * 
	 * @api {POST} /bphtb/penerimaan/target/total Total Target Penerimaan
	 * @apiName getTotalTargetPenerimaan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/penerimaan/target/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Number} data.total_target_penerimaan Total target penerimaan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "total_target_penerimaan": 1191863520
	 *	    }
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function totalTargetPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetTargetPenerimaan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
		} else {
			$year = date('Y');
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$target = $this->bphtbModel->getTotalTargetPenerimaan($year);
		
			$tmp = array();
			$tmp['total_target_penerimaan'] = $target;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}

    /**
	 * 
	 * @api {POST} /bphtb/tunggakan/total Total Tunggakan
	 * @apiName getTotalTunggakan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/tunggakan/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Number} data.total_tunggakan Total tunggakan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "total_tunggakan": 1191863520
	 *	    }
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function totalTunggakan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetTunggakan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
		} else {
			$year = date('Y');
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$totalTunggakan = $this->bphtbModel->getTotalTunggakan($year);
		
			$tmp = array();
			$tmp['total_tunggakan'] = $totalTunggakan;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}


    /**
	 * 
	 * @api {POST} /bphtb/penerimaan/target Daftar Target Penerimaan
	 * @apiName getDaftarTargetPenerimaan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Paling lama tahun 2018.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/penerimaan/target
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.target_penerimaan Daftar target penerimaan.
	 * @apiSuccess {String} data.target_penerimaan.wp_nama Nama wajib pajak.
	 * @apiSuccess {Number} data.target_penerimaan.harus_dibayar Nilai pajak yang harus dibayar.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *		"status": true,
	 *		"message": "OK",
	 *		"data": {
	 *			"target_penerimaan": [{
	 *					"wp_nama": "YULIA PUSPITA SARI",
	 *					"harus_dibayar": 1250000
	 *				},
	 *				{
	 *					"wp_nama": "TUTIK",
	 *					"harus_dibayar": 500000
	 *				}
	 *			]
	 *		}
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function daftarTargetPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest = Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetTargetPenerimaan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
		} else {
			$year = date('Y');
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$target = $this->bphtbModel->getTargetPenerimaan($year);
		
			$tmp = array();
			$tmp['target_penerimaan'] = $target;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}

    /**
	 * 
	 * @api {POST} /bphtb/tunggakan Daftar Tunggakan
	 * @apiName getDaftarTunggakan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/tunggakan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.tunggakan Daftar tunggakan.
	 * @apiSuccess {String} data.tunggakan.wp_nama Nama wajib pajak.
	 * @apiSuccess {Number} data.tunggakan.harus_dibayar Nilai pajak yang harus dibayar.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "tunggakan": [
	 *	            {
	 *	                "wp_nama": "MUSADDAD",
	 *	                "harus_dibayar": 9500000
	 *	            },
	 *	            {
	 *	                "wp_nama": "MUSADDAD",
	 *	                "harus_dibayar": 9500000
	 *	            }
	 *	        ]
	 *	    }
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function daftarTunggakan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetTunggakan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
		} else {
			$year = date('Y');
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$tunggakan = $this->bphtbModel->getDaftarTunggakan($year);
		
			$tmp = array();
			$tmp['tunggakan'] = $tunggakan;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}

    /**
	 * 
	 * @api {POST} /bphtb/penerimaan Daftar Penerimaan
	 * @apiName getDaftarPenerimaan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan" : 6
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/penerimaan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.penerimaan Daftar penerimaan.
	 * @apiSuccess {String} data.penerimaan.wp_nama Nama wajib pajak.
	 * @apiSuccess {Number} data.penerimaan.harus_dibayar Nilai pajak yang harus dibayar.
	 * @apiSuccess {String} data.penerimaan.waktu_pembayaran Waktu pembayaran pajak.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "penerimaan": [
	 *	            {
	 *	                "wp_nama": "RIZAL",
	 *	                "harus_dibayar": 90000,
	 *	                "waktu_pembayaran": "2015-02-02 12:31:00"
	 *	            },
	 *	            {
	 *	                "wp_nama": "WILLIS",
	 *	                "harus_dibayar": 9500000,
	 *	                "waktu_pembayaran": "2015-02-02 12:31:00"
	 *	            }
	 *	        ]
	 *	    }
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function daftarPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetPenerimaan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun dan bulan
		$month = 0;
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
			if (isset($tRequest->bulan)) {
				$month = $tRequest->bulan;
			}
		} else {
			$year = date('Y');
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$penerimaan = $this->bphtbModel->getPenerimaan($year, $month);
		
			$tmp = array();
			$tmp['penerimaan'] = $penerimaan;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}


    /**
	 * 
	 * @api {POST} /bphtb/penerimaan/kelurahan Daftar Penerimaan Kelurahan
	 * @apiName getDaftarPenerimaanKelurahan
	 * @apiGroup BPHTB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan kelurahan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).
	 * @apiParam  {String} [kelurahan] Nama kelurahan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan" : 6
	 * 		"kelurahan" : "CIKUTRA"
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/bphtb/penerimaan/kelurahan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.penerimaan Daftar penerimaan.
	 * @apiSuccess {String} data.penerimaan.kelurahan Nama kelurahan.
	 * @apiSuccess {Number} data.penerimaan.harus_dibayar Nilai pajak yang harus dibayar.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "penerimaan": [
	 *	            {
	 *	                "kelurahan": "CIKUTRA",
	 *	                "harus_dibayar": 90000
	 *	            },
	 *	            {
	 *	                "wp_nama": "CIBEUNYING",
	 *	                "harus_dibayar": 9500000
	 *	            }
	 *	        ]
	 *	    }
	 *	}
	 *
	 * @apiErrorExample Error-Response:
	 *   HTTP/1.1 200 OK
	 *   {
	 *       "success": false,
	 *       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	 * 		 "data" : null
	 *   }
	 * 
	 * 
	 */
	public function daftarPenerimaanKelurahan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!BPHTBValidate::issetPenerimaanPerKelurahan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}
		
		// Get parameter tahun dan bulan
		$month = 0;
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;
			if (isset($tRequest->bulan)) {
				$month = $tRequest->bulan;
			}
		} else {
			$year = date('Y');
		}
		
		// Get parameter tahun dan bulan
		if (isset($tRequest->kelurahan)) {
			$kelurahan = $tRequest->kelurahan;
		} else {
			$kelurahan = "";
		}
		
		// Get parameter kelurahan
		if (isset($tRequest->kelurahan)) {
			$kelurahan = $tRequest->kelurahan;
		} else {
			$kelurahan = "";
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$penerimaan = $this->bphtbModel->getSeluruhPenerimaanPerKelurahan($year, $month, $kelurahan);
		
			$tmp = array();
			$tmp['penerimaan'] = $penerimaan;
			
			$result['status'] = true;		
			$result['message'] = 'OK';	
			$result['data'] = $tmp;
		} else {
			$result['message'] = $auth->message;
		}
		
		Helper::echoResponse($this->json->encode($result));
	}
}
