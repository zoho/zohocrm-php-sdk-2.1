<?php
namespace samples\pipeline;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\pipeline\PipelineOperations;
use com\zoho\crm\api\pipeline\APIException;
use com\zoho\crm\api\pipeline\ResponseWrapper;
require_once "vendor/autoload.php";

class GetPipelines
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

    public static function getPipelines($layoutId)
    {
        $pipelineOperations = new PipelineOperations($layoutId);
        $response = $pipelineOperations->getPipelines();
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
                $pipelines = $responseWrapper->getPipeline();
                foreach($pipelines as $pipeline)
                {
                    echo("Pipeline DisplayValue: " . $pipeline->getDisplayValue() . "\n");
                    echo("Pipeline Default: "); print_r($pipeline->getDefault()); echo("\n");
                    $maps = $pipeline->getMaps();
                    if($maps != null)
                    {
                        foreach($maps as $map)
                        {
                            echo("Pipeline Maps DisplayValue: " . $map->getDisplayValue() . "\n");
                            echo("Pipeline Maps SequenceNumber: " . $map->getSequenceNumber() . "\n");
                            $forecastCategory = $map->getForecastCategory();
                            if($forecastCategory != null)
                            {
                                echo("Pipeline Maps ForecastCategory Name: " . $forecastCategory->getName() . "\n");
                                echo("Pipeline Maps ForecastCategory Id: " . $forecastCategory->getId() . "\n");
                            }
                            echo("Pipeline Maps ActualValue: " . $map->getActualValue() . "\n");
                            echo("Pipeline Maps Id: " . $map->getId() . "\n");
                            echo("Pipeline Maps ForecastType: " . $map->getForecastType() . "\n");
                        }
                    }
                    echo("Pipeline Maps ActualValue: " . $pipeline->getActualValue() . "\n");
                    echo("Pipeline Id: " . $pipeline->getId() . "\n");
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

GetPipelines::initialize();
$layoutId = "34770610091023";
GetPipelines::getPipelines($layoutId);