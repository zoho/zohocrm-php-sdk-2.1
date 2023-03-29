<?php
namespace samples\variables;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\variables\APIException;
use com\zoho\crm\api\variables\ResponseWrapper;
use com\zoho\crm\api\variables\VariablesOperations;
use com\zoho\crm\api\variables\GetVariableByIDParam;
require_once "vendor/autoload.php";

class GetVariableById
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

	public static function getVariableById(string $variableId)
	{
		$variablesOperations = new VariablesOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetVariableByIDParam::group(), "34770613089001");//"General"
		$response = $variablesOperations->getVariableById($variableId,$paramInstance);
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
                $variables = $responseWrapper->getVariables();
                if($variables != null)
                {
                    foreach($variables as $variable)
                    {
                        echo("Variable APIName: " . $variable->getAPIName() . "\n");
                        echo("Variable Name: " . $variable->getName() . "\n");
                        echo("Variable Description: " . $variable->getDescription() . "\n");
                        echo("Variable ID: " . $variable->getId() . "\n");
                        echo("Variable Type: " . $variable->getType() . "\n");
                        $variableGroup = $variable->getVariableGroup();
                        if($variableGroup != null)
                        {
                            echo("Variable VariableGroup APIName: " . $variableGroup->getAPIName() . "\n");
                            echo("Variable VariableGroup ID: " . $variableGroup->getId() . "\n");
                        }
                        echo("Variable Value: " . $variable->getValue() . "\n");
                    }
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
	}
}

GetVariableById::initialize();
$variableId = "347706117195017";
GetVariableById::getVariableById($variableId);