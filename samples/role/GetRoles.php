<?php
namespace samples\role;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\roles\APIException;
use com\zoho\crm\api\roles\ResponseWrapper;
use com\zoho\crm\api\roles\RolesOperations;
require_once "vendor/autoload.php";

class GetRoles
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

	public static function getRoles()
	{
		$rolesOperations = new RolesOperations();
		$response = $rolesOperations->getRoles();
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
                $roles = $responseWrapper->getRoles();
                foreach($roles as $role)
                {
                    echo("Role DisplayLabel: " . $role->getDisplayLabel() . "\n");
                    $forecastManager = $role->getForecastManager();
                    if($forecastManager != null)
                    {
                        echo("Role Forecast Manager User-ID: " . $forecastManager->getId() . "\n");
                        echo("Role Forecast Manager User-Name: " . $forecastManager->getName() . "\n");
                    }
                    echo("Role ShareWithPeers: "); print_r($role->getShareWithPeers()); echo("\n");
                    echo("Role Name: " . $role->getName() . "\n");
                    echo("Role Description: " . $role->getDescription() . "\n");
                    echo("Role ID: " . $role->getId() . "\n");
                    $reportingTo = $role->getReportingTo();
                    if($reportingTo != null)
                    {
                        echo("Role ReportingTo User-ID: " . $reportingTo->getId() . "\n");
                        echo("Role ReportingTo User-Name: " . $reportingTo->getName() . "\n");
                    }
                    echo("Role AdminUser: "); print_r($role->getAdminUser()); echo("\n");
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

GetRoles::initialize();
GetRoles::getRoles();