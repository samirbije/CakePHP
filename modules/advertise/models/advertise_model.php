<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
  * AdminAdvertiseModel
  * Model for Ads
  * 
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @filesource	  admin/ads_model.php
  * @package 	  Model 
  */
class Advertise_model extends MY_Model
{
    var $fields = '';
	var $joins  = array();
	/**
	 * Constructor
	 *
	 * @return advertiseController
	 */	
    public function __construct()
    {
    	parent::__construct();

        $this->prefix  = 'ercs_';
        $this->_TABLES = array('LOCATION'=>$this->prefix.'location',
		'Popup' => $this->prefix . 'popup','Banner' => $this->prefix . 'banner');
		$this->_JOINS  = array(
                            'KEY' => array(
                                        'join_type'  => 'LEFT',
                                        'join_field' => 'join1.id=join2.id',
                                        'select'     => 'field_names',
                                        'alias'      => 'alias_name'
                                    ),
                            );
    }
	 /**
	 * fetches the popup  as per pagination
	 *
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
    public function getLocations ($where = NULL, $order_by = NULL, $limit = array('limit' => NULL,'offset' => ''))
    {
        $fields = 'popup.*';
        if($this->fields != '') {
            $fields = $this->fields;
        }
		foreach ($this->joins as $key) {
			$fields = $fields . ',' . $this->_JOINS[$key]['select'];
		}
        $this->db->select($fields);
       	$this->db->from($this->_TABLES['Popup'] . ' popup');
		foreach($this->joins as $key) {
            $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'], $this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
		}
		(! is_null($where)) ? $this->db->where($where) : NULL;
		(! is_null($order_by)) ? $this->db->order_by($order_by) : NULL;
// PAGER 	LIMIT 	
		if( ! is_null($limit['limit'])) {
			$this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : '' ));
		}
		return $this->db->get();
    }
   /**
	 * fetches the banner as per pagination
	 *
	 * @param int $offset
	 * @param int $limit
	 * @return array
	 */
    public function getBanners ($where = NULL, $order_by = NULL, $limit = array('limit' => NULL,'offset' => ''))
    {
        $fields = 'banner.*';

        if($this->fields != '') {
            $fields = $this->fields;
        }

		foreach ($this->joins as $key) {
			$fields = $fields . ',' . $this->_JOINS[$key]['select'];
		}

        $this->db->select($fields);

        $this->db->from($this->_TABLES['Banner'] . ' banner');

		foreach($this->joins as $key) {
            $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'], $this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
		}

		(! is_null($where)) ? $this->db->where($where) : NULL;
		(! is_null($order_by)) ? $this->db->order_by($order_by) : NULL;

		if( ! is_null($limit['limit'])) {
			$this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : '' ));
		}

		return $this->db->get();
    }
    public function count($where = NULL)
    {
        $this->db->from($this->_TABLES['Popup'].' popup');
        foreach($this->joins as $key) {
            $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'],$this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
        }
       (! is_null($where)) ? $this->db->where($where) : NULL;
        return $this->db->count_all_results();
    }
    public function count_banner($where = NULL)
    {
        $this->db->from($this->_TABLES['Banner'].' banner');
        foreach($this->joins as $key) {
            $this->db->join($this->_TABLES[$key] . ' ' . $this->_JOINS[$key]['alias'],$this->_JOINS[$key]['join_field'], $this->_JOINS[$key]['join_type']);
        }
       (! is_null($where)) ? $this->db->where($where) : NULL;
        return $this->db->count_all_results();
    }
     /**
	 * fetches the all location
	 *
	 * @return string
	 */
    public function getAllLocation()
    {
    	$sql = "SELECT * FROM ercs_location";
    	$result = $this->db->query($sql);
		return $result;
    }
      /**
	 * fetches the all device 
	 *
	 * @return string
	 */
    public function getAllDevice()
    {
    	$sql = "SELECT * FROM ercs_device";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches the all language 
	 *
	 * @return string
	 */
    public function getAllLanguage()
    {
    	$sql = "SELECT * FROM ercs_language";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function getPopUP($id)
    {
    	$sql = "SELECT * FROM ercs_popup where id='{$id}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function getBanner($id)
    {
    	$sql = "SELECT * FROM ercs_banner where id='{$id}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches
	 *
	 * @return string
	 */
    public function getLocationLanguage($location_name='',$language_name='')
    {
    	 $sql = "SELECT * FROM ercs_popup where location_name='{$location_name}' and language_name='{$language_name}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function getLocationLanguageDevice($location_name='',$language_name='',$device_name='')
    {
    	 $sql = "SELECT * FROM ercs_banner where location_name='{$location_name}' and language_name='{$language_name}' and device_name='{$device_name}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function deletePopup($id)
    {
    	$sql = "DELETE FROM ercs_popup WHERE id='{$id}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function deleteBanner($id)
    {
    	$sql = "DELETE FROM ercs_banner WHERE id='{$id}'";
    	$result = $this->db->query($sql);
		return $result;
    }
    /**
	 * fetches 
	 *
	 * @return string
	 */
    public function insertPopupAdvertise($acc_id,$location_name,$language_name)
    {
    		$sql = "INSERT INTO ercs_popup_advertise_info (account_id,language,location) VALUES ('{$acc_id}','{$location_name}','{$language_name}')";
    		$result = $this->db->query($sql);
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function insertBannerAdvertise($acc_id,$location_name,$language_name,$device_name)
    {
    		$sql = "INSERT INTO ercs_banner_advertise_info (account_id,language,location,device) VALUES ('{$acc_id}','{$location_name}','{$language_name}','{$device_name}')";
    		$result = $this->db->query($sql);
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function getStatus($id)
    {
    	$sql = "SELECT * FROM ercs_banner_status where account_id='{$id}'";
    	$result = $this->db->query($sql);
		return $result;
    }
     /**
	 * fetches 
	 *
	 * @return string
	 */
    public function insertAccStatus($acc_id)
    {
    	$sql = "INSERT INTO ercs_banner_status (account_id,status) VALUES ('{$acc_id}','1')";
    	$result = $this->db->query($sql);
    }
}