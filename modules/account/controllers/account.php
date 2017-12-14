<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth/user_model');
	    $this->lang->load('account/account');
	    $this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('auth/user_email');
	}

	public function index()
	{
		redirect('home');
	}

	public function change_password()
	{

		$min_password_length=$this->preference->item('min_password_length');

		$this->form_validation->set_rules('password', 'Old Password', 'required|min_length['.$min_password_length.']|callback_password_check');
		$this->form_validation->set_rules('new_password', 'New Confirmation', 'required|min_length['.$min_password_length.']');
		$this->form_validation->set_rules('conf_password', 'Password Confirmation', 'required|min_length['.$min_password_length.']|matches[new_password]');

		if ($this->form_validation->run($this) == FALSE)
		{
			$data['header'] = "Change Password";
			$data['page'] = $this->config->item('template_admin') .  'account/change_password';
			$data['module'] = 'account';

			flashMsg('warning', validation_errors());

			$this->load->view($this->_container,$data);
		}
		else
		{
			$user_id=$this->session->userdata('id');
			$pass=$this->input->post('new_password');
			$enc_pass=$this->userlib->encode_password($pass);
			// Update password in database
			$date = date('Y-m-d H:i:s');
			$this->user_model->update('Users',array('modified'=>$date, 'password'=>$enc_pass), array('id'=>$user_id));

			// Email the new password to the user
			$query = $this->user_model->fetch('Users','username,email',NULL,array('id'=>$user_id));
			$user = $query->row();
			$data = array(
                    'username'=>$user->username,
                    'email'=>$user->email,
                    'password'=>$pass,
                    'site_name'=>$this->preference->item('site_name'),
                    'site_url'=>base_url()
			);

			//$this->user_email->send($user->email,$this->lang->line('userlib_change_password'),'account/email_change_password',$data);

			$this->session->sess_destroy();
			$this->session->sess_create();
			flashMsg('success','You have successfully changed your password. Please login again.');
			redirect('auth/login');
			//redirect(site_url('home'));
		}
	}

	public function password_check($str)
	{
		$user_id=$this->session->userdata('id');

		$password=$this->userlib->encode_password($str);

		$result=$this->user_model->fetch('Users',NULL,NULL,array('id'=>$user_id,'password'=>$password));

		if ($result->num_rows()==0)
		{
			$this->form_validation->set_message('password_check', 'Password did not matches with our database');
			return FALSE;
		}
		return TRUE;
	}
}