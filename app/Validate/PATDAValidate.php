<?php
namespace Validate;

Class PATDAValidate{

    public static function issetProfil($params){
        return isset($params->npwpd)&&isset($params->token);
    }

    public static function issetTargetPenerimaan($params){
        switch (count((array)$params)) {
            case 1:
                return PATDAValidate::issetToken($params);
            break;
            case 2:
                return PATDAValidate::issetToken($params)
                        &&  (PATDAValidate::issetTahun($params) && $params->tahun >= 2018);
            break;
        }
        return false;
    }

    public static function issetTunggakan($params){
        switch (count((array)$params)) {
            case 1:
                return PATDAValidate::issetToken($params);
            break;
            case 2:
                return PATDAValidate::issetToken($params)
                        &&  PATDAValidate::issetTahun($params);
            break;
        }
        return false;
    }

    public static function issetPenerimaan($params){
        switch (count((array)$params)) {
            case 1:
                return PATDAValidate::issetToken($params);
            break;
            case 2:
                return PATDAValidate::issetToken($params)
                        &&  PATDAValidate::issetTahun($params);
            break;
            case 3:
                return PATDAValidate::issetToken($params)
                        &&  PATDAValidate::issetTahun($params)
                        &&  PATDAValidate::issetBulan($params);
            break;
        }
        return false;
    }

    public static function issetPenerimaanJenisPajak($params){
        switch (count((array)$params)) {
            case 1:
                return PATDAValidate::issetToken($params);
            break;
            case 2:
                return PATDAValidate::issetToken($params)
                        &&  (PATDAValidate::issetTahun($params) || PATDAValidate::issetKodeJenisPajak($params));
            break;
            case 3:
                return PATDAValidate::issetToken($params)
                        &&  PATDAValidate::issetTahun($params)
                        &&  (PATDAValidate::issetBulan($params) || PATDAValidate::issetKodeJenisPajak($params));
            break;
            case 4:
                return PATDAValidate::issetToken($params)
                        &&  PATDAValidate::issetTahun($params)
                        &&  PATDAValidate::issetBulan($params) 
                        &&  PATDAValidate::issetKodeJenisPajak($params);
            break;
        }
        return false;
    }

    private  function issetToken($params) {
        return isset($params->token)&&is_string($params->token);
    }

    private  function issetTahun($params) {
        return isset($params->tahun)&&is_int($params->tahun)&&strlen((string)$params->tahun)==4;
    }

    private  function issetBulan($params) {
        return isset($params->bulan)&&is_int($params->bulan)&&$params->bulan>=1&&$params->bulan<=12;
    }
    
    private  function issetKodeJenisPajak($params) {
        return isset($params->kode_jenis_pajak)&&is_string($params->kode_jenis_pajak);
    }

}

?>