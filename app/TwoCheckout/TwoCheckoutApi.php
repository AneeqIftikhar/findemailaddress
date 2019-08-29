<?php

namespace App\TwoCheckout;
use App\TwoCheckout\TwoCheckoutClient;
use Guzzle\Http\Exception\ClientErrorResponseException;
class TwoCheckoutApi
{

    public function getAllSubscriptions()
    {
    	$TwoCheckoutClient = new TwoCheckoutClient();
    	$products = json_decode($TwoCheckoutClient->get('products/')->getBody()->getContents(), true);
        return $products;
    } 
    public function getAllCurrencies()
    {
    	$TwoCheckoutClient = new TwoCheckoutClient();
        $allowedCurrencies = json_decode($TwoCheckoutClient->get('currencies/')->getBody()->getContents(), true);
        return $allowedCurrencies;
    }
    public function orderCreditCard()
    {
		
        $payload ='{
          "Currency": "usd",
          "Language": "en",
          "Country": "us",
          "CustomerIP": "91.220.121.21",
          "Source": "testAPI.com",
          "ExternalReference": "REST_API_AVANGTE",
          "Items": [
            {
              "Code": "1",
              "Quantity": "1",
              "SubscriptionStartDate": null
            }
          ],
          "BillingDetails": {
            "Address1": "Test Address",
            "City": "LA",
            "State": "California",
            "CountryCode": "US",
            "Email": "customer@2Checkout.com",
            "FirstName": "Customer",
            "LastName": "2Checkout",
            "Zip": "12345"
          },
          "PaymentDetails": {
            "Type": "CC",
            "Currency": "USD",
            "CustomerIP": "91.220.121.21",
            "PaymentMethod": {
              "RecurringEnabled": true,
              "CardNumber": "4111111111111111",
              "CardType": "VISA",
              "ExpirationYear": "2019",
              "ExpirationMonth": "12",
              "CCID": "123",
              "HolderName": "John Doe",
              "HolderNameTime": "12",
              "CardNumberTime": "12",
              "Vendor3DSReturnURL": "http://localhost/email_finder_verifier/public/login/",
              "Vendor3DSCancelURL": "http://localhost/email_finder_verifier/public/login/"
            }
          }
        }';
        
        try {
		    $TwoCheckoutClient = new TwoCheckoutClient();
	        $response = json_decode($TwoCheckoutClient->post('orders/', ['body' => $payload])->getBody()->getContents(),true);
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
    public function createCustomer($fname,$lname,$email,$id,$address1="Dummy Address",$city="London",$state="London",$zip="WC1A1AH",$country_code="gb")
    {
    	//265628652
      //140195918
    	$payload ='{
		    "AvangateCustomerReference": null,
		    "ExternalCustomerReference": "'.$id.'",
		    "FirstName": "'.$fname.'",
		    "LastName": "'.$lname.'",
		    "Email": "'.$email.'",
        "Address1": "'.$address1.'",
        "City": "'.$city.'",
        "State": "'.$state.'",
        "Zip": "'.$zip.'",
        "CountryCode": "'.$country_code.'",
        "Status": "Inactive"
		}';
  		try {
  		    $TwoCheckoutClient = new TwoCheckoutClient();
  	        $response = json_decode($TwoCheckoutClient->post('customers/', ['body' => $payload])->getBody()->getContents(),true);
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

    public function getCustomerSubscriptions($user_ref)
    {
      
      try {
          $TwoCheckoutClient = new TwoCheckoutClient();
          $response = json_decode($TwoCheckoutClient->get('subscriptions/?AvangateCustomerReference='.$user_ref)->getBody()->getContents(), true);
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

    public function getOrderDetails($order_ref)
    {
      
      try {
          $TwoCheckoutClient = new TwoCheckoutClient();
          $response = json_decode($TwoCheckoutClient->get('orders/'.$order_ref.'/')->getBody()->getContents(), true);
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
