<?php
namespace Validate;

Class SurveillanceValidate{

    public static function issetSaveTransaction($params){
        $scParams = (count((array)$params) == 15) ? true : false;

        return $scParams&&(isset($params->Token)&&
                isset($params->NPWPD)&&
                isset($params->NOP)&&
                isset($params->TransactionType)&&
                isset($params->TaxType)&&
                isset($params->BranchCode)&&
                isset($params->BillNumber)&&
                isset($params->Quantity)&&
                isset($params->Invoice)&&
                isset($params->TransactionSource)&&
                isset($params->TransactionDescription)&&
                isset($params->TransactionAmount)&&
                isset($params->TransactionDate)&&
                isset($params->Tax)&&
                isset($params->DeviceId));
    }

    public static function isTransactionType($params){
        if($params->TransactionType != 0 && $params->TransactionType != 1){
            return false;
        }
        return true;
    }

    public static function isTaxType($params){
        if($params->TaxType != 4 && 
           $params->TaxType != 5 &&
           $params->TaxType != 6 &&
           $params->TaxType != 7 &&
           $params->TaxType != 8 &&
           $params->TaxType != 9 &&
           $params->TaxType != 11 &&
           $params->TaxType != 12 &&
           $params->TaxType != 24 &&
           $params->TaxType != 25 &&
           $params->TaxType != 26 &&
           $params->TaxType != 27 &&
           $params->TaxType != 28 &&
           $params->TaxType != 29 &&
           $params->TaxType != 30 &&
           $params->TaxType != 31 &&
           $params->TaxType != 32){

            return false;

        }
        return true;
    }

    public static function isTransactionSource($params){
        if($params->TransactionSource != 1 && 
           $params->TransactionSource != 2 &&
           $params->TransactionSource != 3 &&
           $params->TransactionSource != 4 &&
           $params->TransactionSource != 5 &&
           $params->TransactionSource != 6 &&
           $params->TransactionSource != 7){

            return false;
            
        }
        return true;
    }

    public static function issetSaveAlarm($params){
        $scParams = (count((array)$params) == 5) ? true : false;
        
        return $scParams&&(isset($params->Token)&&
                isset($params->DeviceId)&&
                isset($params->ServerTimeStamp)&&
                isset($params->DeviceTimeStamp)&&
                isset($params->AlarmCode));
    }

}
?>