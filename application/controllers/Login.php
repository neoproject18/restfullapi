<?php

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends RestController
{
	private $_key = "example_key";
	public function index_post()
	{
		$payload = array(
			"iss" => "http://example.org",
			"aud" => "http://example.com",
			"iat" => 1356999524,
			"nbf" => 1357000000
		);

		$jwt = JWT::encode($payload, $this->_key, 'HS256');
		$decoded = JWT::decode($jwt, new Key($this->_key, 'HS256'));

		// print_r($decoded);

		$decoded_array = (array) $decoded;

		JWT::$leeway = 60; // $leeway in seconds
		$decoded = JWT::decode($jwt, new Key($this->_key, 'HS256'));

		$this->response([
			'status' => true,
			'message' => 'Login berhasil',
			'result' => $jwt
		], self::HTTP_OK);
	}

	public function cektoken_post()
	{
		$jwt = $this->input->get_request_header('Authorization');

		try {
			$decode = JWT::decode($jwt, $this->_key, array('HS256'));
			$this->response([
				'status' => true,
				'message' => 'Cek Token',
				'result' => $decode
			], self::HTTP_OK);
			// if ($this->m_login->is_valid_num($decode->username) > 0) {
			// 	$this->session->set_userdata('login_appsi_servis', $decode);
			// 	return true;
			// }
		} catch (Exception $e) {
			exit(json_encode(array('status_code' => 401, 'message' => 'Invalid Token',)));
		}
	}
}
