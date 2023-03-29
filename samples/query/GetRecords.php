<?php
namespace samples\query;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\query\ResponseWrapper;
use com\zoho\crm\api\query\APIException;
use com\zoho\crm\api\query\BodyWrapper;
use com\zoho\crm\api\query\QueryOperations;
require_once "vendor/autoload.php";

class GetRecords
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

	public static function getRecords()
	{
		$queryOperations = new QueryOperations();
		$bodyWrapper = new BodyWrapper();
		$selectQuery = "select Last_Name from Leads where Last_Name is not null limit 200";
		$bodyWrapper->setSelectQuery($selectQuery);
		$response = $queryOperations->getRecords($bodyWrapper);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$responseHandler = $response->getObject();
				if($responseHandler instanceof ResponseWrapper)
				{
					$responseWrapper = $responseHandler;
					$records = $responseWrapper->getData();
					foreach($records as $record)
					{
						echo("Record ID: " . $record->getId() . "\n");
						$createdBy = $record->getCreatedBy();
						if($createdBy != null)
						{
							echo("Record Created By User-ID: " . $createdBy->getId() . "\n");
							echo("Record Created By User-Name: " . $createdBy->getName() . "\n");
							echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
						}
						echo("Record CreatedTime: " . $record->getCreatedTime() . "\n");
						$modifiedBy = $record->getModifiedBy();
						if($modifiedBy != null)
						{
							echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");
							echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");
							echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
						}
						echo("Record ModifiedTime: " . $record->getModifiedTime() . "\n");
						echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName
						echo("Record KeyValues: \n");
                        foreach($record->getKeyValues() as $keyName => $value)
                        {
                            if($value != null)
                            {
                                if((is_array($value) && sizeof($value) > 0) && isset($value[0]))
                                {
                                    echo("Record KeyName : " . $keyName . "\n");
                                    $dataList = $value;
                                    foreach($dataList as $data)
                                    {
                                        if(is_array($data))
                                        {
                                            echo("Record KeyName : " . $keyName  . " - Value :  \n");
                                            foreach($data as $key => $arrayValue)
                                            {
                                                echo($key . " : " . $arrayValue);
                                            }
                                        }
                                        else
                                        {
                                            print_r($data); echo("\n");
                                        }
                                    }
                                }
                                else
                                {
                                    echo("Record KeyName : " . $keyName  . " - Value : " . print_r($value)); echo("\n");
                                }
                            }
                        }
                    }
					$info = $responseWrapper->getInfo();
					if($info != null)
					{
						if($info->getCount() != null)
						{
							echo("Record Info Count: " . $info->getCount() . "\n");
						}
						if($info->getMoreRecords() != null)
						{
							echo("Record Info MoreRecords: " . $info->getMoreRecords() . "\n");
						}
					}
				}
				else if($responseHandler instanceof APIException)
				{
					$exception = $responseHandler;
					echo("Status: " . $exception->getStatus()->getValue() . "\n");
					echo("Code: " . $exception->getCode()->getValue() . "\n");
					echo("Details: " );
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
			else
			{
				print_r($response);
			}
		}
	}
}

GetRecords::initialize();
GetRecords::getRecords();