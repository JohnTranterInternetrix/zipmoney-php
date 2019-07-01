<?php
namespace zipMoney\Tests;

use zipMoney\Request;
use zipMoney\Exception;
use zipMoney\Configuration;
use zipMoney\Api\Checkout;
use zipMoney\Api\Quote;
use zipMoney\Api\Refund;
use zipMoney\Api\Cancel;
use zipMoney\Api\Capture;
use zipMoney\Api\Settings;
use zipMoney\Api\Configure;
use zipMoney\Api\Query;

require_once dirname(__DIR__).'/vendor/autoload.php';

/**
 * @category  ZipMoney
 * @package   ZipMoney_SDK
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2015 ZipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */

class ApiTest extends TestZipMoney
{

    public function testCheckout()
    {
      $checkout = new Checkout();

      $checkout->request->charge = false;
      $checkout->request->currency_code = "AUD";
      $checkout->request->txn_id = false;
      $checkout->request->order_id =  $this->_current_order_id;
      $checkout->request->in_store = false;

      $checkout->request->cart_url    = "https://your-domain/checkout/cart/";
      $checkout->request->success_url = "https://your-domain/checkout/success/";
      $checkout->request->cancel_url  = "https://your-domain/zipmoney/express/cancel/";
      $checkout->request->error_url   = "https://your-domain/zipmoney/express/error/";
      $checkout->request->refer_url   = "https://your-domain/zipmoney/express/refer/";
      $checkout->request->decline_url = "https://your-domain/zipmoney/express/decline/";


      $order = new Request\Order;
        $order->id = 1;
        $order->tax = 110;
        $order->shipping_tax = 0;
        $order->shipping_value = 10;
        $order->total = 120;

      $order_item = new Request\OrderItem;
        $order_item->id = 10758;
        $order_item->sku  = "item-10758"; 
        $order_item->name = "GoPro Hero3+ Silver Edition - Silver";
        $order_item->price =  110;
        $order_item->quantity = 1; 
      
      $order->detail[] = $order_item;
        
      $order_item = new Request\OrderItem;
        $order_item->id = 10759;
        $order_item->sku  = "item-10759"; 
        $order_item->name = "GoPro Hero3+ Silver Edition - Silver1";
        $order_item->price =  110;
        $order_item->quantity = 1;
      
      $order->detail[] = $order_item;

      $checkout->request->order = $order;

      $billingAddress  = new Request\Address;

        $billingAddress->first_name = "firstname";
        $billingAddress->last_name = "lastname";
        $billingAddress->line1 = "line1";
        $billingAddress->line2 = "line2";
        $billingAddress->country = "Australia";
        $billingAddress->zip = "postcode";
        $billingAddress->city = "Sydney";
        $billingAddress->state = "NSW";
      
      $checkout->request->billing_address  = $billingAddress;
        
      $shippingAddress = new Request\Address;

        $shippingAddress->first_name = "firstname";
        $shippingAddress->last_name = "lastname";
        $shippingAddress->line1 = "line1";
        $shippingAddress->line2 = "line2";
        $shippingAddress->country = "Australia";
        $shippingAddress->zip = "postcode";
        $shippingAddress->city = "Sydney";
        $shippingAddress->state = "NSW";
      
      $checkout->request->shipping_address  = $shippingAddress;

      $consumer  = new Request\Consumer;

        $consumer->first_name = "firstname";
        $consumer->last_name = "lastname";
        $consumer->phone = 0400000000;
        $consumer->email = "test@test.com.au";
        $consumer->gender = "male";
        $consumer->dob = "2016-06-16T15:31:23.8051383+10:00";
        $consumer->title = "mr";

      $checkout->request->consumer  = $consumer;
      
      $checkout->request->version = new Request\Version;
      
      $checkout->request->version->platform = "php";


      try{

        $response      = $checkout->process();
        $responseArray = $response->toArray();
                
        $txn_id  = $responseArray['txn_id'];

        $this->assertTrue($response->isSuccess());                  // Check if the response is success
        $this->assertEquals(201,$response->getStatusCode());        // Check if the response code is 201
        $this->assertTrue(!empty($responseArray['redirect_url']));  // Check if redirect_url is set
        $this->assertTrue(!empty($responseArray['txn_id']));        // Check if txn_id is set
        $this->assertEquals('New', $responseArray['status']);       // Check if status value is 'New'

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }

    public function testQuote()
    {

      $quote = new Quote();

      $quote->request->currency_code = "AUD";
      $quote->request->txn_id        = 2112;
      $quote->request->quote_id      = "91005500";

      $quote->request->cart_url    = "https://your-domain/checkout/cart/";
      $quote->request->success_url = "https://your-domain/checkout/success/";
      $quote->request->cancel_url  = "https://your-domain/zipmoney/express/cancel/";
      $quote->request->error_url   = "https://your-domain/zipmoney/express/error/";
      $quote->request->refer_url   = "https://your-domain/zipmoney/express/refer/";
      $quote->request->decline_url = "https://your-domain/zipmoney/express/decline/";

  
      $order = new Request\Order;
        $order->id = 1;
        $order->tax = 110;
        $order->shipping_tax = 0;
        $order->shipping_value = 10;
        $order->total = 120;

      $order_item = new Request\OrderItem;
        $order_item->id = 10758;
        $order_item->sku  = "item-10758"; 
        $order_item->name = "GoPro Hero3+ Silver Edition - Silver";
        $order_item->price =  110;
        $order_item->quantity = 1; 
      
      $order->detail[] = $order_item;
        
      $order_item = new Request\OrderItem;
        $order_item->id = 10759;
        $order_item->sku  = "item-10759"; 
        $order_item->name = "GoPro Hero3+ Silver Edition - Silver1";
        $order_item->price =  110;
        $order_item->quantity = 1;
      
      $order->detail[] = $order_item;

      $quote->request->order = $order;

      $billingAddress  = new Request\Address;

        $billingAddress->first_name = "firstname";
        $billingAddress->last_name = "lastname";
        $billingAddress->line1 = "line1";
        $billingAddress->line2 = "line2";
        $billingAddress->country = "Australia";
        $billingAddress->zip = "postcode";
        $billingAddress->city = "Sydney";
        $billingAddress->state = "NSW";
      
      $quote->request->billing_address  = $billingAddress;
        
      $shippingAddress = new Request\Address;

        $shippingAddress->first_name = "firstname";
        $shippingAddress->last_name = "lastname";
        $shippingAddress->line1 = "line1";
        $shippingAddress->line2 = "line2";
        $shippingAddress->country = "Australia";
        $shippingAddress->zip = "postcode";
        $shippingAddress->city = "Sydney";
        $shippingAddress->state = "NSW";
      
      $quote->request->shipping_address  = $shippingAddress;

      $consumer  = new Request\Consumer;

        $consumer->first_name = "firstname";
        $consumer->last_name = "lastname";
        $consumer->phone = 0400000000;
        $consumer->email = "test@test.com.au";
        $consumer->gender = "male";
        $consumer->dob = "2016-06-16T15:31:23.8051383+10:00";
        $consumer->title = "mr";

      $quote->request->consumer  = $consumer;

      $quote->request->version = new Request\Version;
      
      $quote->request->version->platform = "php";

      try{
        $response = $quote->process();

        $responseArray = $response->toArray();        
        // print_r($responseArray);

        $this->assertEquals(201,$response->getStatusCode());        // Check if the response code is 201
        $this->assertTrue(!empty($responseArray['redirect_url']));  // Check if redirect_url is set
        $this->assertTrue(!empty($responseArray['quote_id']));      // Check if quote_id is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }

    public function testRefund()
    {

      $refund = new Refund();

      $refund->request->reason = "Test Reason";
      $refund->request->txn_id = 111;
      $refund->request->order_id = "91005501";

      
      $order = new Request\Order;
        $order->id = 1;
        $order->tax = 110;
        $order->shipping_value = 10;
        $order->total = 120;
      
      $refund->request->order = $order;
        

      try{
        $response = $refund->process();
        $responseArray = $response->toArray();
        
        // print_r($responseArray);

        $this->assertFalse($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }

    public function testCancel()
    {

      $cancel = new Cancel();

      $cancel->request->txn_id = 111;
      $cancel->request->order_id = "91005501";

      $order = new Request\Order;
        $order->id = 1;
        $order->tax = 110;
        $order->shipping_value = 10;
        $order->total = 120;
      
      $cancel->request->order = $order;

      try{
        $response = $cancel->process();
        $responseArray = $response->toArray();
        
        // print_r($responseArray);

        $this->assertFalse($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }


    public function testCapture()
    {
      $capture = new Capture();
      
      $capture->request->txn_id = 111;
      $capture->request->order_id = "91005501";

      $order = new Request\Order;
        $order->id = 1;
        $order->tax = 110;
        $order->shipping_value = 10;
        $order->total = 120;
      
      $capture->request->order = $order;


      try{
        $response = $capture->process();
        $responseArray = $response->toArray();
        // print_r($responseArray);

        $this->assertFalse($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }


    public function testQuery()
    {

      $query = new Query();

      $queryOrder = new  Request\QueryOrder;
      $queryOrder->id = 1234;

      $query->request->orders[] = $queryOrder;

      try{

        $response      = $query->process();
        $responseArray = $response->toArray();

        // print_r($responseArray);

        $this->assertTrue($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }


    public function testSettings()
    {

      $settings = new Settings();

      try{

        $response      = $settings->process();
        $responseArray = $response->toArray();
        // print_r($responseArray);

        $this->assertTrue($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }


    public function testConfigure()
    {

      $configure = new Configure();

      $configure->request->base_url  = "http://localhost1/";

      try{

        $response      = $configure->process();
        $responseArray = $response->toArray();

        $this->assertTrue($response->isSuccess());  // Check if redirect_url is set

      } catch (Exception $e){
        print_r($e->getMessage());
      }

    }
} 