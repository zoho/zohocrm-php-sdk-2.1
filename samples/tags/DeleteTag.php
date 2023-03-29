<?php
namespace samples\tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\ActionWrapper;
use com\zoho\crm\api\tags\SuccessResponse;
use com\zoho\crm\api\tags\TagsOperations;
require_once "vendor/autoload.php";

class DeleteTag
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

	public static function deleteTag(string $tagId)
	{
		$tagsOperations = new TagsOperations();
		$response = $tagsOperations->deleteTag($tagId);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
           $actionHandler = $response->getObject();
           if($actionHandler instanceof ActionWrapper)
           {
               $actionWrapper = $actionHandler;
               $actionResponses = $actionWrapper->getTags();
               foreach($actionResponses as $actionResponse)
               {
                   if($actionResponse instanceof SuccessResponse)
                   {
                       $successResponse = $actionResponse;
                       echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                       echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                       if($successResponse->getDetails() != null)
                       {
                           echo("Details: " );
                           foreach($successResponse->getDetails() as $key => $value)
                           {
                               echo($key . " : " . $value . "\n");
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
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . " : " . $value . "\n");
                    }
               }
               echo("Message: " . $exception->getMessage()->getValue() . "\n");
           }
		}
	}
}

DeleteTag::initialize();
$tagId = "347706112193003";
DeleteTag::deleteTag($tagId);