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
use com\zoho\crm\api\tags\RemoveTagsFromMultipleRecordsParam;
require_once "vendor/autoload.php";

class RemoveTagsFromMultipleRecords
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

	public static function removeTagsFromMultipleRecords(string $moduleAPIName, array $recordIds, array $tagNames)
	{
		$tagsOperations = new TagsOperations();
		$paramInstance = new ParameterMap();
		foreach($tagNames as $tagName)
		{
			$paramInstance->add(RemoveTagsFromMultipleRecordsParam::tagNames(), $tagName);
		}
		foreach($recordIds as $recordId)
		{
			$paramInstance->add(RemoveTagsFromMultipleRecordsParam::ids(), $recordId);
		}
		$response = $tagsOperations->removeTagsFromMultipleRecords($moduleAPIName, $paramInstance);
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
                if($recordActionWrapper->getLockedCount() != null)
                {
                    echo("Locked Count: " . $recordActionWrapper->getLockedCount() . "\n");
                }
                if($recordActionWrapper->getSuccessCount() != null)
                {
                    echo("Success Count: " . $recordActionWrapper->getSuccessCount() . "\n");
                }
                if($recordActionWrapper->getWfScheduler() != null)
                {
                    echo("WF Scheduler: " . $recordActionWrapper->getWfScheduler() . "\n");
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

RemoveTagsFromMultipleRecords::initialize();
$moduleAPIName = "Leads";
$recordIds = array("34770615623115", "34770616454014");
$tagNames = array("addtag1", "addtag12");
RemoveTagsFromMultipleRecords::removeTagsFromMultipleRecords($moduleAPIName, $recordIds, $tagNames);