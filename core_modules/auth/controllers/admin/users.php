<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price
 * @copyright       Copyright (c) 2008
 * @license         http://www.gnu.org/licenses/lgpl.html
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

// ---------------------------------------------------------------------------

/**
 * Users
 *
 * Allow the user to manage website users
 *
 * @package         BackendPro
 * @subpackage      Controllers
 */
class Users extends Admin_Controller
{
	function Users()
	{
		parent::__construct();

		$this->load->helper('form');

		// Load userlib language
		$this->lang->load('auth/userlib');
        $this->lang->load('auth/user');

		// Check for access permission
		check('Users');
		// Load the validation library
		$this->load->library('validation');

		log_message('debug','BackendPro : Users class loaded');
	}

	/**
	 * View Users
	 *
	 * @access public
	 */
	function index()
	{
		// Get Member Infomation
		$data['users'] = $this->user_model->getUsers();

		// Display Page
		$data['header'] = $this->lang->line('users');
		$data['page'] = $this->config->item('template_admin') . "users/index";
		$data['module'] = 'auth';
		$this->load->view($this->_container,$data);
	}

	public function json()
	{
		$this->_get_search_param();	
		$total=$this->user_model->countUsers();

		$this->_get_search_param();	

		$input = $this->input->get();
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;

		$this->db->limit($pagesize, $offset);

		$rows=$this->user_model->getUsers()->result_array();
// print $this->db->last_query();
		echo json_encode(array('total'=>$total,'records'=>$rows));
	}

	public function _get_search_param()
	{

		$input = $this->input->get();

		//sorting
		if (isset($input['sortdatafield'])) {
			$sortdatafield = $input['sortdatafield'];
			
			if ($sortdatafield == 'group_id') {
				$sortdatafield = 'group';
			}

			$sortorder = (isset($input['sortorder'])) ? $input['sortorder'] :'asc';
			$this->db->order_by($sortdatafield, $sortorder); 
		} else {
			$this->db->order_by("id", "desc"); 
		}


		$input = $this->input->get();

		if (isset($input['filterscount'])) {
			$filtersCount = $input['filterscount'];
			if ($filtersCount > 0) {
				for ($i=0; $i < $filtersCount; $i++) {
					// get the filter's column.
					$filterDatafield 	= $input['filterdatafield' . $i];

					// get the filter's value.
					$filterValue 		=  $input['filtervalue' 	. $i];

					// get the filter's condition.
					$filterCondition 	= $input['filtercondition' . $i];

					// get the filter's operator.
					$filterOperator 	= $input['filteroperator' 	. $i];

					$operatorLike = 'LIKE';

					if ($filterValue == 'true') 
						$filterValue = 1;
					elseif ($filterValue == 'false')
						$filterValue = 0;

					if ($filterDatafield == 'group_id') {
						$filterDatafield = 'group';
					}

					switch($filterCondition) {
						case "CONTAINS":
							$this->db->like($filterDatafield, $filterValue);
							break;
						case "DOES_NOT_CONTAIN":
							$this->db->not_like($filterDatafield, $filterValue);
							break;
						case "EQUAL":
							$this->db->where($filterDatafield, $filterValue);
							break;
						case "GREATER_THAN":
							$this->db->where($filterDatafield . ' >', $filterValue);
							break;
						case "LESS_THAN":
							$this->db->where($filterDatafield . ' <', $filterValue);
							break;
						case "GREATER_THAN_OR_EQUAL":
							$this->db->where($filterDatafield . ' >=', $filterValue);
							break;
						case "LESS_THAN_OR_EQUAL":
							$this->db->where($filterDatafield . ' <=', $filterValue);
							break;
						case "STARTS_WITH":
							$this->db->like($filterDatafield, $filterValue, 'after'); 
							break;
						case "ENDS_WITH":
							$this->db->like($filterDatafield, $filterValue, 'before'); 
							break;
						/*
						case "NOT_EQUAL":
							if($filterValue == '' || $filterValue == null) {
								$records->whereNotNull($filterDatafield);
							} else {
								$records->where($filterDatafield, '<>', "{$filterValue}");
							}
							break;
						 */
					}
				}
			}
		}


	}

	public function combo_json()
    {
		$rows=$this->user_model->getUsers()->result_array();
		echo json_encode($rows);    	
    }

    public function group_combo_json()
    {
    	$sql = "select * from be_acl_groups";
    	$results=$this->db->query($sql)->result_array();
    	foreach($results as $result):
		$rows[] = $result;
		endforeach;
		echo json_encode($rows);
    }

	/**
	 * Set Profile Defaults
	 *
	 * Specify what values should be shown in the profile fields when creating
	 * a new user by default
	 *
	 * @access private
	 */
	function _set_profile_defaults()
	{
		//$this->validation->set_default_value('field1','value');
		//$this->validation->set_default_value('field2','value');
	}

	/**
	 * Get User Details
	 *
	 * Load user detail values from the submited form
	 *
	 * @access private
	 * @return array
	 */
	function _get_user_details()
	{
		$data['id'] = $this->input->post('id');
		$data['username'] = $this->input->post('username');
		$data['email'] = $this->input->post('email');
		$data['group'] = $this->input->post('group');
		$data['active'] = $this->input->post('active');

		// Only if password is set encode it
		if($this->input->post('password') != '')
		{
			$data['password'] = $this->userlib->encode_password($this->input->post('password'));
		}

		return $data;
	}

	/**
	 * Get Profile Details
	 *
	 * Load user profile detail values from the submited form
	 *
	 * @access private
	 * @return array
	 */
	function _get_profile_details()
	{
		$data = array();
		//$data['field1'] = $this->input->post('field1');
		//$data['field2'] = $this->input->post('field2');
		//$data['field3'] = $this->input->post('field3');
		return $data;
	}

	/**
	 * Display Member Form
	 *
	 * @access public
	 * @param integer $id Member ID
	 */
	function form($id = NULL)
	{
		
		// VALIDATION FIELDS
		$fields['id'] = "ID";
		$fields['username'] = $this->lang->line('userlib_username');
		$fields['email'] = $this->lang->line('userlib_email');
		$fields['password'] = $this->lang->line('userlib_password');
		$fields['confirm_password'] = $this->lang->line('userlib_confirm_password');
		$fields['group'] = $this->lang->line('userlib_group');
		$fields['active'] = $this->lang->line('userlib_active');
		$fields = array_merge($fields, $this->config->item('userlib_profile_fields'));
		$this->validation->set_fields($fields);

		// Setup validation rules
		if( is_null($id))
		{
			

			// Use create user rules (make sure no-one has the same email)
			$rules['username'] = "trim|required|spare_username";
			$rules['email'] = "trim|required|valid_email|spare_email";
			$rules['password'] = "trim|required|min_length[".$this->preference->item('min_password_length')."]|matches[confirm_password]";
		}
		else
		{
			// Use edit user rules (make sure no-one other than the current user has the same email)
			$rules['username'] = "trim|required|spare_edit_username";
			$rules['email'] = "trim|required|valid_email|spare_edit_email";
			$rules['password'] = "trim|min_length[".$this->preference->item('min_password_length')."]|matches[confirm_password]";
		}
		$rules = array_merge($rules,$this->config->item('userlib_profile_rules'));

		// Setup form default values
		if( ! is_null($id) AND ! $this->input->post('submit'))
		{
			
			// Modify form, first load
			$user = $this->user_model->getUsers(array('users.id'=>$id));
			$user = $user->row_array();

			$this->validation->set_default_value('group',$user['group_id']);
			unset($user['group']);
			unset($user['group_id']);
			$this->validation->set_default_value($user);
		}
		elseif( is_null($id) AND ! $this->input->post('submit'))
		{
			
			// Create form, first load
			$this->validation->set_default_value('group',$this->preference->item('default_user_group'));
			$this->validation->set_default_value('active','1');

			// Setup profile defaults
			$this->_set_profile_defaults();
		}
		elseif( $this->input->post('submit'))
		{
			// Form submited, check rules
			$this->validation->set_rules($rules);
		}

		// RUN
		if ($this->validation->run() === FALSE)
		{
			// Load Generate Password Assets
			//$this->bep_assets->load_asset_group('GENERATE_PASSWORD');

			// Construct Groups dropdown
			$this->load->model('access_control_model');
			$data['groups'] = $this->access_control_model->buildAClDropdown('group','id');

			// Display form
			$this->validation->output_errors();
			$data['header'] = ( is_null($id)?$this->lang->line('userlib_create_user'):$this->lang->line('userlib_edit_user'));
			$data['page'] = $this->config->item('template_admin') . "users/form_user";
			$data['module'] = 'auth';
			$this->load->view($this->_container,$data);
		}
		else
		{
			// Save form
			if( is_null($id))
			{
				// CREATE
				// Fetch form values
				$user = $this->_get_user_details();
				$user['created'] = date('Y-m-d H:i:s');
				$profile = $this->_get_profile_details();

				$this->db->trans_begin();
				$this->user_model->insert('Users',$user);
				$profile['user_id'] = $this->db->insert_id();
				$this->user_model->insert('UserProfiles',$profile);

				if($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf($this->lang->line('userlib_user_saved'),$user['username']));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('userlib_create_user')));
				}
				redirect('admin/auth/users');
			}
			else
			{
				// SAVE
				$user = $this->_get_user_details();
				$user['modified'] = date('Y-m-d H:i:s');
				$profile = $this->_get_profile_details();

				$this->db->trans_begin();
				$this->user_model->update('Users',$user,array('id'=>$user['id']));

				// The && count($profile) > 0 has been added here since if no update keys=>values
				// are passed to the update method it errors saying the set method must be set
				// See bug #51
				if($this->preference->item('allow_user_profiles') && count($profile) > 0)
				{
					$this->user_model->update('UserProfiles',$profile,array('user_id'=>$user['id']));
				}

				if($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf($this->lang->line('userlib_user_saved'),$user['username']));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),$this->lang->line('userlib_edit_user')));
				}
				redirect('admin/auth/users');
			}
		}
	}

	/**
	 * Delete
	 *
	 * Delete the selected users from the system
	 *
	 * @access public
	 */
	function delete()
	{
		
		if(FALSE === ($selected = $this->input->post('select')))
		{
			redirect('auth/users','location');
		}

		foreach($selected as $user)
		{
			if($user != 1)
			{	// Delete as long as its not the Administrator account
				$this->user_model->delete('Users',array('id'=>$user));
			}
			else
			{
				flashMsg('error',$this->lang->line('userlib_administrator_delete'));
			}
		}

		flashMsg('success',$this->lang->line('userlib_user_deleted'));
		redirect('auth/users','location');
	}

	public function delete_json()
	{
    	$id=$this->input->post('id');
		if($id && is_array($id))
		{
        	foreach($id as $row):
			if($row != 1)
			{			
				$this->user_model->delete('USERS',array('id'=>$row));
			}
            endforeach;
		}
	} 	
	
	public function send_message_json()
	{
			$success=FALSE;
			$this->load->library('email');			
			$subject="Reply from " .site_url();
			
			$config['protocol'] = $this->preference->item('email_protocol');
			$config['mailpath'] = $this->preference->item('email_mailpath');
			$config['smtp_host'] = $this->preference->item('smtp_host');
			$config['smtp_user'] = $this->preference->item('smtp_user');
			$config['smtp_pass'] = $this->preference->item('smtp_pass');
			$config['smtp_port'] = $this->preference->item('smtp_port');
			$config['smtp_timeout'] = $this->preference->item('smtp_timeout');
			$config['wordwrap'] = $this->preference->item('email_wordwrap');
			$config['wrapchars'] = $this->preference->item('email_wrapchars');
			$config['mailtype'] = $this->preference->item('email_mailtype');
			$config['charset'] = $this->preference->item('email_charset');
			$config['bcc_batch_mode'] = $this->preference->item('bcc_batch_mode');
			$config['bcc_batch_size'] = $this->preference->item('bcc_batch_size');
			
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from($this->preference->item('automated_from_email'), $this->preference->item('automated_from_name'));
			$this->email->to($this->input->post('email'));
			
			$this->email->subject($this->input->post('subject'));
			$this->email->message($this->input->post('body'));
			
			if($this->email->send())
			{
				$success=TRUE;	
			}	
			echo json_encode(array('success'=>$success,'msg'=>''));	
	}
}
/* End of file users.php */
/* Location: ./core_modules/auth/controllers/admin/users.php */