<?php
namespace samples\blueprint;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\blueprint\BluePrintOperations;
use com\zoho\crm\api\blueprint\BodyWrapper;
use com\zoho\crm\api\blueprint\APIException;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\blueprint\SuccessResponse;
require_once "vendor/autoload.php";

class UpdateBlueprint
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

	public static function updateBlueprint(string $moduleAPIName, string $recordId, string $transitionId)
	{
	    $bluePrintOperations = new BluePrintOperations($recordId,$moduleAPIName);
		$bodyWrapper = new BodyWrapper();
		$bluePrintList = array();
        $bluePrintClass = 'com\zoho\crm\api\blueprint\BluePrint';
        $bluePrint = new $bluePrintClass();
		$bluePrint->setTransitionId($transitionId);
        $data = new Record();
		$lookup = array();
		$lookup["Phone"] = "8940372937";
		$lookup["id"] = "8940372937";
		// $data->addKeyValue("Lookup_2", $lookup);
		$data->addKeyValue("Phone", "8940372937");
        $data->addKeyValue("Notes", "Updated via blueprint");
        $attachments = array();
        $attachment = array();
		$fileIds = array();
		array_push($fileIds, "blojtd2d13b5f044e4041a3315e0793fb21ef");
        $attachment['$file_id'] = $fileIds;
        array_push($attachments, $attachment);
		$data->addKeyValue("Attachments", $attachments);
		$checkLists = array();
		$list = array();
		$list["list 1"] = true;
		array_push($checkLists, $list);
		$list = array();
		$list["list 2"] = true;
		array_push($checkLists, $list);
		$list = array();
		$list["list 3"] =  true;
		array_push($checkLists, $list);
		$data->addKeyValue("CheckLists", $checkLists);
		$bluePrint->setData($data);
        array_push($bluePrintList, $bluePrint);
        $bodyWrapper->setBlueprint($bluePrintList);
        $response = $bluePrintOperations->updateBlueprint($bodyWrapper);
        if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            $actionResponse = $response->getObject();
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
}

UpdateBlueprint::initialize();
$moduleAPIName = "Leads";
$recordId = "34770614381002";
$transitionId = "34770610173093";
UpdateBlueprint::updateBlueprint($moduleAPIName, $recordId, $transitionId);
