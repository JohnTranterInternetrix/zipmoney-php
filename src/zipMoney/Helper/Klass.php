<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
namespace zipMoney\Helper;

class Klass
{
  /**
   * Instantiates itself with the properties passed as the params
   *
   * @param  array $params
   * @return object
   */
  public static function factory($params = null)
  {
    $class = new self;
    foreach ($params as $value) {
        $class->$value = null;
    }

  return $class;
  }

  /**
   * Clones the empty blueprint-class ($this) into the new data $class.
   *
   * @return object
   */
  public function create()
  {
    $class = clone $this;

    // Populate the new class.
    $properties = array_keys((array) $class);
    foreach (func_get_args() as $key => $value) {
      if (!is_null($value)) {
        $class->$properties[$key] = $value;
      }
    }

    // Return the populated class.
    return $class;
  }
  
  /**
   * Checks if the given property exists for the class 
   *
   * @return bool
   */
  public function __isset($property){
    return isset($this->$property);
  } 
}