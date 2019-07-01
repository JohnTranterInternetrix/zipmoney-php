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

use \zipMoney\Exception\HttpException;

class Http
{
  private $_endpoint        = null;

  private $_config          = array();

  private $_httpHeader      = array();
  
  private $_responseHeader  = array();

  public function __construct($endpoint, $type = "json", $options = null)
  {
    if(!$endpoint)
      throw new  HttpException("Request endpoint url should be Provided", 1);
        
    $this->_endpoint = $endpoint;

    if(is_array($options)){
      $this->_options   = $options;
        
      if($this->_options['headers'])
        $this->setHttpHeaders($this->_options['headers']);
    }
  }
  
  /**
   * Makes curl request for the given url path with HTTP GET
   *
   * @param string $path
   * @return array
   */
  public function get($path)
  {   
    return $this->_curlRequest('GET');
  }
  
  /**
   * Makes curl request with HTTP POST
   *
   * @param array $payload
   * @return array
   */
  public function post($payload = null)
  {   
    return $this->_curlRequest('POST', $payload);
  }
  
  /**
   * Makes curl request for the given url path with HTTP DELETE
   *
   * @param string $path
   * @return array
   */
  public function delete($path)
  {   
    return $this->_curlRequest('DELETE', $path);
  }

  /**
   * Makes curl request for the given url path with HTTP PUT
   *
   * @param string $path
   * @return array
   */
  public function put($path, $params = null)
  { 
    return $this->_curlRequest('PUT', $params);
  }

  /**
   * Appends the given path to the endpoint url
   *
   * @param string $path
   */
  private function _appendPath($path)
  {
    if(!isset($path) || empty($path))             
      throw new  HttpException("Request endpoint path should be provided", 1);
    
    if ($this->_baseEndpointUrl && $path) 
      $this->_endpointUrl = $this->_baseEndpointUrl.ltrim($path, '/');
  }

  /**
   * Sets HTTP header to the request with the passed headers
   *
   * @param array $headers
   */
  public function setHttpHeaders($headers)
  {   
    if(isset($headers) && !empty($headers))
      $this->_httpHeader = $headers;
  }
  
  /**
   * Callback for the CURLOPT_HEADERFUNCTION 
   *
   * @param Object of curl_init $curl
   * @param string $header_line
   */
  private function _responseHeader( $curl, $header_line )
  {   
    if($header_line)
      $this->_responseHeader[] = $header_line;
    return strlen($header_line);
  }


  /**
   * Makes the curl request
   *
   * @param string $httpMethod
   * @param array $requestBody   
   * @return array
   */
  private function _curlRequest($httpMethod, $requestBody = null)
  {
    $this->_responseHeader = array(); 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpMethod);
    curl_setopt($curl, CURLOPT_URL, $this->_endpoint);
    curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
    curl_setopt($curl, CURLOPT_HEADERFUNCTION, array($this,'_responseHeader'));

    if($this->_httpHeader)
      curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_httpHeader);

   // curl_setopt($curl, CURLOPT_HEADER, 1);
  //  curl_setopt($curl, CURLOPT_VERBOSE, true);

    # If SSL flag is true
    if ($this->_config && $this->_config['ssl']){
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($curl, CURLOPT_CAINFO, $this->_config['caFile']);
    }

    if(!empty($requestBody)) {
        //print_r($requestBody);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestBody));
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response    = curl_exec($curl);
    $httpStatus  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $header      = substr($response, 0, $header_size);
    curl_close($curl);

    if ($this->_config && $this->_config['ssl']){
      if ($httpStatus == 0) {
          throw new HttpException('Http code is 0', 1);
      }
    }

    return array('status' => $httpStatus, 'body' => $response,'header'=>$this->_responseHeader);
  }
}
