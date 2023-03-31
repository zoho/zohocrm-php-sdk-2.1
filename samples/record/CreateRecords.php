<?php
namespace samples\record;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\record\APIException;
use com\zoho\crm\api\record\ActionWrapper;
use com\zoho\crm\api\record\BodyWrapper;
use com\zoho\crm\api\record\FileDetails;
use com\zoho\crm\api\record\LineItemProduct;
use com\zoho\crm\api\record\LineTax;
use com\zoho\crm\api\record\Participants;
use com\zoho\crm\api\record\PricingDetails;
use com\zoho\crm\api\record\RecordOperations;
use com\zoho\crm\api\record\RecurringActivity;
use com\zoho\crm\api\record\RemindAt;
use com\zoho\crm\api\record\SuccessResponse;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\record\{ApplyFeatureExecution, Cases, Solutions, Accounts, Campaigns, Calls, Leads, Tasks, Deals, Sales_Orders, Contacts, Quotes, Events, Price_Books, Purchase_Orders, Vendors};
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\record\ImageUpload;
use com\zoho\crm\api\record\Tax;
use com\zoho\crm\api\users\User;
require_once "vendor/autoload.php";

class CreateRecords
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

	public static function createRecords(string $moduleAPIName)
	{
		$recordOperations = new RecordOperations();
		$bodyWrapper = new BodyWrapper();
		$records = array();
		$recordClass = 'com\zoho\crm\api\record\Record';
		$record1 = new $recordClass();
		$apply_feature_execution = new ApplyFeatureExecution();
		$apply_feature_execution->setName("layout_rules");
		$apply_feature_list = array();
		array_push($apply_feature_list,$apply_feature_execution);
		$bodyWrapper->setApplyFeatureExecution($apply_feature_list);
		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$record1->addFieldValue(Leads::City(), "City");
		$record1->addFieldValue(Leads::LastName(), "FROm PHP");
		$record1->addFieldValue(Leads::FirstName(), "First Name");
		$record1->addFieldValue(Leads::Company(), "KKRNP");
		$record1->addFieldValue(Vendors::VendorName(), "Vendor Name");
		$record1->addFieldValue(Deals::Stage(), new Choice("Clo"));
		$record1->addFieldValue(Deals::DealName(), "deal_name");
		$record1->addFieldValue(Deals::Description(), "deals description");
		$record1->addFieldValue(Deals::ClosingDate(), new \DateTime("2021-06-02"));
		$record1->addFieldValue(Deals::Amount(), 50.7);
		$record1->addFieldValue(Campaigns::CampaignName(), "Campaign_Name");
		$record1->addFieldValue(Solutions::SolutionTitle(), "Solution_Title");
		$accounts = new $recordClass();
		$accounts->addKeyValue("id", "34770615848009");
		$record1->addFieldValue(Accounts::AccountName(), $accounts);
		$record1->addFieldValue(Accounts::AccountName(), "Account_Name");
		$record1->addFieldValue(Cases::CaseOrigin(), new Choice("AutomatedSDK"));
		$record1->addFieldValue(Cases::Status(), new Choice("AutomatedSDK"));
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");
		$record1->addKeyValue("Date_1", new \DateTime('2021-03-08'));
		$record1->addKeyValue("Date_Time_2", date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
		$record1->addKeyValue("Subject", "From PHP");
		$record1->addKeyValue("External", "TestExternal123");
		$taxes = array();
		$tax = new Tax();
		$tax->setValue("MyTax1123 - 10.0 %");
		array_push($taxes, $tax);
		$record1->addKeyValue("Tax", $taxes);
		$record1->addKeyValue("Product_Name", "AutomatedSDK");
		$record1->addKeyValue("Products_External", "Products_External");
		$imageUpload = new ImageUpload();
		$imageUpload->setEncryptedId("ae9c7cefa418aec1d6a5cc2d9ab35c320c7da49c4222acd283e275ffade3f0ff");
		$record1->addKeyValue("Image_Upload", [$imageUpload]);
		$fileDetails = array();
		$fileDetail1 = new FileDetails();
		$fileDetail1->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c32b3ec27aa98b6161a4df6e2c0dc1f0f80");
		array_push($fileDetails, $fileDetail1);
		$fileDetail2 = new FileDetails();
		$fileDetail2->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c328db2d16efac6afe745678b577297a3b3");
		array_push($fileDetails, $fileDetail2);
		$fileDetail3 = new FileDetails();
		$fileDetail3->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c3207c8e1a4448a63b609f1ba7bd4aee6eb");
		array_push($fileDetails, $fileDetail3);
		$record1->addKeyValue("File_Upload", $fileDetails);

		$recordOwner = new User();
		$recordOwner->setEmail("abc@zoho.com");
        $record1->addKeyValue("Owner", $recordOwner);
		//Lookup
		$lookup = new $recordClass();
		$lookup->addKeyValue("id", "34770615848009");
		$record1->addKeyValue("lookup", $lookup);
		//MultiLookup
		$multiLookup = array();
		$lookup = new $recordClass();
		$lookup->addKeyValue("id", "34770615848009");
		array_push($multiLookup, $lookup);
		$record1->addKeyValue("multi-selectlookup", $multiLookup);

		/** Following methods are being used only by Inventory modules */
		$vendorName = new $recordClass();
		$vendorName->addFieldValue(Vendors::id(), "34770617247001");
		$record1->addFieldValue(Purchase_Orders::VendorName(), $vendorName);
		$dealName = new $recordClass();
		$dealName->addFieldValue(Deals::id(), "3477061012112003");
		$record1->addFieldValue(Sales_Orders::DealName(), $dealName);
		$contactName = new $recordClass();
		$contactName->addFieldValue(Contacts::id(), "3477061011383004");
		$record1->addFieldValue(Purchase_Orders::ContactName(), $contactName);
		$accountName = new $recordClass();
		$accountName->addKeyValue("name", "automatedAccount");
		$record1->addFieldValue(Quotes::AccountName(), $accountName);
		$record1->addKeyValue("Discount", 10.5);
		$inventoryLineItemList = array();
		$inventoryLineItem = new $recordClass();
		$lineItemProduct = new LineItemProduct();
		$lineItemProduct->setId("3477061012261");
		// $lineItemProduct->addKeyValue("Products_External", "Products_External");
		$inventoryLineItem->addKeyValue("Product_Name", $lineItemProduct);
		$inventoryLineItem->addKeyValue("Quantity", 1.5);
		$inventoryLineItem->addKeyValue("Description", "productDescription");
		$inventoryLineItem->addKeyValue("ListPrice", 10.0);
		$inventoryLineItem->addKeyValue("Discount", "5%");
		$productLineTaxes = array();
		$productLineTax = new LineTax();
		$productLineTax->setName("MyTax1123");
		$productLineTax->setPercentage(20.0);
		array_push($productLineTaxes, $productLineTax);
		$inventoryLineItem->addKeyValue("Line_Tax", $productLineTaxes);
		array_push($inventoryLineItemList, $inventoryLineItem);
		$record1->addKeyValue("Quoted_Items", $inventoryLineItemList);
		$record1->addKeyValue("Invoiced_Items", $inventoryLineItemList);
		$record1->addKeyValue("Purchase_Items", $inventoryLineItemList);
		$record1->addKeyValue("Ordered_Items", $inventoryLineItemList);
		$lineTaxes = array();
		$lineTax = new LineTax();
		$lineTax->setName("MyTax1123");
		$lineTax->setPercentage(20.0);
		array_push($lineTaxes,$lineTax);
		$record1->addKeyValue('$line_tax', $lineTaxes);
		/** End Inventory **/

		/** Following methods are being used only by Activity modules */
		// Tasks,Calls,Events
		$record1->addFieldValue(Tasks::Description(), "Test Task");
		$record1->addKeyValue("Currency",new Choice("INR"));
		$remindAt = new RemindAt();
		$remindAt->setAlarm("FREQ=DAILY;INTERVAL=10;UNTIL=2020-08-14;DTSTART=2020-07-03");
		$record1->addFieldValue(Tasks::RemindAt(), $remindAt);
		$whoId = new $recordClass();
		$whoId->setId("3477061011383004");
		$record1->addFieldValue(Tasks::WhoId(), $whoId);
		$record1->addFieldValue(Tasks::Status(),new Choice("Waiting for input"));
		$record1->addFieldValue(Tasks::DueDate(), new \DateTime('2021-03-08'));
		$record1->addFieldValue(Tasks::Priority(),new Choice("High"));
		$record1->addKeyValue('$se_module', "Accounts");
		$whatId = new $recordClass();
		$whatId->setId("3477061011383001");
		$record1->addFieldValue(Tasks::WhatId(), $whatId);
		/** Recurring Activity can be provided in any activity module*/
		$recurringActivity = new RecurringActivity();
		$recurringActivity->setRrule("FREQ=DAILY;INTERVAL=10;UNTIL=2020-08-14;DTSTART=2020-07-03");
		$record1->addFieldValue(Events::RecurringActivity(), $recurringActivity);
		
        // Events
		$record1->addFieldValue(Events::Description(), "Test Events");
		$startdatetime = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$record1->addFieldValue(Events::StartDateTime(), $startdatetime);
		$participants = array();
		$participant1 = new Participants();
		$participant1->setParticipant("abc@zoho.com");
		$participant1->setType("email");
		$participant1->setId("34770615902017");
		array_push($participants, $participant1);
		$participant2 = new Participants();
		$participant2->addKeyValue("participant", "347706155001");
		$participant2->addKeyValue("type", "lead");
		array_push($participants, $participant2);
		$record1->addFieldValue(Events::Participants(), $participants);
		$record1->addKeyValue('$send_notification', true);
		$record1->addFieldValue(Events::EventTitle(), "From PHP");
		$enddatetime = date_create("2020-07-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$record1->addFieldValue(Events::EndDateTime(), $enddatetime);
		$remindAt = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$record1->addFieldValue(Events::RemindAt(), $remindAt);
		$record1->addFieldValue(Events::CheckInStatus(), "PLANNED");
		$remindAt = new RemindAt();
		$remindAt->setAlarm("FREQ=NONE;ACTION=EMAILANDPOPUP;TRIGGER=DATE-TIME:2020-07-23T12:30:00+05:30");
		$record1->addFieldValue(Tasks::RemindAt(), $remindAt);
		$record1->addKeyValue('$se_module', "Leads");
		$record1->addKeyValue('Remind_At', new \DateTime('2020-03-08'));
		$whatId = new $recordClass();
		$whatId->setId("3477061012255001");
		$record1->addFieldValue(Events::WhatId(), $whatId);
		$record1->addFieldValue(Tasks::WhatId(), $whatId);
		$record1->addFieldValue(Calls::CallType(), new Choice("Outbound"));
		$record1->addFieldValue(Calls::CallStartTime(), date_create("2020-07-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));
		/** End Activity **/
		
        /** Following methods are being used only by Price_Books modules */
		$pricingDetails = array();
		$pricingDetail1 = new PricingDetails();
		$pricingDetail1->setFromRange(1.0);
		$pricingDetail1->setToRange(5.0);
		$pricingDetail1->setDiscount(2.0);
		array_push($pricingDetails, $pricingDetail1);
		$pricingDetail2 = new PricingDetails();
		$pricingDetail2->addKeyValue("from_range", 6.0);
		$pricingDetail2->addKeyValue("to_range", 11.0);
		$pricingDetail2->addKeyValue("discount", 3.0);
		array_push($pricingDetails, $pricingDetail2);
		$record1->addFieldValue(Price_Books::PricingDetails(), $pricingDetails);
		$record1->addKeyValue("Email", "user1223@zoho.com");
		$record1->addFieldValue(Price_Books::Description(), "TEST");
		$record1->addFieldValue(Price_Books::PriceBookName(), "book_name");
		$record1->addFieldValue(Price_Books::PricingModel(), new Choice("Flat"));
        /** Price_Books */

		$tagList = array();
		$tag = new Tag();
		$tag->setName("Testtask");
		array_push($tagList, $tag);
		$record1->setTag($tagList);
		array_push($records, $record1);
		$bodyWrapper->setData($records);
		$trigger = array("approval", "workflow", "blueprint");
		$bodyWrapper->setTrigger($trigger);
		//bodyWrapper.setLarId("34770610087515");
		$headerInstance = new HeaderMap();
		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Quotes.Quoted_Items.Product_Name.Products_External");
		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Products.Products_External");
		$response = $recordOperations->createRecords($moduleAPIName, $bodyWrapper, $headerInstance);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
			if($response->isExpected())
			{
				$actionHandler = $response->getObject();
				if($actionHandler instanceof ActionWrapper)
				{
					$actionWrapper = $actionHandler;
					$actionResponses = $actionWrapper->getData();
					foreach($actionResponses as $actionResponse)
					{
						if($actionResponse instanceof SuccessResponse)
						{
							$successResponse = $actionResponse;
							echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
							echo("Code: " . $successResponse->getCode()->getValue() . "\n");
							echo("Details: " );
							foreach($successResponse->getDetails() as $key => $value)
							{
								echo($key . " : "); print_r($value); echo("\n");
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
			else
			{
				print_r($response);
			}
		}
	}
}

CreateRecords::initialize();
$moduleAPIName = "Leads";
CreateRecords::createRecords($moduleAPIName);