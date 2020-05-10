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


    public function getSession($account,$product_name)
    {

      $payload ='{
       "account": "'.$account.'",
       "items": [
                 {
                     "product": "'.$product_name.'",
                     "quantity": 1
                 }
             ]
      }';
      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->post('sessions', ['body' => $payload])->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
    }
    public function createCustomer($first,$last='',$email,$user_uuid)
    {
    	$payload ='{
           "contact":
           {
              "first":"'.$first.'",
              "last":"'.$last.'",
              "email":"'.$email.'"
           },
           "language":"en",
           "country":"US",
           "lookup":
           {
              "custom":"'.$user_uuid.'"
           }
      }';
  		try {
  		    $FastSpringClient = new FastSpringClient();
  	        $response = json_decode($FastSpringClient->post('accounts', ['body' => $payload])->getBody()->getContents(),true);
  	        return $response;
  		} catch (ClientErrorResponseException $exception) {
  		    $responseBody = json_decode($exception->getResponse()->getBody(),true);
  		    return $responseBody;
  		}
  		catch (\Exception $exception)
  		{
  			$responseBody = json_decode($exception->getResponse()->getBody(),true);
  		  return $responseBody;
  		}
    }
    public function updateCustomer()
    {
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
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
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
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
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
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
    }
    public function getCustomerUsingEmail($email)
    {
      try {
          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->get('accounts/?email='.$email)->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      //GET /accounts?key=value&limit=15&page=2
    }
    public function updateSubscription($subscription_id,$product_name,$prorate)
    {
      try {
          if($prorate)
          {
            $payload ='{"subscriptions": [{
             "subscription": "'.$subscription_id.'",
              "product": "'.$product_name.'",
              "quantity": 1,
              "prorate": true
            }]}';
          }
          else
          {
            $payload ='{"subscriptions": [
            {
             "subscription": "'.$subscription_id.'",
              "product": "'.$product_name.'",
              "quantity": 1
            }]}';
          }

          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->post('subscriptions', ['body' => $payload])->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
    }

    public function cancelSubscription($subscription_id)
    {
      try {

          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->delete('subscriptions/'.$subscription_id)->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
        $responseBody = json_decode($exception->getResponse()->getBody(),true);
          return $responseBody;
      }
    }
    public function uncancelSubscription($subscription_id)
    {
      try {

            $payload ='{"subscriptions": [{
             "subscription": "'.$subscription_id.'",
              "deactivation": null
            }]}';

          $FastSpringClient = new FastSpringClient();
            $response = json_decode($FastSpringClient->post('subscriptions', ['body' => $payload])->getBody()->getContents(),true);
            return $response;
      } catch (ClientErrorResponseException $exception) {
          $responseBody = json_decode($exception->getResponse()->getBody(true)->getContents(),true);
          return $responseBody;
      }
      catch (\Exception $exception)
      {
          $responseBody = json_decode($exception->getResponse()->getBody(true)->getContents(),true);
          return $responseBody;
      }
    }

}
