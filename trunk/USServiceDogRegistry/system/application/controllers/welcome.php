<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->view('common/header');
		$this->load->view('homepage/homepage');
		$this->load->view('common/footer');
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
