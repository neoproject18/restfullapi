<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('HTTP/1.1 200 OK');
	exit();
}

use chriskacerguis\RestServer\RestController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends RestController
{
	private $_key;
	public function __construct()
	{
		parent::__construct();
		$this->_key = config_item("jwt_secret_key");
		$this->load->model('m_user', 'user');
	}

	public function index_post()
	{
		$date = new DateTime();
		$username = $this->post('username', TRUE);
		$password = hash('sha512', $this->post('password', TRUE) . $this->_key);
		$datauser = $this->user->login($username, $password)[0];

		$this->form_validation->set_rules(
			'username',
			'Username',
			'required',
			array('required' => 'Silahkan masukkan username!')
		);

		$this->form_validation->set_rules(
			'password',
			'Password',
			'required',
			array('required' => 'Silahkan masukkan password!')
		);

		if ($this->form_validation->run() == false) {
			$this->response([
				'status' => false,
				'message' => strip_tags(validation_errors())
			], self::HTTP_BAD_REQUEST);
		} else {
			if ($datauser) {
				$payload['username'] = $datauser->username;
				$payload['nama_user'] = $datauser->nama_user;
				$payload['password'] = $datauser->password;
				$payload['id_role'] = $datauser->id_role;
				$payload['nama_role'] = $datauser->nama_role;
				$payload['iat'] = $date->getTimestamp(); //waktu di buat
				$payload['exp'] = $date->getTimestamp() + (86400 * 360 * 10); // 10 tahun

				$this->response(
					[
						'status' => true,
						'message' => 'Login berhasil',
						'result' => array(
							"username" => $datauser->username,
							"nama" => $datauser->nama_user,
							"id_role" => $datauser->id_role,
							"nama_role" => $datauser->nama_role,
						),
						"token" => JWT::encode($payload, $this->_key, 'HS256')
					],
					self::HTTP_OK
				);
			} else {
				$this->response(
					[
						'status' => false,
						'message' => ' Username atau Password Salah'
					],
					self::HTTP_NOT_FOUND
				);
			}
		}
	}

	public function index_get()
	{
		$jwt = $this->input->get_request_header('Authorization');
		// $jwt = $this->post('Authorization', TRUE);

		try {
			$decode = JWT::decode($jwt, $this->_key, array('HS256'));
			$this->response([
				'status' => true,
				'message' => 'Cek Token',
				'result' => $decode
			], self::HTTP_OK);
		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Invalid Token'
			], self::HTTP_UNAUTHORIZED);
		}
	}

	protected function cektoken()
	{
		$jwt = $this->input->get_request_header('Authorization');
		// $jwt = $this->post('Authorization', TRUE);

		try {
			$decode = JWT::decode($jwt, $this->_key, array('HS256'));
		} catch (Exception $e) {
			$this->response([
				'status' => false,
				'message' => 'Invalid Token'
			], self::HTTP_UNAUTHORIZED);
		}
	}



	// public function index2_post()
	// {
	// 	$payload = array(
	// 		"iss" => "http://example.org",
	// 		"aud" => "http://example.com",
	// 		"iat" => 1356999524,
	// 		"nbf" => 1357000000
	// 	);

	// 	$jwt = JWT::encode($payload, $this->_key, 'HS256');
	// 	$decoded = JWT::decode($jwt, new Key($this->_key, 'HS256'));

	// 	$decoded_array = (array) $decoded;

	// 	JWT::$leeway = 60; // $leeway in seconds
	// 	$decoded = JWT::decode($jwt, new Key($this->_key, 'HS256'));

	// 	$this->response([
	// 		'status' => true,
	// 		'message' => 'Login berhasil',
	// 		'result' => $jwt
	// 	], self::HTTP_OK);
	// }
}
