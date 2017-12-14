{PHP_TAG} if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends REST_Controller
{
	public function __construct()
    {
    	parent::__construct();
        $this->load->model('{MODULE}/{MODEL}');
    }

    public function {MODULE}_get()
    {
    	//TODO
    }

    public function {MODULE}_post()
    {
	    //TODO
    }

    public function {MODULE}_delete()
    {
	    //TODO
    }


}