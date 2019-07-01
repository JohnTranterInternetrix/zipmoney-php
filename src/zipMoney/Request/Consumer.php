<?php
/**
 * @category  zipMoney
 * @package   zipMoney PHP Library
 * @author    Sagar Bhandari <sagar.bhandari@zipmoney.com.au>
 * @copyright 2016 zipMoney Payments.
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      http://www.zipmoney.com.au/
 */
namespace zipMoney\Request;

class Consumer{
  
  public  $first_name = null;

  public  $last_name = null; 

  public  $phone = null;

  public  $email = null;

  public  $gender = null;

  public  $dob = null;

  public  $title = null;

  public  $created_at = null;

  public  $lifetime_sales_amount = null;
  
  public  $average_sale_value = null;

  public  $maximum_sale_value = null;

  public  $lifetime_sales_units = null;

  public  $lifetime_sales_refunded_amount = null;

  public  $declined_before = null;

  public  $chargeback_before = null;

}
