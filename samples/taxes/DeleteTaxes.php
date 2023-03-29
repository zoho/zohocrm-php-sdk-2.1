<?php
namespace samples\taxes;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\taxes\APIException;
use com\zoho\crm\api\taxes\ActionWrapper;
use com\zoho\crm\api\taxes\SuccessResponse;
use com\zoho\crm\api\taxes\TaxesOperations;
use com\zoho\crm\api\taxes\DeleteTaxesParam;
require_once "vendor/autoload.php";

class DeleteTaxes
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

	public static function deleteTaxes(array $taxIds)
	{
		$taxesOperations = new TaxesOperations();
		$paramInstance = new ParameterMap();
		foreach($taxIds as $taxId)
		{
			$paramInstance->add(DeleteTaxesParam::ids(), $taxId);
		}
		$response = $taxesOperations->deleteTaxes($paramInstance);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			$actionHandler = $response->getObject();
			if($actionHandler instanceof ActionWrapper)
			{
				$actionWrapper = $actionHandler;
				$actionResponses = $actionWrapper->getTaxes();
				foreach($actionResponses as $actionResponse)
				{
					if($actionResponse instanceof SuccessResponse)
					{
						$successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        if($successResponse->getDetails() != null)
                        {
                            echo("Details: " );
                            foreach($successResponse->getDetails() as $key => $value)
                            {
                                echo($key . " : ");
                                print_r($value);
                                echo("\n");
                            }
                        }
                        echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
					}
					else if($actionResponse instanceof APIException)
					{
                        $exception = $actionResponse;
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
			else if($actionHandler instanceof APIException)
			{
                $exception = $actionHandler;
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

DeleteTaxes::initialize();
$taxIds = array("34770619885005","34770619885006");
DeleteTaxes::deleteTaxes($taxIds);