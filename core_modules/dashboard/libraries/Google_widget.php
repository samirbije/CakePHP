<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_widget
{
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function create()
	{
		return "hello world";
	}
}
