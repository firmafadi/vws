<?php
namespace Validate;

Class PBBValidate{

    public static function issetProfil($params){
        return isset($params->token);
    }

    public static function issetKetetapan($params){
        switch (count((array)$params)) {
            case 1:
                return PBBValidate::issetToken($params);
            break;
            case 2:
                return PBBValidate::issetToken($params)
                        &&  (PBBValidate::issetTahun($params) && $params->tahun >= 2018);
            break;
        }
        return false;
    }

    public static function issetTunggakan($params){
        switch (count((array)$params)) {
            case 1:
                return PBBValidate::issetToken($params);
            break;
            case 2:
                return PBBValidate::issetToken($params)
                        &&  PBBValidate::issetTahun($params);
            break;
        }
        return false;
    }

    public static function issetPenerimaan($params){
        switch (count((array)$params)) {
            case 1:
                return PBBValidate::issetToken($params);
            break;
            case 2:
                return PBBValidate::issetToken($params)
                        &&  PBBValidate::issetTahun($params);
            break;
            case 3:
                return PBBValidate::issetToken($params)
                        &&  PBBValidate::issetTahun($params)
                        &&  PBBValidate::issetBulan($params);
            break;
        }
        return false;
    }

    public static function issetPenerimaanPerKelurahan($params){
        switch (count((array)$params)) {
            case 1:
                return PBBValidate::issetToken($params);
            break;
            case 2:
                return PBBValidate::issetToken($params)
                        &&  (PBBValidate::issetTahun($params) || PBBValidate::issetKodeKelurahan($params));
            break;
            case 3:
                return PBBValidate::issetToken($params)
                        &&  PBBValidate::issetTahun($params)
                        &&  (PBBValidate::issetBulan($params) || PBBValidate::issetKodeKelurahan($params));
            break;
            case 4:
                return PBBValidate::issetToken($params)
                        &&  PBBValidate::issetTahun($params)
                        &&  PBBValidate::issetBulan($params) 
                        &&  PBBValidate::issetKodeKelurahan($params);
            break;
        }
        return false;
    }

    // public static function issetToken($params){
    //     return isset($params->token);
    // }

    // public static function issetTokenYear($params){
    //     return isset($params->token)&&isset($params->tahun);
    // }

    public static function issetTokenYearMonth($params){
        return isset($params->token)&&isset($params->tahun)&&isset($params->bulan);
    }

    public static function issetTokenYearMonthJenisPajak($params){
        return isset($params->token)&&isset($params->tahun)&&isset($params->bulan)&&isset($params->jenis_pajak);
    }

    public static function issetTokenYearKelurahan($params){
        return isset($params->token)&&isset($params->tahun)&&isset($params->kelurahan);
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

    private  function issetKodeKelurahan($params) {
        return isset($params->kode_kelurahan)&&is_string($params->kode_kelurahan);
    }
}

?>