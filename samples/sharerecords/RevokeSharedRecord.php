<?php
namespace samples\sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\DeleteActionWrapper;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\sharerecords\SuccessResponse;
require_once "vendor/autoload.php";

class RevokeSharedRecord
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

	public static function revokeSharedRecord(string $moduleAPIName, string $recordId)
	{
	    $shareRecordsOperations = new ShareRecordsOperations($recordId, $moduleAPIName);
		$response = $shareRecordsOperations->revokeSharedRecord();
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $deleteActionHandler = $response->getObject();
            if($deleteActionHandler instanceof DeleteActionWrapper)
            {
                $deleteActionWrapper = $deleteActionHandler;
                $deleteActionResponse = $deleteActionWrapper->getShare();
                if($deleteActionResponse instanceof SuccessResponse)
                {
                    $successResponse = $deleteActionResponse;
                    echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                    echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                    echo("Details: " );
                    foreach($successResponse->getDetails() as $key => $value)
                    {
                        echo($key . " : " . $value . "\n");
                    }
                    echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
                }
                else if($deleteActionResponse instanceof APIException)
                {
                    $exception = $deleteActionResponse;
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
            else if($deleteActionHandler instanceof APIException)
            {
                $exception = $deleteActionHandler;
                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                echo("Code: " . $exception->getCode()->getValue() . "\n");
                if($exception->getDetails() != null)
                {
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . " : " . $value . "\n");
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}
}

RevokeSharedRecord::initialize();
$moduleAPIName = "Leads";
$recordId = "34770615623115";
RevokeSharedRecord::revokeSharedRecord($moduleAPIName, $recordId);