<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
  * Admin.php
  * Controller for ads
  * 
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @author		  Samir prasad <samir@otech.ne.jp>
  * @filesource	  ads/Admin.php
  * @version 	  1.0.1
  * @package 	  Controller 
  */
class Admin extends Admin_Controller
{
	/**
	 * Constructor
	 *
	 * @return adsController
	 */		
	public function __construct(){
    	parent::__construct();
		$this->load->model('ads/ads_model');
		$this->lang->load('ads/ads');
    }
	/**
	 * @see lists()
	 *
	 */
	public function index()
	{
		// Display Page
		$data['header'] = 'Popup';
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'ads';
		$this->load->view($this->_container,$data);
	}
	/**
	 * @see banner history lists()
	 * 
	 */
	  public function banner()
	  {
	  	// Display Page
		$data['header'] = 'Banner';
		$data['page'] = $this->config->item('template_admin') . "banner";
		$data['module'] = 'ads';
		$this->load->view($this->_container,$data);
	  }
	  
	  /**
	 * json create  for banner  history
	 * 
	 * 
	 */
	public function banner_json()
	{
		$this->_get_search_param();
		$total=$this->ads_model->bannerCount();
		$input = $this->input->get();
		/**************************************************************
		*  PAGER SETTINGS
		**************************************************************/	
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;
		// pager limit 
		$this->db->limit($pagesize, $offset);
		// params
		$this->_get_search_param();
		$rows=$this->ads_model->getBanner()->result_array();
		// json output
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}
	  
	/**
	 * json create  for popup history
	 * 
	 * 
	 */
	public function json()
	{
		$this->_get_search_param();
		$total=$this->ads_model->count();
		$input = $this->input->get();
		/**************************************************************
		*  PAGER SETTINGS
		**************************************************************/	
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;
		// pager limit 
		$this->db->limit($pagesize, $offset);
		// params
		$this->_get_search_param();
		$rows=$this->ads_model->getPopups()->result_array();
		// json output
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}
	/**
	 * @see parameters()
	 *
	 */
	public function _get_search_param() {
		
		$input = $this->input->get();
		//sorting
		if (isset($input['sortdatafield'])) {
			$sortdatafield = $input['sortdatafield'];
			$sortorder = (isset($input['sortorder'])) ? $input['sortorder'] :'asc';
			$this->db->order_by($sortdatafield, $sortorder); 
		} else {
			$this->db->order_by("id", "asc"); 
		}
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
					}
				}
			}
		}
	}
	/**
	 * @see datewise()
	 *
	 */
	private function _datewise($field,$from,$to)
	{
		if(!empty($from) && !empty($to))
		{
			$this->db->where("(date_format(".$field.",'%Y-%m-%d') between '".date('Y-m-d',strtotime($from)).
					"' and '".date('Y-m-d',strtotime($to))."')");
		}
		else if(!empty($from))
		{
			$this->db->like($field,date('Y-m-d',strtotime($from)));				
		}		
	}
}