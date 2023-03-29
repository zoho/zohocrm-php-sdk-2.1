<?php
namespace samples\variables;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\variables\APIException;
use com\zoho\crm\api\variables\ActionWrapper;
use com\zoho\crm\api\variables\BodyWrapper;
use com\zoho\crm\api\variables\SuccessResponse;
use com\zoho\crm\api\variables\VariablesOperations;
use com\zoho\crm\api\variables\Variable;
require_once "vendor/autoload.php";

class UpdateVariableById
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

	public static function updateVariableById(string $variableId)
	{
		$variablesOperations = new VariablesOperations();
		$request = new BodyWrapper();
        $variableList = array();
		$variable1 = new Variable();
		$variable1->setAPIName("TestAPIName");
		array_push($variableList, $variable1);
		$request->setVariables($variableList);
		$response = $variablesOperations->updateVariableById($variableId,$request);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getVariables();
                foreach($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details: " );
                        foreach($successResponse->getDetails() as $key => $value)
                        {
                            echo($key . " : " . $value . "\n");
                        }
                        echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
                    }
                    else if($actionResponse instanceof APIException)
                    {
                        $exception = $actionResponse;
                        echo("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo("Code: " . $exception->getCode()->getValue() . "\n");
                        echo("Details: " );
                        foreach($exception->getDetails() as $key => $value)
                        {
                            echo($key . " : " . $value . "\n");
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

UpdateVariableById::initialize();
$variableId = "347706117195017";
UpdateVariableById::updateVariableById($variableId);