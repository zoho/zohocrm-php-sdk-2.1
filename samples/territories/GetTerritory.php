<?php
namespace samples\territories;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\territories\APIException;
use com\zoho\crm\api\territories\ResponseWrapper;
use com\zoho\crm\api\territories\TerritoriesOperations;
use com\zoho\crm\api\customviews\Criteria;
require_once "vendor/autoload.php";

class GetTerritory
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

	public static function getTerritory(string $territoryId)
	{
		$territoriesOperations = new TerritoriesOperations();
		$response = $territoriesOperations->getTerritory($territoryId);
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
                $territoryList = $responseWrapper->getTerritories();
                if($territoryList != null)
                {
                    foreach($territoryList as $territory)
                    {
                        echo("Territory CreatedTime: " ); print_r($territory->getCreatedTime()); echo("\n");
                        echo("Territory PermissionType: " . $territory->getPermissionType() . "\n");
                        echo("Territory ModifiedTime: " ); print_r($territory->getModifiedTime()); echo("\n");
                        $manager = $territory->getManager();
                        if($manager != null)
                        {
                            echo("Territory Manager User-Name: " . $manager->getName() . "\n");
                            echo("Territory Manager User-ID: " . $manager->getId() . "\n");
                        }
                        $criteria = $territory->getAccountRuleCriteria();
                        if($criteria != null)
                        {
                            self::printCriteria($criteria);
                        }
                        echo("Territory Name: " . $territory->getName() . "\n");
                        $modifiedBy = $territory->getModifiedBy();
                        if($modifiedBy != null)
                        {
                            echo("Territory Modified By User-Name: " . $modifiedBy->getName() . "\n");
                            echo("Territory Modified By User-ID: " . $modifiedBy->getId() . "\n");
                        }
                        echo("Territory Description: " . $territory->getDescription() . "\n");
                        echo("Territory ID: " . $territory->getId() . "\n");
                        $reportingTo = $territory->getReportingTo();
                        if($reportingTo != null)
                        {
                            echo("Territory ReportingTo User-Name: " . $reportingTo->getName() . "\n");
                            echo("Territory ReportingTo User-ID: " . $reportingTo->getId() . "\n");
                        }
                        $dealcriteria = $territory->getDealRuleCriteria();
                        if($dealcriteria != null)
                        {
                            self::printCriteria($dealcriteria);
                        }
                        $createdBy = $territory->getCreatedBy();
                        if($createdBy != null)
                        {
                            echo("Territory Created By User-Name: " . $createdBy->getName() . "\n");
                            echo("Territory Created By User-ID: " . $createdBy->getId() . "\n");
                        }
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
                    echo($key . " : " . $value . "\n");
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}

	private static function printCriteria(Criteria $criteria)
    {
        $field = $criteria->getField();
        if($field != null)
        {
            echo("Territory Query Criteria Field Id: " . $field->getId(). "\n");
            echo("Territory Query Criteria Field APIName: " . $field->getAPIName(). "\n");
        }
		if($criteria->getComparator()!= null)
		{
			echo("Territory Query Criteria Comparator: " . $criteria->getComparator()->getValue(). "\n");
		}
		if($criteria->getValue() != null)
		{
            echo("Territory Query Criteria Value: "); print_r($criteria->getValue()); echo("\n");
		}
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
			echo("Territory Query Criteria Group Operator: " . $criteria->getGroupOperator()->getValue(). "\n");
        }
    }
}

GetTerritory::initialize();
$territoryId = "34770613051397";
GetTerritory::getTerritory($territoryId);