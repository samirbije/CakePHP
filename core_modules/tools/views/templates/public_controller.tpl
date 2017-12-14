{PHP_TAG} if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class {CONTROLLER} extends Public_Controller
{
	public function __construct(){
    	parent::__construct();
        $this->load->model('{MODULE}/{MODEL}');
        $this->lang->load('{MODULE}/{LANG}');
    }

	public function index()
	{
		// Display Page
		$data['header'] = lang('{MODULE}');
		$data['view_page'] = $this->config->item('template_public') . "index";
		$data['module'] = '{MODULE}';
		$this->load->view($this->_container,$data);
	}
}