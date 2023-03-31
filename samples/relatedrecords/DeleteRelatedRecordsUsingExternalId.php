<?php
namespace samples\relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\ActionWrapper;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\DelinkRecordsParam;
use com\zoho\crm\api\relatedrecords\SuccessResponse;
require_once "vendor/autoload.php";

class DeleteRelatedRecordsUsingExternalId
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

	public static function deleteRelatedRecordsUsingExternalId(string $moduleAPIName, string $externalValue, string $relatedListAPIName, array $relatedListIds)
	{
		$xExternal = "Leads.External,Products.Products_External";
	    $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName, $xExternal);
		$paramInstance = new ParameterMap();
		foreach($relatedListIds as $relatedListId)
		{
			$paramInstance->add(DelinkRecordsParam::ids(), $relatedListId);
		}
		$response = $relatedRecordsOperations->deleteRelatedRecordsUsingExternalId($externalValue, $paramInstance);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$actionHandler = $response->getObject();
				if($actionHandler instanceof ActionWrapper)
				{
					$actionWrapper = $actionHandler;
					$actionResponses = $actionWrapper->getData();
					foreach($actionResponses as $actionResponse)
					{
						if($actionResponse instanceof SuccessResponse)
						{
							$successResponse = $actionResponse;
							echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
							echo("Code: " . $successResponse->getCode()->getValue() . "\n");
							echo("Details: " );
							foreach($successResponse->getDetails() as $key => $value)
							{
								echo($key . " : " . $value . "\n");
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
			else
			{
				print_r($response);
			}
		}
	}
}

DeleteRelatedRecordsUsingExternalId::initialize();
$moduleAPIName = "Leads";
$externalValue = "TestExternalLead111";
$relatedListAPIName = "products";
$relatedListIds = array("AutomatedSDKExternal", "34770615919001");
DeleteRelatedRecordsUsingExternalId::deleteRelatedRecordsUsingExternalId($moduleAPIName, $externalValue, $relatedListAPIName, $relatedListIds);