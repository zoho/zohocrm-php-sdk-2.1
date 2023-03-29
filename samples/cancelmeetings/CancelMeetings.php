<?php
namespace samples\cancelmeetings;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\cancelmeetings\ActionWrapper;
use com\zoho\crm\api\cancelmeetings\APIException;
use com\zoho\crm\api\cancelmeetings\CancelMeetingsOperations;
use com\zoho\crm\api\cancelmeetings\BodyWrapper;
use com\zoho\crm\api\cancelmeetings\Notify;
use com\zoho\crm\api\cancelmeetings\SuccessResponse;
require_once "vendor/autoload.php";

class CancelMeetings
{
    public static function initialize()
    {
        $user = new UserSignature('myname@mydomain.com');
        $environment = USDataCenter::PRODUCTION();
        $token = (new OAuthBuilder())
        ->clientId("1000.xxx")
        ->clientSecret("xxxx")
        ->refreshToken("1000.xx.xxx")
        ->build();
        (new InitializeBuilder())
            ->user($user)
            ->environment($environment)
            ->token($token)
            ->initialize();
    }

    public static function cancelmeetings(int $event_id, bool $send_cancel_mail)
    {
        $cmo = new CancelMeetingsOperations($event_id);
        $bodyWrapper = new BodyWrapper();
        $notify = new Notify();
        $notify->setSendCancellingMail($send_cancel_mail);
        $notify_list = array();
        array_push($notify_list,$notify);
        $bodyWrapper->setData($notify_list);
        $response = $cmo->cancelMeetings($bodyWrapper);
        if($response != null)
        {
            echo("Status code" . $response->getStatusCode() . "\n");
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

CancelMeetings::initialize();
$eventId = "34352434";
$send_cancel_mail = true;
CancelMeetings::cancelmeetings($eventId, $send_cancel_mail);