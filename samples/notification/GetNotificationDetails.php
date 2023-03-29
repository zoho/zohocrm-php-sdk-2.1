<?php
namespace samples\notification;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\notification\APIException;
use com\zoho\crm\api\notification\NotificationOperations;
use com\zoho\crm\api\notification\GetNotificationDetailsParam;
use com\zoho\crm\api\notification\ResponseWrapper;
require_once "vendor/autoload.php";

class GetNotificationDetails
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

	public static function getNotificationDetails()
	{
		$notificationOperations = new NotificationOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetNotificationDetailsParam::channelId(), "1006800211");
		$paramInstance->add(GetNotificationDetailsParam::module(), "Accounts");
		$paramInstance->add(GetNotificationDetailsParam::page(), 1);
		$paramInstance->add(GetNotificationDetailsParam::perPage(), 2);
		$response = $notificationOperations->getNotificationDetails($paramInstance);
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
				$responseHandler = $response->getObject();
				if($responseHandler instanceof ResponseWrapper)
				{
					$responseWrapper = $responseHandler;
					$notifications = $responseWrapper->getWatch();
					foreach($notifications as $notification)
					{
						echo("Notification NotifyOnRelatedAction: " . $notification->getNotifyOnRelatedAction() . "\n");
						echo("Notification ChannelExpiry: "); print_r($notification->getChannelExpiry());
						echo("Notification ResourceUri: " . $notification->getResourceUri() . "\n");
						echo("Notification ResourceId: " . $notification->getResourceId() . "\n");
						echo("Notification NotifyUrl: " . $notification->getNotifyUrl() . "\n");
						echo("Notification ResourceName: " . $notification->getResourceName() . "\n");
						echo("Notification ChannelId: " . $notification->getChannelId() . "\n");
						$fields = $notification->getEvents();
						if($fields != null)
						{
							foreach($fields as $fieldName)
							{
								echo("Notification Events: " . $fieldName . "\n");
							}
						}
						echo("Notification Token: " . $notification->getToken() . "\n");
					}
					$info = $responseWrapper->getInfo();
					if($info != null)
					{
						echo("Record Info PerPage: " . $info->getPerPage() . "\n");
						echo("Record Info Count: " . $info->getCount() . "\n");
						echo("Record Info Page: " . $info->getPage() . "\n");
						echo("Record Info MoreRecords: "); print_r($info->getMoreRecords()); echo("\n");
					}
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
			else if($response->getStatusCode() != 204 )
			{
				print_r($response);
			}
		}
	}
}
GetNotificationDetails::initialize();
GetNotificationDetails::getNotificationDetails();