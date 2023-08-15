<?php
namespace Model;

use \PDO;
use \Config\Database;
use \Services_JSON;
use \Controller\AppController;


class PBBModel extends Database{	 
	private $json;
	private $thnTagih;
	private $appController;
	
	public function __construct(){
		$this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->getConnGwPBB();
		$this->appController = new AppController;
		$this->thnTagih = $this->appController->getThnTagih();
	}

	public function getProfile($NOP){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT WP_NAMA as nama, 
					WP_ALAMAT as alamat, 
					OP_LUAS_BUMI as bumi, 
					OP_LUAS_BANGUNAN as bangunan
					FROM PBB_SPPT 
					WHERE NOP ='{$NOP}'
					ORDER BY SPPT_TAHUN_PAJAK desc LIMIT 1";
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

	public function getListTagihan($NOP){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT SPPT_TAHUN_PAJAK AS TAHUN,
					SPPT_PBB_HARUS_DIBAYAR AS TAGIHAN,
					PAYMENT_FLAG STATUS
				FROM PBB_SPPT
				WHERE NOP = '{$NOP}' 
				ORDER BY SPPT_TAHUN_PAJAK DESC
				LIMIT 5";
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

    
	public function getJumlahKetetapanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		$query = "
		SELECT
		(CASE WHEN PAYMENT_FLAG = 1 THEN 'Sudah Bayar'
		WHEN (PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL) THEN 'Belum Bayar'
		END) AS status,
		COUNT(*) AS 'jml_op'
		FROM
		PBB_SPPT
		WHERE
		{$thn}
		AND (PAYMENT_FLAG = 1 OR PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL)
		GROUP BY
		status";
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
    
	public function getTotalPenerimaanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		$query = "
			SELECT
			SUM(PBB_TOTAL_BAYAR) total
			FROM
			PBB_SPPT
			WHERE
			{$thn} AND
			PAYMENT_FLAG = 1";
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
    
	public function getTotalTunggakanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
			SELECT
			SUM(SPPT_PBB_HARUS_DIBAYAR) total
			FROM
			PBB_SPPT
			WHERE
			{$thn} AND
			(PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL)";
		
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
	
	public function getKetetapanPerKecamatanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
		SELECT 
			OP_KECAMATAN AS Kecamatan,   
			SUM(PBB_TOTAL_BAYAR) As 'Jumlah Pajak'  
		from   PBB_SPPT  
		where   {$thn}
		group by OP_KECAMATAN  order 
		by OP_KECAMATAN asc";
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

	public function getPenerimaanPerKecamatanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
		SELECT 
			OP_KECAMATAN AS Kecamatan,   
			SUM(PBB_TOTAL_BAYAR) As 'pajak'  
		from   PBB_SPPT  
		where {$thn} AND
		payment_flag = 1 
		group by OP_KECAMATAN  order 
		by OP_KECAMATAN asc";
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

	public function getTunggakanPerKecamatanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}

		$query = "
		SELECT 
			OP_KECAMATAN AS Kecamatan,   
			SUM(SPPT_PBB_HARUS_DIBAYAR) As 'pajak'  
		from   PBB_SPPT  
		where {$thn} AND
		payment_flag = 0 
		group by OP_KECAMATAN  order 
		by OP_KECAMATAN asc";
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

	public function getKetetapanPerKelurahan($y,$kecNama='',$kec='',$kel=''){
		$result = array('exist' => 0, 'data' => null);
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}

		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}

		if($kec!=''){
			$idKec=$kec;

			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN_KODE='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
		SELECT wil, sum(penerimaan) as penerimaan, sum(tunggakan) as tunggakan FROM (
			SELECT
			CPC_TKL_KELURAHAN AS wil,   
			0 penerimaan, 
			0 tunggakan
		FROM CPPMOD_TAX_KELURAHAN
		WHERE CPC_TKL_KCID='{$idKec}'
		GROUP BY CPC_TKL_KELURAHAN
		UNION
			SELECT
			OP_KELURAHAN AS wil,   
			SUM(PBB_TOTAL_BAYAR) penerimaan, 
			0 tunggakan
		FROM PBB_SPPT 
		WHERE  {$thn} 
		{$kecNama}{$kec} {$kel} AND PAYMENT_FLAG = 1
		GROUP BY OP_KELURAHAN_KODE
		UNION
		SELECT
			OP_KELURAHAN AS wil,   
			0 penerimaan, 
			SUM(SPPT_PBB_HARUS_DIBAYAR) tunggakan
		FROM PBB_SPPT 
		WHERE  {$thn} 
		{$kecNama}{$kec} {$kel} AND  (PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL) 
		GROUP BY OP_KELURAHAN_KODE)tbl group by wil";
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

	public function getTunggakanPerKelurahan($y,$kecNama, $kec, $kel){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$idKec =  $kec;
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN_KODE='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
		SELECT wil, sum(pajak) as pajak FROM(SELECT
			CPC_TKL_KELURAHAN AS wil,   
			0 pajak
			FROM CPPMOD_TAX_KELURAHAN
			WHERE CPC_TKL_KCID='{$idKec}'
			GROUP BY CPC_TKL_KELURAHAN
			UNION
			
				SELECT 
				OP_KELURAHAN AS wil,   
				SUM(SPPT_PBB_HARUS_DIBAYAR) As 'pajak'  
			from   PBB_SPPT  
			where {$thn} AND
			(PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL)
			{$kecNama} {$kec} {$kel} 
			group by OP_KELURAHAN)tbl group by wil";
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

	public function getTunggakanKelurahan($y,$kecNama, $kec, $kel){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN_KODE='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
				SELECT
				A.OP_KELURAHAN AS wil,   
				SUM(SPPT_PBB_HARUS_DIBAYAR) penerimaan
			FROM PBB_SPPT A 
			WHERE {$thn} {$kecNama} {$kec} {$kel}  AND (PAYMENT_FLAG = 0 OR PAYMENT_FLAG IS NULL)
			GROUP BY OP_KELURAHAN_KODE 
			ORDER BY OP_KELURAHAN asc";
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

	public function getPenerimaanKelurahanKecamatan($y,$kecNama, $kec, $kel){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN_KODE='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
				SELECT
				A.OP_KELURAHAN AS wil,   
				SUM(PBB_TOTAL_BAYAR) penerimaan
			FROM PBB_SPPT A 
			WHERE {$thn} {$kecNama} {$kec} {$kel} AND PAYMENT_FLAG = 1 
			GROUP BY OP_KELURAHAN_KODE 
			ORDER BY OP_KELURAHAN asc;";
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

	public function getPenerimaanPerKelurahan($y,$kecNama, $kec, $kel){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$idKec=$kec;
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN_KODE='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

			$query = "SELECT wil, sum(pajak) as pajak FROM(SELECT
			CPC_TKL_KELURAHAN AS wil,   
			0 pajak
			FROM CPPMOD_TAX_KELURAHAN
			WHERE CPC_TKL_KCID='{$idKec}'
			GROUP BY CPC_TKL_KELURAHAN
			UNION
			
			SELECT 
			OP_KELURAHAN AS wil,   
			SUM(PBB_TOTAL_BAYAR) As 'pajak'  
			from   PBB_SPPT  
			where   {$thn} AND
			PAYMENT_FLAG = 1
			{$kecNama} {$kec} {$kel}   
			group by OP_KELURAHAN)tbl GROUP BY wil";
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

	
	public function getKetetapanPerKecamatan($y){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "SPPT_TAHUN_PAJAK = '{$y}'";
		}else{
			$thn = "SPPT_TAHUN_PAJAK >= '{$y2}' AND SPPT_TAHUN_PAJAK <= '{$y1}'";
		}
		
		$query = "
		select
			A.OP_KECAMATAN AS Kecamatan,   
			SUM(PBB_TOTAL_BAYAR)  penerimaan,
			SUM(SPPT_PBB_HARUS_DIBAYAR) tunggakan
		from PBB_SPPT A
		WHERE  {$thn}	
		AND OP_KECAMATAN = A.OP_KECAMATAN
		group by OP_KECAMATAN 
		order by OP_KECAMATAN asc";
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
		
		// return $this->json->encode($result);
		return $result;
	}

	public function getKecamatan(){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "
		SELECT CPC_TKC_ID, CPC_TKC_KECAMATAN FROM CPPMOD_TAX_KECAMATAN order by CPC_TKC_KECAMATAN ASC";
		// var_dump($query);exit;
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

	public function getKelurahan($kel){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "
		SELECT CPC_TKL_ID, CPC_TKL_KELURAHAN FROM CPPMOD_TAX_KELURAHAN WHERE CPC_TKL_KCID= '{$kel}'	order by CPC_TKL_KELURAHAN ASC";
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

	// NEW GILAR //

	public function getKetetapan($year){
		$result = array();
		
		$query = "	SELECT 	NOP as 'nop', 
							WP_NAMA as 'wp_nama', 
							SPPT_PBB_HARUS_DIBAYAR as 'harus_dibayar'
							-- PBB_DENDA as 'denda', 
							-- PBB_TOTAL_BAYAR as 'total_bayar', 
							-- PAYMENT_FLAG as 'status_pembayaran' 
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE SPPT_TAHUN_PAJAK = {$year}";

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$ketetapan = null;
					$ketetapan['nop'] = $dt->nop;	
					$ketetapan['wp_nama'] = $dt->wp_nama;	
					$ketetapan['ketetapan'] = doubleval($dt->harus_dibayar);	
					// if ($dt->status_pembayaran == "" || $dt->status_pembayaran == null || $dt->status_pembayaran == "0") {
						// $ketetapan['status_pembayaran'] = "Belum dibayar";	
					// } else {
						// $ketetapan['denda'] = doubleval($dt->denda);
						// $ketetapan['total_bayar'] = doubleval($dt->total_bayar);
						// $ketetapan['status_pembayaran'] = "Sudah dibayar";	
					// }
					array_push($result, $ketetapan);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getTotalKetetapan($year){
		// $result = array();
		
		$query = "SELECT SUM(SPPT_PBB_HARUS_DIBAYAR) as 'harus_dibayar'/*, SUM(PBB_DENDA) as 'denda', SUM(PBB_TOTAL_BAYAR) as 'total_bayar', PAYMENT_FLAG */
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE SPPT_TAHUN_PAJAK = {$year}";

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					// $result['harus_dibayar'] = doubleval($dt->harus_dibayar);	
					// $result['denda'] = doubleval($dt->denda);
					// $result['total_bayar'] = doubleval($dt->total_bayar);
					$result = doubleval($dt->harus_dibayar);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getPenerimaan($year, $month){
		$result = array();
		
		$query = "SELECT /*NOP as 'nop', WP_NAMA as 'wp_nama', SPPT_PBB_HARUS_DIBAYAR as 'harus_dibayar', PBB_DENDA as 'denda',*/ PBB_TOTAL_BAYAR as 'total_bayar'/*, DATE_FORMAT(PAYMENT_PAID, '%Y-%m-%d') as 'tanggal_bayar'*/ 
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE YEAR(PAYMENT_PAID) = {$year} 
						AND PAYMENT_FLAG = 1";

		if ($month >= 1 && $month <= 12) {
			$query = $query . " AND MONTH(PAYMENT_PAID) = {$month}";
		}

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$ketetapan = null;
					$ketetapan['nop'] = $dt->nop;	
					// $ketetapan['wp_nama'] = $dt->wp_nama;	
					// $ketetapan['harus_dibayar'] = doubleval($dt->harus_dibayar);
					// $ketetapan['denda'] = doubleval($dt->denda);
					$ketetapan['total_bayar'] = doubleval($dt->total_bayar);
					// $ketetapan['tanggal_bayar'] = $dt->tanggal_bayar;	
					array_push($result, $ketetapan);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getTotalPenerimaan($year, $month){
		$result = array();
		
		$query = "	SELECT SUM(SPPT_PBB_HARUS_DIBAYAR) as 'harus_dibayar', SUM(PBB_DENDA) as 'denda', SUM(PBB_TOTAL_BAYAR) as 'total_bayar'
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE YEAR(PAYMENT_PAID) = {$year} 
						AND PAYMENT_FLAG = 1";

		if ($month >= 1 && $month <= 12) {
			$query = $query . " AND MONTH(PAYMENT_PAID) = {$month}";
		}

		echo $query;

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['harus_dibayar'] = doubleval($dt->harus_dibayar);
					$result['denda'] = doubleval($dt->denda);
					$result['total_bayar'] = doubleval($dt->total_bayar);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getPenerimaanKelurahan($year, $month, $kodeKelurahan){
		$result = array();
		
		$query = "SELECT 	OP_KELURAHAN_KODE 'kode_kelurahan', 
							OP_KELURAHAN 'nama_kelurahan', 
							SUM(SPPT_PBB_HARUS_DIBAYAR) as 'harus_dibayar', 
							SUM(PBB_DENDA) as 'denda', 
							SUM(PBB_TOTAL_BAYAR) as 'total_bayar'
					FROM 	GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE 	YEAR(PAYMENT_PAID) = {$year} 
						AND PAYMENT_FLAG = 1";

		if ($month >= 1 && $month <= 12) {
			$query = $query . " AND MONTH(PAYMENT_PAID) = {$month}";
		}

		if ($kodeKelurahan != "") {
			$query = $query . " AND OP_KELURAHAN_KODE = {$kodeKelurahan}";
		}

		$query = $query . " GROUP BY OP_KELURAHAN_KODE";

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$penerimaan = null;
					$penerimaan['kode_kelurahan'] = $dt->kode_kelurahan;	
					$penerimaan['nama_kelurahan'] = $dt->nama_kelurahan;	
					$penerimaan['harus_dibayar'] = doubleval($dt->harus_dibayar);
					$penerimaan['denda'] = doubleval($dt->denda);
					$penerimaan['total_bayar'] = doubleval($dt->total_bayar);
					array_push($result, $penerimaan);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getTunggakan($year) {
		$result = array();
		
		$query = "	SELECT NOP as 'nop', 
						WP_NAMA as 'wp_nama', 
						SPPT_PBB_HARUS_DIBAYAR as 'harus_dibayar', 
						PBB_DENDA as 'denda', 
						PBB_TOTAL_BAYAR as 'total_bayar'
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE SPPT_TAHUN_PAJAK = {$year} 
						AND (PAYMENT_FLAG = 0 OR PAYMENT_FLAG = null OR PAYMENT_FLAG = '')";

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$ketetapan = null;
					$ketetapan['nop'] = $dt->nop;	
					$ketetapan['wp_nama'] = $dt->wp_nama;	
					$ketetapan['harus_dibayar'] = doubleval($dt->harus_dibayar);	
					array_push($result, $ketetapan);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}

	public function getTotalTunggakan($year) {
		$result = array();
		
		$query = "	SELECT 
						SUM(SPPT_PBB_HARUS_DIBAYAR) as 'harus_dibayar', 
						SUM(PBB_DENDA) as 'denda', 
						SUM(PBB_TOTAL_BAYAR) as 'total_bayar'
					FROM GW_PBB_PALEMBANG.PBB_SPPT 
					WHERE SPPT_TAHUN_PAJAK = {$year} 
						AND (PAYMENT_FLAG = 0 OR PAYMENT_FLAG = null OR PAYMENT_FLAG = '')";

		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result['harus_dibayar'] = doubleval($dt->harus_dibayar);	
					$result['denda'] = doubleval($dt->denda);
					$result['total_bayar'] = doubleval($dt->total_bayar);
				}		
			}			
		} catch (Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		
		return $result;
	}



}
?>
