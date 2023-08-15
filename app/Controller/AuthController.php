<?php
namespace Controller;

use \Core\Helper;
use \Core\Request;
use \Config\Constant;
use \Controller\LogController;
use \Firebase\JWT;
use \Firebase\ExpiredException;
use \Firebase\BeforeValidException;
use \Firebase\SignatureInvalidException;
use \Validate\AuthValidate;

class AuthController{
    private static $key = "jwT0k3nvtaxws";
    private $log;

    public function __construct(){
		$this->log = new LogController;
    }

    /**
     * 
     * @api {post} /token Request token.
     * @apiName GetToken
     * @apiGroup Auth
     * @apiVersion  0.1.2
     * @apiPermission public
     * 
     * @apiDescription Request token yang akan digunakan untuk autentikasi pengiriman data ke VTax Web Service.
     * 
     * @apiExample Contoh Penggunaan :
     * http://119.252.160.220/vtax-web-service/token 
     * 
     * @apiParam {String} Authorized_Code Kode verifikasi agar bisa mendapatkan token.
     * 
     * @apiParamExample  {json} Request Body:
     * {
     *     Authorized_Code : XXXXX
     * }
     * 
     * @apiSuccess {boolean} status Status response, jika berhasil bernilai true dan jika gagal bernilai false.
     * @apiSuccess {String} message Deskripsi response.
     * @apiSuccess {object} data Informasi token.
     * @apiSuccess {String} data.token Token.
     * @apiSuccess {String} data.expired Durasi berlakunya token (dalam format time).
     * 
     * @apiSuccessExample {json} Success-Respon: 
     * {
     *       "status": true,
     *       "message": "Token created!",
     *       "data": {
     *           "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1Nzg1NDMyOTMsIm5iZiI6MTU3ODU0MzMwMywiZXhwIjoxNTc4NTQ2ODkzLCJkYXRhIjpbXX0.KizrzfUpsjFbW5nTVuXNCeCDbdTaB_N5ul0fungq4oI",
     *           "expired": "01:00:00"
     *       }
     *  }
     * 
     * @apiError {String} Parameter-Invalid Parameter tidak sesuai.
     * @apiError {String} Authorized_Code-Invalid Authorized_Code tidak terdaftar.
     * 
     * @apiErrorExample {json} Error-Response (contoh):
     *  {
     *       "status": false,
     *       "message": "Parameter invalid!",
     *       "data": null
     *  }
     */

	public function requestToken(){
        header('Access-Control-Allow-Origin: *'); 

        $tReq = Request::json();

		// save log request
        $this->log->request(json_encode($tReq));
        
        if(AuthValidate::issetRequestToken($tReq)){
            if($this->authUser($tReq->Authorized_Code)){
                $result = json_encode(array('status' => true, 
                                        'message' => 'Token created!.',
                                        'data' => $this->generateToken()));
                // save log response
                $this->log->response($result);
            
                echo $result;

            }else{
                $result = json_encode(array('status' => false, 
                                        'message' => 'Authorized_Code invalid!',
                                        'data' => null));
                // save log response
                $this->log->response($result);
                
                echo $result;
            }    
        }else{
            $result = json_encode(array('status' => false, 
                                        'message' => 'Parameter invalid!',
                                        'data' => null));

            // save log response
            $this->log->response($result);

            echo $result;
        }
	}

	public function generateToken(){
		$payload = array(
			"iss" => "vtax-mba",
			"aud" => "mitra-vtax",
			"iat" => time(), //waktu generate token -- filter optional
            "nbf" => time() + Constant::$NBF_TOKEN, //waktu sebelum token bisa digunakan -- filter optional
            "nbft" => Constant::$NBF_TOKEN,
            "exp" => time() + Constant::$EXP_TOKEN, //rentan waktu token valid dari waktu nbf -- filter optional
            "data" => array() //berisi data -- optional
		);
		
        $jwt = JWT::encode($payload, self::$key);        
        $token = array('token' => $jwt, 'expired' => Helper::convertTime(Constant::$EXP_TOKEN));

        return $token;
    }
    
    public function validateToken($jwt){
        try{
            $decoded = JWT::decode($jwt, self::$key, array('HS256'));
            $decoded_array = (array) $decoded;
            
            return json_encode(array('status' => true, 'message' => 'success'));
    
        }catch(ExpiredException $e){
            return json_encode(array('status' => false, 'message' => 'Token invalid : Expired -  '.$e->getMessage()));
        }catch(BeforeValidException $e){
            return json_encode(array('status' => false, 'message' => 'Token invalid : BeforeValid - '.$e->getMessage()));
        }catch(SignatureInvalidException $e){
            return json_encode(array('status' => false, 'message' => 'Token invalid : SignatureInvalid - '.$e->getMessage()));
        }catch(\Exception $e){
            return json_encode(array('status' => false, 'message' => 'Token invalid : Other - '.$e->getMessage()));
        }
    }
    
    public function authUser($authCode){
        $exist = false;
        foreach(Constant::$AUTHORIZED_CODE_USER as $code){
            if($authCode == $code){
                $exist = true;
                break;
            }
        }

        return $exist;
    }
}
?>