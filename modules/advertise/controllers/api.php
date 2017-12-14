<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends REST_Controller
{
	public function __construct() {
    parent::__construct('rest_server');
        $this->load->helper('language');
        $this->load->config('settings');
        $this->load->model('advertise/advertise_model');
		$this->lang->load('advertise/advertise');
		$this->load->library('validation');
    }
    /**
     * popup
     *
     * Process the poup ad
     *
     * @access  public
     * @param   string  accounid
     * @param   string  locationname
     * @param   string  language name
     * @return  xml
     */
   public function getPopupAd_get($acc_id='',$location_name='',$language_name='')
   {
   	if ( empty($acc_id) ||  empty($location_name)  ||  empty($language_name) ) {
    		$this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
    	}
   		$location_name 	= 	strtolower($location_name);
   		$language_name	= 	strtolower($language_name);
   		$results = $this->advertise_model->insertPopupAdvertise($acc_id,$location_name,$language_name);
   	 	$new_var = $location_name.'_'.$language_name.".xml";
   	 	$file_name	= $_SERVER['DOCUMENT_ROOT'].'ercs/xml/popup/'.$new_var;
   		if(file_exists($file_name))
   	   	{
 		  	header('Content-Type: text/xml');   	   		
   	   		$homepage = site_url('xml/popup/'.$new_var);
			print file_get_contents($homepage); 
   		}
   		else
   		{
   			header('Content-Type: text/xml');   			
   			$location = site_url('xml/popup/japan_english.xml');
   			
			print file_get_contents($location);
   		}
  }
  /**
     * popup
     *
     * Process the Banner ad
     *
     * @access  public
     * @param   string  accounid
     * @param   string  locationname
     * @param   string  language name
     * @return  xml
     */
   public function getBannerAd_get($acc_id='',$location_name='',$language_name='',$device_name='')
   {
   	if ( empty($acc_id) ||  empty($location_name)  ||  empty($language_name) || empty($device_name) ) {
    		$this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
    	}
   		$location_name 	= 	strtolower($location_name);
   		$language_name	= 	strtolower($language_name);
   		$device_name	= 	strtolower($device_name);
   		$result 		=   $this->advertise_model->getStatus($acc_id)->row_array();
   		if($result['status']==1)
   		{
   		 $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_not_found'), 
                    'message'       => 'advertise removed for this account'
                    )
                );
   		}
   		$results 		= 	$this->advertise_model->insertBannerAdvertise($acc_id,$location_name,$language_name,$device_name);
   	 	$new_var 		= 	$location_name.'_'.$language_name.'_'.$device_name.".xml";
   	 	$file_name		= 	$_SERVER['DOCUMENT_ROOT'].'ercs/xml/banner/'.$new_var;
   		if(file_exists($file_name))
   	   	{
 		  	header('Content-Type: text/xml');   	   		
   	   		$homepage = site_url('xml/banner/'.$new_var);
			print file_get_contents($homepage); 
   		}
   		else
   		{
			header('Content-Type: text/xml');   			
   			$location = site_url('xml/banner/japan_english_'.$device_name.'.xml');
 			print file_get_contents($location);
   		}
  }
    /**
     * Banner
     *
     * Process the Remove ad
     *
     * @access  public
     * @param   string  accounid
     * @param   string  locationname
     * @param   string  language name
     * @return  xml
     */
   public function adRemove_get($acc_id='',$location_name='',$language_name='',$device_name='')
   {
   	if ( empty($acc_id) ||  empty($location_name)  ||  empty($language_name) || empty($device_name) ) {
    		$this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
    	}
   		$location_name 	= 	strtolower($location_name);
   		$language_name	= 	strtolower($language_name);
   		$device_name	= 	strtolower($device_name);
   		$results 		= 	$this->advertise_model->insertAccStatus($acc_id);
   		$this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'message'       => 'Banner removed for this account'
                    )
                );
  }
  function test_get()
  {
  	$url="http://localhost/ercs/advertise/api/getPopupAd/123/nepal/nepali/";
	$xmlinfo = simplexml_load_file($url);
	print_r($xmlinfo);
  }
}