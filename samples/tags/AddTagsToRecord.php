<?php
namespace samples\tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\RecordActionWrapper;
use com\zoho\crm\api\tags\SuccessResponse;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\AddTagsToRecordParam;
require_once "vendor/autoload.php";

class AddTagsToRecord
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

	public static function addTagsToRecord(string $moduleAPIName, string $recordId, array $tagNames)
	{
		$tagsOperations = new TagsOperations();
		$paramInstance = new ParameterMap();
		foreach($tagNames as $tagName)
		{
			$paramInstance->add(AddTagsToRecordParam::tagNames(), $tagName);
		}
		$paramInstance->add(AddTagsToRecordParam::overWrite(), "false");
		$response = $tagsOperations->addTagsToRecord($recordId, $moduleAPIName,$paramInstance);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $recordActionHandler = $response->getObject();
            if($recordActionHandler instanceof RecordActionWrapper)
            {
                $recordActionWrapper = $recordActionHandler;
                $actionResponses = $recordActionWrapper->getData();
                foreach($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details: \n" );
                        foreach($successResponse->getDetails() as $key => $value)
                        {
                            echo($key . " : "); print_r($value); echo("\n");
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
                           echo($key . " : "); print_r($value); echo("\n");
                        }
                        echo("Message: " . $exception->getMessage()->getValue() . "\n");
                    }
                }
            }
            else if($recordActionHandler instanceof APIException)
            {
                $exception = $recordActionHandler;
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

AddTagsToRecord::initialize();
$moduleAPIName = "Leads";
$recordId =  "34770615623115";
$tagNames = array("addtag1", "addtag12");
AddTagsToRecord::addTagsToRecord($moduleAPIName, $recordId, $tagNames);