<?php
namespace samples\modules;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\modules\APIException;
use com\zoho\crm\api\modules\ActionWrapper;
use com\zoho\crm\api\modules\BodyWrapper;
use com\zoho\crm\api\modules\Module;
use com\zoho\crm\api\modules\ModulesOperations;
use com\zoho\crm\api\modules\SuccessResponse;
use com\zoho\crm\api\profiles\Profile;
require_once "vendor/autoload.php";

class UpdateModuleById
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

	public static function updateModuleById(string $moduleId)
	{
		$moduleOperations = new ModulesOperations();
		$modules = array();
		$profiles = array();
		$profile = new Profile();
		$profile->setId("34770610026014");
		array_push($profiles, $profile);
		$module = new Module();
		$module->setProfiles($profiles);
		$module->setAPIName("apiName2");
		array_push($modules, $module);
		$request = new BodyWrapper();
		$request->setModules($modules);
		$response = $moduleOperations->updateModuleById($moduleId, $request);
		if($response != null)
		{
			echo("Status code " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getModules();
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

UpdateModuleById::initialize();
$moduleId = "34770613753009";
UpdateModuleById::updateModuleById($moduleId);
