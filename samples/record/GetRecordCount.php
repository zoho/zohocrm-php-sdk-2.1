<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\RecordCountParam;
use com\zoho\crm\api\record\CountWrapper;
require_once "vendor/autoload.php";

class GetRecordCount
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

	public static function getRecordCount()
	{
		$recordOperations = new RecordOperations();
		$moduleAPIName = "Leads";
		$paramInstance = new ParameterMap();
		$paramInstance->add(RecordCountParam::phone(), "(990) -0");
		$response = $recordOperations->recordCount($moduleAPIName, $paramInstance);
		if($response != null)
		{
            echo("Status code " . $response->getStatusCode() . "\n");
            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");
                return;
			}
			if($response->isExpected())
			{
				$countHandler = $response->getObject();
				if($countHandler instanceof CountWrapper)
				{
					$countWrapper = $countHandler;
					echo("Record Count: " . $countWrapper->getCount()); echo("\n");
				}
				else if($countHandler instanceof APIException)
				{
					$exception = $countHandler;
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
			else
			{
				
				print_r($response);
			}
		}
	}
}

GetRecordCount::initialize();
GetRecordCount::getRecordCount();