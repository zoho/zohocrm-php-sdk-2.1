<?php
namespace samples\sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\ResponseWrapper;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\sharerecords\GetSharedRecordDetailsParam;
require_once "vendor/autoload.php";

class GetSharedRecordDetails
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

	public static function getSharedRecordDetails(string $moduleAPIName, string $recordId)
	{
	    $shareRecordsOperations = new ShareRecordsOperations( $recordId,$moduleAPIName);
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetSharedRecordDetailsParam::view(), "summary");
		// $paramInstance->add(GetSharedRecordDetailsParam::sharedTo(), "34770615791024");
		$response = $shareRecordsOperations->getSharedRecordDetails($paramInstance);
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
                $shareRecords = $responseWrapper->getShare();
                if($shareRecords != null)
                {
                    foreach($shareRecords as $shareRecord)
                    {
                        echo("ShareRecord ShareRelatedRecords: "); print_r($shareRecord->getShareRelatedRecords()); echo("\n");
                        $sharedThrough = $shareRecord->getSharedThrough();
                        if($sharedThrough != null)
                        {
                            echo("RelatedRecord SharedThrough EntityName: " . $sharedThrough->getEntityName() . "\n");
                            $module = $sharedThrough->getModule();
                            if($module != null)
                            {
                                echo("RelatedRecord SharedThrough Module ID: " . $module->getId() . "\n");
                                echo("RelatedRecord SharedThrough Module Name: " . $module->getName() . "\n");
                            }
                            echo("RelatedRecord SharedThrough ID: " . $sharedThrough->getId() . "\n");
                        }
                        echo("ShareRecord SharedTime: "); print_r($shareRecord->getSharedTime()); echo("\n");
                        echo("ShareRecord Permission: " . $shareRecord->getPermission() . "\n");
                        $user = $shareRecord->getUser();
                        if($user != null)
                        {
                            echo("ShareRecord User-ID: " . $user->getId() . "\n");
                            echo("RelatedRecord User-FullName: " . $user->getFullName() . "\n");
                            echo("RelatedRecord User-Zuid: " . $user->getZuid() . "\n");
                        }
                        $sharedBy = $shareRecord->getSharedBy();
                        if($sharedBy != null)
                        {
                            echo("ShareRecord SharedBy User-ID: " . $sharedBy->getId() . "\n");
                            echo("RelatedRecord SharedBy User-FullName: " . $sharedBy->getFullName() . "\n");
                            echo("RelatedRecord SharedBy User-Zuid: " . $sharedBy->getZuid() . "\n");
                        }
                    }
                }
                if($responseWrapper->getShareableUser() != null)
                {
                    $shareableUsers = $responseWrapper->getShareableUser();
                    foreach($shareableUsers as $shareableUser)
                    {
                        echo("ShareRecord User-ID: " . $shareableUser->getId());
                        echo("ShareRecord User-FullName: " . $shareableUser->getFullName());
                        echo("ShareRecord User-Zuid: " . $shareableUser->getZuid());
                    }
                }
            }
            else if($responseHandler instanceof APIException)
			{
				$exception = $responseHandler;
				echo("Status: " . $exception->getStatus()->getValue() . "\n");
				echo("Code: " . $exception->getCode()->getValue() . "\n");
                if($exception->getDetails() != null)
                {
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " .$value . "\n");
                    }
                }
				echo("Message: " . $exception->getMessage()->getValue() . "\n");
			}
		}
	}
}

GetSharedRecordDetails::initialize();
$moduleAPIName = "Leads";
$recordId = "34770615623115";
GetSharedRecordDetails::getSharedRecordDetails($moduleAPIName, $recordId);