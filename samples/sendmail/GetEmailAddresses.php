<?php
namespace samples\sendmail;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\sendmail\SendMailOperations;
use com\zoho\crm\api\sendmail\ResponseWrapper;
use com\zoho\crm\api\sendmail\APIException;
require_once "vendor/autoload.php";

class GetEmailAddresses
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

    public static function getEmailAddresses()
    {
        $sendMailOperations = new SendMailOperations();
        $response = $sendMailOperations->getEmailAddresses();
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
                $emails = $responseWrapper->getFromAddresses();
                foreach($emails as $email)
                {
                    echo("UserName: " . $email->getUserName() . "\n");
                    echo("Mail Type: " . $email->getType() . "\n");
                    echo("Mail : " . $email->getEmail() . "\n");
                    echo("Mail ID: " . $email->getId() . "\n");
                    echo("Mail Default: " . $email->getDefault() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
			{
				$exception = $responseHandler;
				echo("Status: " . $exception->getStatus()->getValue() . "\n");
				echo("Code: " . $exception->getCode()->getValue() . "\n");
				echo("Details: " );
				foreach($exception->getDetails() as $key => $value)
				{
					echo($key . ": " .$value . "\n");
				}
				echo("Message: " . $exception->getMessage()->getValue() . "\n");
			}
        }
    }
}

GetEmailAddresses::initialize();
GetEmailAddresses::getEmailAddresses();