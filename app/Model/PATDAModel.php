<?php
namespace Model;

use \PDO;
use \Config\Database;
use \Services_JSON;
use \Controller\AppController;

class PATDAModel extends Database{	 
	private $json;
	private $thnTagih;
	
	public function __construct(){
		$this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->getConnGwPATDA();
		$this->getConnSwPATDA();
		$this->appController = new AppController;
		$this->thnTagih = $this->appController->getThnTagih();
    }
    
    public function getPembayaran($npwpd,$code){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT
        npwpd as NPWPD,
        payment_code AS CODE,
        wp_nama AS NAMA,
        op_alamat AS ALAMAT,
        simpatda_dibayar AS TAGIHAN,
        simpatda_denda AS DENDA,
        payment_paid AS TGL_BAYAR,
        payment_flag AS STATUS
        FROM
        SIMPATDA_GW
        WHERE npwpd ='{$npwpd}'
        AND payment_code = '{$code}'";
        // print_r($query);exit;
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['data'][] = $dt;	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $this->json->encode($result);
	}

    public function getProfilTagihan($npwpd){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT
				A.npwpd as NPWPD,
				A.wp_nama AS NAMA_WP,
				A.wp_alamat AS ALAMAT_WP,
				A.op_nama AS NAMA_OP,
				A.op_alamat AS ALAMAT_OP,
				B.jenis AS JENIS_PAJAK,
				A.simpatda_tahun_pajak AS TAHUN_PAJAK,
				A.simpatda_bulan_pajak AS BULAN_PAJAK,
				A.masa_pajak_awal AS MASA_PAJAK_AWAL,
				A.masa_pajak_akhir AS MASA_PAJAK_AKHIR,
				A.sptpd AS SPTPD,
				A.sspd AS SSPD,
				A.simpatda_dibayar AS TAGIHAN,
				A.simpatda_denda AS DENDA,
				A.payment_paid AS TGL_BAYAR,
				CASE WHEN A.payment_flag = 1 THEN 'Sudah Bayar' ELSE 'Belum Bayar' END AS STATUS_BAYAR
				FROM
				SIMPATDA_GW A, SIMPATDA_TYPE B
				WHERE 
				A.npwpd ='{$npwpd}' AND
				A.simpatda_type = B.id
				ORDER BY A.saved_date desc
				LIMIT 1";
		//echo $query;
        
		try {
			$stmt = $this->connGW->prepare($query);
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

    public function getProfilWP($npwpd){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT
				CPM_NPWPD as NPWPD,
				CPM_NAMA_WP AS NAMA_WP,
				CPM_ALAMAT_WP AS ALAMAT_WP,
				CPM_KELURAHAN_WP AS KELURAHAN_WP,
				CPM_KECAMATAN_WP AS KECAMATAN_WP
				FROM
				PATDA_WP
				WHERE 
				CPM_NPWPD ='{$npwpd}'";
        
		try {
			$stmt = $this->connSW->prepare($query);
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

	public function getKetetapanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}
		
		$query = "
		SELECT (CASE WHEN PAYMENT_FLAG = 1 THEN 'Sudah Bayar' 
		WHEN PAYMENT_FLAG <> 1 THEN 'Belum Bayar'END) AS Status,  
		COUNT(*) AS 'jml_op' 
		FROM  SIMPATDA_GW 
		WHERE {$thn} GROUP BY  PAYMENT_FLAG ";
  		// echo $query;exit;
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['data'][] = $dt;	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $this->json->encode($result);
	}   
    
	public function getPenerimaanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
		SELECT SUM(PATDA_TOTAL_BAYAR) as Nilai
		FROM SIMPATDA_GW 
		WHERE {$thn} 
		AND  PAYMENT_FLAG = 1 ";
		try {
			$stmt = $this->connGW->prepare($query);
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
    
	public function getTunggakanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
		SELECT SUM(SIMPATDA_DIBAYAR) as Nilai
		FROM SIMPATDA_GW 
		WHERE {$thn} 
		AND  PAYMENT_FLAG <> 1  ";
		// echo $query;exit;
		try {
			$stmt = $this->connGW->prepare($query);
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
    
    public function getTunggakanPerJenisPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "A.SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND A.SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}
		
		$query = "
		SELECT   B.jenis Jenis,  
		 SUM(A.simpatda_dibayar) 'pajak' 
		 FROM   SIMPATDA_GW A, SIMPATDA_TYPE B 
		WHERE  {$thn} AND 
		 A.simpatda_type = B.id AND   
		 A.payment_flag <> 1  GROUP BY   
		 B.jenis ORDER BY  B.jenis ";
		// echo $query;exit;
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['data'][] = $dt;	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $this->json->encode($result);
    } 
 
    public function getPenerimaanPerJenisPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "A.SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND A.SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}
		
		$query = "
		SELECT  B.jenis Jenis,  
				SUM(A.patda_total_bayar) 'pajak' 
		FROM   SIMPATDA_GW A, SIMPATDA_TYPE B 
		WHERE  {$thn} 
		AND  A.simpatda_type = B.id 
		AND   A.payment_paid IS NOT NULL  
		GROUP BY  B.jenis ORDER BY  B.jenis ";
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['data'][] = $dt;	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $this->json->encode($result);
	}	 

	public function getKetetapan($y){
		$result = array('exist' => 0, 'data' => null);
	
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "A.SIMPATDA_TAHUN_PAJAK >= '{$y2}' AND A.SIMPATDA_TAHUN_PAJAK <= '{$y1}'";
		}	
	
		$query = "
		SELECT Jenis, sum(penerimaan) penerimaan, sum(tunggakan) tunggakan from(SELECT   B.jenis Jenis,   
				SUM(A.simpatda_dibayar) as penerimaan, 0 tunggakan 
				FROM   SIMPATDA_GW A, SIMPATDA_TYPE B 
				WHERE {$thn} AND 
				A.simpatda_type = B.id AND   
				A.payment_flag <> 1  GROUP BY   
				B.jenis
				union
		SELECT   B.jenis Jenis,  0 penerimaan,
				SUM(A.simpatda_dibayar) as tunggakan 
				FROM   SIMPATDA_GW A, SIMPATDA_TYPE B 
				WHERE {$thn} AND 
				A.simpatda_type = B.id AND   
				A.payment_flag = 1  GROUP BY   
				B.jenis) tbl GROUP BY Jenis
		";
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				$result['exist'] = $stmt->rowCount();
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['data'][] = $dt;	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $this->json->encode($result);
	}
 
    public function getJumlahTargetPenerimaanPerTahun($y){
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y1}'";
		}
		
		$query = "
		SELECT  SUM(A.simpatda_dibayar) 'pajak' 
		FROM   	SIMPATDA_GW A
		WHERE  	{$thn} 
				AND	A.payment_paid IS NOT NULL
				AND A.payment_flag = '1'";
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result = doubleval($dt->pajak);	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}
 
    public function getJumlahTunggakanPerTahun($y){
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "A.SIMPATDA_TAHUN_PAJAK = '{$y1}'";
		}
		
		$query = "
		SELECT  SUM(A.simpatda_dibayar) 'total_tunggakan' 
		FROM   	SIMPATDA_GW A
		WHERE  	{$thn} 
				AND	(A.payment_paid IS NULL
					OR A.payment_flag = '0'
					OR A.payment_flag IS NULL)";
		
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result = doubleval($dt->total_tunggakan);	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}
 
    public function getDaftarPenerimaan($y, $m){
		
		$thn = "GW.SIMPATDA_TAHUN_PAJAK = '{$y}'";
		
		$query = "SELECT npwpd, wp_nama, jenis, simpatda_tahun_pajak, simpatda_bulan_pajak, simpatda_dibayar, patda_denda, patda_admin_gw, patda_misc_fee, patda_total_bayar, payment_flag 
				FROM SIMPATDA_GW GW, SIMPATDA_TYPE T
				WHERE GW.simpatda_type = T.id
				AND {$thn}";

		if ($m != '') {
			$query = $query . " AND (GW.SIMPATDA_BULAN_PAJAK = '{$m}' OR GW.SIMPATDA_BULAN_PAJAK = '0{$m}')";
		}

		$query = $query . " ORDER BY GW.simpatda_bulan_pajak, T.jenis";

		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$penerimaan = null;
					$penerimaan['npwpd'] = $dt->npwpd;	
					$penerimaan['nama'] = $dt->wp_nama;	
					$penerimaan['jenis'] = $dt->jenis;	
					$penerimaan['tahun'] = $dt->simpatda_tahun_pajak;	
					$penerimaan['bulan'] = $dt->simpatda_bulan_pajak;	
					$penerimaan['total_tagihan'] = doubleval($dt->simpatda_dibayar);	
					$penerimaan['denda'] = doubleval($dt->patda_denda);	
					$penerimaan['total_bayar'] = doubleval($dt->patda_total_bayar);	
					$penerimaan['status'] = $dt->payment_flag;	
					array_push($result, $penerimaan);
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}
 
    public function getDaftarPenerimaanJenis($year, $month, $kodeJenisPajak){
		
		$thn = "GW.SIMPATDA_TAHUN_PAJAK = '{$year}'";
		
		$query = "	SELECT jenis, simpatda_dibayar, patda_denda, patda_admin_gw, patda_misc_fee, patda_total_bayar 
					FROM SIMPATDA_GW GW, SIMPATDA_TYPE T
					WHERE GW.simpatda_type = T.id
						AND {$thn}";

		if ($month < 10) {
			$month = "0" . $month;
		}

		if ($month != '') {
			$query = $query . " AND GW.SIMPATDA_BULAN_PAJAK = '{$month}'";
		}

		if ($kodeJenisPajak != "") {
			$query = $query . " AND GW.SIMPATDA_TYPE = '{$kodeJenisPajak}'";
		}

		$query = $query . " GROUP BY T.jenis ORDER BY T.jenis";

		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$penerimaan = null;
					$penerimaan['jenis_pajak'] = $dt->jenis;	
					$penerimaan['total_tagihan'] = doubleval($dt->simpatda_dibayar);	
					$penerimaan['denda'] = doubleval($dt->patda_denda);	
					$penerimaan['total_bayar'] = doubleval($dt->patda_total_bayar);	
					array_push($result, $penerimaan);
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}

}

?>
