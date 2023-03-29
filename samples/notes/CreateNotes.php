<?php
namespace samples\notes;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\notes\APIException;
use com\zoho\crm\api\notes\ActionWrapper;
use com\zoho\crm\api\notes\BodyWrapper;
use com\zoho\crm\api\notes\NotesOperations;
use com\zoho\crm\api\notes\SuccessResponse;
use com\zoho\crm\api\record\Record;
require_once "vendor/autoload.php";

class CreateNotes
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

	public static function createNotes()
	{
		$notesOperations = new NotesOperations();
		$bodyWrapper = new BodyWrapper();
		$notes = array();
		for($i = 1; $i <= 5; $i++)
		{
            $nodeClass = 'com\zoho\crm\api\notes\Note';
			$note = new $nodeClass();
			$note->setNoteTitle("Contacted");
			$note->setNoteContent("Need to do further tracking");
			$parentRecord = new Record();
			$parentRecord->setId("347706118046059");
			$note->setParentId($parentRecord);
			$note->setSeModule("Leads");
			array_push($notes, $note);
		}
		$bodyWrapper->setData($notes);
		$response = $notesOperations->createNotes($bodyWrapper);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
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
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details: " );
                        if($successResponse->getDetails() != null)
                        {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue)
                            {
                                echo($keyName . " : ");
                                print_r($keyValue);
                                echo("\n");
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

CreateNotes::initialize();
CreateNotes::createNotes();