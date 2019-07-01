<?php
namespace zipMoney\Helper;

class Util
{
  /**
   * Converts object to array
   *
   * @param  object $d
   * @return object
   */
  public static function objectToArray($d)
  {        
    return json_decode(json_encode($d), true);;
  }

  /**
   *  Prepares and sanitizes the request. Removes the parameters having null values
   *
   * @param  array 
   * @return array
   */
  public static function prepareRequest($requestArray)
  {   
    $newArray = array();
    
    foreach ($requestArray as $key => $value) {
      if(is_array($value) && count($value)){
        // Check if return value is not empty
        if($retVal = self::prepareRequest($value))
           $newArray[$key] = $retVal;
      } else {
        if(!is_null($value)){
         $newArray[$key] = $value;
        }
      }
    }

    return $newArray;
  }
}