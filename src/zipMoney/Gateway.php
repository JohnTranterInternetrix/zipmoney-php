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

use zipMoney\Configuration;
use zipMoney\Api;

class Gateway
{
   private static $_api, $_config, $_webhook, $_express;

 
  /**
   * Returns the zipMoney\Api Object.
   *
   * @return  \zipMoney\Api
   */
  public static function api()
  {
    if(!isset(self::$_api) && !self::$_api instanceof Api)
      self::configure_api();
  return self::$_api;
  }

  /**
   * Instantiates the zipMoney\Api Object.
   *
   */
  public static function configure_api()
  {
    self::$_api = new Api();
  }   
}