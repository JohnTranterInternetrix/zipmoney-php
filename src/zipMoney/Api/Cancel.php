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


class Cancel 
{
  public  $request;

  private $_params = array("txn_id",  "quote_id",  "order_id", "order" , "version", "metadata");

  public function __construct()
  {
    $this->request  =  Klass::factory($this->_params);
  }
  
  /**
   * Processes the \cancel api request
   *
   * @return \zipMoney\Response
   * @throws \zipMoney\Exception
   */
  public function process()
  {
    if(!$this->validate())
      throw new \zipMoney\Exception(implode("\n",$this->_errors));

    return Gateway::api()->cancel($this->request);
  }

  /**
   * Validates the request
   *
   * @return bool
   */
  public function validate()
  {
    $this->_errors = [];
    
    if(!isset($this->request->txn_id))
      $this->_errors[] = "txn_id must be provided";
        
    if(!isset($this->request->order))
      $this->_errors[] = "order must be provided";
        
    if(!isset($this->request->order->id))
      $this->_errors[] = "order->id must be provided";
  
    if(!isset($this->request->order->total))
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