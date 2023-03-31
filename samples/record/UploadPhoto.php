<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\FileBodyWrapper;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\util\StreamWrapper;
require_once "vendor/autoload.php";

class UploadPhoto
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

	public static function uploadPhoto(string $moduleAPIName, string $recordId, string $absoluteFilePath)
	{
		$recordOperations = new RecordOperations();
		$fileBodyWrapper = new FileBodyWrapper();
		$streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);
		$fileBodyWrapper->setFile($streamWrapper);
		$response = $recordOperations->uploadPhoto($recordId, $moduleAPIName,$fileBodyWrapper);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$fileHandler = $response->getObject();
				if($fileHandler instanceof SuccessResponse)
				{
					$successResponse = $fileHandler;
					echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
					echo("Code: " . $successResponse->getCode()->getValue() . "\n");
					echo("Details: " );
					if($successResponse->getDetails() != null)
					{
						foreach ($successResponse->getDetails() as $keyName => $keyValue)
						{
							echo($keyName . ": " . $keyValue . "\n");
						}
					}
					echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
				}
				else if($fileHandler instanceof APIException)
				{
					$exception = $fileHandler;
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
			else
			{
				print_r($response);
			}
		}
	}
}

UploadPhoto::initialize();
$moduleAPIName = "Leads";
$recordId = "347706118046059";
$absoluteFilePath = "/Documents/download.png";
UploadPhoto::uploadPhoto($moduleAPIName, $recordId, $absoluteFilePath);