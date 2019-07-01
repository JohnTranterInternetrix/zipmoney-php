<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

namespace zipMoney;

class Request 
{
  
  /**
   * Factory method to instantiate the request object with given parameters
   *
   * @param array $params   
   * @return Request
   */
  public static function factory($params = null)
  {
    $class = new self;

    foreach ($params as $property => $propertyConfig) {
      if(is_array($propertyConfig)){
        if(isset($propertyConfig["type"])){
          $class->$property = new $propertyConfig["type"];
        } else if(isset($propertyConfig["value"])){
          $value  = $propertyConfig["value"];
        } 
      } else {
        $class->$propertyConfig = null;
      }
    }

  return $class;
  }
  
  /**
   * Checks if the property is set
   *
   * @param array $property   
   * @return bool
   */
  public function __isset($property){
    return isset($this->$property);
  } 
}
