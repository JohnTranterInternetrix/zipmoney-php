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

class Query 
{
  public  $request;

  private $_params = array("orders", "version", "metadata");

  public function __construct()
  {
    $this->request  =  Klass::factory($this->_params);
  }
  
  /**
   * Processes the \query api request
   *
   * @return \zipMoney\Response
   * @throws \zipMoney\Exception
   */
  public function process()
  {
    
    if(!$this->validate())
      throw new Exception(implode("\n",$this->_errors));

    return Gateway::api()->query($this->request);
  }

  /**
   * Validates the request
   *
   * @return bool
   */
  public function validate()
  {
    $this->_errors = [];
    
    if(!count($this->request->orders))
      $this->_errors[] = "orders must be provided";

    foreach ($this->request->orders as $key => $item) {
      if(!isset($item->id))
        $this->_errors[] = "orders[$key].id must be provided";
    }
        
    if($this->_errors)
      return false;
    else 
      return true;
  }
}