<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ConvertActionWrapper;
use com\zoho\crm\api\record\ConvertBodyWrapper;
use com\zoho\crm\api\record\LeadConverter;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\SuccessfulConvert;
use com\zoho\crm\api\record\{ Deals };
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\record\CarryOverTags;
require_once "vendor/autoload.php";

class ConvertLead
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

	public static function convertLead(string $recordId)
	{
		$recordOperations = new RecordOperations();
		$request = new ConvertBodyWrapper();
		$data = array();
		$record1 = new LeadConverter();
		// $record1->setOverwrite(true);
		// $record1->setNotifyLeadOwner(true);
		// $record1->setNotifyNewEntityOwner(true);
		// $record1->setAccounts("34770615848125");
		// $record1->setContacts("34770610358009");
		// $record1->setAssignTo("34770610173021");
		$recordClass = 'com\zoho\crm\api\record\Record';
		$deals = new $recordClass();
		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$deals->addFieldValue(Deals::DealName(), "deal_name");
		$deals->addFieldValue(Deals::Description(), "deals description");
		$deals->addFieldValue(Deals::ClosingDate(), new \DateTime("2021-06-02"));
		$deals->addFieldValue(Deals::Stage(), new Choice("Closed Won"));
		$deals->addFieldValue(Deals::Amount(), 50.7);
		$deals->addKeyValue("Pipeline", new Choice("Qualification"));
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$deals->addKeyValue("Custom_field", "Value");
		$deals->addKeyValue("Custom_field_2", "value");
		$record1->setDeals($deals);
		$carryOverTags = new CarryOverTags();
		$carryOverTags->setAccounts(["Test"]);
		$carryOverTags->setContacts(["Test"]);
		$carryOverTags->setDeals(["Test"]);
		$record1->setCarryOverTags($carryOverTags);
		array_push($data, $record1);
		$request->setData($data);
		$response = $recordOperations->convertLead($recordId,$request );
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$convertActionHandler = $response->getObject();
				if($convertActionHandler instanceof ConvertActionWrapper)
				{
					$convertActionWrapper = $convertActionHandler;
					$convertActionResponses = $convertActionWrapper->getData();
					foreach($convertActionResponses as $convertActionResponse)
					{
						if($convertActionResponse instanceof SuccessfulConvert)
						{
							$successfulConvert = $convertActionResponse;
							echo("LeadConvert Accounts ID: " . $successfulConvert->getAccounts() . "\n");
							echo("LeadConvert Contacts ID: " . $successfulConvert->getContacts() . "\n");
							echo("LeadConvert Deals ID: " . $successfulConvert->getDeals() . "\n");
						}
						else if($convertActionResponse instanceof APIException)
						{
							$exception = $convertActionResponse;
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
				else if($convertActionHandler instanceof APIException)
				{
					$exception = $convertActionHandler;
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
			else
			{
				print_r($response);
			}
		}
	}
}

ConvertLead::initialize();
$recordId = "347706118046059";
ConvertLead::convertLead($recordId);