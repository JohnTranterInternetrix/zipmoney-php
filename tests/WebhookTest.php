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

class WebhookTest extends TestZipMoney
{
    


    public function testWebhookValidSubscription()
    {  

      if(!$this->webhook_endpoint)
        return;

      $ch = curl_init( $this->webhook_endpoint );
      # Setup request to send json via POST.
      $payload =  '{
              "Type" : "SubscriptionConfirmation",
              "MessageId" : "fe202b5d-43e1-4f58-8d2b-b14a46de3e70",
              "Token" : "2336412f37fb687f5d51e6e241d59b68cca49906632685027c0c87fb03853b30e8c01db85e702587e2eecb17c042d4079f74cc572e3bfbc416798164e9b158bb1f7a3be095a8bbbce75c6de9133346d139ad1389b9323810cf9b1f453f2f19f27104f3b487d4ef6941696a8f6c5ff1d39661fc04c9dc9c88eae44a9af63003eaa97bd67043acbc242289f15d19d1294f",
              "TopicArn" : "arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000",
              "Message" : "You have chosen to subscribe to the topic arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000.\nTo confirm the subscription, visit the SubscribeURL included in this message.",
              "SubscribeURL" : "https://sns.ap-southeast-2.amazonaws.com/?Action=ConfirmSubscription&TopicArn=arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000&Token=2336412f37fb687f5d51e6e241d59b68cca49906632685027c0c87fb03853b30e8c01db85e702587e2eecb17c042d4079f74cc572e3bfbc416798164e9b158bb1f7a3be095a8bbbce75c6de9133346d139ad1389b9323810cf9b1f453f2f19f27104f3b487d4ef6941696a8f6c5ff1d39661fc04c9dc9c88eae44a9af63003eaa97bd67043acbc242289f15d19d1294f",
              "Timestamp" : "2017-01-17T03:41:51.262Z",
              "SignatureVersion" : "1",
              "Signature" : "PgtTp8ZS+P8PQ8UddsxNipxyUlFAmzWv2lkucObNU1Kd+3VFql2/Mo7rq80e9+t/PSdYt3mwvxt3G8kTD7C4+9QombbarhMPXIAK5YKu/nBa8u147dY7mQJMJpa8MJFlaRDdiZOnzJoOA2uSpDTR6uAvTXCwN1pXbEmUCpICFZ++g6lguM6JwHb4CSXYTJ7uxJUi36M71zyohWztVaNHpwJtgtFBOOUt3vjEzZusPUDaZhYyALqfmdkAL3f/8kFCzcpA2CDe6GHYNhq8e2eLWhtnqd7OHk8K4uHOUh+uBoNV71fmUE1FZMoRVwLT7g87B86XOD0X8Tg5jog8uHPu/Q==",
              "SigningCertURL" : "https://sns.ap-southeast-2.amazonaws.com/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem"
            }';
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
      curl_close($ch);
      $this->assertEquals(200,$status);
    } 

    public function testWebhookInValid()
    {
      if(!$this->webhook_endpoint)
        return ;

      $ch = curl_init( $this->webhook_endpoint  );
      # Setup request to send json via POST.
      $payload =  '{
              "Type" : "SubscriptionConfirmation",
              "MessageId" : "fe202b5d-43e1-4f58-8d2b-b14a46de3e70",
              "Token" : "2336412f37fb687f5d51e6e241d59b68cca49906632685027c0c87fb03853b30e8c01db85e702587e2eecb17c042d4079f74cc572e3bfbc416798164e9b158bb1f7a3be095a8bbbce75c6de9133346d139ad1389b9323810cf9b1f453f2f19f27104f3b487d4ef6941696a8f6c5ff1d39661fc04c9dc9c88eae44a9af63003eaa97bd67043acbc242289f15d19d1294f",
              "TopicArn" : "arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000",
              "Message" : "You have chosen to subscribe to the topic arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000.\nTo confirm the subscription, visit the SubscribeURL included in this message.",
              "SubscribeURL" : "https://sns.ap-southeast-2.amazonaws.com/?Action=ConfirmSubscription&TopicArn=arn:aws:sns:ap-southeast-2:381371729123:Sagar-s-Woocommerce-Local-4-11564-100000&Token=2336412f37fb687f5d51e6e241d59b68cca49906632685027c0c87fb03853b30e8c01db85e702587e2eecb17c042d4079f74cc572e3bfbc416798164e9b158bb1f7a3be095a8bbbce75c6de9133346d139ad1389b9323810cf9b1f453f2f19f27104f3b487d4ef6941696a8f6c5ff1d39661fc04c9dc9c88eae44a9af63003eaa97bd67043acbc242289f15d19d1294f",
              "Timestamp" : "2017-01-17T03:41:51.262Z",
              "SignatureVersion" : "1",
              "Signature" : "sPgtTp8ZS+P8PQ8UddsxNipxyUlFAmzWv2lkucObNU1Kd+3VFql2/Mo7rq80e9+t/PSdYt3mwvxt3G8kTD7C4+9QombbarhMPXIAK5YKu/nBa8u147dY7mQJMJpa8MJFlaRDdiZOnzJoOA2uSpDTR6uAvTXCwN1pXbEmUCpICFZ++g6lguM6JwHb4CSXYTJ7uxJUi36M71zyohWztVaNHpwJtgtFBOOUt3vjEzZusPUDaZhYyALqfmdkAL3f/8kFCzcpA2CDe6GHYNhq8e2eLWhtnqd7OHk8K4uHOUh+uBoNV71fmUE1FZMoRVwLT7g87B86XOD0X8Tg5jog8uHPu/Q==",
              "SigningCertURL" : "https://sns.ap-southeast-2.amazonaws.com/SimpleNotificationService-b95095beb82e8f6a046b3aafc7f4149a.pem"
            }';
      curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
      curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
      $result = curl_exec($ch);     
      $status = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
      curl_close($ch);
      $this->assertEquals(404,$status);
      $this->assertEquals("Invalid Notification. Cannot validate the signature.",$result);
    }

} 