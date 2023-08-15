<?php
	/*
		\d+ = One or more digits (0-9)
		\w+ = One or more word characters (a-z 0-9 _)
		[a-z0-9_-]+ = One or more word characters (a-z 0-9 _) and the dash (-)
		.* = Any character (including /), zero or more
		[^/]+ = Any character but /, one or more
	*/
	
	$router->get('/', '\Controller\AppController@index');

	/** AUTH */
	$router->post('/token', '\Controller\AuthController@requestToken');

	/** SURVEILLANCE */
	$router->get('/connectionMobilePOS', '\Controller\SurveillanceController@checkConnectionMobilePOS');
	$router->post('/transMobilePOS', '\Controller\SurveillanceController@receiveTransMobilePOS');
	$router->post('/transMobilePOSJson', '\Controller\SurveillanceController@receiveTransMobilePOSJson');
	$router->post('/data/transaction', '\Controller\SurveillanceController@saveTransaction');
	$router->post('/data/alarm', '\Controller\SurveillanceController@saveAlarm');
	
	/** PBB */
	$router->get('/dashboardPBB', '\Controller\PBBController@dataDashboardPBB');
	$router->get('/dashboardPBB/detail', '\Controller\PBBController@dataDetailDashboardPBB');
	$router->get('/dashboardPBB/penerimaan', '\Controller\PBBController@dataPenerimaan');
	$router->get('/dashboardPBB/tunggakan', '\Controller\PBBController@dataTunggakan');
	$router->post('/dashboardPBB', '\Controller\PBBController@dataDashboardPBB');
	$router->post('/dashboardPBB/detail', '\Controller\PBBController@dataDetailDashboardPBB');
	$router->post('/dashboardPBB/tunggakan', '\Controller\PBBController@dataTunggakan');	
	$router->post('/dashboardPBB/penerimaan', '\Controller\PBBController@dataPenerimaan');
	$router->post('/dashboardPBB/filter', '\Controller\PBBController@filter');
	$router->post('/inquiryPBB', '\Controller\PBBController@inquery');
	$router->post('/pbb/ketetapan', '\Controller\PBBController@daftarKetetapan');
	$router->post('/pbb/ketetapan/total', '\Controller\PBBController@totalKetetapan');
	$router->post('/pbb/penerimaan', '\Controller\PBBController@daftarPenerimaan');
	$router->post('/pbb/penerimaan/total', '\Controller\PBBController@totalPenerimaan');
	$router->post('/pbb/penerimaan/kelurahan', '\Controller\PBBController@daftarPenerimaanKelurahan');
	$router->post('/pbb/tunggakan', '\Controller\PBBController@daftarTunggakan');
	$router->post('/pbb/tunggakan/total', '\Controller\PBBController@totalTunggakan');

	/** BPHTB */
	$router->get('/dashboardBPHTB', '\Controller\BPHTBController@dataDashboardBPHTB');
	$router->get('/dashboardBPHTB/detail', '\Controller\BPHTBController@dataDetailDashboardBPHTB');
	$router->get('/dashboardBPHTB/penerimaan', '\Controller\BPHTBController@dataPenerimaan');
	$router->get('/dashboardBPHTB/tunggakan', '\Controller\BPHTBController@dataTunggakan');
	$router->post('/dashboardBPHTB', '\Controller\BPHTBController@dataDashboardBPHTB');
	$router->post('/dashboardBPHTB/detail', '\Controller\BPHTBController@dataDetailDashboardBPHTB');
	$router->post('/dashboardBPHTB/tunggakan', '\Controller\BPHTBController@dataTunggakan');	
	$router->post('/dashboardBPHTB/penerimaan', '\Controller\BPHTBController@dataPenerimaan');	
	$router->post('/dashboardBPHTB/filter', '\Controller\BPHTBController@filter');
	$router->post('/inquiryBPHTB', '\Controller\BPHTBController@inquery');
	$router->post('/bphtb/penerimaan/target/total', '\Controller\BPHTBController@totalTargetPenerimaan');
	$router->post('/bphtb/penerimaan/target', '\Controller\BPHTBController@daftarTargetPenerimaan');
	$router->post('/bphtb/tunggakan', '\Controller\BPHTBController@daftarTunggakan');
	$router->post('/bphtb/tunggakan/total', '\Controller\BPHTBController@totalTunggakan');
	$router->post('/bphtb/penerimaan', '\Controller\BPHTBController@daftarPenerimaan');
	$router->post('/bphtb/penerimaan/kelurahan', '\Controller\BPHTBController@daftarPenerimaanKelurahan');

	/** 9PAJAK */
	$router->get('/dashboardPATDA', '\Controller\PATDAController@dataDashboardPATDA');
	$router->get('/dashboardPATDA/detail', '\Controller\PATDAController@dataDetailDashboardPATDA');
	$router->get('/dashboardPATDA/penerimaan', '\Controller\PATDAController@dataPenerimaan');
	$router->get('/dashboardPATDA/tunggakan', '\Controller\PATDAController@dataTunggakan');
	$router->post('/dashboardPATDA/detail', '\Controller\PATDAController@dataDetailDashboardPATDA');
	$router->post('/dashboardPATDA/penerimaan', '\Controller\PATDAController@dataPenerimaan');
	$router->post('/dashboardPATDA/tunggakan', '\Controller\PATDAController@dataTunggakan');
	$router->post('/inquiryPATDA', '\Controller\PATDAController@inquiry');
	//update from: /inquiryProfilePATDA
	$router->post('/patda/inquiry/profil-tagihan', '\Controller\PATDAController@getProfilTagihan');
	//pudate from: /inquiryProfileWP
	$router->post('/patda/inquiry/profil-wp', '\Controller\PATDAController@getProfilWP');
	$router->post('/patda/penerimaan/target/total', '\Controller\PATDAController@dataJumlahTargetPenerimaan');
	$router->post('/patda/tunggakan/total', '\Controller\PATDAController@totalTunggakan');
	$router->post('/patda/penerimaan', '\Controller\PATDAController@daftarPenerimaan');
	$router->post('/patda/penerimaan/jenispajak', '\Controller\PATDAController@daftarPenerimaanJenisPajak');


	///////////////////
	$router->get('/kecamatan', '\Controller\PBBController@dataKecamatan');
	$router->post('/kelurahan', '\Controller\PBBController@dataKelurahan');
	
	$router->run();
?>
