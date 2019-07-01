<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
namespace zipMoney\Webhook;

use zipMoney\Configuration;
use zipMoney\Exception;

abstract class Express
{
  const ACTION_RESPONSE_TYPE_GET_SHIPPING_METHODS     = 'shippingmethods';
  const ACTION_RESPONSE_TYPE_GET_QUOTE_DETAILS        = 'quotedetails';
  const ACTION_RESPONSE_TYPE_CONFIRM_SHIPPING_METHOD  = 'confirmshippingmethod';
  const ACTION_RESPONSE_TYPE_CONFIRM_ORDER            = 'confirmorder';
  const ACTION_RESPONSE_TYPE_FINALISE_ORDER           = 'finaliseorder';
  const ACTION_RESPONSE_TYPE_CANCEL_QUOTE             = 'cancelquote';
  
  private $_merchantId  = null;

  private $_merchantKey = null;

  public function __construct()
  {
    $this->_merchantId  = Configuration::$merchant_id;
    $this->_merchantKey = Configuration::$merchant_key;
  }

  /**
   * Matches the passed merchant credentials with the configured ones
   *
   * @param string $merchantId
   * @param string $merchantKey
   * @return bool 
   */
  private function _validateCredentials($merchantId,$merchantKey)
  {
    if($merchantId == $this->_merchantId && $merchantKey == $this->_merchantKey){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Listens for requests and decodes the request params 
   *
   * @param string $action_type
   */
  public function listen($action_type)
  {
    $data = file_get_contents("php://input");

    if (!$data)
      throw new \zipMoney\Exception("Notification parameters cannot be empty");        

    $params = json_decode($data);
    $this->_processRequest($params,$action_type);
  }


  /**
   * Delegates for further processing to proper action handlers based on action_type
   *
   * @param string $action_type
   */
  protected function _processRequest($params,$action_type)
  {
    if(!$this->_validateCredentials($params->merchant_id,$params->merchant_key))
      throw new Exception("Merchant Credentials donot match");

    switch ($action_type) {
      case self::ACTION_RESPONSE_TYPE_GET_SHIPPING_METHODS:
        $this->_actionGetShippingMethods($params);
        break;
      case self::ACTION_RESPONSE_TYPE_GET_QUOTE_DETAILS:
        $this->_actionGetQuoteDetails($params);
        break;
      case self::ACTION_RESPONSE_TYPE_CONFIRM_SHIPPING_METHOD:
        $this->_actionConfirmShippingMethods($params);
        break;
      case self::ACTION_RESPONSE_TYPE_CONFIRM_ORDER:
        $this->_actionConfirmOrder($params);
        break;
      default:
        break;
    }
  }

  /**
   * Adds merchant credentials to the params
   *
   * @param array $params
   */
  protected  function _addApiKeys(&$params)
  {
    if (!isset($params['merchant_id'])) {
      $params['merchant_id'] = $this->_merchantId;
    }

    if (!isset($params['merchant_key'])) { 
      $params['merchant_key'] = $this->_merchantKey;
    }
  }

  /**
   * Prints the json response and stops further execution
   *
   * @param array $params
   */
  public function sendResponse($params)
  {
    if(!isset($params) ||  empty($params))
        throw new  Exception("Error Sending Response. No parameter provided", 1);
    
    //Add api keys to response
    $this->_addApiKeys($params);

    header('Content-Type: application/json');
    die(json_encode($params));
  }

  /**
   * Process get shipping methods call from the api.
   *
   * @param  $params
   */
  protected function _actionGetShippingMethods($params){}
 
  /**
   * Process confirm shipping method call from the api.
   *
   * @param  $params
   */
  protected function _actionConfirmShippingMethods($params){}

  /**
   * Process confirm order  call from the api.
   *
   * @param  $params
   */
  protected function _actionConfirmOrder($params){}

  /**
   * Process finalise orderd call from the api.
   *
   * @param  $params
   */
  protected function _actionFinaliseOrder($params){}

  /**
   * Process get quote details  call from the api.
   *
   * @param  $params
   */
  protected function _actionGetQuoteDetails($params){}
}