<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * ACL Groups Controller
 *
 * Provide the ability to manage ACL groups
 *
 * @package         BackendPro
 * @subpackage      Controllers
 */
class Groups extends Admin_Controller
{
	function __construct()
	{
		parent::__construct();

		// Load needed files
		$this->lang->load('access_control');
		$this->load->model('access_control_model');
		$this->load->helper('form');

		// Check for access permission
		check('Groups');

		log_message('debug','BackendPro : Acl_groups class loaded');
	}

	function index()
	{
		// Display Page
		$data['header'] = $this->lang->line('access_groups');
		$data['page'] = $this->config->item('template_admin') . "access_control/groups";
		$data['module'] = 'auth';
		$this->load->view($this->_container,$data);
	}
	
	function json()
	{
		//$obj = & $this->access_control_model->group;
		//$groups=$this->access_control_model->getTreeArray($obj);
		//echo '<pre>';
		//print_r($groups);
		//echo json_encode(array($groups['Member']));
		return NULL;
	}

	/**
	 * Display Form
	 *
	 * Display a form to either create/modify a group depending on
	 * wheather a $id parameter has been passed in
	 *
	 * @param integer $id Group ID
	 */
	function form($id = NULL)
	{
		// Setup form validation
		$this->load->library('validation');
		$fields['id'] = "ID";
		$fields['name'] = $this->lang->line('access_name');
		$fields['disabled'] = $this->lang->line('access_disabled');
		$fields['parent'] = $this->lang->line('access_parent_name');
		$this->validation->set_fields($fields);

		$rules['name'] = "trim|required|max_length[254]";
		$rules['parent'] = "required";

		if( ! is_null($id) AND ! $this->input->post('submit'))
		{
			// Modify form, first load
			$node = $this->access_control_model->group->getNodeFromId($id);

			// Check it isn't the root group
			if( $this->access_control_model->group->checkNodeIsRoot($node)){
				flashMsg('warning',sprintf($this->lang->line('access_group_root'),$node['name']));
				redirect('admin/auth/groups');
			}

			// Fetch the disabled value
			$query = $this->access_control_model->fetch('groups','disabled',NULL,array('id'=>$id));
			$row = $query->row();

			// Load default values into form
			$parent = $this->access_control_model->group->getAncestor($node);
			$this->validation->set_default_value('id',$id);
			$this->validation->set_default_value('disabled',$row->disabled);
			$this->validation->set_default_value('name',$node['name']);
			$this->validation->set_default_value('parent',$parent['name']);
		}
		elseif( is_null($id) AND ! $this->input->post('submit'))
		{
			// Create form, first load
			$this->validation->set_default_value('disabled','0');
		}
		elseif( $this->input->post('submit'))
		{
			// Form submited, check rules
			$this->validation->set_rules($rules);
		}

		if($this->validation->run() === FALSE)
		{
			// FAIL
			// Display Errors
			$this->validation->output_errors();

			// Get Resources
			$data['groups'] = $this->access_control_model->buildACLDropdown('group');

			// Display Page
			$data['header'] = (is_null($id)?$this->lang->line('access_create_group'):$this->lang->line('access_edit_group'));
			$data['page'] = $this->config->item('template_admin') . "access_control/form_group";
			$data['module'] = 'auth';
			$this->load->view($this->_container,$data);
		}
		else
		{
			// PASS
			$name = $this->input->post('name');
			$disabled = $this->input->post('disabled');
			$parent = $this->input->post('parent');

			if( is_null($id))
			{
				// Create Group
				$this->load->library('auth/khacl');

				$this->db->trans_begin();
				if( ! $this->khacl->aro->create($name,$parent))
				{
					flashMsg('warning',sprintf($this->lang->line('access_group_exists'),$name));
					redirect('admin/auth/groups/form');
				}

				$this->access_control_model->insert('groups',array('id'=>$this->db->insert_id(),'disabled'=>$disabled));

				if( $this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf($this->lang->line('access_group_created'),$name));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('access_create_group')));
				}
			}
			else
			{
				// Update Group
				$id = $this->input->post('id');
				$node = $this->access_control_model->group->getNodeFromId($id);
				$new_parent = $this->access_control_model->group->getNodeWhere("name='".$parent."'");

				// Check the assigment isn't illeagal
				if($this->access_control_model->group->checkNodeIsChildOrEqual($new_parent,$node)){
					flashMsg('warning',sprintf($this->lang->line('access_group_illegal_assignment'),$name));
					redirect('admin/auth/groups/form/'.$id);
				}

				$this->db->trans_begin();

				$this->access_control_model->group->setNodeAsLastChild($node,$new_parent);
				$this->access_control_model->update('groups',array('disabled'=>$disabled),array('id'=>$id));
				$this->access_control_model->update('aros',array('name'=>$name),array('id'=>$id));

				if($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf($this->lang->line('access_group_saved'),$name));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('access_edit_group')));
				}
			}
			redirect('admin/auth/groups');
		}
	}

	/**
	 * Delete Groups
	 *
	 * Delete the given groups
	 */
	function delete()
	{
		if(FALSE === ($groups = $this->input->post('select')))
		{
			redirect('admin/auth/groups','location');
		}

		$this->load->library('auth/khacl');
		foreach($groups as $group)
		{
			// Check we havn't already deleted it as a child of another node
			$query = $this->access_control_model->fetch('aros',NULL,NULL,array('name'=>$group));
			if($query->num_rows() === 0){
				flashMsg('success',sprintf($this->lang->line('access_group_deleted'),$group));
				continue;
			}

			if( $this->khacl->aro->delete($group))
			{
				flashMsg('success',sprintf($this->lang->line('access_group_deleted'),$group));
			}
			else
			{
				flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('access_delete_group')));
			}
		}

		// Make sure any users with a NULL group are assigned the default group
		$this->user_model->update('Users',array('group'=>$this->preference->item('default_user_group')),'`group` IS NULL');

		redirect('admin/auth/groups','location');
	}
}

/* End of file acl_groups.php */
/* Location: ./core_modules/auth/controllers/admin/acl_groups.php */