<?php
namespace Validate;

Class AuthValidate{

    public static function issetRequestToken($params){
        $scParams = (count((array)$params) == 1) ? true : false;
        return $scParams&&isset($params->Authorized_Code);
    }


}
?>