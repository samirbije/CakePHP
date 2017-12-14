<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
  * AdminAdsModel
  * Model for Ads
  * 
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @author		  Samir Prasad <samir@otech.ne.jp>
  * @filesource	  admin/ads_model.php
  * @package 	  Model 
  */
class Ads_model extends MY_Model
{
    var $fields = '';
	var $joins  = array();
	/**
	 * Constructor
	 *
	 * @return adsController
	 */	
    public function __construct()
    {
    	parent::__construct();
        $this->prefix  = 'ercs_';
        $this->_TABLES = array('POPUP'=>$this->prefix.'popup_advertise_info','BANNER'=>$this->prefix.'banner_advertise_info');
    }
	/**
	 * Fetches the poup history  listings
	 *
	 * @return array
	 */
    public function getPopups ($where = NULL, $order_by = NULL, $limit = array('limit' => NULL,'offset' => ''))
    {
        $fields = 'popup_advertise_info.*';
        if($this->fields != '') {
            $fields = $this->fields;
        }
		foreach ($this->joins as $key) {
			$fields = $fields . ',' . $this->_JOINS[$key]['select'];
		}
        $this->db->select($fields);
        $this->db->from($this->_TABLES['POPUP'] . ' popup_advertise_info');
		(! is_null($where)) ? $this->db->where($where) : NULL;
		(! is_null($order_by)) ? $this->db->order_by($order_by) : NULL;

		if( ! is_null($limit['limit'])) {
			$this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : '' ));
		}

		return $this->db->get();
    }
	/**
	 * Counts the page rows
	 *
	 * @return int
	 */
    public function count($where = NULL)
    {
        $this->db->from($this->_TABLES['POPUP'].' popup_advertise_info');
	 (! is_null($where)) ? $this->db->where($where) : NULL;
	    return $this->db->count_all_results();
    }
    
    /**
	 * Fetches the poup history  listings
	 *
	 * @return array
	 */
    public function getBanner ($where = NULL, $order_by = NULL, $limit = array('limit' => NULL,'offset' => ''))
    {
        $fields = 'banner_advertise_info.*';
        if($this->fields != '') {
            $fields = $this->fields;
        }
		foreach ($this->joins as $key) {
			$fields = $fields . ',' . $this->_JOINS[$key]['select'];
		}
        $this->db->select($fields);
        $this->db->from($this->_TABLES['BANNER'] . ' banner_advertise_info');
		(! is_null($where)) ? $this->db->where($where) : NULL;
		(! is_null($order_by)) ? $this->db->order_by($order_by) : NULL;

		if( ! is_null($limit['limit'])) {
			$this->db->limit($limit['limit'], ( isset($limit['offset']) ? $limit['offset'] : '' ));
		}

		return $this->db->get();
    }
	/**
	 * Counts the page rows
	 *
	 * @return int
	 */
    public function bannerCount($where = NULL)
    {
        $this->db->from($this->_TABLES['BANNER'].' banner_advertise_info');
	 (! is_null($where)) ? $this->db->where($where) : NULL;
	    return $this->db->count_all_results();
    }
}