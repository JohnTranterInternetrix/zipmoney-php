<?php
namespace zipMoney\Tests;

use \zipMoney\Api;
use \zipMoney\Configuration;

require_once dirname(__DIR__).'/vendor/autoload.php';
/**
 * @category  ZipMoney
 * @package   ZipMoney_SDK
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2015 ZipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

class TestZipMoney extends  \PHPUnit_Framework_TestCase
{
  protected $_current_order_id;
  
  protected $_txn_id;

  protected $webhook_endpoint = null;

  protected $express_endpoint = null;

  public function setUp(){ 

    $config = require "Config.php";

    if(empty($config["merchant_id"]))
      throw new \Exception("Merchant Id is required");

    if(empty($config["merchant_key"])) 
      throw new \Exception("Merchant Key is required");

    Configuration::$merchant_id  = $config["merchant_id"];
    Configuration::$merchant_key = $config["merchant_key"];
    Configuration::$environment  = $config["environment"]?$config["environment"]:"test";

    if(isset($config["webhook_endpoint"]))
      $this->webhook_endpoint = $config["webhook_endpoint"];
    
    if(isset($config["express_endpoint"]))
      $this->express_endpoint = $config["express_endpoint"];
  

    $this->_current_order_id = rand(10000,9999999);

  }


}