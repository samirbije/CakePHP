<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends REST_Controller
{
	public function __construct()
    {
    	parent::__construct();
        $this->load->model('exchangerate/exchangerate_model');
    }

    public function exchangerate_get()
    {
    	//TODO
    }

    public function exchangerate_post()
    {
	    //TODO
    }

    public function exchangerate_delete()
    {
	    //TODO
    }


}