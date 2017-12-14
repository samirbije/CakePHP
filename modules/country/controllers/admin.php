<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{

	public function __construct(){
		parent::__construct();
		$this->load->model('country/country_model');
		$this->lang->load('country/country');
	}

	public function index()
	{
		// Display Page
		$data['header'] = lang('country');
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'country';
		$this->load->view($this->_container,$data);
	}

	public function json()
	{
		$this->_get_search_param();
		$total=$this->country_model->count();
		
		$input = $this->input->get();
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;

		$this->db->limit($pagesize, $offset);

		$this->_get_search_param();
		$rows=$this->country_model->getCountries()->result_array();
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}

	public function _get_search_param()
	{
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
					}
				}
			}
		}
	}

	public function combo_json()
	{
		$this->db->where('active', '1');
		$rows=$this->country_model->getCountries()->result_array();
		echo json_encode($rows);
	}

	public function delete_json()
	{
		$id=$this->input->post('id');
		if($id && is_array($id))
		{
			foreach($id as $row):
				$this->country_model->delete('COUNTRIES',array('id'=>$row));
			endforeach;
		}
	}

	public function save()
	{
        $data=$this->_get_posted_data(); //Retrive Posted Data

        if(!$this->input->post('id'))
        {
        	$data['created']= $data['modified'] = date('Y-m-d H:i:s');
        	$success=$this->country_model->insert('COUNTRIES',$data);
        }
        else
        {
        	$data['modified'] = date('Y-m-d H:i:s');
        	$success=$this->country_model->update('COUNTRIES',$data,array('id'=>$data['id']));
        }

        if($success)
        {
        	$success = TRUE;
        	$msg=lang('success_message');
        }
        else
        {
        	$success = FALSE;
        	$msg=lang('failure_message');
        }

        echo json_encode(array('msg'=>$msg,'success'=>$success));

    }

    private function _get_posted_data()
    {
    	$data=array();
    	$data['id'] = $this->input->post('id');
    	$data['country'] = $this->input->post('country');
    	$data['code'] = $this->input->post('code');
    	if ($this->input->post('active')) {
    		$data['active'] = 1;
    	} else {
    		$data['active'] = 0;
    	}
    	$data['created'] = $this->input->post('created');
    	$data['modified'] = $this->input->post('modified');

    	return $data;
    }

    public function check_duplicate() {
    	$field = $this->input->post('field');
    	$value = $this->input->post('value');
    	$this->db->where($field, $value);

    	if ($this->input->post('id')) {
    		$this->db->where('id <>', $this->input->post('id'));
    	}

    	$total=$this->country_model->count();

    	if ($total == 0) 
    		echo json_encode(array('success' => true));
    	else
    		echo json_encode(array('success' => false));
    }
}