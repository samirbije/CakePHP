<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * An open source development control panel written in PHP
 *
 * @package		BackendPro
 * @author		Adam Price
 * @copyright	Copyright (c) 2008, Adam Price
 * @license		http://www.gnu.org/licenses/lgpl.html
 * @link		http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ACL Actions Controller
 *
 * Provide the ability to manage ACL actions
 *
 * @package         BackendPro
 * @subpackage      Controllers
 */
class Actions extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		// Load needed files
		$this->lang->load('access_control');
		$this->load->model('access_control_model');

		// Check for access permission
		check('Actions');

		log_message('debug','BackendPro : Acl_actions class loaded');
	}

	function index()
	{
		$this->load->helper('form');

		// Display Page
		$data['header'] = $this->lang->line('access_actions');
		$data['page'] = $this->config->item('template_admin') . "access_control/actions";
		$data['module'] = 'auth';
		$this->load->view($this->_container,$data);
	}

	/**
	 * Create action
	 *
	 * @access public
	 */
	function form($id = NULL)
	{
		// Setup validation
		$this->load->library('validation');
		$fields['id'] = "ID";
		$fields['name'] = $this->lang->line('access_name');
		$fields['resource'] = $this->lang->line('access_resource');

		$rules['name'] = 'trim|required|min_length[3]|max_length[254]';

		$this->validation->set_fields($fields);

		if( ! is_null($id) AND ! $this->input->post('submit'))
		{
			// Load values into form
			$node = $this->access_control_model->resource->getNodeFromId($id);

			// Check it isn't the root
			if( $this->access_control_model->resource->checkNodeIsRoot($node)){
				flashMsg('warning',sprintf($this->lang->line('access_resource_root'),$node['name']));
				redirect('admin/auth/actions');
			}

			$parent = $this->access_control_model->resource->getAncestor($node);
			$this->validation->set_default_value('id',$id);
			//$this->validation->set_default_value('name',$node['name']);
			//$this->validation->set_default_value('parent',$parent['name']);
		}
		elseif( $this->input->post('submit'))
		{
			// Form submited, check rules
			$this->validation->set_rules($rules);
		}


		if($this->validation->run() === FALSE)
		{
			// FAIL
			$this->validation->output_errors();

			// Get Resources
			$data['resources'] = $this->access_control_model->buildACLDropdown('resource');

			// Display Page
			$data['header'] = (is_null($id)?$this->lang->line('access_create_action'):$this->lang->line('access_edit_action'));
			$data['page'] = $this->config->item('template_admin') . "access_control/form_action";
			$data['module'] = 'auth';
			$this->load->view($this->_container,$data);
		}
		else
		{
			// PASS
			$name = $this->input->post('name');
			$resource = $this->input->post('resource') ? $this->input->post('resource') : null;

			$this->load->library('auth/khacl');

			if($this->khacl->axo->create($name, $resource))
			{
				flashMsg('success',sprintf($this->lang->line('access_action_created'),$name));
				redirect('admin/auth/actions');
			}
			else
			{
				flashMsg('warning',sprintf($this->lang->line('access_action_exists'),$name));
				redirect('admin/auth/actions/form');
			}
			
		}
		//
	}

	/**
	 * Delete Actions
	 *
	 * @access public
	 */
	function delete()
	{
		if(FALSE === ($actions = $this->input->post('select')))
		{
			redirect('admin/auth/actions','location');
		}

		$this->load->library('auth/khacl');
		foreach($actions as $action)
		{
			list($id, $name) = explode("|", $action);
			if( $this->khacl->axo->delete($id))
			{
				flashMsg('success',sprintf($this->lang->line('access_action_deleted'),$name));
			}
			else
			{
				flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('access_delete_action')));
			}
		}
		redirect('admin/auth/actions','location');
	}
}

/* End of file acl_actions.php */
/* Location: ./core_modules/auth/controllers/admin/acl_actions.php */