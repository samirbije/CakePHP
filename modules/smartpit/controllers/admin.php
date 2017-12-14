<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	public function __construct() {
		parent::__construct();
		$this->load->model('smartpit/smartpit_model');
		$this->lang->load('smartpit/smartpit');
		$this->bep_assets->load_asset_group('PLUPLOAD');
	}

	public function index() {
		// Display Page
		$data['header'] = lang('smartpit');
		$data['page'] = $this->config->item('template_admin') . "index";
		$data['module'] = 'smartpit';
		$this->load->view($this->_container,$data);
	}
	public function uploadFiles()
	{
		print_r($_FILES);

		$config['upload_path'] = CSV_UPLOAD_PATH;
		$config['allowed_types'] = 'csv';
		$config['max_size'] = '1024000';
		$config['remove_spaces'] = true;
		//$config['file_name'] = uniqid();

		//load upload library
		$this->load->library('upload', $config);

		if($this->upload->do_upload('file')) {
			$data=$this->upload->data();
			$file = $data['full_path'];
			$this->db->trans_start();
			$sql= "LOAD DATA LOCAL INFILE '{$file}' INTO TABLE ercs_smartpit FIELDS TERMINATED BY ',' LINES TERMINATED BY \"\r\n\"  (smartpit_number,barcode_number,password,number_type,status,created)";
			$this->db->query($sql);
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$success = FALSE;
				$msg=lang('failure_message');
			}
			else
			{
				$this->db->trans_commit();
	        	$success = TRUE;
				$msg=lang('success_message');
			}
			
			echo json_encode(array('msg'=>$msg,'success'=>$success));

		} else {
			echo json_encode($this->upload->display_errors());
		}
	}
	public function download(){
		$this->load->helper('url');
		$this->load->helper('download');
		//$file_path = file_get_contents(base_url("/uploads/sample.csv")); 
		$data = 'smartpit_number,barcode_number,password,number_type,status,created';
		$file_name = 'sample.csv';
		force_download($file_name, $data);

	}

	public function json() {
		$this->_get_search_param();
		$total=$this->smartpit_model->count();
		
		$input = $this->input->get();
		$pagenum  = (isset($input['pagenum'])) ? $input['pagenum'] : 0;
		$pagesize  = (isset($input['pagesize'])) ? $input['pagesize'] : 10;
		$offset = $pagenum * $pagesize;

		$this->db->limit($pagesize, $offset);

		$this->_get_search_param();
		$rows=$this->smartpit_model->getSmartpits()->result_array();
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}

	public function _get_search_param() {
		
		$input = $this->input->get();

		//sorting
		if (isset($input['sortdatafield'])) {
			$sortdatafield = $input['sortdatafield'];

			$sortorder = (isset($input['sortorder'])) ? $input['sortorder'] :'asc';
			$this->db->order_by($sortdatafield, $sortorder); 
		} else {
			$this->db->order_by("sn", "asc"); 
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

	public function save() {

        $data=$this->_get_posted_data(); //Retrive Posted Data

        if(!$this->input->post('sn')) {
        	$success=$this->smartpit_model->insert('SMARTPIT',$data);
        } else {
        	$success=$this->smartpit_model->update('SMARTPIT',$data,array('sn'=>$data['sn']));
        }

        if($success) {
        	$success = TRUE;
        	$msg=lang('success_message');
        } else {
        	$success = FALSE;
        	$msg=lang('failure_message');
        }

        echo json_encode(array('msg'=>$msg,'success'=>$success));
    }

    private function _get_posted_data()
    {
    	$data=array();
    	$data['sn'] = $this->input->post('sn');
    	$data['smartpit_number'] = $this->input->post('smartpit_number');
    	$data['barcode_number'] = $this->input->post('barcode_number');
    	$data['password'] = $this->input->post('password');
    	$data['number_type'] = $this->input->post('number_type');
    	$data['status'] = $this->input->post('status');
    	$data['account_id'] = $this->input->post('account_id');
    	$data['facebook_id'] = $this->input->post('facebook_id');
    	$data['created'] = $this->input->post('created');
    	$data['modified'] = $this->input->post('modified');

    	return $data;
    }


    /*temporary functions need to work on this*/
    public function temp () {
    	$sql = "select status, count(*) as count from ercs_smartpit where number_type = 'online' group by status order by status desc ";
    	$r = $this->db->query($sql)->result_array();

    	echo json_encode($r);
    }

    public function temp2 () {
    	$sql = "select status, count(*) as count from ercs_smartpit where number_type = 'offline' group by status order by status desc ";
    	$r = $this->db->query($sql)->result_array();

    	echo json_encode($r);
    }

}