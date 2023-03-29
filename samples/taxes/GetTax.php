<?php
namespace samples\taxes;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\taxes\APIException;
use com\zoho\crm\api\taxes\ResponseWrapper;
use com\zoho\crm\api\taxes\TaxesOperations;
require_once "vendor/autoload.php";

class GetTax
{
    public static function initialize()
    {
        $user = new UserSignature('myname@mydomain.com');
        $environment = USDataCenter::PRODUCTION();
        $token = (new OAuthBuilder())
        ->clientId("1000.xxxx")
        ->clientSecret("xxxxxx")
        ->refreshToken("1000.xxxxx.xxxxx")
        ->build();
        (new InitializeBuilder())
            ->user($user)
            ->environment($environment)
            ->token($token)
            ->initialize();
    }

	public static function getTax(string $taxId)
	{
		$taxesOperations = new TaxesOperations();
		$response = $taxesOperations->getTax($taxId);
		if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
			$responseHandler = $response->getObject();
			if($responseHandler instanceof ResponseWrapper)
			{
				$responseWrapper = $responseHandler;
				$taxes = $responseWrapper->getTaxes();
				if($taxes != null)
				{
					foreach($taxes as $tax)
					{
						echo("Tax DisplayLabel: " . $tax->getDisplayLabel() . "\n");
						echo("Tax Name: " . $tax->getName() . "\n");
						echo("Tax ID: " . $tax->getId() . "\n");
						echo("Tax Value: " . $tax->getValue() . "\n");
					}
					$preference = $responseWrapper->getPreference();
					if($preference != null)
					{
						echo("Preference AutoPopulateTax: " . $preference->getAutoPopulateTax() . "\n");
						echo("Preference ModifyTaxRates: " . $preference->getModifyTaxRates() . "\n");
					}
				}
				else if($responseHandler instanceof APIException)
				{
					$exception = $responseHandler;
					echo("Status: " . $exception->getStatus()->getValue() . "\n");
					echo("Code: " . $exception->getCode()->getValue() . "\n");
					echo("Details: " );
					foreach($exception->getDetails() as $key => $value)
					{
						echo($key . " : " . $value . "\n");
					}
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
		}
	}
}

GetTax::initialize();
$taxId = "34770619873024";
GetTax::getTax($taxId);