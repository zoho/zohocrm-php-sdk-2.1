<?php
namespace samples\attachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\attachments\AttachmentsOperations;
use com\zoho\crm\api\attachments\APIException;
use com\zoho\crm\api\attachments\FileBodyWrapper;
require_once "vendor/autoload.php";

class DownloadAttachment
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

    public static function downloadAttachment(string $moduleAPIName, string $recordId, string $attachmentId, string $destinationFolder)
    {
        $attachmentOperations = new AttachmentsOperations($moduleAPIName, $recordId);
        $response = $attachmentOperations->downloadAttachment($attachmentId);
        if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if($response->getStatusCode() == 204)
            {
                echo("No Content\n");
                return;
            }
            $responseHandler = $response->getObject();
            if($responseHandler instanceof FileBodyWrapper)
            {
                    $fileBodyWrapper = $responseHandler;
                    $streamWrapper = $fileBodyWrapper->getFile();
                    $fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");
                    $stream = $streamWrapper->getStream();
                    fputs($fp, $stream);
                    fclose($fp);
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
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
    }
}

DownloadAttachment::initialize();
$moduleAPIName = "Leads";
$recordId = "347706111829018";
$attachmentId = "347706111902001";
$destinationFolder = "/Documents";
DownloadAttachment::downloadAttachment($moduleAPIName, $recordId, $attachmentId, $destinationFolder);