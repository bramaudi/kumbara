<?php

class Home extends Controllers {

	public function main($slug = '')
	{
		// $this->view('admin/header');
		$this->view('home/index');
		// $this->view('admin/footer');
	}
	
}