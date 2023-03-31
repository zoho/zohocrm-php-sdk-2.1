<?php
namespace samples\fieldattachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\fieldattachments\FieldAttachmentsOperations;
use com\zoho\crm\api\fieldattachments\FileBodyWrapper;
use com\zoho\crm\api\fieldattachments\APIException;
require_once "vendor/autoload.php";

class GetFieldAttachments
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

    public static function getFieldAttachments(string $moduleAPIName, string $recordId, string $fieldsAttachmentId=null, $destinationFolder=null)
    {
        $fieldAttachmentsOperations = new FieldAttachmentsOperations($moduleAPIName, $recordId, $fieldsAttachmentId);
        $response = $fieldAttachmentsOperations->getFieldAttachments();
        if($response != null)
		{
            echo("Status code : " . $response->getStatusCode() . "\n");
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
GetFieldAttachments::initialize();
$destinationFolder = "/Documents";
GetFieldAttachments::getFieldAttachments("Leads","34770616920147","34770619483", $destinationFolder);
?>
