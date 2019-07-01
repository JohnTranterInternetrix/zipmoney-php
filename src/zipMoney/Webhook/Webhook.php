<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
namespace zipMoney\Webhook;

use zipMoney\Configuration;
use zipMoney\Exception;

/*
 *  Event Types
-----------------------
    authorise_succeeded
    authorise_failed
    authorise_under_review
    cancel_succeeded
    cancel_failed
    capture_succeeded
    capture_failed
    refund_succeeded
    refund_failed
    order_cancelled
    charge_succeeded
    charge_failed
*/


abstract class Webhook
{
  
  private $_merchantId = null;

  private $_merchantKey = null;

  private $_hostPattern = '/^sns\.[a-zA-Z0-9\-]{3,}\.amazonaws\.com(\.cn)?$/';
  
  const SIGNATURE_VERSION_1 = '1';

  const EVENT_TYPE_AUTH_SUCCESS   = "authorise_succeeded";
  const EVENT_TYPE_AUTH_FAIL      = "authorise_failed";
  const EVENT_TYPE_AUTH_REVIEW    = "authorise_under_review";
  const EVENT_TYPE_AUTH_DECLINED  = "authorise_declined";

  const EVENT_TYPE_CANCEL_SUCCESS = "cancel_succeeded";
  const EVENT_TYPE_CANCEL_FAIL    = "cancel_failed";

  const EVENT_TYPE_CAPTURE_SUCCESS = "capture_succeeded";
  const EVENT_TYPE_CAPTURE_FAIL    = "capture_failed";

  const EVENT_TYPE_REFUND_SUCCESS = "refund_succeeded";
  const EVENT_TYPE_REFUND_FAIL    = "refund_failed";

  const EVENT_TYPE_ORDER_CANCELLED = "order_cancelled";

  const EVENT_TYPE_CHARGE_SUCCESS = "charge_succeeded";
  const EVENT_TYPE_CHARGE_FAIL    = "charge_failed";

  const EVENT_TYPE_CONFIG_UPDATE  = "configuration_updated";
  
  /**
   * Validates credentials contained in WebHook Request
   *
   * @param  string $merchantId
   * @param  string $merchantKey
   * @return bool
   */
  private function _validateCredentials($merchantId, $merchantKey)
  {
    if($merchantId == Configuration::$merchant_id && $merchantKey == Configuration::$merchant_key){
        return true;
    }
    else{
        return false;
    }
  }


  /**
   * Listen for WebHook Request
   *
   */
  public function listen()
  {
    $data = file_get_contents("php://input");

    if (!$data){            
      http_response_code(404);
      throw new Exception("Notification parameters cannot be empty");        
    }

    $params = json_decode($data);

    if(!$params){            
      http_response_code(404);
      throw new Exception("Invalid Notification Parameters");        
    }
    
    // Check if the message signature is valid
    if(!$this->isValid($params)){
      http_response_code(404);
      throw new Exception("Invalid Notification. Cannot validate the signature.");        
    }
               
    if (isset($params->Type) && $params->Type == 'SubscriptionConfirmation') {
      $this->_subscribe($params->SubscribeURL);
      die();
    } else if (isset($params->Type) &&  $params->Type == 'Notification') {
      $this->_processRequest($params);
    }
  }

  /**
   * Delegates for further processing to proper notification handlers based on notification_type
   *
   * @param  $params
   */
  protected function _processRequest($params)
  {
    if (!$params->Message)
      throw new Exception("Notification message cannot be empty");

    $message  = json_decode($params->Message);

    if (!$message->response)
      throw new Exception("Notification response cannot be empty");

    if(!$this->_validateCredentials($message->response->merchant_id,$message->response->merchant_key))
      throw new Exception("Merchant Credentials donot match");

    switch ($message->type) {
      case self::EVENT_TYPE_AUTH_SUCCESS:
          # code...
           $this->_eventAuthSuccess($message->response);
          break;
      case self::EVENT_TYPE_AUTH_FAIL:
          # code... 
           $this->_eventAuthFail($message->response);
          break;
      case self::EVENT_TYPE_AUTH_REVIEW:
          # code...
           $this->_eventAuthReview($message->response);
          break;
      case self::EVENT_TYPE_AUTH_DECLINED:
          # code...
           $this->_eventAuthDeclined($message->response);
          break;
      case self::EVENT_TYPE_CANCEL_SUCCESS:
          # code...
           $this->_eventCancelSuccess($message->response);
          break;
      case self::EVENT_TYPE_CANCEL_FAIL:
          # code...
           $this->_eventCancelFail($message->response);
          break;
      case self::EVENT_TYPE_CAPTURE_SUCCESS:
          # code...
          $this->_eventCaptureSuccess($message->response);
          break;
      case self::EVENT_TYPE_CAPTURE_FAIL:
          # code...
           $this->_eventCaptureFail($message->response);
          break;
      case self::EVENT_TYPE_REFUND_SUCCESS:
          # code...
           $this->_eventRefundSuccess($message->response);
          break;
      case self::EVENT_TYPE_REFUND_FAIL:
          # code...
           $this->_eventRefundFail($message->response);
          break;
      case self::EVENT_TYPE_ORDER_CANCELLED:
          # code...
           $this->_eventOrderCancel($message->response);
          break;
      case self::EVENT_TYPE_CHARGE_SUCCESS:
          # code...
           $this->_eventChargeSuccess($message->response);
          break;
      case self::EVENT_TYPE_CHARGE_FAIL:
          # code... 
           $this->_eventChargeFail($message->response);
          break;
      case self::EVENT_TYPE_CONFIG_UPDATE:
          # code... 
           $this->_eventConfigUpdate($message->response);
          break;
      default:
          # code...
          break;
    }

  }

  /**
   * Process Authorisation Success
   *
   * @param  $response
   */
  protected function _eventAuthSuccess($response){}  

  /**
   * Process Authorisation Failure
   *
   * @param  $response
   */
  protected function _eventAuthFail($response){}

  /**
   * Process Authorisation Review 
   *
   * @param  $response
   */
  protected function _eventAuthReview($response){}
  
  /**
   * Process Authorisation Review 
   *
   * @param  $response
   */
  protected function _eventAuthDeclined($response){}
  
  /**
   * Process Cancel Success 
   *
   * @param  $response
   */
  protected function _eventCancelSuccess($response){}

  /**
   * Process Cancel Fail 
   *
   * @param  $response
   */
  protected function _eventCancelFail($response){}

  /**
   * Process Capture Success 
   *
   * @param  $response
   */
  protected function _eventCaptureSuccess($response){}

  /**
   * Process Capture Failure 
   *
   * @param  $response
   */
  protected function _eventCaptureFail($response){}

  /**
   * Process Refund Success 
   *
   * @param  $response
   */
  protected function _eventRefundSuccess($response){}

  /**
   * Process Refund Fail 
   *
   * @param  $response
   */
  protected function _eventRefundFail($response){}

  /**
   * Process Order Cancel
   *
   * @param  $response
   */
  protected function _eventOrderCancel($response){}

  /**
   * Process Charge Success 
   *
   * @param  $response
   */
  protected function _eventChargeSuccess($response){}

  /**
   * Process Charge Fail 
   *
   * @param  $response
   */
  protected function _eventChargeFail($response){}
  
  /**
   * Process Charge Fail 
   *
   * @param  $response
   */
  protected function _eventConfigUpdate($response){}

  /**
   * Subscribe url on the endpoint
   *
   * @param  $subscribeUrl
   */
  private function _subscribe($subscribeUrl)
  {
      if(!$subscribeUrl)
          throw new \zipMoney\Exception("SubscribeURL cannot be empty");

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $subscribeUrl);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($curl);
      curl_close($curl);

  }  

  /**
   * Validates a message from SNS to ensure that it was delivered by AWS.
   *
   * @param  Array $message
   * @throws \zipMoney\Exception
   */
  public function validate($message)
  {
    // Get the certificate.
    $this->validateUrl($message->SigningCertURL);
    $certificate = $this->url_get_contents($message->SigningCertURL);

    // Extract the public key.
    $key = openssl_get_publickey($certificate);
    if (!$key) {
      throw new \zipMoney\Exception(
        'Cannot get the public key from the certificate.'
      );
    }
    // Verify the signature of the message.
    $content = $this->getStringToSign($message);
    $signature = base64_decode($message->Signature);
    if (!openssl_verify($content, $signature, $key, OPENSSL_ALGO_SHA1)) {
      throw new \zipMoney\Exception(
        'The message signature is invalid.'
      );
    }
  }

  private function url_get_contents ($url) 
  {
    if (function_exists('curl_exec')){ 
        $conn = curl_init($url);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $url_get_contents_data = (curl_exec($conn));
        curl_close($conn);
    }elseif(function_exists('file_get_contents')){
        $url_get_contents_data = file_get_contents($url);
    }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
        $handle = fopen ($url, "r");
        $url_get_contents_data = stream_get_contents($handle);
    }else{
        $url_get_contents_data = false;
    }

    return $url_get_contents_data;
  } 

  /**
   * Determines if a message is valid and that is was delivered by AWS. This method does not throw exceptions and returns a simple boolean value.
   *
   * @param array $message
   * @return bool
   */
  public function isValid($message)
  {
      try {
          $this->validate($message);
          return true;
      } catch (\Exception $e) {
          return false;
      }
  }

  /**
   * Builds string-to-sign according to the SNS message spec.
   *
   * @param array $message
   * @return string
   */
  public function getStringToSign($message)
  {
    static $signableKeys = [
      'Message',
      'MessageId',
      'Subject',
      'SubscribeURL',
      'Timestamp',
      'Token',
      'TopicArn',
      'Type',
    ];

    if ($message->SignatureVersion !== self::SIGNATURE_VERSION_1) {
      throw new \zipMoney\Exception(
          "The SignatureVersion \"{$message->SignatureVersion}\" is not supported."
      );
    }

    $stringToSign = '';
    foreach ($signableKeys as $key) {
      if (isset($message->$key)) {
        $stringToSign .= "{$key}\n{$message->$key}\n";
      }
    }

  return $stringToSign;
  }

  /**
   * Ensures that the URL of the certificate is one belonging to AWS, and not just something from the amazonaws domain, which could include S3 buckets.
   *
   * @param string $url
   * @throws \zipMoney\Exception 
   */
  private function validateUrl($url)
  {
    $parsed = parse_url($url);
    if (empty($parsed['scheme'])
        || empty($parsed['host'])
        || $parsed['scheme'] !== 'https'
        || substr($url, -4) !== '.pem'
        || !preg_match($this->_hostPattern, $parsed['host'])
    ) {
        throw new \zipMoney\Exception(
            'The certificate is located on an invalid domain.'
        );
    }
  }
}
