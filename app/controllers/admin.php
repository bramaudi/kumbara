<?php

class Admin extends Controllers
{
	public function main($action = '')
	{
		if ($this->logged())
		{
			if (method_exists($this, $action))
			{
				$this->$action();
			}
			else {
				$this->post();
			}
		}

		else {
			$this->login();
		}

		$this->view('admin/footer', ['logged' => $this->logged()]);
	}

	public function login()
	{
		$this->view('admin/header', ['title' => 'Login']);
		$this->view('admin/login');
	}


	public function logout(Type $var = null)
	{
		setcookie('user_login', '', time()-(3600*720),'/');
		header('location: /admin');
	}


	public function post()
	{
		$this->view('admin/header', ['title' => 'Post']);
		$this->view('admin/post');
		// $this->model('Posts_Model')->read();
	}


	public function logged()
	{
		$user = $this->model('Users_Model');
		if (isset($_COOKIE['user_login'])) {
			if ($user->verify($_COOKIE['user_login'])) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

}