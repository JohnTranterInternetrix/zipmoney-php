<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
namespace zipMoney\Api;

use zipMoney\Gateway;
use zipMoney\Exception;
use zipMoney\Request;
use zipMoney\Configuration;

class Checkout 
{
  public  $request;

  private $_params = array(
                           "charge", 
                           "currency_code", 
                           "in_store", 
                           "txn_id", 
                           "token",  
                           "order_id",  
                           "cart_url" , 
                           "success_url" , 
                           "cancel_url", 
                           "refer_url" , 
                           "error_url", 
                           "decline_url",  
                           "merchant_id", 
                           "merchant_key", 
                           "order" => array("type" => "\\zipMoney\\Request\\Order"), 
                           "consumer" => array("type" => "\\zipMoney\\Request\\Consumer"), 
                           "billing_address" => array("type" => "\\zipMoney\\Request\\Address"), 
                           "shipping_address"=> array("type" => "\\zipMoney\\Request\\Address"), 
                           "version" => array("type" => "\\zipMoney\\Request\\Version"), 
                           "metadata" => array("type" => "\\zipMoney\\Request\\Metadata")
                           );

  public function __construct()
  {
    $this->request  =  Request::factory($this->_params);
  }
  
  /**
   * Processes the \checkout api request
   *
   * @return \zipMoney\Response
   * @throws \zipMoney\Exception
   */
  public function process()
  {
    if(!$this->validate())
      throw new Exception(implode("\n",$this->_errors));

    return Gateway::api()->checkout($this->request);
  }

  /**
   * Validates the request
   *
   * @return bool
   */
  public function validate()
  {
    $this->_errors = [];

    if(!isset($this->request->charge))
      $this->_errors[] = "charge must be provided";
    else if(!is_bool($this->request->charge))
      $this->_errors[] = "charge must be a boolean value";
    
    if(!isset($this->request->currency_code))
      $this->_errors[] = "currency_code must be provided";
        
    if(!isset($this->request->order_id))
      $this->_errors[] = "order_id must be provided";
        
    if(!isset($this->request->order))
      $this->_errors[] = "order->total must be provided";
        
    if(!isset($this->request->order->shipping_value))
      $this->_errors[] = "order->shipping_value must be provided";
        
    if(!isset($this->request->order->tax))
      $this->_errors[] = "order->tax must be provided";
    
    if($this->_errors)
      return false;
    else 
      return true;
  }
}