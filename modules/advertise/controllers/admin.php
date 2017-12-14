<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
  * Admin.php
  * Controller for advertise
  * 
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @author		  Samir prasad <samir@otech.ne.jp>
  * @filesource	  advertise/Admin.php
  * @version 	  1.0.1
  * @package 	  Controller 
  */
class Admin extends Admin_Controller
{
	/**
	 * Constructor
	 *
	 * @return advertiseController
	 */	
	public function __construct() {
		parent::__construct();
		$this->load->model('advertise/advertise_model');
		$this->lang->load('advertise/advertise');
		$this->load->library('validation');
 	}
	/**
	 * @see lists()
	 *
	 */
	public function index() {
		
		// Display Page
		$data['header'] = lang('advertise');
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'advertise';
		$this->load->view($this->_container,$data);
	}
	/**
	 * @see delete() 
	 * 
	 */
	public function delete($id) {
		$data['header'] = lang('advertise');
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'advertise';
		$row1 = $this->advertise_model->getPopUP($id)->result_array();;
		$rows=$this->advertise_model->deletePopup($id);
		$filename_main = $_SERVER['DOCUMENT_ROOT'].'/ercs/xml/popup/'.strtolower($row1[0]['location_name']).'_'.strtolower($row1[0]['language_name']).'.xml';
		$filename_image = $_SERVER['DOCUMENT_ROOT'].'/ercs/uploaded/popup/'.$row1[0]['image'];
		unlink($filename_main);
		unlink($filename_image);
		$this->load->view($this->_container,$data);
	}
	/**
	 * @see delete banner() 
	 * 
	 */
	public function banner_delete($id) {
		// Display Page
		$data['header'] = lang('advertise');
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'advertise';
		$row1 = $this->advertise_model->getBanner($id)->result_array();;
		$rows=$this->advertise_model->deleteBanner($id);
		$filename_main = $_SERVER['DOCUMENT_ROOT'].'/ercs/xml/banner/'.strtolower($row1[0]['location_name']).'_'.strtolower($row1[0]['language_name']).'_'.strtolower($row1[0]['device_name']).'.xml';
		$filename_image = $_SERVER['DOCUMENT_ROOT'].'/ercs/uploaded/banner/'.$row1[0]['image'];
		unlink($filename_main);
		unlink($filename_image);
		$this->load->view($this->_container,$data);
	}
	/**
	 * json create  for poup
	 * 
	 * 
	 */
	public function json() {
		// count 
		$total=$this->advertise_model->count();
		// get input var
		$input = $this->input->get();
		/**************************************************************
		*  PAGER SETTINGS
		**************************************************************/	
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;
		// pager limit 
		$this->db->limit($pagesize, $offset);
		//  fetch location data
		$rows=$this->advertise_model->getLocations()->result_array();
		// loop
		foreach($rows as $key=>$r) 
		{
			$rows_new[$key]['id'] 				= $r['id'];
			$rows_new[$key]['location_name'] 	= $r['location_name'];
			$rows_new[$key]['image'] 			= $r['image'];
			$rows_new[$key]['created_date'] 	= $r['created_date'];
			$rows_new[$key]['language_name'] 	= $r['language_name'];
			$location_name 						= strtolower($r['location_name']);
			$language_name 						= strtolower($r['language_name']);
			$rows_new[$key]['xml'] 				= "<a href='http://{$_SERVER['SERVER_NAME']}/ercs/xml/popup/{$location_name}_{$language_name}.xml' target='_blank'>xml</a>";
		}
		// json out put
		echo json_encode(array('total'=>$total,'rows'=>$rows_new));
	}
	/**
	 * Display for banner lists
	 * 
	 * 
	 */
	 public function banner() {
		// Display Page
		$data['header'] = lang('advertise');
		$data['page'] = $this->config->item('template_admin') . "banner";
		$data['module'] = 'advertise';
		$this->load->view($this->_container,$data);
	}
	/**
	 * json create  for banner lists
	 * 
	 * 
	 */
	 public function banner_json() {
		$total=$this->advertise_model->count_banner();
		$input = $this->input->get();
		/**************************************************************
		*  PAGER SETTINGS
		**************************************************************/	
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;
		// pager limit 
		$this->db->limit($pagesize, $offset);
		//  fetch banner data
		$rows=$this->advertise_model->getBanners()->result_array();
		foreach($rows as $key=>$r) 
		{
			$rows_new[$key]['id'] 				= $r['id'];
			$rows_new[$key]['location_name'] 	= $r['location_name'];
			$rows_new[$key]['image'] 			= $r['image'];
			$rows_new[$key]['created_date'] 	= $r['created_date'];
			$rows_new[$key]['language_name'] 	= $r['language_name'];
			$rows_new[$key]['text'] 			= $r['text'];
			$rows_new[$key]['device_name'] 		= $r['device_name'];
			$location_name 						= strtolower($r['location_name']);
			$language_name 						= strtolower($r['language_name']);
			$device_name 						= strtolower($r['device_name']);
			$rows_new[$key]['xml'] 				= "<a href='http://{$_SERVER['SERVER_NAME']}/ercs/xml/banner/{$location_name}_{$language_name}_{$device_name}.xml' target='_blank'>xml</a>";
		}
		echo json_encode(array('total'=>$total,'rows'=>$rows_new));
	}
	/**
	 * Display add Form
	 *
	 * @access public
	 * @param integer $id Member ID
	 */
	function banner_form($id = NULL)
	{
		// VALIDATION FIELDS
		$fields['id'] = "ID";
		$fields['location_name'] 		= 'location_name';
		$fields['language_name'] 		= 'language_name';
		//$fields['image'] 				= $this->lang->line('userlib_image');
		$this->validation->set_fields($fields);
		// Setup validation rules
		if( is_null($id))
		{
			// Use create user rules 
			$rules['location_name'] 	= "trim|required|spare_location";
			$rules['language_name'] 	= "trim|required|spare_language";
			$rules['device_name'] 		= "trim|required|spare_device_name";
			$rules['url'] 				= "trim|prep_url|valid_url_format|url_exists";
			$rules['timer'] 			= "trim|required|spare_timer";
			//$rules['image'] 			= "trim|required|spare_image";
		}
		else
		{
			// Use edit user rules 
			$rules['location_name'] 	= "trim|required|spare_edit_location";
			$rules['language_name'] 	= "trim|required|spare_edit_language";
			$rules['device_name'] 		= "trim|required|spare_edit_device_name";
			//$rules['image'] 			= "trim|required|spare_edit_image";
		}
		// Setup form default values
		if( ! is_null($id) AND ! $this->input->post('submit'))
		{
			// Modify form, first load
			$popup = $this->advertise_model->getLocations(array('id'=>$id));
			$popup = $popup->row_array();
			$this->validation->set_default_value($popup);
		}
		elseif( is_null($id) AND ! $this->input->post('submit'))
		{
			// Create form, first load
			$this->validation->set_default_value('active','1');
		}
		elseif( $this->input->post('submit') &&  is_null($id))
		{
			$location_language = $this->advertise_model->getLocationLanguageDevice($this->input->post('location_name'),$this->input->post('language_name'),$this->input->post('device_name'));
			$location_language = $location_language->row_array();
				if(isset($location_language['location_name']))
				{
					$rules['location_names and location language'] 	= "trim|required|spare_location";
				}
				if($this->input->post('callback1')=='image')
				{
					$config['upload_path']='uploaded/banner';
					$config['allowed_types']='jpeg|png|gif|mp4|jpg';
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image'))
					{
						$msg='success';
					}
					else
					{
						$rules['image'] 			= "trim|required|spare_image";
					}
				}	
				// Form submited, check rules
			$this->validation->set_rules($rules);
		}
		// RUN
		if ($this->validation->run() === FALSE)
		{
			$data['locations'] = $this->advertise_model->getAllLocation()->result_array();
			$data['languages'] = $this->advertise_model->getAllLanguage()->result_array();
			$data['devices'] = $this->advertise_model->getAllDevice()->result_array();
			// Display form
			$this->validation->output_errors();
			$data['header'] = "Banner";
			$data['page'] = $this->config->item('template_admin') . "form_banner";
			$data['module'] = 'advertise';
			$this->load->view($this->_container,$data);
		}
		else
		{
			// Save form
			if( is_null($id))
			{
				// CREATE
				// Fetch form values
				$popup['location_name'] 		= $this->input->post('location_name');
				$popup['language_name'] 		= $this->input->post('language_name');
				$popup['device_name'] 			= $this->input->post('device_name');
				$popup['text'] 					= $this->input->post('text');
			 	$popup['image'] 				= $_FILES['image']['name'];
			 	$popup['user_id']				= $this->session->userdata('id');
			 	$popup['timer'] 				= $this->input->post('timer');
				$this->db->trans_begin();
				// XML write 
				if(isset($popup['image']) && $popup['image']!='' && $this->input->post('callback1')=='image')
				{
					$img = site_url('uploaded/banner/'.$popup['image']);
					$popup['image'] 				= $_FILES['image']['name'];
					$popup['text'] 					='';
				}
				else
				{
					$img 							= $popup['text'];
					$popup['text'] 					= $this->input->post('text');
					$popup['image']					='';
				}
				if(isset($popup['url']) && $popup['url']!='')
				{
					$img = '<a href='.$popup['image'].'>'.$img.'</a>'; 
				}
				$timer = $popup['timer'];
				$filename_main = $_SERVER['DOCUMENT_ROOT'].'ercs/xml/banner/'.strtolower($this->input->post('location_name')).'_'.strtolower($this->input->post('language_name')).'_'.$this->input->post('device_name').'.xml';
				$xml = new SimpleXMLElement('<xml/>');
			    $track = $xml->addChild('item');
			    $track->addChild('message', $img);
			    $track->addChild('timer', $timer);
			    $filename_main = $_SERVER['DOCUMENT_ROOT'].'/ercs/xml/banner/'.strtolower($this->input->post('location_name')).'_'.strtolower($this->input->post('language_name')).'_'.$this->input->post('device_name').'.xml';
			    @unlink($filename_main);
				$file = $filename_main;		
				if(!file_exists(dirname($file)))
				mkdir(dirname($file), 0777, true);
				$file1 = $filename_main;
				$current = $xml->asXML();
				// Write the contents back to the file
				file_put_contents($file1, $current);
				//$popup['xml'] 				= $current;
				$this->advertise_model->insert('Banner',$popup);
				if($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf('Banner Advertise Saved',$popup['location_name']));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),''));
				}
				redirect('admin/advertise/banner');
			}
		}
	}
	/**
	 * Display pop up Form
	 *
	 * @access public
	 * @param integer $id popup ID
	 */
	function form($id = NULL)
	{
		// VALIDATION FIELDS
		$fields['id'] = "ID";
		$fields['location_name'] 		= 'location_name';
		$fields['language_name'] 		= 'language_name';
		$fields['url'] 					= 'url';
		//$fields['image'] 				= $this->lang->line('userlib_image');
		$this->validation->set_fields($fields);
		// Setup validation rules
		if( is_null($id))
		{
			// Use create ads rules 
			$rules['location_name'] 	= "trim|required|spare_location";
			$rules['language_name'] 	= "trim|required|spare_language";
			$rules['url'] 				= "trim|prep_url|valid_url_format|url_exists";
		}
		else
		{
			// Use edit user rules 
			$rules['location_name'] 	= "trim|required|spare_edit_location";
			$rules['language_name'] 	= "trim|required|spare_edit_language";
			//$rules['image'] 			= "trim|required|spare_edit_image";
		}
		// Setup form default values
		
		if( ! is_null($id) AND ! $this->input->post('submit'))
		{
			// Modify form, first load
			$popup = $this->advertise_model->getLocations(array('id'=>$id));
			$popup = $popup->row_array();
			$this->validation->set_default_value($popup);
		}
		elseif( is_null($id) AND ! $this->input->post('submit'))
		{
			// Create form, first load
			$this->validation->set_default_value('active','1');
		}
		elseif( $this->input->post('submit') &&  is_null($id))
		{
			$this->validation->set_rules('password', 'Old Password', 'required|min_length[8]|callback_password_check');
			$location_language = $this->advertise_model->getLocationLanguage($this->input->post('location_name'),$this->input->post('language_name'));
			$location_language = $location_language->row_array();
				$config['upload_path']='uploaded/popup';
				$config['allowed_types']='jpeg|png|gif|mp4|jpg';
				$this->load->library('upload', $config);
				if(isset($location_language['location_name']))
				{
					$rules['location_names and location language'] 	= "trim|required|spare_location";
				}
				if($this->upload->do_upload('image'))
				{
					$msg='success';
				}
				else
				{
					$rules['image'] 			= "trim|required|spare_image";
				}
				// Form submited, check rules
			$this->validation->set_rules($rules);
		}
		// RUN
		if ($this->validation->run() === FALSE)
		{
			$data['locations'] = $this->advertise_model->getAllLocation()->result_array();
			$data['languages'] = $this->advertise_model->getAllLanguage()->result_array();
			// Display form
			$this->validation->output_errors();
			$data['header'] = "POPUP";
			$data['page'] = $this->config->item('template_admin') . "form_popup";
			$data['module'] = 'advertise';
			$this->load->view($this->_container,$data);
		}
		else
		{
			// Save form
			if( is_null($id))
			{
				// CREATE
				// Fetch form values
				$popup['location_name'] 		= $this->input->post('location_name');
				$popup['language_name'] 		= $this->input->post('language_name');
				$popup['url'] 					= $this->input->post('url');
			 	$popup['image'] 				= $_FILES['image']['name'];
			 	$popup['user_id']				= $this->session->userdata('id');
				$this->db->trans_begin();
				// XML write 
				$img = site_url('uploaded/popup/'.$popup['image']);
				if(isset($popup['url']) && $popup['url']!='')
				{
					$img = '<a href='.$popup['url'].'>'.$img.'</a>'; 
				}
				$filename_main = $_SERVER['DOCUMENT_ROOT'].'ercs/xml/popup/'.strtolower($this->input->post('location_name')).'_'.strtolower($this->input->post('language_name')).'.xml';
				$xml = new SimpleXMLElement('<xml/>');
			    $track = $xml->addChild('item');
			    $track->addChild('message', $img);
			    $filename_main = $_SERVER['DOCUMENT_ROOT'].'/ercs/xml/popup/'.strtolower($this->input->post('location_name')).'_'.strtolower($this->input->post('language_name')).'.xml';
			    @unlink($filename_main);
				$file = $filename_main;		
				if(!file_exists(dirname($file)))
				mkdir(dirname($file), 0777, true);
				$file1 = $filename_main;
				$current = $xml->asXML();
				// Write the contents back to the file
				file_put_contents($file1, $current);
				//$popup['xml'] 				= $current;
				$this->advertise_model->insert('Popup',$popup);
				if($this->db->trans_status() === TRUE)
				{
					$this->db->trans_commit();
					flashMsg('success',sprintf('POPUP Advertise Saved',$popup['location_name']));
				}
				else
				{
					$this->db->trans_rollback();
					flashMsg('error',sprintf($this->lang->line('backendpro_action_failed'),''));
				}
				redirect('admin/advertise/index');
			}
		}
	}
}