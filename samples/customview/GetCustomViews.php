<?php
namespace samples\customview;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\customviews\APIException;
use com\zoho\crm\api\customviews\CustomViewsOperations;
use com\zoho\crm\api\customviews\ResponseWrapper;
require_once "vendor/autoload.php";

class GetCustomViews
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

	public static function getCustomViews(string $moduleAPIName)
	{
		$customViewsOperations = new CustomViewsOperations($moduleAPIName);
		$response = $customViewsOperations->getCustomViews();
		if($response != null)
		{
            echo("Status code : " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
            if($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $customViews = $responseWrapper->getCustomViews();
                foreach($customViews as $customView)
                {
                    echo("CustomView DisplayValue: " . $customView->getDisplayValue() . "\n");
                    echo("CustomView Default: " . $customView->getDefault() . "\n");
                    echo("CustomView ModifiedTime: "); print_r($customView->getModifiedTime()); echo("\n");
                    echo("CustomView SystemName: " . $customView->getSystemName() . "\n");
                    echo("CustomView Name: " . $customView->getName() . "\n");
                    echo("CustomView SystemDefined: " . $customView->getSystemDefined() . "\n");
                    $modifiedBy = $customView->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("CustomView Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo("CustomView Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo("CustomView ID: " . $customView->getId() . "\n");
                    echo("CustomView Category: " . $customView->getCategory() . "\n");
                    echo("CustomView LastAccessedTime: "); print_r($customView->getLastAccessedTime()); echo("\n");
                    if($customView->getFavorite() != null)
                    {
                        echo("CustomView Favorite: " . $customView->getFavorite() . "\n");
                    }
                    $createdBy = $customView->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("CustomView Created By User-Name: " . $createdBy->getName() . "\n");
                        echo("CustomView Created By User-ID: " . $createdBy->getId() . "\n");
                    }
                }
                $info = $responseWrapper->getInfo();
                if($info != null)
                {
                    if($info->getPerPage() != null)
                    {
                        echo("CustomView Info PerPage: " . $info->getPerPage() . "\n");
                    }
                    if($info->getDefault() != null)
                    {
                        echo("CustomView Info Default: " . $info->getDefault() . "\n");
                    }
                    if($info->getCount() != null)
                    {
                        echo("CustomView Info Count: " . $info->getCount() . "\n");
                    }
                    $translation = $info->getTranslation();
                    if($translation != null)
                    {
                        echo("CustomView Info Translation PublicViews: " . $translation->getPublicViews() . "\n");
                        echo("CustomView Info Translation OtherUsersViews: " . $translation->getOtherUsersViews() . "\n");
                        echo("CustomView Info Translation SharedWithMe: " . $translation->getSharedWithMe() . "\n");
                        echo("CustomView Info Translation CreatedByMe: " . $translation->getCreatedByMe() . "\n");
                    }
                    if($info->getPage() != null)
                    {
                        echo("CustomView Info Page: " . $info->getPage() . "\n");
                    }
                    echo("CustomView Info MoreRecords: "); print_r($info->getMoreRecords()); echo("\n");
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
                    echo($key . ": " .$value . "\n");
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}
}

GetCustomViews::initialize();
$moduleAPIName = "Leads";
GetCustomViews::getCustomViews($moduleAPIName);