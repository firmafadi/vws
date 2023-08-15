<?php
namespace Controller;

use \Core\Helper;
use \Model\PBBModel;
use \Services_JSON;
use \Core\Request;
use \Validate\PBBValidate;
use \Controller\AuthController;
use \Controller\AppController;


class PBBController{
	private $json;
	private $pbbModel;
	private $currentYear;

    public function __construct(){		
        $this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->pbbModel = new PBBModel;
		$this->auth = new AuthController;
		$this->appController = new AppController;
		$this->currentYear = $this->appController->getThnTagih();
    }
    
    public function inquery(){	
        header('Access-Control-Allow-Origin:*'); 
		$result		= array();
		$profile = $this->pbbModel->getProfile($_POST['NOP']);
		$listTagihan = $this->pbbModel->getListTagihan($_POST['NOP']);
		
		$tmp = array();
		$tmp['profile'] = $profile;
		$tmp['listTagihan'] = $listTagihan;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
	}
	
	public function dataDashboardPBB(){
        header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;		

		$result = array('result' => NULL, 'response_code' => NULL);
		
		
		$ketetapan = $this->pbbModel->getJumlahKetetapanPerTahun($thn);
		$penerimaan = $this->pbbModel->getTotalPenerimaanPerTahun($thn);
		$tunggakan = $this->pbbModel->getTotalTunggakanPerTahun($thn);
		
		$tmp = array();
		$tmp['ketetapan'] = $ketetapan;
		$tmp['penerimaan'] = $penerimaan;
		$tmp['tunggakan'] = $tunggakan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataDetailDashboardPBB(){
        header('Access-Control-Allow-Origin:*'); 

		$result = array('result' => NULL, 'response_code' => NULL);

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;		
		
		$ketetapan = $this->pbbModel->getKetetapanPerKecamatan($thn);
		$penerimaan = $this->pbbModel->getTotalPenerimaanPerTahun($thn);
		$tunggakan = $this->pbbModel->getTotalTunggakanPerTahun($thn);
		
		$tmp = array();
		$tmp['ketetapan'] = $ketetapan;
		$tmp['penerimaan'] = $penerimaan;
		$tmp['tunggakan'] = $tunggakan;

		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$result = array('result' => NULL, 'response_code' => NULL);
		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;
				
		$penerimaan = $this->pbbModel->getPenerimaanPerKecamatanPerTahun($thn);
	
		$tmp = array();
		$tmp['penerimaan'] = $penerimaan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataTunggakan(){
        header('Access-Control-Allow-Origin:*'); 

		$result = array('result' => NULL, 'response_code' => NULL);
		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;
		
		$tunggakan = $this->pbbModel->getTunggakanPerKecamatanPerTahun($thn);
	
		$tmp = array();
		$tmp['tunggakan'] = $tunggakan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataKecamatan(){
		$result = array('result' => NULL, 'response_code' => NULL);
		
		$kecamatan = $this->pbbModel->getKecamatan();
		
		$tmp = array();
		$tmp['kecamatan'] = $kecamatan;

		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}

	public function dataKelurahan(){
		header('Access-Control-Allow-Origin:*'); 
		$result		= array();
		$kelurahan = $this->pbbModel->getKelurahan($_POST['ID_KEC']);

		$tmp = array();
		$tmp['kelurahan'] = $kelurahan;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
	}

	// public function detailDashboard(){
	// 	header('Access-Control-Allow-Origin:*'); 
	// 	$result		= array();

	// 	$ketetapan = $this->pbbModel->getKetetapanPerKelurahan(2018,$_POST['kec']);

	// 	$tmp = array();
	// 	$tmp['ketetapan'] = $ketetapan;

	// 	$result['response_code'] = 'OK' ;		
	// 	$result['result'] = $tmp;
		 
	// 	Helper::echoResponse($this->json->encode($result));
	// }

	// public function detailTunggakan(){
	// 	header('Access-Control-Allow-Origin:*'); 
	// 	$result		= array();

	// 	$tunggakan = $this->pbbModel->getTunggakanPerKelurahan(2018,$_POST['wil'],'','');

	// 	$tmp = array();
	// 	$tmp['tunggakan'] = $tunggakan;

	// 	$result['response_code'] = 'OK' ;		
	// 	$result['result'] = $tmp;
		 
	// 	Helper::echoResponse($this->json->encode($result));
	// }

	public function filter(){
		header('Access-Control-Allow-Origin:*'); 
		$result		= array();

		// var_dump($_POST);exit;
		if($_POST['jns'] == "tunggakan"){
			$filtered = $this->pbbModel->getTunggakanPerKelurahan($_POST['thn'],'',$_POST['kec'],$_POST['kel']);		
		}elseif ($_POST['jns'] == "penerimaan") {
			$filtered = $this->pbbModel->getPenerimaanPerKelurahan($_POST['thn'],'',$_POST['kec'],$_POST['kel']);		
		}elseif ($_POST['jns'] == "ketetapan"){
			$filtered = $this->pbbModel->getKetetapanPerKelurahan($_POST['thn'],'',$_POST['kec'],$_POST['kel']);
			
			// var_dump(json_decode($filtered));exit;
			// $input = json_decode($filtered)->data;
			// var_dump($input);exit;
			// foreach ($input as $a => $values) {
			// 	$key = $values[$a]['penerimaan'];
			// 	var_dump($key);exit;
			// 	$output[$key][] = $values;
			// }
			
			// Don't want the referenceUid in the keys? Reset them:
			// $output = array_values($output);
			// var_dump($output);exit;
			// echo count($input);exit;
			// for ($i=0; $i < count($input) ; $i++) { 
			// 	for ($j=0; $j < count($input) ; $j++) { 
			// 		if
			// 	}
			// }

			// for ($i=0; $i <count($input) ; $i++) { 
				
			// }
			// var_dump($input);exit;
			// print_r(array_count_values($filtered[$i]['wil']));exit;
			// $new_array = array();
			// $exists_array    = array();
			// // var_dump($input[0]->wil);exit;
			// foreach( $input as $key => $element ) {
			// 	var_dump($element[$key]);exit;
			// if( !in_array( $element[$key]->wil, $exists_array )) {
			// 	$exists_array[]    = $element[$key]->wil;
			// }
			// // else{
			// // 	$element['wil'] = 'New Value';
			// // }
			// }
			// var_dump($new_array);exit;
		// $penerimaan = $this->pbbModel->getPenerimaanKelurahanKecamatan($_POST['thn'],'',$_POST['kec'],$_POST['kel']);
		// $tunggakan = $this->pbbModel->getPenerimaanKelurahanKecamatan($_POST['thn'],'',$_POST['kec'],$_POST['kel']);

		// $penerimaan = json_decode($penerimaan)->data;
		// $tunggakan = json_decode($tunggakan)->data;
		
		// $filtered
		// var_dump()
			// for ($i=0; $i <count($input) ; $i++) { 
			// 	$same[] =  $input[$i]->wil;
			// }
			// $arr = array();
	
			// // print_r(array_count_values($same));

			// array_diff($same, array_unique($same));

			// var_dump($same);exit;
			// $arr=array();
			// array_diff_assoc($arr, array_unique($same));
			// print_r($arr);exit;
			// print_r(array_unique($same));exit;
			// var_dump($same);exit;
			// print_r(array_count_values($same));exit;
		}
		// var_dump($filtered);exit;
		$tmp = array();
		$tmp['filtered'] = $filtered;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
	}
	
	// public function dataClicked(){
	// 	header('Access-Control-Allow-Origin:*'); 
	// 	$result		= array();

	// 	if($_POST['jns'] == "tunggakan"){
	// 		$tunggakan = $this->pbbModel->getTunggakanPerKelurahan(2018,$_POST['wil'],'','');
	// 	}

	// 	$tmp = array();
	// 	$tmp['data'] = $tunggakan;

	// 	$result['response_code'] = 'OK' ;		
	// 	$result['result'] = $tmp;
		 
	// 	Helper::echoResponse($this->json->encode($result));
	// }



	// NEW GILAR //
	
    // 
	// 
	// @api {POST} /pbb/ketetapan Daftar Ketetapan
	// @apiName getDaftarKetetapan
	// @apiGroup PBB
	// @apiVersion  0.1.1
    // @apiPermission public
	// 
    // @apiDescription Mengambil daftar ketetapan di tahun tertentu, sesuai dengan parameter yang dimasukkan.
	// 
	// @apiParam  {String} token Mandatory Kode autentikasi.
	// @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Paling lama tahun 2018.
	// 
    // @apiParamExample  {json} Request Body:
	// {
	// 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	// 		"tahun" : 2015
	// } 
	//   
    // @apiExample Contoh Penggunaan:
    // http://119.252.160.220/vtax-web-service/pbb/ketetapan 
	// 
	// @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	// @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	// @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	// @apiSuccess {Object[]} data.ketetapan Dafter ketetapan.
	// @apiSuccess {String} data.ketetapan.nop Nomor NOP.
	// @apiSuccess {String} data.ketetapan.wp_nama Nama wajib pajak.
	// @apiSuccess {Number} data.ketetapan.ketetapan Nilai pajak yang haris dibayar (ketetapan).
	// @apiSuccess {Number} data.ketetapan.denda Nilai denda pajak.
	// @apiSuccess {Number} data.ketetapan.total_bayar Nilai yang dibayarkan.
	// @apiSuccess {String} data.ketetapan.status_pembayaran Status pembayaran pajak.
	// 
	// @apiSuccessExample {json} Success-Response:
	// HTTP/1.1 200 OK
	// 	{
	// 	    "status": true,
	// 	    "message": "OK",
	// 	    "data": {
	// 	        "ketetapan": [
	// 	            {
	// 	                "nop": "167101000412130031",
	// 	                "wp_nama": "NINDI LARASSATI",
	// 	                "harus_dibayar": 25000,
	// 	                "denda": 3500,
	// 	                "total_bayar": 28500,
	// 	                "status_pembayaran": "Sudah dibayar"
	// 	            },
	// 	            {
	// 	                "nop": "167107000105131440",
	// 	                "wp_nama": "YAHYA SYAHADA MACHMUD HARJANTO",
	// 	                "harus_dibayar": 25000,
	// 	                "status_pembayaran": "Belum dibayar"
	// 	            }
	// 	        ]
	// 	    }
	// 	}
	// 
	// @apiErrorExample Error-Response:
	//   HTTP/1.1 200 OK
	//   {
	//       "success": false,
	//       "message": "Token invalid : SignatureInvalid - Signature verification failed"
	// 		 "data" : null
	//   }
	// 
	// 
	// 
    // public function daftarKetetapan(){
    //     header('Access-Control-Allow-Origin:*'); 

    //     // $year = (isset($_GET['year'])) ? $_GET['year'] : '' ;
		
	// 	$tRequest = Request::json();

	// 	// validasi parameter
	// 	if(!PBBValidate::issetKetetapan($tRequest)){
	// 		$result['message'] = "Parameters invalid!";
	// 		echo $this->json->encode($result);
	// 		die;
	// 	}

	// 	// Get parameter tahun
	// 	if (isset($tRequest->tahun)) {
	// 		$year = $tRequest->tahun;
	// 	} else {
	// 		$year = date('Y');
	// 	}

	// 	// validasi token
	// 	$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

	// 	$result = array('status' => false, 'message' => NULL, 'data' => NULL);

	// 	if ($auth->status) {	
	// 		$ketetapan = $this->pbbModel->getKetetapan($year);
		
	// 		$tmp = array();
	// 		$tmp['ketetapan'] = $ketetapan;
			
	// 		$result['status'] = true;		
	// 		$result['message'] = 'OK';	
	// 		$result['data'] = $tmp;
	// 	} else {
	// 		$result['message'] = $auth->message;
	// 	}
		
	// 	Helper::echoResponse($this->json->encode($result));
	// }
    
    /**
	 * 
	 * @api {POST} /pbb/ketetapan/total Total Ketetapan
	 * @apiName getTotalKetetapan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total ketetapan di tahun tertentu, sesuai parameter tahun.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/ketetapan/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.total_ketetapan Total pajak yang harus dibayar (total ketetapan).
	//  * @apiSuccess {Number} data.total_ketetapan.harus_dibayar Nilai pajak yang haris dibayar.
	//  * @apiSuccess {Number} data.total_ketetapan.denda Nilai denda pajak.
	//  * @apiSuccess {Number} data.total_ketetapan.total_bayar Nilai yang dibayarkan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "total_ketetapan": {
	 *	                "harus_dibayar": 25000,
	 *	                "denda": 3500,
	 *	                "total_bayar": 28500
	 *	            }
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
    public function totalKetetapan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest = Request::json();

		// validasi parameter
		if(!PBBValidate::issetKetetapan($tRequest)){
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
			$totalKetetapan = $this->pbbModel->getTotalKetetapan($year);
		
			$tmp = array();
			$tmp['total_ketetapan'] = $totalKetetapan;
			
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
	 * @api {POST} /pbb/penerimaan Daftar Penerimaan
	 * @apiName getDaftarPenerimaan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12)
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan"	: 6
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/penerimaan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.penerimaan Daftar penerimaan.
	 * @apiSuccess {String} data.penerimaan.nop Nomor NOP.
	 * @apiSuccess {String} data.penerimaan.wp_nama Nama wajib pajak.
	 * @apiSuccess {Number} data.penerimaan.harus_dibayar Nilai pajak yang haris dibayar.
	 * @apiSuccess {Number} data.penerimaan.denda Nilai denda pajak.
	 * @apiSuccess {Number} data.penerimaan.total_bayar Nilai yang dibayarkan.
	 * @apiSuccess {String} data.penerimaan.tanggal_bayar Tanggal pembayaran pajak.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *		"status": true,
	 *		"message": "OK",
	 *		"data": {
	 *			"penerimaan": [{
	 *					"nop": "167106000500230340",
	 *					"wp_nama": "KENCANA TENGGONO, CS",
	 *					"harus_dibayar": 180580,
	 *					"denda": 14446,
	 *					"total_bayar": 195026,
	 *					"tanggal_bayar": "2016-01-25"
	 *				},
	 *				{
	 *					"nop": "167106000500230400",
	 *					"wp_nama": "KENCANA TENGGONO, CS",
	 *					"harus_dibayar": 164340,
	 *					"denda": 13147,
	 *					"total_bayar": 177487,
	 *					"tanggal_bayar": "2016-01-28"
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
    public function daftarPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!PBBValidate::issetPenerimaan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}

		$month = 0;

		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;

			// Get parameter bulan
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
			$penerimaan = $this->pbbModel->getPenerimaan($year, $month);
		
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
	 * @api {POST} /pbb/penerimaan/total Total Penerimaan
	 * @apiName getTotalPenerimaan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12)
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan"	: 6
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/penerimaan/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.total_penerimaan Data total penerimaan.
	 * @apiSuccess {Number} data.total_penerimaan.harus_dibayar Nilai pajak yang haris dibayar.
	 * @apiSuccess {Number} data.total_penerimaan.denda Nilai denda pajak.
	 * @apiSuccess {Number} data.total_penerimaan.total_bayar Nilai yang dibayarkan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "total_penerimaan": {
	 *	                "harus_dibayar": 25000,
	 *	                "denda": 3500,
	 *	                "total_bayar": 28500
	 *	            }
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
    public function totalPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!PBBValidate::issetPenerimaan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}

		$month = 0;

		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;

			// Get parameter bulan
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
			$totalPenerimaan = $this->pbbModel->getTotalPenerimaan($year, $month);
		
			$tmp = array();
			$tmp['total_penerimaan'] = $totalPenerimaan;
			
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
	 * @api {POST} /pbb/penerimaan/kelurahan Daftar Penerimaan Kelurahan
	 * @apiName getDaftarPenerimaanKelurahan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan kelurahan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).
	 * @apiParam  {String} [kode_kelurahan] Kode kelurahan. Jika tidak diisi, akan menampilkan penerimaan dari seluruh kelurahan. Jika diisi, hanya akan menampilkan penerimaan dari kode kelurahan yang dimasukkan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan"	: 6,
	 * 		"kode_kelurahan" : "1671090005"
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/penerimaan/kelurahan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.penerimaan Daftar penerimaan kelurahan.
	 * @apiSuccess {String} data.penerimaan.kode_kelurahan Kode kelurahan.
	 * @apiSuccess {String} data.penerimaan.nama_kelurahan Nama kelurahan.
	 * @apiSuccess {Number} data.penerimaan.harus_dibayar Nilai pajak yang haris dibayar.
	 * @apiSuccess {Number} data.penerimaan.denda Nilai denda pajak.
	 * @apiSuccess {Number} data.penerimaan.total_bayar Nilai yang dibayarkan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "penerimaan": [
	 *	            {
	 *	                "kode_kelurahan": "1671090005",
	 *	                "nama_kelurahan": "TALANG AMAN",
	 *	                "harus_dibayar": 2806400,
	 *	                "denda": 325180,
	 *	                "total_bayar": 3131580
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
		if(!PBBValidate::issetPenerimaanPerKelurahan($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}

		$month = 0;

		// Get parameter tahun
		if (isset($tRequest->tahun)) {
			$year = $tRequest->tahun;

			// Get parameter bulan
			if (isset($tRequest->bulan)) {
				$month = $tRequest->bulan;
			}
		} else {
			$year = date('Y');
		}

		$kodeKelurahan = "";
		
		if (isset($tRequest->kode_kelurahan)) {
			$kodeKelurahan = $tRequest->kode_kelurahan;
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$penerimaan = $this->pbbModel->getPenerimaanKelurahan($year, $month, $kodeKelurahan);
		
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
	 * @api {POST} /pbb/tunggakan Daftar Tunggakan
	 * @apiName getDaftarTunggakan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/tunggakan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.tunggakan Daftar tunggakan.
	 * @apiSuccess {String} data.tunggakan.nop Nomor NOP.
	 * @apiSuccess {String} data.tunggakan.wp_nama Nama wajib pajak.
	 * @apiSuccess {Number} data.penerimaan.harus_dibayar Nilai pajak yang haris dibayar.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *		"status": true,
	 *		"message": "OK",
	 *		"data": {
	 *			"tunggakan": [{
	 *					"nop": "167101000412130031",
	 *					"wp_nama": "NINDI LARASSATI",
	 *					"harus_dibayar": 25000
	 *				},
	 *				{
	 *					"nop": "167106000401700610",
	 *					"wp_nama": "NY SRI MULYANA WIRAWAN",
	 *					"harus_dibayar": 23858600
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
	public function daftarTunggakan(){
        header('Access-Control-Allow-Origin:*'); 

        // $year = (isset($_GET['year'])) ? $_GET['year'] : '' ;
		
		$tRequest	= Request::json();

		// validasi parameter
		if(!PBBValidate::issetTunggakan($tRequest)){
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
			$tunggakan = $this->pbbModel->getTunggakan($year);
		
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
	 * @api {POST} /pbb/tunggakan/total Total Tunggakan
	 * @apiName getTotalTunggakan
	 * @apiGroup PBB
	 * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
	 * @apiParam  {String} token Mandatory Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * } 
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/pbb/tunggakan/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object} data.total_tunggakan Data total tunggakan.
	 * @apiSuccess {Number} data.total_tunggakan.harus_dibayar Nilai pajak yang haris dibayar.
	 * @apiSuccess {Number} data.total_tunggakan.denda Nilai denda pajak.
	 * @apiSuccess {Number} data.total_tunggakan.total_bayar Nilai yang dibayarkan.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 *	{
	 *	    "status": true,
	 *	    "message": "OK",
	 *	    "data": {
	 *	        "total_tunggakan": {
	 *	                "harus_dibayar": 25000,
	 *	                "denda": 3500,
	 *	                "total_bayar": 28500
	 *	            }
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

        // $year = (isset($_GET['year'])) ? $_GET['year'] : '' ;
		
		$tRequest	= Request::json();

		// validasi parameter
		if(!PBBValidate::issetTunggakan($tRequest)){
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
			$totalTunggakan = $this->pbbModel->getTotalTunggakan($year);
		
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

}
