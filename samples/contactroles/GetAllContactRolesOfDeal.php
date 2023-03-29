<?php
namespace samples\contactroles;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\contactroles\ContactRolesOperations;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\contactroles\APIException;
use com\zoho\crm\api\contactroles\RecordResponseWrapper;
require_once "vendor/autoload.php";

class GetAllContactRolesOfDeal
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

    public static function getAllContactRolesOfDeal($dealId)
	{
		$contactRolesOperations = new ContactRolesOperations();
		$paramInstance = new ParameterMap();
        // $paramInstance->add(GetAllContactRolesOfDealParam::ids(),[""]);
		$response = $contactRolesOperations->getAllContactRolesOfDeal($dealId, $paramInstance);
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
				$responseHandler = $response->getObject();	
				if($responseHandler instanceof RecordResponseWrapper)
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
						echo("Record CreatedTime: "); print_r($record->getCreatedTime()); echo("\n");
						$modifiedBy = $record->getModifiedBy();
						if($modifiedBy != null)
						{
							echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");
							echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");
							echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
						}
                        echo("Record CreatedTime: "); print_r($record->getModifiedTime()); echo("\n");
						//To get particular field value 
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
                                    echo("Record KeyName : " . $keyName  . " - Value : " . print_r($value));
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

GetAllContactRolesOfDeal::initialize();
$dealId = "34770610358013";
GetAllContactRolesOfDeal::getAllContactRolesOfDeal($dealId);