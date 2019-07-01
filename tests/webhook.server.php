<?php

error_reporting(E_ALL);
ini_set("display_errors",1);


use zipMoney\Request;
use zipMoney\Exception;
use zipMoney\Configuration;
use zipMoney\Webhook\Webhook;


require_once '../vendor/autoload.php';
 
$config = require_once "Config.php";

if(empty($config["merchant_id"]))
  throw new \Exception("Merchant Id is required");

if(empty($config["merchant_key"])) 
  throw new \Exception("Merchant Key is required");

Configuration::$merchant_id  = $config["merchant_id"];
Configuration::$merchant_key = $config["merchant_key"];
Configuration::$environment  = $config["environment"]?$config["environment"]:"test";

class ZipMoneyWebHook extends Webhook
{
    
}


try{
  $webhookTest  = new ZipMoneyWebHook();
  $webhookTest->listen();
} catch(Exception $e){
  echo $e->getMessage();
}