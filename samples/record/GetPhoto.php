<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\FileBodyWrapper;
use com\zoho\crm\api\record\RecordOperations;
require_once "vendor/autoload.php";

class GetPhoto
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

	public static function getPhoto(string $moduleAPIName, string $recordId, string $destinationFolder)
	{
		$recordOperations = new RecordOperations();
		$response = $recordOperations->getPhoto($recordId,$moduleAPIName );
		if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
			}
			if($response->isExpected())
			{
				$downloadHandler = $response->getObject();
				if($downloadHandler instanceof FileBodyWrapper)
				{
					$fileBodyWrapper = $downloadHandler;
					$streamWrapper = $fileBodyWrapper->getFile();
					$fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");
					$stream = $streamWrapper->getStream();
					fputs($fp, $stream);
					fclose($fp);
				}
				else if($downloadHandler instanceof APIException)
				{
					$exception = $downloadHandler;
					echo("Status: " . $exception->getStatus()->getValue() . "\n");
					echo("Code: " . $exception->getCode()->getValue() . "\n");
					if($exception->getDetails() != null)
					{
						echo("Details: \n");
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

GetPhoto::initialize();
$moduleAPIName = "Leads";
$recordId = "347706118046059";
$destinationFolder = "/Documents/";
GetPhoto::getPhoto($moduleAPIName, $recordId, $destinationFolder);