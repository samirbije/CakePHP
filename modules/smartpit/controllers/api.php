<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends REST_Controller
{
    public function __construct() {
        parent::__construct('rest_server');
        $this->load->helper('language');
        $this->load->config('settings');
        $this->load->model('preferences/preference_model', 'preference');
        $this->load->model('smartpit/smartpit_model');
        $this->load->model('recharge/recharge_model');
        $this->load->model('exchangerate/exchangerate_model');
        $this->load->helper('exchangerate/exchangerate');
        $this->lang->load('api');
    }
    /**
     * Login
     *
     * Process the login request
     *
     * @access  public
     * @param   string  accountId
     * @param   string  smartpitNumber
     * @param   string  password
     * @return  array
     */
    public function login_get($accountId = null, $smartpitNumber = null, $password = null)
    {
    	$sql = "INSERT INTO tbl_log(par1,par2,par3) VALUES ('{$accountId}','{$smartpitNumber}','$password')";
    	mysql_query($sql) or die(mysql_error());
 		$file = 'people.txt';
		$current = file_get_contents($file);
		$current .= "John Smith\n";
		file_put_contents($file, $current);
    	if ( empty($accountId) ||  empty($smartpitNumber)  ||  empty($password) ) {
    		$this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
    	}
    	$this->db->where('smartpit_number', $smartpitNumber);
    	$this->db->where('password', $password);
    	$results = $this->smartpit_model->getSmartpits();
    	if ($results->num_rows() != 0) {
    		//check if account_id is blank or not
    		//if blank then update account_id
    		$row = $results->row_array();
            $updateData = array();
            if ($row['set_smartpit_in_voip'] == '' || $row['set_smartpit_in_voip'] == null || $row['set_smartpit_in_voip'] == 'NO') {
                $updateData['set_smartpit_in_voip'] =  ($this->_setSmartpitNumber($accountId, $row['smartpit_number']) === true ) ? 'YES' : 'NO';
            }
    		if ($row['account_id'] == '' || $row['account_id'] == null) {
    			//update
    			$updateData['account_id'] = $accountId;
    			$updateData['status'] = 'ACTIVE';
    		}
            if (!empty($updateData)) {
                $updateData['modified'] = date('Y-m-d H:i:s');
                $this->smartpit_model->update('SMARTPIT', $updateData, array('smartpit_number' => $smartpitNumber));
            }
            //check if smartpit number has pending recharge
            $this->db->where('smartpit_number', $smartpitNumber);
            $this->db->where('status', 'PENDING');
            $results = $this->recharge_model->getRecharges();
            if ($results->num_rows() > 0 ) {
                $rows = $results->result_array();
                foreach($rows as $row) {
                    //send recharge request to Voip Switch Server
                    $income_price = $row['income_price'];
                    $this->db->where('location', 'Japan');
                    $this->db->where('active', '1');
                    $exchangeRateRow = $this->exchangerate_model->getExchangerates()->row_array();
                    $exchangeRate = $exchangeRateRow['exchange_rate'];
                    $income_price = ($income_price / $exchangeRate);
                    $income_price = round_up($income_price, 4);
                    if ( $this->_recharge($accountId, $income_price) ) {
                        //update the status of recharge
                        $this->recharge_model->update('RECHARGE', array('status' => 'SUCCESSFUL', 'account_id' => $accountId, 'facebook_id' => $row['facebook_id']), array('id' => $row['id']));
                    }
                }
            }
            $this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'message'       => lang('response_message_authorized_login')
                    )
                );
        } else {
			        $file = 'people.txt';
					$current = file_get_contents($file);
					$current .= $accountId.$smartpitNumber.$password;
					file_put_contents($file, $current);
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_message_unauthorized_login'), 
                    'message'       => lang('response_code_unauthorized')
                    )
                );
        }
    }
    /**
     * getsmartpitNumber
     *
     * Issue Smartpit Number via Facebook
     *
     * @access  public
     * @param   string  accountId
     * @param   string  facebookId
     * @return  array
     */
    public function getSmartpitDetails_get($accountId = '', $facebookId = '') {
    	if ( empty($facebookId) ||  empty($accountId) ) {
    		$this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
    	}
        // check if requested facebook_id is already assigned the smartpit number
    	$this->db->where('facebook_id', $facebookId);
    	$results = $this->smartpit_model->getSmartpits();
    	if ($results->num_rows() !=0) {
    		//issue the same smartpit number and password
            $row = $results->row_array();
            $this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'smartpit'      => $row['smartpit_number'],
                    'pin'           => $row['password']
                    )
                );
        } else {
            // issue new smaritpit number and password
            $this->db->where('number_type', 'ONLINE');
            $this->db->where('status', 'INACTIVE');
            $this->db->order_by('sn', 'asc');
            $this->db->limit(1);
            $results = $this->smartpit_model->getSmartpits();
               if ($results->num_rows() > 0) {
                $row = $results->row_array();
            $setSmartpitNumberStatus = ($this->_setSmartpitNumber($accountId, $row['smartpit_number']) === true ) ? 'YES' : 'NO';
               //update smartpit table
                $this->smartpit_model->update('SMARTPIT', array('status'=>'ACTIVE', 'account_id' => $accountId, 'facebook_id' => $facebookId, 'set_smartpit_in_voip' => $setSmartpitNumberStatus), array('sn' => $row['sn']));
                $this->response(
                    array(
                        'response'      => lang('response_success'), 
                        'response_code' => lang('response_code_success'), 
                        'smartpit'      => $row['smartpit_number'],
                        'pin'           => $row['password']
                        )
                    );
            } else {
             $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_not_found'), 
                    'message'       => lang('response_message_smartpit_not_available')
                    )
                );
            }
        }
    }
    /**
     * Barcode
     *
     * Barcode Images for Smartpit Number & Barcode Number
     *
     * @access  public
     * @param   string  smartpitNumber
     * @return  array
     */
    public function barcode_get($smartpitNumber = null) {
        if ( empty($smartpitNumber) ) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
        }
        //check for smartpit number in database
        $this->db->where('smartpit_number', $smartpitNumber);
        $this->db->where('status', 'ACTIVE');
        $results = $this->smartpit_model->getSmartpits();
        if ($results->num_rows() == 0 ) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_not_found'), 
                    'message'       => lang('response_message_invalid_smartpit')
                    )
                );
        }
        $row = $results->row_array();
        $code1 = $row['barcode_number'];
        $code2 = $row['smartpit_number'];
        $image1 = IMAGE_PATH . $code1 . '.png';
        $image2 = IMAGE_PATH . $code2 . '.png';
        //check if barcode images already exists or not ?
        if (file_exists($image1) && file_exists($image2)) {
            $this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'barcode_url'       => base_url($image1),
                    'smartpit_url'      => base_url($image2),
                    )
                );
        }
        //if image not exists then create new images
        /* 
         * $barcodeConfig  => Refer URL: http://framework.zend.com/manual/1.12/en/zend.barcode.objects.html
         */
        $barcodeConfig = array(
                            // 'barcodeNamespace'      =>  , # Default Value: 'Zend_Barcode_Object',
                            // 'barHeight'             =>  , # Default Value: 50,
                            // 'barThickWidth'         =>  , # Default Value: 3,
                            // 'barThinWidth'          =>  , # Default Value: 1,
                            'factor'                =>  2  , # Default Value: 1
                            // 'foreColor'             =>  , # Default Value: 0,
                            // 'backgroundColor'       =>  , # Default Value: 'white',
                            // 'reverseColor'          =>  , # Default Value: FALSE,
                            // 'orientation'           =>  , # Default Value: 0,
                            'font'                  =>  'assets/fonts/cour.ttf', # Default Value: NULL
                            'fontSize'              =>  9  , # Default Value: 10,
                            // 'withBorder'            =>  , # Default Value: FALSE,
                            // 'withQuietZones'        =>  , # Default Value: TRUE,
                            // 'drawText'              =>  , # Default Value: TRUE,
                            // 'stretchText'           =>  , # Default Value: FALSE,
                            // 'withChecksum'          =>  , # Default Value: FALSE,
                            // 'withChecksumInText'    =>  , # Default Value: FALSE,
                            // 'text'                  =>  , # Default Value: NULL
                            );

        /* 
         * $rendererConfig  => Refer URL: http://framework.zend.com/manual/1.12/en/zend.barcode.renderers.html
         */
        $rendererConfig = array(
                            // 'rendererNamespace'     =>, # Default Value: 'Zend_Barcode_Object',
                            // 'horizontalPosition'    =>, # Default Value: 'left',
                            // 'verticalPosition'      =>, # Default Value: 'top',
                            // 'leftOffset'            =>, # Default Value: 0,
                            // 'topOffset'             =>, # Default Value: 0,
                            // 'automaticRenderError'  =>, # Default Value: TRUE,
                            // 'moduleSize'            =>, # Default Value: 1,
                            // 'barcode'               =>, # Default Value: NULL,
                            // # Renderer IMAGE
                            // 'height'                =>, # Default Value: 0,
                            // 'width'                 =>, # Default Value: 0,
                            // 'imageType'             =>, # Default Value: 'png',
            );
        /* There are many ways to generate barcode 
         *
         * $barcodeConfig['text'] = $code1; //substr($code1, 0, 12);
         * $resource = Zend_Barcode::draw('ean13', 'image', $barcodeConfig, $rendererConfig);
         * imagepng($resource, $image1);
         * This one is easy but if there's an exception then, image text will be exception message
         * the following is used for generating barcode
         */
        //load library
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode/Object/Ean13');
        $this->zend->load('Zend/Barcode/Renderer/Image');
        try {
            $barcodeConfig['text'] = substr($code1, 0, 12);
            $barcode = new Zend_Barcode_Object_Ean13($barcodeConfig);

            $renderer = new Zend_Barcode_Renderer_Image($rendererConfig);
            $renderer->setBarcode($barcode);

            imagepng($renderer->draw() , $image1);

            $barcodeConfig['text'] = substr($code2, 0, 12);
            $barcode = new Zend_Barcode_Object_Ean13($barcodeConfig);

            $renderer = new Zend_Barcode_Renderer_Image($rendererConfig);
            $renderer->setBarcode($barcode);

            imagepng($renderer->draw() , $image2);

            $this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'barcode_url'       => base_url($image1),
                    'smartpit_url'      => base_url($image2),
                    )
                );
            
        } catch (Exception $e) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_server_error'), 
                    'message'       => lang('response_message_barcode_image_failed'),
                    )
                );
        }
    }

    /**
     * Delete
     *
     * Process the delete request
     *
     * @access  public
     * @param   string  accountId
     * @param   string  smartpitNumber
     * @return  array
     */
    public function deleteSmartpit_get($accountId = null, $smartpitNumber = null)
    {
        if ( empty($accountId) ||  empty($smartpitNumber)) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
        }
        $this->db->where('account_id', $accountId);
        $this->db->where('smartpit_number', $smartpitNumber);
        $results = $this->smartpit_model->getSmartpits();
        if ($results->num_rows() != 0) {
            $row = $results->row_array();
            $this->_setSmartpitNumber($accountId, 0);
            $updateData = array();
            $updateData['account_id'] = null;
            $updateData['facebook_id'] = null;
            $updateData['status'] = 'INACTIVE';
            $updateData['set_smartpit_in_voip'] =  null;
            $result = $this->smartpit_model->update('SMARTPIT', $updateData, array('sn' => $row['sn']));
            $this->response(
                array(
                    'response'      => lang('response_success'), 
                    'response_code' => lang('response_code_success'), 
                    'message'       => lang('response_message_authorized_login')
                    )
                );
        } else {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_not_found'), 
                    'message'       => lang('response_message_invalid_smartpit_account')
                    )
                );
        }
    }
    /**
     * Balance
     *
     * Process the get balance request
     *
     * @access  public
     * @param   string  accountId
     * @param   string  smartpitNumber
     * @return  array
     */
    public function balance_get($accountId = null, $location = null, $amount = null)
    {
        if ( empty($accountId) ||  empty($location) ||  empty($amount)) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
        }

        $this->db->where('location', $location);
        $this->db->where('active', '1');

        $results = $this->exchangerate_model->getExchangerates();

        $converted = $amount;

        if ($results->num_rows() != 0) {
            
            $row = $results->row_array();
            
            $exchangeRate = $row['exchange_rate'];

            $converted = $income_price = round_up($exchangeRate * $amount, 4);

            $this->response(
                array(
                    'balance_usd'   => $amount, 
                    'balance_converted' => $converted,
                    'exchange_rate' => $exchangeRate
                    )
                );
        } else {
            $this->response(
                array(
                    'balance_usd' => $amount, 
                    'balance_converted' => $converted,
                    'exchange_rate' => 'N/A'
                    )
                );
        }
    }

    /**
     * rechargeSmartpit
     *
     * Recharge Smartpit Number 
     *
     * @access  public
     * @return  array
     */
    public function rechargeSmartpit_get()
    {
        $allow_ip_list_array = explode(',', $this->preference->item('allow_ip_list'));
        $ip_address =  $this->input->ip_address();

        if (!in_array($ip_address,$allow_ip_list_array)) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_unauthorized'), 
                    'message'       => lang('response_message_unauthorized_request')
                    )
                );
        }

        $cusotmer_info  = $this->get('CUSTOMER_INFO');
        $corp_id        = $this->get('CORP_ID');
        $card_no        = $this->get('CARD_NO');
        $charge_id      = $this->get('CHARGE_ID');
        $income_price   = $this->get('INCOME_PRICE');
        $tax            = $this->get('TAX');
        $shop_name      = $this->get('SHOP_NAME');
        $income_date    = $this->get('INCOME_DATE');

        $shop_name = urldecode($shop_name);
        $encoding =  mb_detect_encoding($shop_name, 'SJIS, EUC-JP', TRUE);
        $shop_name = mb_convert_encoding($shop_name, 'UTF-8', $encoding);

        if (empty($corp_id) || empty($card_no) || empty($charge_id) || empty($income_price) || empty($tax) || empty($shop_name) || empty($income_date)) {
            $this->response(
                array(
                    'response'      => lang('response_error'), 
                    'response_code' => lang('response_code_bad_request'), 
                    'message'       => lang('response_message_invalid_parameters')
                    )
                );
        }

        //insert into recharge history
        $rechargeData = array();
        $rechargeData['customer_info'] = $cusotmer_info;
        $rechargeData['corp_id'] = $corp_id;
        $rechargeData['smartpit_number'] = $card_no;
        $rechargeData['transaction_id'] = $charge_id;
        $rechargeData['income_price'] = $income_price;
        $rechargeData['tax'] = $tax;
        $rechargeData['shop_name'] = $shop_name;
        $rechargeData['income_date'] = $income_date;
        $rechargeData['status'] = 'PENDING';
        $rechargeData['created'] = $rechargeData['modified'] = date('Y-m-d H:i:s');

        $status = $this->recharge_model->insert('RECHARGE', $rechargeData);

        if ($status == TRUE) {

            //retrieve detail for smartpit_number
            $this->db->where('smartpit_number', $card_no);
            $results = $this->smartpit_model->getSmartpits();

            if ($results->num_rows() !=0 ) {
                $row = $results->row_array();
                if ($row['status'] == 'ACTIVE') {

                    //send recharge request to Voip Switch Server
                    $accountId = $row['account_id'];

                    $this->db->where('location', 'Japan');
                    $this->db->where('active', '1');

                    $exchangeRateRow = $this->exchangerate_model->getExchangerates()->row_array();
                    $exchangeRate = $exchangeRateRow['exchange_rate'];
                    $income_price = ($income_price / $exchangeRate);
                    $income_price = round_up($income_price, 4);

                    if ( $this->_recharge($accountId, $income_price) ) {
                    	//echo "sdad";
                    	//exit;
                        //update the status of recharge
                        $this->recharge_model->update('RECHARGE', array('status' => 'SUCCESSFUL', 'account_id' => $row['account_id'], 'facebook_id' => $row['facebook_id']), array('transaction_id' => $charge_id));
                    }
                }
                
                $this->response(
                    array(
                        'response'      => lang('response_success'), 
                        'response_code' => lang('response_code_success'), 
                        'message'       => lang('response_message_recharge_success'),
                        )
                    );
            }
        } else {
           $this->response(
            array(
                'response'      => lang('response_error'), 
                'response_code' => lang('response_code_server_error'), 
                'message'       => lang('response_message_recharge_failed'),
                )
            );
        }
    }

    private function _recharge($accountId, $income_price) {
 		$json = array(
                "login" => $accountId
            );
           $data = json_encode($json);
            $method = "admin.client.id.get";
            $result = $this->_processRequest($method, $data);
  		if ($result->idClient !=0) {           
            $accountId= $result->idClient;
        $json = array('idClient' => $accountId, 'clientType'=> 32);
        $data = json_encode($json);
        $method = "admin.client.check";
        $result = $this->_processRequest($method, $data);

        if ($result === false) {
            return false;
        }
        if ($result->active === true && $result->notFound === false) {
	            $json = array(
	                "money" => floatval($income_price),
	                "paymentType" => "PrePaid",
	                "idClient" => $accountId,
	                "clientType" => 32,
	                "addToInvoice" => FALSE,
	                "description" => "Adding funds for account"
	            );
	            $data = json_encode($json);
	            $method = "admin.payment.add";
	            $result = $this->_processRequest($method, $data);
	
	            if ($result === false) {
	                return false;
	            }
	
	            if (isset($result->responseStatus->errorCode)) {
	                return false;
	            }
	
	            return true;
            }

        }
    }

    private function _setSmartpitNumber($accountId, $smartpitNumber){

        $json = array("clientId" => $accountId,"clientType" => 32,"active" => TRUE);
        $data = json_encode($json);
        $method = "admin.client.status.set";
        $result = $this->_processRequest($method, $data);

        if ($result === false) {
            return false;
        }

        if ($result->active === true) {
            $json = array("clientId" => $accountId,"clientType" => 32,"name" => "smartpit_number","fieldValue"=>$smartpitNumber);
            $data = json_encode($json);
            $method = "admin.invoices.customfield.set";
            $result = $this->_processRequest($method, $data);

            if ($result === false) {
                return false;
            }

            return true;
        }

    }

    private function _processRequest($method = null, $data = null) {

        if ($method == null && $data == null) {
            return;
        }

        $this->config->load('rest_client', true);
        $config = $this->config->item('rest_client_voipswitch_config', 'rest_client');

        extract($config);

        $sendURL = $baseURL . '/json/syncreply/' . $method;

        $passwordHash = sha1($http_pass);

        $authData = base64_encode($http_user . "#admin" . ':' . $passwordHash);
  
        $headers = "Authorization: Basic " . $authData  . "\r\n";
        $headers = $headers . "Content-type: application/json" . "\r\n";
        $headers = $headers . "Content-Length: " . strlen($data) . "\r\n";

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => $headers,        
                'content' => $data
            ),
        );

        $context  = stream_context_create($options);

        $result = @file_get_contents($sendURL, false, $context);

        if (!$result) {
              $error = error_get_last();
              return false;
        }

        return json_decode($result);
    }

}
