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

class Response 
{
  private $_response = null;

  private $_responseBody = null;

  private $_responseHeader = null;

  private $_statusCode = null;

  public function __construct($response)
  {
    if(!isset($response) || empty($response)) 
      throw new  Exception("Response Empty", 1);
    
    $this->_statusCode   = $response['status'];
    $this->_responseBody = $response['body'];
    $this->_responseHeader = $response['header'];
  }

  /**
   * Converts the _responseBody to array
   *
   * @return array 
   */
  public function toArray()
  {   
    if(!$this->_responseBody)
      return false;
   
    return $this->_objectToArray(json_decode($this->_responseBody)); 
  }

  /**
   * Converts the _responseBody to toObject
   *
   * @return object 
   */
  public function toObject()
  {   
    if(!$this->_responseBody)
      return false;
   
    return json_decode($this->_responseBody); 
  }

  /**
   * Converts the object to array
   *
   * @return array 
   */
  private function _objectToArray($d)
  {
    if (is_object($d)) {
      $d = get_object_vars($d);
    }

    if (is_array($d)) {
      return array_map(array($this,__FUNCTION__), $d);
    }
    else {
      return $d;
    }
  }

  /**
   * Extracts and returns the redirect_url from the response
   *
   * @return string 
   */
  public function getRedirectUrl()
  {
    $responseArray = $this->toArray();
    return isset($responseArray['redirect_url']) && !empty($responseArray['redirect_url']) ? $responseArray['redirect_url']:null; 
  }

  /**
   * Extracts and returns the HTTP STATUS CODE from the response
   *
   * @return string 
   */
  public function getStatusCode()
  {
    return $this->_statusCode;
  }

  /**
   * Checks if the response is a success
   *
   * @return bool 
   */
  public function isSuccess()
  {
    return $this->getStatusCode() == 200 || $this->getStatusCode() == 201 ?  true : false;
  }
}