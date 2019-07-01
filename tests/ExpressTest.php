<?php
namespace zipMoney\Tests;

use zipMoney\Request;
use zipMoney\Exception;
use zipMoney\Configuration;
use zipMoney\Webhook\Webhook;

require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * @category  ZipMoney
 * @package   ZipMoney_SDK
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2015 ZipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

class ExpressTest extends TestZipMoney
{



    public function testGetQuoteDetails()
    {  

      if(!$this->express_endpoint)
        return;

      $ch = curl_init( $this->express_endpoint . "?action=quotedetails");
      $payload = array("merchant_id"=>4,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals(200,$status);
    
    } 

    public function testParametersValidation()
    { 
      if(!$this->express_endpoint)
        return;

      $ch = curl_init( $this->express_endpoint . "?action=quotedetails");
      $payload = array("merchant_id"=>4,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals("Notification parameters cannot be empty",$result);
      $this->assertEquals(200,$status);
    } 


    public function testMechantIdValid()
    { 
      if(!$this->express_endpoint)
        return;

      $ch = curl_init( $this->express_endpoint . "?action=quotedetails");
      $payload = array("merchant_id"=>45,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals("Merchant Credentials donot match",$result);
      $this->assertEquals(200,$status);
    
    } 

    public function testShippingMethods()
    {
      
      if(!$this->express_endpoint)
        return;

      $ch = curl_init( $this->express_endpoint . "?action=shippingmethods");
      $payload = array("merchant_id"=>4,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals(200,$status);
    
    } 
    
    public function testConfirmShippingMethods()
    { 

      if(!$this->express_endpoint)
        return;

    
      $ch = curl_init( $this->express_endpoint . "?action=confirmshippingmethod");
      $payload = array("merchant_id"=>4,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals(200,$status);
    
    } 

    public function testConfirmOrder()
    { 

      if(!$this->express_endpoint)
        return;

    
      $ch = curl_init( $this->express_endpoint . "?action=confirmorder");
      $payload = array("merchant_id"=>4,"merchant_key"=>"4mod1Yim1GEv+D5YOCfSDT4aBEUZErQYMJ3EtdOGhQY=");
     
      curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($payload) );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals(200,$status);
    
    } 
} 