<?php
namespace samples\bulkwrite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkwrite\FileBodyWrapper;
use com\zoho\crm\api\bulkwrite\BulkWriteOperations;
use com\zoho\crm\api\util\StreamWrapper;
use com\zoho\crm\api\bulkwrite\UploadFileHeader;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\bulkwrite\SuccessResponse;
use com\zoho\crm\api\bulkwrite\APIException;
require_once "vendor/autoload.php";

class UploadFile
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

	public static function uploadFile(string $orgID, string $absoluteFilePath)
	{
		$bulkWriteOperations = new BulkWriteOperations();
		$fileBodyWrapper = new FileBodyWrapper();
	    $streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);
        // $file = fopen($absoluteFilePath, "rb");
        // $stream = fread($file, filesize($absoluteFilePath));
        // fclose($file);
		// $streamWrapper = new StreamWrapper(basename($absoluteFilePath), $stream);
		$fileBodyWrapper->setFile($streamWrapper);
		$headerInstance = new HeaderMap();
		$headerInstance->add(UploadFileHeader::feature(), "bulk-write");
		$headerInstance->add(UploadFileHeader::XCRMORG(), $orgID);
        $response = $bulkWriteOperations->uploadFile($fileBodyWrapper, $headerInstance);
        if($response != null)
        {
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if($actionResponse instanceof SuccessResponse)
            {
                $successResponse = $actionResponse;
                echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo("Details: " );
                foreach($successResponse->getDetails() as $key => $value)
                {
                    echo( $key . " : ");
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
                if($exception->getDetails() != null)
                {
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value . "\n");
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }
}

UploadFile::initialize();
$orgID = "xxxx";
$absoluteFilePath = "/Documents/Leads.zip";
UploadFile::uploadFile($orgID, $absoluteFilePath);
