<?php

class User extends Controllers
{
	protected $db;


	public function __construct(Type $var = null)
	{
		$this->db = $this->model('Users_Model');
	}


	public function main(Type $var = null)
	{
		# code...
	}


	public function register()
	{
		$data = (object)$_POST;
		$error = 0;

		if (empty($data->username)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Username is empty.';
		} elseif ($this->db->exists($data->username)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Username already in use.';
		} elseif (strlen($data->username) < 3) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Username minimum is 3.';
		} elseif (strlen($data->username) > 20) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Username maximum is 20.';
		} elseif (empty($data->password) OR empty($data->verpass)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Password is empty.';
		} elseif ($data->verpass !== $data->password) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Password not match.';
		} elseif (!$error) {

			$data->fullname = empty($data->fullname) ? ucwords($data->username) : $data->fullname;

			$data->password = password_hash($data->password, PASSWORD_DEFAULT);
			
			if ($this->db->create($data))
			{
				$json['error'] = 0;
				$json['message'] = 'User resgistered.';
			}
			else {
				$json['error'] = 1;
				$json['message'] = 'Failed to register.';
			}

		}

		echo json_encode($json);
	}


	public function login()
	{
		// $data = (object)$_POST;
		$data = json_decode(file_get_contents('php://input'));
		$error = 0;

		if (empty($data->username)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Username is empty.';
		} elseif (empty($data->password)) {
			$error = 1;
			$json['error'] = 1;
			$json['message'] = 'Password is empty.';
		} elseif (!$error) {

			if ($this->db->login($data->username, $data->password)){
				
				setcookie('user_login', $this->db->get('code', $data->username)->code, time()+(3600*720), '/');

				$json['error'] = 0;
				$json['message'] = 'You`re logged in.';
			}
			else {
				$json['error'] = 1;
				$json['message'] = 'Wrong username or password.';
			}

		}

		echo json_encode($json);

	}


}