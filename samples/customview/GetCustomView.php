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

class GetCustomView
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

	public static function getCustomView(string $moduleAPIName, string $customViewId)
	{
		$customViewsOperations = new CustomViewsOperations($moduleAPIName);
		$response = $customViewsOperations->getCustomView($customViewId);
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
                    echo("CustomView AccessType: " . $customView->getAccessType() . "\n");
                    $criteria = $customView->getCriteria();
                    if($criteria != null)
                    {
                        self::printCriteria($criteria);
                    }
                    echo("CustomView SystemName: " . $customView->getSystemName() . "\n");
                    echo("CustomView SortBy: " . $customView->getSortBy() . "\n");
                    $createdBy = $customView->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("CustomView Created By User-Name: " . $createdBy->getName() . "\n");
                        echo("CustomView Created By User-ID: " . $createdBy->getId() . "\n");
                    }
                    $sharedToArray = $customView->getSharedTo();
                    if($sharedToArray != null)
                    {
                        foreach($sharedToArray as $sharedTo)
                        {
                            echo("CustomView sharedTo Name: " . $sharedTo->getName() . "\n");
                            echo("CustomView sharedTo Id: " . $sharedTo->getId() . "\n");
                            echo("CustomView sharedTo Type: " . $sharedTo->getType() . "\n");
                            echo("CustomView sharedTo Subordinates: " . $sharedTo->getSubordinates() . "\n");
                        }
                    }
                    echo("CustomView Default: "); print_r($customView->getDefault()); echo("\n");
                    echo("CustomView ModifiedTime: "); print_r($customView->getModifiedTime()); echo("\n");
                    echo("CustomView Name: " . $customView->getName() . "\n");
                    echo("CustomView SystemDefined: " . $customView->getSystemDefined() . "\n");
                    $modifiedBy = $customView->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("CustomView Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo("CustomView Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo("CustomView ID: " . $customView->getId() . "\n");
                    $fields = $customView->getFields();
                    if($fields != null)
                    {
                        foreach($fields as $field)
                        {
                            echo("CustomView Fields APIName: " . $field->getAPIName() . "\n");
                            echo("CustomView Fields Id: " . $field->getId() . "\n");
                        }
                    }
                    echo("CustomView Category: " . $customView->getCategory() . "\n");
                    echo("CustomView LastAccessedTime: "); print_r($customView->getLastAccessedTime()); echo("\n");
                    echo("CustomView SortOrder: "); print_r($customView->getSortOrder()); echo("\n");
                    if($customView->getFavorite() != null)
                    {
                        echo("CustomView Favorite: " . $customView->getFavorite() . "\n");
                    }
                }
                $info = $responseWrapper->getInfo();
                if($info != null)
                {
                    $translation = $info->getTranslation();
                    if($translation != null)
                    {
                        echo("CustomView Info Translation PublicViews: " . $translation->getPublicViews() . "\n");
                        echo("CustomView Info Translation OtherUsersViews: " . $translation->getOtherUsersViews() . "\n");
                        echo("CustomView Info Translation SharedWithMe: " .  $translation->getSharedWithMe() . "\n");
                        echo("CustomView Info Translation CreatedByMe: " .  $translation->getCreatedByMe() . "\n");
                    }
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

	private static function printCriteria($criteria)
    {
        if( $criteria->getComparator() != null)
        {
		    echo("CustomView Criteria Comparator: " . $criteria->getComparator()->getValue() . "\n");
        }
        $field = $criteria->getField();
        if($field != null)
        {
            echo("CustomView Criteria Field: " . $field->getAPIName() . "\n");
            echo("CustomView Criteria Field: " . $field->getId() . "\n");
        }
        echo("CustomView Criteria Value: "); print_r($criteria->getValue()); echo("\n");
		$criteriaGroup = $criteria->getGroup();
		if($criteriaGroup != null)
		{
			foreach($criteriaGroup as $criteria1)
			{
				self::printCriteria($criteria1);
			}
		}
        if($criteria->getGroupOperator() != null)
        {
		    echo("CustomView Criteria Group Operator: " . $criteria->getGroupOperator()->getValue() . "\n");
        }
    }
}

GetCustomView::initialize();
$moduleAPIName = "Leads";
$customID = "34770615629003";
GetCustomView::getCustomView($moduleAPIName, $customID);
