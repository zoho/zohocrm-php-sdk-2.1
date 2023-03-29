<?php
namespace samples\variables;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\variablegroups\VariableGroup;
use com\zoho\crm\api\variables\APIException;
use com\zoho\crm\api\variables\ActionWrapper;
use com\zoho\crm\api\variables\BodyWrapper;
use com\zoho\crm\api\variables\SuccessResponse;
use com\zoho\crm\api\variables\VariablesOperations;
use com\zoho\crm\api\variables\Variable;
require_once "vendor/autoload.php";

class CreateVariables
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

	public static function createVariables()
	{
		$variablesOperations = new VariablesOperations();
		$request = new BodyWrapper();
		$variableList = array();
		$variable1 = new Variable();
		$variable1->setName("asdasd");
		$variable1->setAPIName("sdsd");
		$variableGroup = new VariableGroup();
		$variableGroup->setName("General");
		$variableGroup->setId("34770613089001");
		$variable1->setVariableGroup($variableGroup);
		$variable1->setType("integer");
		$variable1->setValue("42");
		$variable1->setDescription("This denotes variable 5 description");
		array_push($variableList, $variable1);
		$variable1 = new Variable();
		$variable1->setName("Variable661");
		$variable1->setAPIName("Variable661");
		$variableGroup = new VariableGroup();
		$variableGroup->setName("General");
		$variable1->setVariableGroup($variableGroup);
		$variable1->setType("text");
		$variable1->setValue("H2ello");
		$variable1->setDescription("This denotes variable 6 description");
		array_push($variableList, $variable1);
		$request->setVariables($variableList);
		$response = $variablesOperations->createVariables($request);
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

CreateVariables::initialize();
CreateVariables::createVariables();