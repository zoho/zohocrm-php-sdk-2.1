<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\MassUpdateActionWrapper;
use com\zoho\crm\api\record\MassUpdateBodyWrapper;
use com\zoho\crm\api\record\MassUpdateSuccessResponse;
use com\zoho\crm\api\record\RecordOperations;
require_once "vendor/autoload.php";

class MassUpdateRecords
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

	public static function massUpdateRecords(string $moduleAPIName)
	{
		$recordOperations = new RecordOperations();
		$request = new MassUpdateBodyWrapper();
		$records = array();
		$recordClass = 'com\zoho\crm\api\record\Record';
		$record1 = new $recordClass();
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("City", "Value");
		array_push($records, $record1);
		$request->setData($records);
		$request->setCvid("34770610087501");
		$ids = array("3477061012054037");
		$request->setIds($ids);
		// $territory = new Territory();
		// $territory->setId("34770613051357");
		// $territory->setIncludeChild(true);
		// $request->setTerritory($territory);
		$request->setOverWrite(true);
		$response = $recordOperations->massUpdateRecords($moduleAPIName, $request);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$massUpdateActionHandler = $response->getObject();
				if($massUpdateActionHandler instanceof MassUpdateActionWrapper)
				{
					$massUpdateActionWrapper = $massUpdateActionHandler;
					$massUpdateActionResponses = $massUpdateActionWrapper->getData();
					foreach($massUpdateActionResponses as $massUpdateActionResponse)
					{
						if($massUpdateActionResponse instanceof MassUpdateSuccessResponse)
						{
							$massUpdateSuccessResponse = $massUpdateActionResponse;
							echo("Status: " . $massUpdateSuccessResponse->getStatus()->getValue() . "\n");
							echo("Code: " . $massUpdateSuccessResponse->getCode()->getValue() . "\n");
							if($massUpdateSuccessResponse->getDetails() != null)
							{
								echo("Details: " );
								foreach ($massUpdateSuccessResponse->getDetails() as $keyName => $keyValue)
								{
									echo($keyName . ": ");
									print_r($keyValue);
									echo("\n");
								}
							}
							echo("Message: " . $massUpdateSuccessResponse->getMessage()->getValue() . "\n");
						}
						else if($massUpdateActionResponse instanceof APIException)
						{
							$exception = $massUpdateActionResponse;
							echo("Status: " . $exception->getStatus()->getValue() . "\n");
							echo("Code: " . $exception->getCode()->getValue() . "\n");
							echo("Details: " );
							if($exception->getDetails() != null)
							{
								foreach ($exception->getDetails() as $keyName => $keyValue)
								{
									echo($keyName . ": " . $keyValue . "\n");
								}
							}
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				else if($massUpdateActionHandler instanceof APIException)
				{
					$exception = $massUpdateActionHandler;
					echo("Status: " . $exception->getStatus()->getValue() . "\n");
					echo("Code: " . $exception->getCode()->getValue() . "\n");
					if($exception->getDetails() != null)
					{
						echo("Details: " );
						foreach ($exception->getDetails() as $keyName => $keyValue)
						{
							echo($keyName . ": " . $keyValue . "\n");
						}
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

MassUpdateRecords::initialize();
$moduleAPIName = "Leads";
MassUpdateRecords::massUpdateRecords($moduleAPIName);