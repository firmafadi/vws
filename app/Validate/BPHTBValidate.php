<?php
namespace Validate;

Class BPHTBValidate{

    public static function issetPenerimaan($params){
        switch (count((array)$params)) {
            case 1:
                return BPHTBValidate::issetToken($params);
            break;
            case 2:
                return BPHTBValidate::issetToken($params)
                        &&  BPHTBValidate::issetTahun($params);
            break;
            case 3:
                return BPHTBValidate::issetToken($params)
                        &&  BPHTBValidate::issetTahun($params)
                        &&  BPHTBValidate::issetBulan($params);
            break;
        }
        return false;
    }

    public static function issetTargetPenerimaan($params){
        switch (count((array)$params)) {
            case 1:
                return BPHTBValidate::issetToken($params);
            break;
            case 2:
                return BPHTBValidate::issetToken($params)
                        &&  (BPHTBValidate::issetTahun($params) && $params->tahun >= 2018);
            break;
        }
        return false;
    }

    public static function issetTunggakan($params){
        switch (count((array)$params)) {
            case 1:
                return BPHTBValidate::issetToken($params);
            break;
            case 2:
                return BPHTBValidate::issetToken($params)
                        &&  BPHTBValidate::issetTahun($params);
            break;
        }
        return false;
    }

    public static function issetPenerimaanPerKelurahan($params){
        switch (count((array)$params)) {
            case 1:
                return BPHTBValidate::issetToken($params);
            break;
            case 2:
                return BPHTBValidate::issetToken($params)
                        &&  (BPHTBValidate::issetTahun($params) || BPHTBValidate::issetKelurahan($params));
            break;
            case 3:
                return BPHTBValidate::issetToken($params)
                        &&  BPHTBValidate::issetTahun($params)
                        &&  (BPHTBValidate::issetBulan($params) || BPHTBValidate::issetKelurahan($params));
            break;
            case 4:
                return BPHTBValidate::issetToken($params)
                        &&  BPHTBValidate::issetTahun($params)
                        &&  BPHTBValidate::issetBulan($params) 
                        &&  BPHTBValidate::issetKelurahan($params);
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

    private  function issetKelurahan($params) {
        return isset($params->kelurahan)&&is_string($params->kelurahan);
    }
}

?>