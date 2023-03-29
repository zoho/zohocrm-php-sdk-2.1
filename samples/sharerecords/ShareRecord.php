<?php
namespace samples\sharerecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\sharerecords\APIException;
use com\zoho\crm\api\sharerecords\ActionWrapper;
use com\zoho\crm\api\sharerecords\BodyWrapper;
use com\zoho\crm\api\sharerecords\ShareRecordsOperations;
use com\zoho\crm\api\sharerecords\SuccessResponse;
use com\zoho\crm\api\users\User;
require_once "vendor/autoload.php";

class ShareRecord
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

	public static function shareRecord(string $moduleAPIName, string $recordId)
	{
	    $shareRecordsOperations = new ShareRecordsOperations( $recordId, $moduleAPIName);
		$request = new BodyWrapper();
		$shareList = array();
        $shareRecordClass = "com\zoho\crm\api\sharerecords\ShareRecord";
		for($i = 0; $i < 1; $i++)
		{
			$share1 = new $shareRecordClass();
			$share1->setShareRelatedRecords(true);
			$share1->setPermission("read_write");
			$user = new User();
			$user->setId("34770615791024");
			$share1->setUser($user);
			array_push($shareList, $share1);
        }
        $share1 = new $shareRecordClass();
		$share1->setShareRelatedRecords(true);
		$share1->setPermission("read_write");
		$user = new User();
		$user->setId("34770615791024");
		$share1->setUser($user);
		array_push($shareList, $share1);
		$request->setShare($shareList);
		$response = $shareRecordsOperations->shareRecord($request);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getShare();
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
                                echo($key . " : " . $value . "\n");
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

ShareRecord::initialize();
$moduleAPIName = "Leads";
$recordId = "34770615623115";
ShareRecord::shareRecord($moduleAPIName, $recordId);