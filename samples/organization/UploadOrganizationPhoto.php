<?php
namespace samples\organization;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\org\APIException;
use com\zoho\crm\api\org\OrgOperations;
use com\zoho\crm\api\org\SuccessResponse;
use com\zoho\crm\api\org\FileBodyWrapper;
use com\zoho\crm\api\util\StreamWrapper;
require_once "vendor/autoload.php";

class UploadOrganizationPhoto
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

	public static function uploadOrganizationPhoto(string $absoluteFilePath)
	{
		$orgOperations = new OrgOperations();
		$fileBodyWrapper = new FileBodyWrapper();
		$streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);
		$fileBodyWrapper->setFile($streamWrapper);
		$response = $orgOperations->uploadOrganizationPhoto($fileBodyWrapper);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if($actionResponse instanceof SuccessResponse)
            {
                $successResponse = $actionResponse;
                echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo("Details: " );
                if($successResponse->getDetails() != null)
                {
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
					echo($key . ": " .$value . "\n");
				}
				echo("Message: " . $exception->getMessage()->getValue() . "\n");
			}
		}
	}
}

UploadOrganizationPhoto::initialize();
$absoluteFilePath = "/Documents/download.png";
UploadOrganizationPhoto::uploadOrganizationPhoto($absoluteFilePath);