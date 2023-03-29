<?php
namespace samples\bulkwrite;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkwrite\BulkWriteOperations;
use com\zoho\crm\api\bulkwrite\APIException;
use com\zoho\crm\api\bulkwrite\BulkWriteResponse;
require_once "vendor/autoload.php";

class GetBulkWriteJobDetails
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

	public static function getBulkWriteJobDetails(string $jobId)
	{
		$bulkWriteOperations = new BulkWriteOperations();
		$response = $bulkWriteOperations->getBulkWriteJobDetails($jobId);
		if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseWrapper = $response->getObject();
            if($responseWrapper instanceof BulkWriteResponse)
            {
                $bulkWriteResponse = $responseWrapper;
                echo("Bulkwrite Job Status: " . $bulkWriteResponse->getStatus() . "\n");
                echo("Bulkwrite CharacterEncoding: " . $bulkWriteResponse->getCharacterEncoding() . "\n");
                $resources = $bulkWriteResponse->getResource();
                if($resources != null)
                {
                    foreach($resources as $resource)
                    {
                        echo("Bulkwrite Resource Status: " . $resource->getStatus()->getValue() . "\n");
                        echo("Bulkwrite Resource Type: " . $resource->getType()->getValue() . "\n");
                        $module = $resource->getModule();
                        if($module != null)
                        {
                            echo("Bulkwrite Resource Module Name : " . $module->getAPIName() . "\n");
                            echo("Bulkwrite Resource Module Id : " . $module->getId() . "\n");
                        }
                        $fieldMappings = $resource->getFieldMappings();
                        if($fieldMappings != null)
                        {
                            foreach($fieldMappings as $fieldMapping)
                            {
                                echo("Bulkwrite Resource FieldMapping Module: " . $fieldMapping->getAPIName() . "\n");
                                echo("Bulkwrite Resource FieldMapping Index: "); print_r($fieldMapping->getIndex()); echo("\n");
                                if($fieldMapping->getFormat() != null)
                                {
                                    echo("Bulkwrite Resource FieldMapping Format: " . $fieldMapping->getFormat() . "\n");
                                }
                                if($fieldMapping->getFindBy() != null)
                                {
                                    echo("Bulkwrite Resource FieldMapping FindBy: " . $fieldMapping->getFindBy() . "\n");
                                }
                                if($fieldMapping->getModule() != null)
                                {
                                    echo("Bulkwrite Resource FieldMapping Module: "); print_r($fieldMapping->getModule()); echo("\n");
                                }
                                if($fieldMapping->getDefaultValue() != null)
                                {
                                    foreach($fieldMapping->getDefaultValue() as $key => $value)
                                    {
                                        echo($key . ": " . $value . "\n");
                                    }
                                }
                            }
                        }
                        if($resource->getFindBy() != null)
                        {
                            echo("Bulkwrite Resource FindBy: " . $resource->getFindBy() . "\n");
                        }
                        $file = $resource->getFile();
                        if($file != null)
                        {
                            echo("Bulkwrite Resource File Status: " . $file->getStatus()->getValue() . "\n");
                            echo("Bulkwrite Resource File Name: " . $file->getName() . "\n");
                            echo("Bulkwrite Resource File AddedCount: " . $file->getAddedCount() . "\n");
                            echo("Bulkwrite Resource File SkippedCount: " . $file->getSkippedCount() . "\n");
                            echo("Bulkwrite Resource File UpdatedCount: " . $file->getUpdatedCount() . "\n");
                            echo("Bulkwrite Resource File TotalCount: " . $file->getTotalCount() . "\n");
                        }
                        echo("Bulkwrite Resource Code: " . $resource->getCode() . "\n");
                    }
                }
                echo("Bulkwrite ID: " . $bulkWriteResponse->getId() . "\n");
                $callback = $bulkWriteResponse->getCallback();
                if($callback != null)
                {
                    echo("Bulkwrite Callback URL : " . $callback->getUrl() . "\n");
                     echo("Bulkwrite Method URL : " . $callback->getMethod()->getValue() . "\n");
                }
                $result = $bulkWriteResponse->getResult();
                if($result != null)
                {
                    echo("Bulkwrite DownloadUrl: " . $result->getDownloadUrl() . "\n");
                }
                $createdBy = $bulkWriteResponse->getCreatedBy();
                if($createdBy != null)
                {
                    echo("Bulkread Created By User-ID: " . $createdBy->getId() . "\n");
                    echo("Bulkread Created By User-Name: " . $createdBy->getName() . "\n");
                }
                echo("Bulkwrite Operation: " . $bulkWriteResponse->getOperation() . "\n");
                echo("Bulkwrite File CreatedTime: "); print_r($bulkWriteResponse->getCreatedTime()); echo("\n");
            }
            else if($responseWrapper instanceof APIException)
            {
                $exception = $responseWrapper;
                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                echo("Code: " . $exception->getCode()->getValue() . "\n");
                if($exception->getDetails() != null)
                {
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . " : " . $value);
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }
}

GetBulkWriteJobDetails::initialize();
$jobId = "347706118196001";
GetBulkWriteJobDetails::getBulkWriteJobDetails($jobId);
