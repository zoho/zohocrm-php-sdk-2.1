<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\record\{ApplyFeatureExecution, Field, Leads };
require_once "vendor/autoload.php";

class UpsertRecords
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

	public static function upsertRecords(string $moduleAPIName)
	{
		$recordOperations = new RecordOperations();
		$request = new BodyWrapper();
		$records = array();
		$recordClass = 'com\zoho\crm\api\record\Record';
		$record1 = new $recordClass();
		$apply_feature_execution = new ApplyFeatureExecution();
		$apply_feature_execution->setName("layout_rules");
		$apply_feature_list = array();
		array_push($apply_feature_list,$apply_feature_execution);
		$request->setApplyFeatureExecution($apply_feature_list);

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$field = new Field("");
		$record1->addFieldValue(Leads::City(), "City");
		$record1->addFieldValue(Leads::LastName(), "Last Name");
		$record1->addFieldValue(Leads::FirstName(), "First Name");
		$record1->addFieldValue(Leads::Company(), "Company1");
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");
		$record1->addKeyValue("Custom_field_2", "value");
		array_push($records, $record1);
		$record2 = new $recordClass();
		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$record2->addFieldValue(Leads::City(), "City");
		$record2->addFieldValue(Leads::LastName(), "Last Name");
		$record2->addFieldValue(Leads::FirstName(), "First Name");
		$record2->addFieldValue(Leads::Company(), "Company12");
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record2->addKeyValue("Custom_field", "Value");
		$record2->addKeyValue("Custom_field_2", "value");
		$record2->addKeyValue("External", "External1");
		array_push($records, $record2);
		$duplicateCheckFields = array("City", "Last_Name", "First_Name");
		$request->setDuplicateCheckFields($duplicateCheckFields);
		$request->setData($records);
		$headerInstance = new HeaderMap();
		// $headerInstance->add(UpsertRecordsHeader::XEXTERNAL(), "Leads.External");
		$response = $recordOperations->upsertRecords($moduleAPIName, $request, $headerInstance);
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
								echo($key . " : ");
								print_r($value);
								echo("\n");
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

UpsertRecords::initialize();
$moduleAPIName = "Leads";
UpsertRecords::upsertRecords($moduleAPIName);