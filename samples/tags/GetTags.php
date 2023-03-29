<?php
namespace samples\tags;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\tags\APIException;
use com\zoho\crm\api\tags\ResponseWrapper;
use com\zoho\crm\api\tags\TagsOperations;
use com\zoho\crm\api\tags\GetTagsParam;
require_once "vendor/autoload.php";

class GetTags
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

	public static function getTags(string $moduleAPIName)
	{
		$tagsOperations = new TagsOperations();
		$paramInstance = new ParameterMap();
		$paramInstance->add(GetTagsParam::module(), $moduleAPIName);
		// $paramInstance->add(GetTagsParam::myTags(), ""); //Displays the names of the tags created by the current user.
		$response = $tagsOperations->getTags($paramInstance);
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
                $tags = $responseWrapper->getTags();
                if($tags != null)
                {
                    foreach($tags as $tag)
                    {
                        echo("Tag CreatedTime: "); print_r($tag->getCreatedTime()); echo("\n");
                        echo("Tag ModifiedTime: "); print_r($tag->getModifiedTime()); echo("\n");
                        echo("Tag Name: " . $tag->getName() . "\n");
                        $modifiedBy = $tag->getModifiedBy();
                        if($modifiedBy != null)
                        {
                            echo("Tag Modified By User-ID: " . $modifiedBy->getId() . "\n");
                            echo("Tag Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        }
                        echo("Tag ID: " . $tag->getId() . "\n");
                        $createdBy = $tag->getCreatedBy();
                        if($createdBy != null)
                        {
                            echo("Tag Created By User-ID: " . $createdBy->getId() . "\n");
                            echo("Tag Created By User-Name: " . $createdBy->getName() . "\n");
                        }
                    }
                }
                $info = $responseWrapper->getInfo();
                if($info != null)
                {
                    echo("Tag Info Count: " . $info->getCount() . "\n");
                    echo("Tag Info AllowedCount: " . $info->getAllowedCount() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
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

GetTags::initialize();
$moduleAPIName = "Leads";
GetTags::getTags($moduleAPIName);