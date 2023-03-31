<?php
namespace samples\bulkread;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\bulkread\BulkReadOperations;
use com\zoho\crm\api\bulkread\CallBack;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\bulkread\Query;
use com\zoho\crm\api\bulkread\Criteria;
use com\zoho\crm\api\bulkread\ActionWrapper;
use com\zoho\crm\api\bulkread\SuccessResponse;
use com\zoho\crm\api\bulkread\APIException;
use com\zoho\crm\api\bulkread\RequestWrapper;
use com\zoho\crm\api\modules\Module;
use com\zoho\crm\api\fields\Field;
require_once "vendor/autoload.php";

class CreateBulkReadJob
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

	public static function createBulkReadJob(string $moduleAPIName)
	{
		$bulkReadOperations = new BulkReadOperations();
		$requestWrapper = new RequestWrapper();
		$callback = new CallBack();
		$callback->setUrl("https://www.example.com/callback");
		$callback->setMethod(new Choice("post"));
		$requestWrapper->setCallback($callback);
        $query = new Query();
        $module = new Module();
        $module->setAPIName($moduleAPIName);
		$query->setModule($module);
		// $query->setCvid("34770610087501");
		$fieldAPINames = array();
		array_push($fieldAPINames, "Last_Name");
		$query->setFields($fieldAPINames);
		$query->setPage(1);
		$criteria = new Criteria();
        $criteria->setGroupOperator(new Choice("or"));
		$criteriaList = array();
		$group11 = new Criteria();
		$group11->setGroupOperator(new Choice("and"));
		$groupList11 = array();
        $group111 = new Criteria();
        $field = new Field();
        $field->setAPIName("Last_Name");
		$group111->setField($field);
		$group111->setComparator(new Choice("equal"));
		$group111->setValue("TestPHPSDK");
		array_push($groupList11, $group111);
        $group112 = new Criteria();
        $field = new Field();
        $field->setAPIName("Owner");
		$group112->setField($field);
		$group112->setComparator(new Choice("in"));
		$owner = array("34770610173021");
		$group112->setValue($owner);
		array_push($groupList11, $group112);
		$group11->setGroup($groupList11);
		array_push($criteriaList, $group11);
		$group12 = new Criteria();
		$group12->setGroupOperator(new Choice("or"));
		$groupList12 = array();
        $group121 = new Criteria();
        $field = new Field();
        $field->setAPIName("Company");
		$group121->setField($field);
		$group121->setComparator(new Choice("equal"));
		$group121->setValue("KK");
		array_push($groupList12, $group121);
        $group122 = new Criteria();
        $field = new Field();
        $field->setAPIName("Created_Time");
		$group122->setField($field);
		$group122->setComparator(new Choice("between"));
		$createdTime = array(date_create("2020-07-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())), date_create("2020-10-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
		$group122->setValue($createdTime);
		array_push($groupList12, $group122);
		$group12->setGroup($groupList12);
		array_push($criteriaList, $group12);
		$criteria->setGroup($criteriaList);
		$query->setCriteria($criteria);
		$requestWrapper->setQuery($query);
		// $requestWrapper->setFileType(new Choice("ics"));
        $response = $bulkReadOperations->createBulkReadJob($requestWrapper);
        if($response != null)
        {
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getData();
                foreach ($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue(). "\n");
                        echo("Code: " . $successResponse->getCode()->getValue(). "\n");
                        echo("Details: " );
                        foreach($successResponse->getDetails() as $key => $value)
                        {
                            echo($key . " : " ); print_r($value); echo("\n");
                        }
                        echo("Message: " . $successResponse->getMessage()->getValue(). "\n");
                    }
                    else if($actionResponse instanceof APIException)
                    {
                        $exception = $actionResponse;
                        echo("Status: " . $exception->getStatus()->getValue(). "\n");
                        echo("Code: " . $exception->getCode()->getValue(). "\n");
                        echo("Details: " );
                        foreach($exception->getDetails() as $key => $value)
                        {
                            echo($key . " : " ); print_r($value); echo("\n");
                        }
                        echo("Message: " . $exception->getMessage()->getValue(). "\n");
                    }
                }
            }
            else if($actionHandler instanceof APIException)
            {
            $exception = $actionHandler;
            echo("Status: " . $exception->getStatus()->getValue(). "\n");
            echo("Code: " . $exception->getCode()->getValue(). "\n");
            echo("Details: " );
            foreach($exception->getDetails() as $key => $value)
            {
                echo($key . " : " ); print_r($value); echo("\n");
            }
            echo("Message: " . $exception->getMessage()->getValue(). "\n");
            }
        }
    }
}

CreateBulkReadJob::initialize();
$moduleAPIName = "Leads";
CreateBulkReadJob::createBulkReadJob($moduleAPIName);
