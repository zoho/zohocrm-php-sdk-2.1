<?php
namespace samples\tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\CountWrapper;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\GetRecordCountForTagParam;
require_once "vendor/autoload.php";

class GetRecordCountForTag
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

	public static function getRecordCountForTag(string $moduleAPIName, string $tagId)
	{
		$tagsOperations = new TagsOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetRecordCountForTagParam::module(), $moduleAPIName);
		//Call getRecordCountForTag method that takes paramInstance and tagId as parameter
		$response = $tagsOperations->getRecordCountForTag($tagId,$paramInstance);
		if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
            $countHandler = $response->getObject();
            if($countHandler instanceof CountWrapper)
            {
                $countWrapper = $countHandler;
                echo("Tag Count: " . $countWrapper->getCount() . "\n");
            }
            else if($countHandler instanceof APIException)
            {
                $exception = $countHandler;
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

GetRecordCountForTag::initialize();
$moduleAPIName = "Leads";
$tagId = "347706116961005";
GetRecordCountForTag::getRecordCountForTag($moduleAPIName, $tagId);
