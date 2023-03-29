<?php
namespace samples\bulkread;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkread\BulkReadOperations;
use com\zoho\crm\api\bulkread\ResponseWrapper;
use com\zoho\crm\api\bulkread\Criteria;
use com\zoho\crm\api\bulkread\APIException;
require_once "vendor/autoload.php";

class GetBulkReadJobDetails
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

	public static function getBulkReadJobDetails(string $jobId)
	{
		$bulkReadOperations = new BulkReadOperations();
        $response = $bulkReadOperations->getBulkReadJobDetails($jobId);
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
                $jobDetails = $responseWrapper->getData();
                foreach($jobDetails as $jobDetail)
                {
                    echo("Bulkread Job ID: " . $jobDetail->getId(). "\n");
                    echo("Bulkread Operation: " . $jobDetail->getOperation(). "\n");
                    echo("Bulkread State: " . $jobDetail->getState()->getValue(). "\n");
                    $result = $jobDetail->getResult();
                    if($result != null)
                    {
                        echo("Bulkread Result Page: " . $result->getPage(). "\n");
                        echo("Bulkread Result Count: " . $result->getCount(). "\n");
                        echo("Bulkread Result Download URL: " . $result->getDownloadUrl(). "\n");
                        echo("Bulkread Result Per_Page: " . $result->getPerPage(). "\n");
                        echo("Bulkread Result MoreRecords: " . $result->getMoreRecords(). "\n");
                    }
                    $query = $jobDetail->getQuery();
                    if($query != null)
                    {
                        $fields = $query->getFields();
                        if($fields != null)
                        {
                            foreach($fields as $fieldName)
                            {
                                echo("Bulkread Query Fields: " . $fieldName. "\n");
                            }
                        }
                        $module = $query->getModule();
                        if($module != null)
                        {
                            echo("Bulkread Query Module Name : " . $module->getAPIName() . "\n");
                            echo("Bulkread Query Module Id : " . $module->getId() . "\n");
                        }
                        $criteria = $query->getCriteria();
                        if($criteria != null)
                        {
                            GetBulkReadJobDetails::printCriteria($criteria);
                        }
                        echo("Bulkread Query Page: " . $query->getPage(). "\n");
                        echo("Bulkread Query cvid: " . $query->getCvid(). "\n");
                    }
                    $createdBy = $jobDetail->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("Bulkread Created By User-ID: " . $createdBy->getId(). "\n");
                        echo("Bulkread Created By user-Name: " . $createdBy->getName(). "\n");
                    }
                    echo("Bulkread CreatedTime: " ); print_r($jobDetail->getCreatedTime()); echo("\n");
                    echo("Bulkread File Type: " . $jobDetail->getFileType(). "\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo("Status: " . $exception->getStatus()->getValue(). "\n");
                echo("Code: " . $exception->getCode()->getValue(). "\n");
                echo("Details: " );
                foreach($exception->getDetails() as $key => $value)
                {
                    echo($key . " : " . $value . "\n");
                }
                echo("Message: " . $exception->getMessage()->getValue(). "\n");
            }
        }
    }
	private static function printCriteria(Criteria $criteria)
    {
        $field = $criteria->getField();
        if($field != null)
        {
            echo("Bulkread Query Criteria Field Id: " . $field->getId(). "\n");
            echo("Bulkread Query Criteria Field APIName: " . $field->getAPIName(). "\n");
        }
		if($criteria->getComparator()!= null)
		{
			echo("Bulkread Query Criteria Comparator: " . $criteria->getComparator()->getValue(). "\n");
		}
		if($criteria->getValue() != null)
		{
            echo("Bulkread Query Criteria Value: "); print_r($criteria->getValue()); echo("\n");
		}
		$criteriaGroup = $criteria->getGroup();
		if($criteriaGroup != null)
		{
			foreach($criteriaGroup as $criteria1)
			{
				GetBulkReadJobDetails::printCriteria($criteria1);
			}
		}
		if($criteria->getGroupOperator() != null)
		{
			echo("Bulkread Query Criteria Group Operator: " . $criteria->getGroupOperator()->getValue(). "\n");
		}
    }
}

GetBulkReadJobDetails::initialize();
$jobId = "347706118193001";
GetBulkReadJobDetails::getBulkReadJobDetails($jobId);