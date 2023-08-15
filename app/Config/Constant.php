<?php
namespace Config;

class Constant{
    //list of access code for user to get token
    public static $AUTHORIZED_CODE_USER = array('mitravtax');

    // expire time (in second) for active token
    public static $EXP_TOKEN = 3600;

    // nbf token information (in second)
    public static $NBF_TOKEN = 5;

}
?>