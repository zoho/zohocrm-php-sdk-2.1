<?php
namespace samples\bulkwrite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkwrite\FileBodyWrapper;
use com\zoho\crm\api\bulkwrite\BulkWriteOperations;
use com\zoho\crm\api\bulkwrite\APIException;
require_once "vendor/autoload.php";

class DownloadBulkWriteResult
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

    public static function downloadBulkWriteResult(string $downloadUrl, string $destinationFolder)
	{
		$bulkWriteOperations = new BulkWriteOperations();
        $response = $bulkWriteOperations->downloadBulkWriteResult($downloadUrl);
        if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if($responseHandler instanceof FileBodyWrapper)
            {
                $fileBodyWrapper = $responseHandler;
                $streamWrapper = $fileBodyWrapper->getFile();
                //Create a file instance with the absolute_file_path
                $fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");
                $stream = $streamWrapper->getStream();
                fputs($fp, $stream);
                fclose($fp);
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                if($exception->getStatus() != null)
                {
                    echo("Status: " . $exception->getStatus()->getValue());
                }
                if($exception->getCode() != null)
                {
                    echo("Code: " . $exception->getCode()->getValue());
                }
                if($exception->getDetails() != null)
                {
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value);
                    }
                }
                if($exception->getMessage() != null)
                {
                    echo("Message: " . $exception->getMessage()->getValue());
                }
                if($exception->getXError() != null)
                {
                    echo("XError: " . $exception->getXError()->getValue());
                }
                if($exception->getXInfo() != null)
                {
                    echo("XInfo: " . $exception->getXInfo()->getValue());
                }
                if($exception->getHttpStatus() != null)
                {
                    echo("Message: " . $exception->getHttpStatus());
                }
            }
        }
    }
}

DownloadBulkWriteResult::initialize();
$downloadUrl = "https://download-accl.zoho.com/v2/crm/673573045/bulk-write/347706118196001/347706118196001.zip";
$destinationFolder = "/Documents";
DownloadBulkWriteResult::downloadBulkWriteResult($downloadUrl, $destinationFolder);
