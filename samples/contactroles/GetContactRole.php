<?php
namespace samples\contactroles;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\contactroles\ContactRolesOperations;
use com\zoho\crm\api\contactroles\ResponseWrapper;
use com\zoho\crm\api\contactroles\APIException;
require_once "vendor/autoload.php";

class GetContactRole
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

	public static function getContactRole(string $contactRoleId)
	{
		$contactRolesOperations = new ContactRolesOperations();
        $response = $contactRolesOperations->getContactRole($contactRoleId);
        if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $contactRoles = $responseWrapper->getContactRoles();
                foreach($contactRoles as $contactRole)
                {
                    echo("ContactRole ID: " . $contactRole->getId() . "\n");
                    echo("ContactRole Name: " . $contactRole->getName() . "\n");
                    echo("ContactRole SequenceNumber: " . $contactRole->getSequenceNumber() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo("Status: " . $exception->getStatus()->getValue());
                echo("Code: " . $exception->getCode()->getValue());
                echo("Details: " );
                foreach($exception->getDetails() as $key => $value)
                {
                    echo($key . ": " . $value);
                }
                echo("Message: " . $exception->getMessage()->getValue());
            }
        }
    }
}

GetContactRole::initialize();
$contactRoleId = "34770610207275";
GetContactRole::getContactRole($contactRoleId);