<?php
namespace samples\contactroles;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\contactroles\BodyWrapper;
use com\zoho\crm\api\contactroles\ContactRolesOperations;
use com\zoho\crm\api\contactroles\ContactRole;
use com\zoho\crm\api\contactroles\APIException;
use com\zoho\crm\api\contactroles\ActionWrapper;
use com\zoho\crm\api\contactroles\SuccessResponse;
require_once "vendor/autoload.php";

class UpdateContactRoles
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

	public static function updateContactRoles()
	{
		$contactRolesOperations = new ContactRolesOperations();
		$bodyWrapper = new BodyWrapper();
		$contactRolesList = array();
		$cr1 = new ContactRole();
		$cr1->setId("347706112212001");
		$cr1->setName("Edited1we");
		array_push($contactRolesList, $cr1);
		$cr2 = new ContactRole();
		$cr2->setId("34770619608009");
		$cr2->setName("Edited2");
		array_push($contactRolesList, $cr2);
		$bodyWrapper->setContactRoles($contactRolesList);
        $response = $contactRolesOperations->updateContactRoles($bodyWrapper);
        if($response != null)
        {
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getContactRoles();
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

UpdateContactRoles::initialize();
UpdateContactRoles::updateContactRoles();