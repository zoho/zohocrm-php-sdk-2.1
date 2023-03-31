<?php
namespace samples\bulkwrite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkwrite\BulkWriteOperations;
use com\zoho\crm\api\bulkwrite\SuccessResponse;
use com\zoho\crm\api\bulkwrite\APIException;
use com\zoho\crm\api\bulkwrite\RequestWrapper;
use com\zoho\crm\api\bulkwrite\CallBack;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\bulkwrite\Resource;
use com\zoho\crm\api\bulkwrite\FieldMapping;
use com\zoho\crm\api\modules\Module;
require_once "vendor/autoload.php";

class CreateBulkWriteJob
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

	public static function createBulkWriteJob(string $moduleAPIName, string $fileId)
	{
		$bulkWriteOperations = new BulkWriteOperations();
		$requestWrapper = new RequestWrapper();
		$callback = new CallBack();
		$callback->setUrl("https://www.example.com/callback");
		$callback->setMethod(new Choice("post"));
		$requestWrapper->setCallback($callback);
		$requestWrapper->setCharacterEncoding("UTF-8");
		$requestWrapper->setOperation(new Choice("insert"));
		$resource = array();
		$resourceIns = new Resource();
		$resourceIns->setType(new Choice("data"));
        $module = new Module();
        $module->setAPIName($moduleAPIName);
		$resourceIns->setModule($module);
		$resourceIns->setFileId($fileId);
		$resourceIns->setIgnoreEmpty(true);
		$resourceIns->setFindBy("Email");
		$fieldMappings = array();
		$fieldMapping = new FieldMapping();
		$fieldMapping->setAPIName("Last_Name");
		$fieldMapping->setIndex(0);
		array_push($fieldMappings, $fieldMapping);
        $fieldMapping = new FieldMapping();
        $fieldMapping->setAPIName("Email");
		$fieldMapping->setIndex(1);
		array_push($fieldMappings, $fieldMapping);
		$fieldMapping = new FieldMapping();
        $fieldMapping->setAPIName("Company");
		$fieldMapping->setIndex(2);
		array_push($fieldMappings, $fieldMapping);
		$fieldMapping = new FieldMapping();
        $fieldMapping->setAPIName("Phone");
		$fieldMapping->setIndex(3);
		array_push($fieldMappings, $fieldMapping);
		$fieldMapping = new FieldMapping();
        $fieldMapping->setAPIName("Website");
        //$fieldMapping->setFormat("");
        //$fieldMapping->setFindBy("");
        $defaultValue = array();
        $defaultValue["value"] = "https://www.zohoapis.com";
        $fieldMapping->setDefaultValue($defaultValue);
		array_push($fieldMappings, $fieldMapping);
		$resourceIns->setFieldMappings($fieldMappings);
		array_push($resource, $resourceIns);
		$requestWrapper->setResource($resource);
        $response = $bulkWriteOperations->createBulkWriteJob($requestWrapper);
        if($response != null)
        {
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
            if($actionResponse instanceof SuccessResponse)
            {
                $successResponse = $actionResponse;
                echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                echo("Details: " );
                foreach($successResponse->getDetails() as $key => $value)
                {
                    echo( $key . ": ");
                    print_r($value);
                    echo("\n");
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
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value . "\n");
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }
}

CreateBulkWriteJob::initialize();
$moduleAPIName = "Leads";
$fileId = "347706118194001";
CreateBulkWriteJob::createBulkWriteJob($moduleAPIName, $fileId);

