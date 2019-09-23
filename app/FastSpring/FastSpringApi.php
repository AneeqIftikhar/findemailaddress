<?php

namespace App\FastSpring;
use App\FastSpring\FastSpringClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
class FastSpringApi
{
    private $username;
    private $password;

    public function __construct()
    {
        $this->username =  env('FASTSPRING_USERNAME', '');
        $this->password = env('FASTSPRING_PASSWORD', '');
    }
    

    public function getSession()
    {

      /*

          {
            "id": "2aqIUau0TOSsP56ZvBEa7A",
            "currency": "USD",
            "expires": 1569337591062,
            "order": null,
            "account": "rO_bGfPeTdipo__qUxC5_g",
            "subtotal": 29.99,
            "items": [
                {
                    "product": "small",
                    "quantity": 1
                }
            ]
        }
      */

    $payload ='{
     "account": "rO_bGfPeTdipo__qUxC5_g",                                        
     "items": [
               {
                   "product": "small",
                   "quantity": 1
               }
           ]
      }';
      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->post('sessions', ['body' => $payload])->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
        $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
    }
    public function createCustomer()
    {

    // "account": "rO_bGfPeTdipo__qUxC5_g",
    //"id": "rO_bGfPeTdipo__qUxC5_g",
    //"action": "account.create",
    //"result": "success"
    	$payload ='{  
           "contact":
           {  
              "first":"John",         
              "last":"Doe",
              "email":"j.doe@fastspring.com"
           },
           "language":"en",
           "country":"US",
           "lookup":
           {               
              "custom":"customKey"
           }
      }';
  		try {
  		    $FastSpringClient = new FastSpringClient();
  	        $response = json_decode($FastSpringClient->post('accounts', ['body' => $payload])->getBody()->getContents(),true);
  	        return $response;
  		} catch (ClientErrorResponseException $exception) {
  		    $responseBody = $exception->getResponse()->getBody(true);
  		    return $responseBody;
  		}
  		catch (\Exception $exception)
  		{
  			$responseBody = $exception->getResponse()->getBody(true);
  		    return $responseBody;
  		}
    }
    public function updateCustomer()
    {

    // "account": "rO_bGfPeTdipo__qUxC5_g",
    //"id": "rO_bGfPeTdipo__qUxC5_g",
    //"action": "account.create",
    //"result": "success"
      $payload ='{  
           "contact":
           {  
              "first":"John",         
              "last":"Doe",
              "email":"aneeq@gmail.com"
           },
           "language":"en",
           "country":"US",
           "lookup":
           {               
              "custom":"customKey"
           }
      }';
      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->post('accounts/rO_bGfPeTdipo__qUxC5_g', ['body' => $payload])->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
        $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
    }
    public function getAllCustomers()
    {

      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->get('accounts')->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
        $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
    }
    public function getCustomer($account)
    {
      

      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->get('accounts/'.$account)->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
        $responseBody = $exception->getResponse()->getBody(true);
          return $responseBody;
      }
    }

      
}
