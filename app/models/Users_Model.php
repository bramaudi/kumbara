<?php

class Users_Model extends Database
{
	public function create($data)
	{
		$this->query("
			INSERT INTO tb_users
			SET	username = ?, password = ?, fullname = ?, code = ?
		");
		$this->bind(1, $data->username);
		$this->bind(2, $data->password);
		$this->bind(3, $data->fullname);
		$this->bind(4, uniqid());
		$this->execute();

		return $this->exists($data->username) ? true : false;
	}


	public function login($username, $password)
	{
		if ($this->exists($username))
		{
			$db = $this->get('password', $username);

			if (password_verify($password, $db->password))
			{
				return true;
			}
			else {
				return false;
			}

		}
		else {
			return  false;
		}
	}


	public function get($column, $key)
	{
		if (!(int)$key)
		{
			$this->query("SELECT $column FROM tb_users WHERE username = ?");
			$this->bind(1, $key);
			$this->execute();
			return $this->return('fetch', PDO::FETCH_OBJ);
		}
		else
		{
			$this->query("SELECT $column FROM tb_users WHERE id = ?");
			$this->bind(1, $key);
			$this->execute();
			return $this->return('fetch', PDO::FETCH_OBJ);
		}
	}


	public function exists($username)
	{
		return $this->num_rows("SELECT COUNT(id) FROM tb_users WHERE username = '".$username."'");
	}


	public function verify($code)
	{
		return $this->num_rows("SELECT COUNT(id) FROM tb_users WHERE code = '".$code."'");
	}

}