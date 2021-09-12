<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends ResourceController
{

	protected $format = 'json';

	public function createJWT($email, $password)
	{
		/**
		 * JWT claim types
		 * https://auth0.com/docs/tokens/concepts/jwt-claims#reserved-claims
		 */

		// add code to fetch through db and check they are valid
		// sending no email and password also works here because both are empty
		if ($email != null && $password != null) {
			$key = Services::getSecretKey();
			$payload = [
                'iat' => getdate(time()),
				'data' => ['Email' => $email, 'Password' => $password],
			];

			/**
			 * IMPORTANT:
			 * You must specify supported algorithms for your application. See
			 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
			 * for a list of spec-compliant algorithms.
			 */
			$jwt = JWT::encode($payload, $key);
			return $jwt;
			//return $jwt;
		}

		return $this->respond(['message' => 'Invalid login details'], 401);
	}

    public function validateToken($token){
        try {
            $key = Services::getSecretKey();
            return JWT::decode($token,$key,array('HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function verifyToken(){
        $key = Services::getSecretKey();
        $token = $this->request->getPost("token");

        if($this->validateToken($token) == false){
            return $this->respond(['message'=>'Token Invalido'],401);
        }else{
            $data = JWT::decode($token,$key,array('HS256'));
            return $this->respond(['data'=>$data],200);
        }
    }
}