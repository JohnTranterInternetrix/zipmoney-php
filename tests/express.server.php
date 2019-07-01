<?php

error_reporting(E_ALL);
ini_set("display_errors",1);


use zipMoney\Request;
use zipMoney\Exception;
use zipMoney\Configuration;
use zipMoney\Webhook\Express;


require_once '../vendor/autoload.php';
  
$config = require_once "Config.php";

if(empty($config["merchant_id"]))
  throw new \Exception("Merchant Id is required");

if(empty($config["merchant_key"])) 
  throw new \Exception("Merchant Key is required");

Configuration::$merchant_id  = $config["merchant_id"];
Configuration::$merchant_key = $config["merchant_key"];
Configuration::$environment  = $config["environment"]?$config["environment"]:"test";

class ZipMoneyExpress extends Express
{
    protected function _actionGetQuoteDetails($params)
    {
      $response = array("_actionGetQuoteDetails");

      $this->sendResponse($response);

    }


    protected function _actionGetShippingMethods($params)
    {
      $response = array("_actionGetShippingMethods");

      $this->sendResponse($response);

    }

    protected function _actionConfirmShippingMethods($params)
    {
      $response = array("_actionConfirmShippingMethods");

      $this->sendResponse($response);

    }

    protected function _actionConfirmOrder($params)
    {
      $response = array("_actionConfirmOrder");

      $this->sendResponse($response);

    }

}

try{

  $expressTest  = new ZipMoneyExpress();

  $action = trim($_GET['action']);
  $expressTest->listen($action);

 


} catch(Exception $e){
  echo $e->getMessage();
}