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
use zipMoney\Helper\Klass;

class Quote 
{
  public  $request;

  private $_params = array("currency_code", "txn_id", "token",  "quote_id",  "checkout_source", "cart_url" , "success_url" , "cancel_url", "refer_url" , "error_url", "decline_url",  "merchant_id", 
                           "merchant_key", "order", "consumer", "billing_address", "shipping_address", "version", "metadata");

  public function __construct()
  {
    $this->request  =  Klass::factory($this->_params);
  }
 
  /**
   * Processes the \quote api request
   *
   * @return \zipMoney\Response
   * @throws \zipMoney\Exception
   */
  public function process()
  {
    if(!$this->validate())
      throw new Exception(implode("\n",$this->_errors));

    return Gateway::api()->quote($this->request);
  }


  /**
   * Validates the request
   *
   * @return bool
   */
  public function validate()
  {
    $this->_errors = [];

    
    if(!isset($this->request->currency_code))
      $this->_errors[] = "currency_code must be provided";
        
    if(!isset($this->request->quote_id))
      $this->_errors[] = "quote_id must be provided";
        
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