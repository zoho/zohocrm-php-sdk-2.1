<?php
namespace samples\tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\ActionWrapper;
use com\zoho\crm\api\tags\ConflictWrapper;
use com\zoho\crm\api\tags\MergeWrapper;
use com\zoho\crm\api\tags\SuccessResponse;
use com\zoho\crm\api\tags\TagsOperations;
require_once "vendor/autoload.php";

class MergeTags
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

	public static function mergeTags(string $tagId, string $conflictId)
	{
		$tagsOperations = new TagsOperations();
		$request = new MergeWrapper();
		$tags = array();
		$mergeTag = new ConflictWrapper();
		$mergeTag->setConflictId($conflictId);
		array_push($tags, $mergeTag);
		$request->setTags($tags);
		$response = $tagsOperations->mergeTags($tagId,$request);
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
                               echo($key . " : ");
                               print_r($value);
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
               echo("Details: " );
               foreach($exception->getDetails() as $key => $value)
               {
                   echo($key . " : " . $value . "\n");
               }
               echo("Message: " . $exception->getMessage()->getValue() . "\n");
           }
		}
	}
}

MergeTags::initialize();
$tagId = "347706112193003";
$conflictId = "347706112193003";
MergeTags::mergeTags($tagId, $conflictId);