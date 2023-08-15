<?php
namespace Model;

use \PDO;
use \Config\Database;
use \Services_JSON;
use \Controller\AppController;

class BPHTBModel extends Database{	 
	private $json;
	private $thnTagih;
	private $appController;
	
	
	public function __construct(){
		$this->json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
		$this->getConnGwBPHTB();
		$this->getConnSwBPHTB();
		$this->appController = new AppController;
		$this->thnTagih = $this->appController->getThnTagih();
	}

	public function getTagihan($nop,$code){
		$result = array('exist' => 0, 'data' => null);
		
		$query = "SELECT
		wp_nama as nama,
		wp_alamat as alamat,
		op_kelurahan as kelurahan,
		op_kecamatan as kecamatan,
		op_luas_tanah as lTanah,
		op_luas_bangunan as lBangunan,
		payment_paid as tglBayar,
		payment_flag as status,
		bphtb_dibayar as pembayaran
		FROM
		ssb
		WHERE op_nomor= '{$nop}'
		AND id_ssb ='{$code}'";
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

	public function getKetetapanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		$query = "
		SELECT  (CASE WHEN (PAYMENT_FLAG = 0) THEN 'Belum Bayar'          
		WHEN PAYMENT_FLAG = 1 THEN 'Sudah Bayar'      
		END) AS Status,  COUNT(*) AS 'jml_op' 
		FROM  ssb WHERE    
		{$thn} AND 
		payment_flag = 0 or payment_flag = 1 
		GROUP BY  PAYMENT_FLAG ";
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
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		$query = "
		SELECT  
		sum(bphtb_dibayar) Nilai 
		FROM  ssb 
		WHERE {$thn} 
		and PAYMENT_FLAG = 1 ";
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
    
	public function getTunggakanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		$query = "
		SELECT  
		sum(bphtb_dibayar) Nilai 
		FROM  ssb 
		WHERE {$thn} 
		and PAYMENT_FLAG = 0 ";
		
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

	public function getTunggakanPerKecamatanPerTahun($y){
		$result = array('exist' => 0, 'data' => null);
		
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}

		$query = "
		select OP_KECAMATAN AS Kecamatan, 
		SUM(bphtb_dibayar) As 'pajak'  
		from   ssb  
		where   {$thn} 
		AND payment_flag = 0 group by OP_KECAMATAN  order by OP_KECAMATAN asc";
		
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
			$thn = "saved_date like '{$y}$'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}

		$query = "
		select OP_KECAMATAN AS Kecamatan, 
		SUM(bphtb_dibayar) As 'pajak'  
		from   ssb  
		where  {$thn} 
		AND payment_flag = 1 AND payment_paid is not null group by OP_KECAMATAN  order by OP_KECAMATAN asc";
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

	public function getKetetapan($y){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}

		$query = "
		Select A.op_kecamatan AS wil, 
		(SELECT SUM(bphtb_dibayar) As 'pajak'  from   ssb  where   {$thn} AND payment_flag = 1 AND op_kelurahan = A.op_kelurahan) penerimaan,
		(SELECT SUM(bphtb_dibayar) As 'pajak'  from   ssb  where   {$thn} AND (payment_flag = 0 or payment_flag is null)  AND op_kelurahan = A.op_kelurahan) tunggakan
		from   ssb A
		where  {$thn}
		group by op_kecamatan  order by op_kecamatan asc
		";

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

	public function getTunggakanPerKelurahan($y,$kecNama, $kec, $kel){
		$result = array('exist' => 0, 'data' => null);
		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
				Select op_kelurahan AS Kecamatan, 
				SUM(bphtb_dibayar) As 'pajak'  
				from   ssb  
				where   {$thn}
				AND (payment_flag = 0 or payment_flag is null) 
				{$kecNama} {$kec} {$kel}
				group by op_kelurahan  order by op_kelurahan asc";
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

	public function getPenerimaanPerKelurahan($y='',$kecNama='', $kec='', $kel=''){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}%'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "
		Select op_kelurahan AS Kecamatan, 
		SUM(bphtb_dibayar) As 'pajak'  
		from   ssb  
		where  {$thn}
		AND payment_flag = 1 
		{$kecNama} {$kec} {$kel}
		group by op_kelurahan  order by op_kelurahan asc";
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

	public function getKetetapanPerKelurahan($y,$kecNama='',$kec='',$kel=''){
		$result = array('exist' => 0, 'data' => null);

		$y1 = date('Y');
		$y2 = $y1-$this->thnTagih;
		if ($y != '') {
			$thn = "saved_date like '{$y}'";
		}else{
			$thn = "saved_date BETWEEN '{$y2}%' AND '{$y1}%'";
		}
		
		if($kecNama!=''){
			$kecNama = "AND OP_KECAMATAN='{$kecNama}'";
		}
		if($kec!=''){
			$kec = "AND OP_KECAMATAN_KODE='{$kec}'";
		}
		if($kel!=''){
			$kel = "AND OP_KELURAHAN='{$kel}'";
		}

		$y = ($y!='') ? $y : date('Y')-1 ;

		$query = "		
		Select A.op_kelurahan AS wil, 
		(SELECT SUM(bphtb_dibayar) As 'pajak'  from   ssb  where   saved_date LIKE '$y%' AND payment_flag = 1 AND op_kelurahan = A.op_kelurahan) penerimaan,
		(SELECT SUM(bphtb_dibayar) As 'pajak'  from   ssb  where   saved_date LIKE '$y%' AND (payment_flag = 0 or payment_flag is null)  AND op_kelurahan = A.op_kelurahan) tunggakan
		from   ssb A
		where  {$thn}
		{$kecNama} {$kec} {$kel}
		group by op_kelurahan  order by op_kelurahan asc";
		
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


	public function getSwitchingID($pTRX_ID){
		$result = array('exist' => 0, 'data' => null);
		$query = "
				SELECT 
					id_switching AS ID
				FROM ssb
				WHERE
				id_ssb = '{$pTRX_ID}'";
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

	public function getProfile($pNOP,$pSwitchingID){
		$result = array('exist' => 0, 'data' => null);
		$query = "
				SELECT 
					wp_noktp AS NIK, 
					wp_nama AS NAMA, 
					wp_alamat AS ALAMAT, 
					op_kelurahan AS KELURAHAN_OP,
					op_kecamatan AS KECAMATAN_OP,
					op_kabupaten AS KOTA_OP,
					payment_flag AS FLAG,
					bphtb_dibayar AS PEMBAYARAN,
					DATE_FORMAT(payment_paid,'%d%m%Y') AS TANGGAL_PEMBAYARAN
				FROM ssb
				WHERE
				op_nomor = '{$pNOP}' AND
				id_switching = '{$pSwitchingID}'";
		
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

	public function getDataOP($pSwitchingID){
		$result = array('exist' => 0, 'data' => null);
		$query = "
				SELECT 
					CPM_OP_LUAS_TANAH AS LUASTANAH,
					CPM_OP_LUAS_BANGUN AS LUASBANGUNAN
				FROM CPPMOD_SSB_DOC
				WHERE
				CPM_SSB_ID = '{$pSwitchingID}'";
		
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
	
	public function getValidate($pSwitchingID){
		$result = array('exist' => 0, 'data' => null);
		$query = "
				SELECT 
					MAX(CPM_TRAN_STATUS) AS STATUS
				FROM CPPMOD_SSB_TRANMAIN
				WHERE
				CPM_TRAN_SSB_ID = '{$pSwitchingID}'";
		
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
	


	// NEW GILAR

	public function getTotalTargetPenerimaan($year){
		
		$currentYear = date('Y');
		if ($year != '') {
			$thn = "YEAR(saved_date) = '{$year}'";
		}else{
			$thn = "YEAR(saved_date) = '{$currentYear}'";
		}
		
		$query = "	SELECT  SUM(bphtb_dibayar) 'target' 
					FROM   	ssb
					WHERE  	{$thn}";
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result = doubleval($dt->target);	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}

	public function getTotalTunggakan($year){
		
		$currentYear = date('Y');
		if ($year != '') {
			$thn = "YEAR(saved_date) = '{$year}'";
		}else{
			$thn = "YEAR(saved_date) = '{$currentYear}'";
		}
		
		$query = "	SELECT  SUM(bphtb_dibayar) 'target' 
					FROM   	ssb
					WHERE  	{$thn} 
						AND	(payment_flag IS NULL OR payment_flag = '0')";
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
					$result = doubleval($dt->target);	
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}

	public function getTargetPenerimaan($year){
		
		$query = "	SELECT  wp_nama, bphtb_dibayar 'harus_dibayar' 
					FROM   	ssb
					WHERE  	YEAR(saved_date) = '{$year}'";

		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){	
					while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
						$target = null;
						$target['wp_nama'] = $dt->wp_nama;	
						$target['harus_dibayar'] = doubleval($dt->harus_dibayar);	
						array_push($result, $target);
					}
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}

	public function getDaftarTunggakan($year){
		
		$currentYear = date('Y');
		if ($year != '') {
			$thn = "YEAR(saved_date) = '{$year}'";
		}else{
			$thn = "YEAR(saved_date) = '{$currentYear}'";
		}
		
		$query = "	SELECT  wp_nama, bphtb_dibayar 'harus_dibayar' 
					FROM   	ssb
					WHERE  	{$thn} 
						AND	(payment_flag IS NULL OR payment_flag = '0')";
		
		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){	
					while($dt = $stmt->fetch(PDO::FETCH_OBJ)){
						$target = null;
						$target['wp_nama'] = $dt->wp_nama;	
						$target['harus_dibayar'] = doubleval($dt->harus_dibayar);	
						array_push($result, $target);
					}
				}											
			}			
		}catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
			die();
		}
		return $result;
	}

	public function getPenerimaan($year, $month){

		$currentYear = date('Y');
		if ($year != '') {
			$thn = "YEAR(saved_date) = '{$year}'";
		}else{
			$thn = "YEAR(saved_date) = '{$currentYear}'";
		}
		
		$query = "	SELECT  wp_nama, bphtb_dibayar 'harus_dibayar', payment_paid 'waktu_pembayaran'
					FROM   	ssb
					WHERE  	{$thn} 
						AND	payment_flag = '1'";

		if ($month >= 1 && $month <= 12) {
			$query = $query . " AND MONTH(payment_paid) = {$month}";
		}

		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){	
						$penerimaan = null;
						$penerimaan['wp_nama'] = $dt->wp_nama;	
						$penerimaan['harus_dibayar'] = doubleval($dt->harus_dibayar);	
						$penerimaan['waktu_pembayaran'] = $dt->waktu_pembayaran;	
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

	public function getSeluruhPenerimaanPerKelurahan($year, $month, $kelurahan){

		$currentYear = date('Y');
		if ($year != '') {
			$thn = "YEAR(saved_date) = '{$year}'";
		}else{
			$thn = "YEAR(saved_date) = '{$currentYear}'";
		}
		
		$query = "	SELECT  op_kelurahan, SUM(bphtb_dibayar) 'harus_dibayar'
					FROM   	ssb
					WHERE  	{$thn} 
						AND	payment_flag = '1'";

		if ($month >= 1 && $month <= 12) {
			$query = $query . " AND MONTH(payment_paid) = {$month}";
		}

		if ($kelurahan != "") {
			$query = $query . " AND op_kelurahan = '{$kelurahan}'";
		}

		$query = $query . " GROUP BY op_kelurahan";

		$result = array();
		
		try {
			$stmt = $this->connGW->prepare($query);
			$stmt->execute();		
			if($stmt->rowCount() > 0){
				while($dt = $stmt->fetch(PDO::FETCH_OBJ)){	
						$penerimaan = null;
						$penerimaan['kelurahan'] = $dt->op_kelurahan;	
						$penerimaan['harus_dibayar'] = doubleval($dt->harus_dibayar);		
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
