<?php
namespace samples\attachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\attachments\AttachmentsOperations;
use com\zoho\crm\api\attachments\APIException;
use com\zoho\crm\api\attachments\FileBodyWrapper;
use com\zoho\crm\api\util\StreamWrapper;
use com\zoho\crm\api\attachments\SuccessResponse;
use com\zoho\crm\api\attachments\ActionWrapper;
require_once "vendor/autoload.php";

class UploadAttachments
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

    public static function uploadAttachments(string $moduleAPIName, string $recordId, string $absoluteFilePath)
    {
        $attachmentOperations = new AttachmentsOperations($moduleAPIName, $recordId);
        $fileBodyWrapper = new FileBodyWrapper();
        $streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);
		$fileBodyWrapper->setFile($streamWrapper);
        $response = $attachmentOperations->uploadAttachment($fileBodyWrapper);
        if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getData();
                foreach ($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status : " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code : " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details : \n" );
                        if($successResponse->getDetails() != null)
                        {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue)
                            {
                                echo($keyName . " : "); print_r($keyValue); echo("\n");
                            }
                        }
                        echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
                    }
                    else if($actionResponse instanceof APIException)
                    {
                        $exception = $actionResponse;
                        echo("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo("Code: " . $exception->getCode()->getValue() . "\n");
                        echo("Details: \n" );
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
            }
            else if($actionHandler instanceof APIException)
            {
                $exception = $actionHandler;
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

UploadAttachments::initialize();
$moduleAPIName = "Leads";
$recordId = "347706111829018";
$absoluteFilePath = "/Documents/download.png";
UploadAttachments::uploadAttachments($moduleAPIName, $recordId, $absoluteFilePath);
