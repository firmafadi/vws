<?php
namespace Controller;


use \Core\Helper;
use \Core\Request;
use \Model\PATDAModel;
use \Validate\PATDAValidate;
use \Controller\AuthController;
use \Services_JSON;

class PATDAController{
	private $json;
	private $patdaModel;
	
    public function __construct(){		
        $this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->auth = new AuthController;
        $this->patdaModel = new PATDAModel;
    }

    public function inquiry(){	
        header('Access-Control-Allow-Origin:*'); 
		$result		= array();
		$tagihan = $this->patdaModel->getPembayaran($_POST['NPWPD'],$_POST['CODE']);

		$tmp = array();
		$tmp['tagihan'] = $tagihan;

		$result['response_code'] = 'OK' ;		
		$result['result'] = $tmp;
		 
		Helper::echoResponse($this->json->encode($result));
    }
    
    public function dataDashboardPATDA(){
		$result = array('result' => NULL, 'response_code' => NULL);
		header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;
		
		$ketetapan = $this->patdaModel->getKetetapanPerTahun($thn);
		$penerimaan = $this->patdaModel->getPenerimaanPerTahun($thn);
		$tunggakan = $this->patdaModel->getTunggakanPerTahun($thn);
		
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

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '';
		$result = array('result' => NULL, 'response_code' => NULL);
		$tunggakan = $this->patdaModel->getTunggakanPerJenisPerTahun($thn);
	
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
		$penerimaan = $this->patdaModel->getPenerimaanPerJenisPerTahun($thn);
	
		$tmp = array();
		$tmp['penerimaan'] = $penerimaan;
		
		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}
	
	public function dataDetailDashboardPATDA(){
		$result = array('result' => NULL, 'response_code' => NULL);
        header('Access-Control-Allow-Origin:*'); 

		$thn = (isset($_POST['thn'])) ? $_POST['thn'] : '' ;
		
		$ketetapan = $this->patdaModel->getKetetapan($thn);
		$penerimaan = $this->patdaModel->getPenerimaanPerTahun($thn);
		$tunggakan = $this->patdaModel->getTunggakanPerTahun($thn);
		
		$tmp = array();
		$tmp['ketetapan'] = $ketetapan;
		$tmp['penerimaan'] = $penerimaan;
		$tmp['tunggakan'] = $tunggakan;

		$result['response_code'] = 'OK';		
		$result['result'] = $tmp;
		
		Helper::echoResponse($this->json->encode($result));
	}
	
	/**
	 * 
	 * @api {POST} /patda/penerimaan/target/total Total Target Penerimaan
	 * @apiName getTotalTargetPenerimaan
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
     * @apiParam {String} token Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Paling lama tahun 2018.
	 * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * }      
	 * 
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/patda/penerimaan/target/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Number} data.total_target_penerimaan Total target penerimaan.
	 * 
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * {
     * 		"status": true,
     * 		"message": "OK",
     * 		"data": {
     *     		"total_target_penerimaan": 0
     * 		}
	 * }
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
    public function dataJumlahTargetPenerimaan(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!PATDAValidate::issetTargetPenerimaan($tRequest)){
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
			$target = $this->patdaModel->getJumlahTargetPenerimaanPerTahun($year);
		
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
	 * @api {POST} /patda/tunggakan/total Total Tunggakan
	 * @apiName getTotalTunggakan
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.
	 * 
     * @apiParam {String} token Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015
	 * }      
	 * 
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/patda/tunggakan/total
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Number} data.total_tunggakan Total tunggakan.
	 * 
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * {
     * 		"status": true,
     * 		"message": "OK",
     * 		"data": {
     *     		"total_tunggakan": 0
     * 		}
	 * }
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
		if(!PATDAValidate::issetTunggakan($tRequest)){
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
			$totalTunggakan = $this->patdaModel->getJumlahTunggakanPerTahun($year);
		
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
	 * @api {POST} /patda/penerimaan Daftar Penerimaan
	 * @apiName getDaftarPenerimaan
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
     * @apiParam {String} token Kode autentikasi.
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
     * http://119.252.160.220/vtax-web-service/patda/penerimaan
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object[]} data.penerimaan Dafter penerimaan.
	 * @apiSuccess {String} data.penerimaan.npwpd Nomor NPWPD.
	 * @apiSuccess {String} data.penerimaan.nama Nama toko/wajib pajak.
	 * @apiSuccess {String} data.penerimaan.jenis Jenis pajak.
	 * @apiSuccess {String} data.penerimaan.tahun Tahun pajak.
	 * @apiSuccess {String} data.penerimaan.bulan Bulan pajak.
	 * @apiSuccess {String} data.penerimaan.total_tagihan Total tagihan pajak.
	 * @apiSuccess {String} data.penerimaan.denda Total denda dari pajak.
	 * @apiSuccess {String} data.penerimaan.total_bayar Total pajak yang telah dibayar.
	 * @apiSuccess {String} data.penerimaan.status Status pembayaran pajak.
	 * 
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * {
     * 		"status": true,
     * 		"message": "OK",
     * 		"data": {
     *     		"penerimaan": [
     *       		{
	 *           		 "npwpd": "P2030097502",
	 *  		         "nama": "VONY PETRO RASI",
	 *  		         "jenis": "Hiburan (NonReg)",
	 *  		         "tahun": "2017",
	 *  		         "bulan": "00",
	 *  		         "total_tagihan": 83000000,
	 *  		         "denda": 0,
	 *  		         "total_bayar": 83000000,
	 *  		         "status": "1"
	 *  		     },
	 *  		     {
	 *  		         "npwpd": "P2030106609",
	 *  		         "nama": "EMIEL OCTARIA",
	 *  		         "jenis": "Hiburan (NonReg)",
	 *  		         "tahun": "2017",
	 *  		         "bulan": "00",
	 *  		         "total_tagihan": 4360000,
	 *  		         "denda": 0,
	 *  		         "total_bayar": 4360000,
	 *  		         "status": "1"
	 *  		     }
	 * 			]
     * 		}
	 * }
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
		if(!PATDAValidate::issetPenerimaan($tRequest)){
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
			$penerimaan = $this->patdaModel->getDaftarPenerimaan($year, $month);
		
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
	 * @api {POST} /patda/penerimaan/jenispajak Daftar Penerimaan Jenis Pajak
	 * @apiName getDaftarPenerimaanJenisPajak
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
	 * 
     * @apiDescription Mengambil daftar penerimaan jenis pajak pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.
	 * 
     * @apiParam {String} token Kode autentikasi.
	 * @apiParam  {Number} [tahun] Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.
	 * @apiParam  {Number} [bulan] Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).
	 * @apiParam  {String} [kode_jenis_pajak] Kode jenis pajak.
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"tahun" : 2015,
	 * 		"bulan" : 6,
	 * 		"kode_jenis_pajak" : "5"
	 * }      
	 *   
     * @apiExample Contoh Penggunaan:
     * http://119.252.160.220/vtax-web-service/patda/penerimaan/jenispajak
	 * 
	 * @apiSuccess {Boolean} status Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).
	 * @apiSuccess {String} message Pesan dari hasil eksekusi endpoint.
	 * @apiSuccess {Object} data Semua data yang diperoleh ditampilkan di sini.
	 * @apiSuccess {Object[]} data.penerimaan Dafter penerimaan.
	 * @apiSuccess {String} data.penerimaan.jenis_pajak Jenis pajak.
	 * @apiSuccess {String} data.penerimaan.total_tagihan Total tagihan pajak.
	 * @apiSuccess {String} data.penerimaan.denda Total denda dari pajak.
	 * @apiSuccess {String} data.penerimaan.total_bayar Total pajak yang telah dibayar.
	 * 
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * {
     * 		"status": true,
     * 		"message": "OK",
     * 		"data": {
     *     		"penerimaan": [
     *       		{
	 *  		         "jenis_pajak": "Hiburan (NonReg)",
	 *  		         "total_tagihan": 83000000,
	 *  		         "denda": 0,
	 *  		         "total_bayar": 83000000
	 *  		     },
	 *  		     {
	 *  		         "jenis_pajak": "Hiburan (NonReg)",
	 *  		         "total_tagihan": 4360000,
	 *  		         "denda": 0,
	 *  		         "total_bayar": 4360000
	 *  		     }
	 * 			]
     * 		}
	 * }
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
    public function daftarPenerimaanJenisPajak(){
        header('Access-Control-Allow-Origin:*'); 

		$tRequest	= Request::json();

		// validasi parameter
		if(!PATDAValidate::issetPenerimaanJenisPajak($tRequest)){
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

		// Get parameter jenis pajak
		$kodeJenisPajak = "";
		if (isset($tRequest->kode_jenis_pajak)) {
			$kodeJenisPajak = $tRequest->kode_jenis_pajak;
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		$result = array('status' => false, 'message' => NULL, 'data' => NULL);

		if ($auth->status) {	
			$penerimaan = $this->patdaModel->getDaftarPenerimaanJenis($year, $month, $kodeJenisPajak);
		
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
     * @api {post} /patda/inquiry/profil-tagihan Get Profil Tagihan
     * @apiName GetTagihan
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
     * 
     * @apiDescription Mengambil data profil tagihan (9 Pajak).
     * 
     * @apiExample Contoh Penggunaan :
     * http://119.252.160.220/vtax-web-service/patda/inquiry/profil-tagihan
     * 
     * @apiParam {String} token Kode autentikasi.
     * @apiParam {String} npwpd NPWPD.
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"npwpd" : "P2020033870"
	 * }      
     * @apiSuccess {boolean} status Status response, jika berhasil bernilai true dan jika gagal bernilai false.
     * @apiSuccess {String} message Deskripsi response.
     * @apiSuccess {object} data Informasi tagihan.
     * @apiSuccess {String} data.NPWPD NPWPD.
     * @apiSuccess {String} data.NAMA_WP Nama wajib pajak.
     * @apiSuccess {String} data.ALAMAT_WP Alamat wajib pajak.
     * @apiSuccess {String} data.NAMA_OP Nama objek pajak.
     * @apiSuccess {String} data.ALAMAT_WP Alamat objek pajak.
     * @apiSuccess {String} data.JENIS_PAJAK Jenis pajak.
     * @apiSuccess {String} data.TAHUN_PAJAK Tahun pajak.
     * @apiSuccess {String} data.BULAN_PAJAK Periode pajak.
     * @apiSuccess {String} data.MASA_PAJAK_AWAL Tanggal masa awal pajak.
     * @apiSuccess {String} data.MASA_PAJAK_AKHIR Tanggal masa akhir pajak.
     * @apiSuccess {String} data.SPTPD Nomor SPTPD.
     * @apiSuccess {String} data.SSPD Nomor SSPD.
     * @apiSuccess {String} data.TAGIHAN Tagihan.
     * @apiSuccess {String} data.DENDA Denda.
     * @apiSuccess {String} data.TGL_BAYAR Tanggal bayar.
     * @apiSuccess {String} data.STATUS_BAYAR Status bayar.
	 * 
     * @apiSuccessExample {json} Success-Respon: 
     * {
     *      "status": true,
     * 		"message": "Record exist!",
     * 		"data": {
     *   		"NPWPD": "P2020033870",
     *  		"NAMA_WP": "JAMAL",
     *   		"ALAMAT_WP": "JL.KOL.H.BURLIAN NO.89",
     *   		"NAMA_OP": "RM ISTANA BUNDA I",
     *   		"ALAMAT_OP": "JL.KOL.H.BURLIAN NO.189",
     *   		"JENIS_PAJAK": "Restoran",
     *   		"TAHUN_PAJAK": "2017",
     *   		"BULAN_PAJAK": "01",
     *   		"MASA_PAJAK_AWAL": "2017-01-01",
     *   		"MASA_PAJAK_AKHIR": "2017-01-31",
     *   		"SPTPD": "900001527/RES/17",
     *   		"SSPD": "900001527/RES/17",
     *   		"TAGIHAN": "1500000.00",
     *   		"DENDA": "0",
     *   		"TGL_BAYAR": "2017-02-14 14:26:11",
     *   		"STATUS_BAYAR": "Sudah Bayar"
     * 		}
     *  }
     * 
     * @apiError {String} Parameter-Invalid Parameter tidak sesuai.
     * @apiError {String} Token-Ivalid Token salah, expired, dll.
     * @apiError {String} No-Record Data tidak ada.
     * 
     * @apiErrorExample {json} Error-Response (contoh):
     *  {
     *       "status": false,
     *       "message": "Parameter invalid!",
     *  }
     */	
	public function getProfilTagihan(){
        header('Access-Control-Allow-Origin: *'); 
                
		$tRequest	= Request::json();
		$result = array('status' => false, 'message' => null, 'data' => null);		

		// validasi parameter
		if(!PATDAValidate::issetProfil($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));

		if($auth->status){
			$row = $this->json->decode($this->patdaModel->getProfilTagihan($tRequest->npwpd));
			if($row->exist){
				$result['status'] = true;			
				$result['message'] = "Record exist!";			
				$result['data'] = $row->data;
			}else{
				$result['status'] = false;			
				$result['message'] = "No Record!";			
			}			
		}else{
			$result['message'] = $auth->message;
		}
			
		
		echo $this->json->encode($result);
	}

	/**
     * 
     * @api {post} /patda/inquiry/profil-wp Get Profil Wajib Pajak
     * @apiName GetWP
     * @apiGroup Patda
     * @apiVersion  0.1.1
     * @apiPermission public
     * 
     * @apiDescription Mengambil data profil wajib pajak (9 Pajak).
     * 
     * @apiExample Contoh Penggunaan :
     * http://119.252.160.220/vtax-web-service/patda/inquiry/profil-wp
     * 
     * @apiParam {String} token Kode autentikasi.
     * @apiParam {String} npwpd NPWPD.
     * 
     * @apiParamExample  {json} Request Body:
	 * {
	 * 		"token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c",	
	 * 		"npwpd" : "P210000080101"
	 * }      
     * @apiSuccess {boolean} status Status response, jika berhasil bernilai true dan jika gagal bernilai false.
     * @apiSuccess {String} message Deskripsi response.
     * @apiSuccess {object} data Informasi wajib pajak.
     * @apiSuccess {String} data.NPWPD NPWPD.
     * @apiSuccess {String} data.NAMA_WP Nama wajib pajak.
     * @apiSuccess {String} data.ALAMAT_WP Alamat wajib pajak.
     * @apiSuccess {String} data.KELURAHAN_WP Kelurahan (alamat) wajib pajak.
     * @apiSuccess {String} data.KECAMATAN_WP Kecamatan (alamat) wajib pajak.
	 * 
     * @apiSuccessExample {json} Success-Respon: 
     * {
     *      "status": true,
     * 		"message": "Record exist!",
     * 		"data": {
     *   			"NPWPD": "P210000080101",
     *   			"NAMA_WP": "Hotel semeru",
     *   			"ALAMAT_WP": "Jl. A. Yani 144 Mintaragen",
     *   			"KELURAHAN_WP": "RANDUGUNTING",
     *   			"KECAMATAN_WP": "TEGAL SELATAN"
     * 		}
     *  }
     * 
     * @apiError {String} Parameter-Invalid Parameter tidak sesuai.
     * @apiError {String} Token-Ivalid Token salah, expired, dll.
     * @apiError {String} No-Record Data tidak ada.
     * 
     * @apiErrorExample {json} Error-Response (contoh):
     *  {
     *       "status": false,
     *       "message": "Parameter invalid!",
     *  }
     */	
	public function getProfilWP(){
        header('Access-Control-Allow-Origin: *'); 
                
		$tRequest	= Request::json();
		$result = array('status' => false, 'message' => null, 'data' => null);		
		
		// validasi parameter
		if(!PATDAValidate::issetProfil($tRequest)){
			$result['message'] = "Parameters invalid!";
			echo $this->json->encode($result);
			die;
		}

		// validasi token
		$auth = $this->json->decode($this->auth->validateToken($tRequest->token));
		
		if($auth->status){
			$row = $this->json->decode($this->patdaModel->getProfilWP($tRequest->npwpd));
			if($row->exist){
				$result['status'] = true;			
				$result['message'] = "Record exist!";			
				$result['data'] = $row->data;
			}else{
				$result['status'] = false;			
				$result['message'] = "No Record!";			
			}	
		}else{
			$result['message'] = $auth->message;
		}

		
		echo $this->json->encode($result);
	}

}
?>
