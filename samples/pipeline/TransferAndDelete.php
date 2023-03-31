<?php
namespace samples\pipeline;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\pipeline\PipelineOperations;
use com\zoho\crm\api\pipeline\TransferAndDeleteWrapper;
use com\zoho\crm\api\pipeline\Stage;
use com\zoho\crm\api\pipeline\TransferPipeLine;
use com\zoho\crm\api\pipeline\SuccessResponse;
use com\zoho\crm\api\pipeline\APIException;
use com\zoho\crm\api\pipeline\TransferActionWrapper;
require_once "vendor/autoload.php";

class TransferAndDelete
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

    public static function transferAndDelete($layoutId)
    {
        $pipelineOperations = new PipelineOperations($layoutId);
        $transferAndDeleteWrapper = new TransferAndDeleteWrapper();
        $transferPipeLine = new TransferPipeLine();
        $pipelineClass = "com\\zoho\\crm\\api\\pipeline\\PipeLine";
        $pipeline = new $pipelineClass();
        $pipeline->setFrom("36523973712004");
        $pipeline->setTo("36523973097007");
        $transferPipeLine->setPipeline($pipeline);
        $stage = new Stage();
        $stage->setFrom("3652396817");
        $stage->setTo("3652396819");
        $transferPipeLine->setStages([$stage]);
        $transferAndDeleteWrapper->setTransferPipeline([$transferPipeLine]);
        $response = $pipelineOperations->transferAndDelete($transferAndDeleteWrapper);
        if($response != null)
        {
            echo("Status code" . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof TransferActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getTransferPipeline();
                foreach ($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
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
                    else if($actionResponse instanceof APIException)
                    {
                        $exception = $actionResponse;
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

TransferAndDelete::initialize();
$layoutId = "34770610091023";
TransferAndDelete::transferAndDelete($layoutId);