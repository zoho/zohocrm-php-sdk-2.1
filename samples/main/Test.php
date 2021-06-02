<?php

require_once '../../../../vendor/autoload.php';

use com\zoho\crm\sample\initializer\Initialize;

use com\zoho\crm\sample\attachments\Attachment;

use com\zoho\crm\sample\blueprint\BluePrint;

use com\zoho\crm\sample\bulkread\BulkRead;

use com\zoho\crm\sample\bulkwrite\BulkWrite;

use com\zoho\crm\sample\contactroles\ContactRoles;

use com\zoho\crm\sample\currencies\Currency;

use com\zoho\crm\sample\customview\CustomView;

use com\zoho\crm\sample\fields\Fields;

use com\zoho\crm\sample\file\File;

use com\zoho\crm\sample\layouts\Layout;

use com\zoho\crm\sample\modules\Modules;

use com\zoho\crm\sample\notes\Note;

use com\zoho\crm\sample\organization\Organization;

use com\zoho\crm\sample\profile\Profile;

use com\zoho\crm\sample\record\Record;

use com\zoho\crm\sample\relatedlist\RelatedList;

use com\zoho\crm\sample\relatedrecords\RelatedRecords;

use com\zoho\crm\sample\role\Role;

use com\zoho\crm\sample\sharerecords\ShareRecords;

use com\zoho\crm\sample\tags\Tag;

use com\zoho\crm\sample\taxes\Tax;

use com\zoho\crm\sample\territories\Territory;

use com\zoho\crm\sample\users\User;

use com\zoho\crm\sample\variablegroups\VariableGroup;

use com\zoho\crm\sample\variables\Variable;

use com\zoho\crm\sample\notification\Notification;

use com\zoho\crm\sample\query\Query;

use com\zoho\crm\sample\assignmentrules\AssignmentRules;

use com\zoho\crm\sample\emailtemplates\EmailTemplate;

use com\zoho\crm\sample\fieldattachments\FieldAttachment;

use com\zoho\crm\sample\inventorytemplates\InventoryTemplate;

use com\zoho\crm\sample\pipeline\PipeLine;

use com\zoho\crm\sample\sendmail\SendMail;

use com\zoho\crm\sample\wizards\Wizard;

use com\zoho\crm\api\record\GetRecordsParam;

use com\zoho\crm\api\record\RecordOperations;

use com\zoho\crm\api\HeaderMap;

use com\zoho\crm\api\ParameterMap;

class Test
{
    public static function main()
    {
        Initialize::initialize();

		self::AssignmentRules();

        self::Attachment();

        self::BluePrint();

        self::BulkRead();

        self::BulkWrite();

        self::ContactRoles();

		self::Currency();

		self::CustomView();

		self::EmailTemplate();

		self::FieldAttachment();

		self::Field();

		self::File();

		self::InventoryTemplate();

		self::Layout();

		self::Module();

		self::Note();

		self::Notification();

		self::Organization();

		self::PipeLine();

		self::Profile();

		self::Query();

		self::Record();

		self::RelatedList();

		self::RelatedRecords();

		self::Role();

		self::SendMail();

		self::ShareRecords();

		self::Tags();

		self::Tax();

		self::Territory();

		self::User();

		self::VariableGroup();

		self::Variable();

		self::Wizard();
	}

	public static function AssignmentRules()
	{
		$ruleId = "34770614353013";

		AssignmentRules::getAssignmentRules();

		AssignmentRules::getAssignmentRule($ruleId);
	}

    public static function Attachment()
    {
        $moduleAPIName = "Leads";

        $recordId = "34770616890001";

        $absoluteFilePath = "/Users/username/Desktop/test.html";

        $attachmentIds = array("34770619774001", "34770619773001");

        $destinationFolder = "/Users/username/Documents/AutomateSDK/2.1API/Server/PHP/GitLab/SampleApp/zohocrm-php-sdk-sample-application/file";

        $attachmentId = "34770619772001";

        $attachmentURL = "https://5.imimg.com/data5/KJ/UP/MY-8655440/zoho-crm-500x500.png";

        Attachment::uploadAttachments($moduleAPIName, $recordId, $absoluteFilePath);

        Attachment::getAttachments($moduleAPIName, $recordId);

        Attachment::deleteAttachments($moduleAPIName, $recordId, $attachmentIds);

        Attachment::downloadAttachment($moduleAPIName, $recordId, $attachmentId, $destinationFolder);

        Attachment::deleteAttachment($moduleAPIName, $recordId, $attachmentId);

        Attachment::uploadLinkAttachments($moduleAPIName, $recordId, $attachmentURL);
    }

    public static function BluePrint()
	{
		$moduleAPIName = "Leads";

		$recordId = "34770614381002";

		$transitionId = "34770610173093";

        BluePrint::getBlueprint($moduleAPIName, $recordId);

		BluePrint::updateBlueprint($moduleAPIName, $recordId, $transitionId);
    }

    public static function BulkRead()
    {
        $moduleAPIName = "Leads";

		$jobId = "34770619781005";

		$destinationFolder = "/Users/username/Documents/AutomateSDK/PHPSDK/attachment";

		BulkRead::createBulkReadJob($moduleAPIName);

		BulkRead::getBulkReadJobDetails($jobId);

		BulkRead::downloadResult($jobId, $destinationFolder);
    }

    public static function BulkWrite()
	{
		$absoluteFilePath = "/Users/username/Documents/JAVASDK/csvfile/upload/insert/Leads.zip";

		$orgID = "673573045";

		$moduleAPIName = "Leads";

		$fileId  = "34770619791001";

		$jobID = "34770619793002";

		$downloadUrl = "https://download-accl.zoho.com/v2/crm/673573045/bulk-write/34770619793002/34770619793002.zip";

		$destinationFolder = "/Users/username/Documents/AutomateSDK/PHPSDK/attachment";

		BulkWrite::uploadFile($orgID, $absoluteFilePath);

		BulkWrite::createBulkWriteJob($moduleAPIName, $fileId);

		BulkWrite::getBulkWriteJobDetails($jobID);

		BulkWrite::downloadBulkWriteResult($downloadUrl, $destinationFolder);
    }

    public static function ContactRoles()
	{
		$contactRoleId = "34770619608005";

		$contactRoleIds = array("34770619801002","34770619801003","34770619675003","34770619675005");

		ContactRoles::getContactRoles();

		ContactRoles::createContactRoles();

		ContactRoles::updateContactRoles();

		ContactRoles::deleteContactRoles($contactRoleIds);

		ContactRoles::getContactRole($contactRoleId);

		ContactRoles::updateContactRole($contactRoleId);

		ContactRoles::deleteContactRole($contactRoleId);
    }

    public static function Currency()
	{
		$currencyId = "34770616011001";

		Currency::getCurrencies();

		Currency::addCurrencies();

		Currency::updateCurrencies();

		Currency::enableMultipleCurrencies();

		Currency::updateBaseCurrency();

		Currency::getCurrency($currencyId);

		Currency::updateCurrency($currencyId);
	}

	public static function CustomView()
	{
		$moduleAPIName = "Leads";

		$customID = "34770615629003";

		// $names = ["Products", "Tasks", "Vendors", "Calls", "Leads", "Deals", "Campaigns", "Quotes", "Invoices", "Attachments", "Price_Books", "Sales_Orders", "Contacts", "Solutions", "Events", "Purchase_Orders", "Accounts", "Cases", "Notes" ];

		// foreach($names as $name)
		// {
		// 	CustomView::getCustomViews($name);
		// }

		CustomView::getCustomViews($moduleAPIName);

		CustomView::getCustomView($moduleAPIName, $customID);
	}

	public static function EmailTemplate()
	{
		$id = "347706179";

		EmailTemplate::getEmailTemplates("Deals");

		EmailTemplate::getEmailTemplateById($id);
	}

	public static function FieldAttachment()
	{
		$destinationFolder = "/Users/username/Documents/AutomateSDK/PHPSDK/attachment";

		FieldAttachment::getFieldAttachments("Leads","34770616920147","34770619480003", $destinationFolder);
	}

	public static function Field()
	{
		$moduleAPIName = "Leads";

		$fieldId = "34770610022011";

		// $names = ["Products", "Tasks", "Vendors", "Calls", "Leads", "Deals", "Campaigns", "Quotes", "Invoices", "Attachments", "Price_Books", "Sales_Orders", "Contacts", "Solutions", "Events", "Purchase_Orders", "Accounts", "Cases", "Notes" ];

		// foreach ($names as $name)
		// {
		// 	Fields::getFields($name);
		// }

		Fields::getFields($moduleAPIName);

		Fields::getField($moduleAPIName, $fieldId);
	}

	public static function File()
	{
		$destinationFolder =  "/Users/username/Documents/AutomateSDK/2.1API/Server/PHP/GitLab/SampleApp/zohocrm-php-sdk-sample-application/file";

		$id = "ae9c7cefa418aec1d6a5cc2d9ab35c3219aa6eb6389a478c36b635dd374044fb";

		File::uploadFiles();

		File::getFile($id, $destinationFolder);
	}

	public static function InventoryTemplate()
	{
		$id = "34770610174009";

		InventoryTemplate::getInventoryTemplates();

		InventoryTemplate::getInventoryTemplateById($id);
	}

	public static function Layout()
	{
		$moduleAPIName = "Leads";

		$layoutId = "34770615902025";

		// $names = ["Products", "Tasks", "Vendors", "Calls", "Leads", "Deals", "Campaigns", "Quotes", "Invoices", "Attachments", "Price_Books", "Sales_Orders", "Contacts", "Solutions", "Events", "Purchase_Orders", "Accounts", "Cases", "Notes" ];

		// foreach ($names as $name)
		// {
		// 	Layout::getLayouts($name);
		// }

		Layout::getLayouts($moduleAPIName);

		Layout::getLayout($moduleAPIName, $layoutId);
	}

	public static function Module()
	{
		$moduleAPIName = "apiName1";

		$moduleId = "34770613905003";

		Modules::getModules();

		Modules::getModule($moduleAPIName);

		Modules::updateModuleByAPIName($moduleAPIName);

		Modules::updateModuleById($moduleId);
	}

	public static function Note()
	{
		$notesId = array("34770619848006","34770619848005","34770619848004");

		$noteId = "34770619848003";

		Note::getNotes();

		Note::createNotes();

		Note::updateNotes();

		Note::deleteNotes($notesId);

		Note::getNote($noteId);

		Note::updateNote($noteId);

		Note::deleteNote($noteId);
	}

	public static function Notification()
	{
		$channelIds = array("1006800211");

		Notification::enableNotifications();

		Notification::getNotificationDetails();

		Notification::updateNotifications();

		Notification::updateNotification();

		Notification::disableNotifications($channelIds);

		Notification::disableNotification();
	}

	public static function Organization()
	{
		$absoluteFilePath = "/Users/username/Desktop/download.png";

		Organization::getOrganization();

		Organization::uploadOrganizationPhoto($absoluteFilePath);
	}

	public static function PipeLine()
	{
		$layoutId = "34770610091023";

		PipeLine::createPipelines($layoutId);

		PipeLine::updatePipelines($layoutId);

		PipeLine::getPipelines($layoutId);

		PipeLine::getPipeline($layoutId, "34770619482001");

		PipeLine::updatePipeline($layoutId, "34770619482001");

		PipeLine::transferAndDelete($layoutId);
	}

	public static function Profile()
	{
		$profileId = "34770610026011";

		Profile::getProfiles();

		Profile::getProfile($profileId);
	}

	public static function Query()
	{
		Query::getRecords();
	}

	public static function Record()
	{
		$moduleAPIName = "Leads";

		$recordId = "34770619872001";

		$destinationFolder =  "/Users/username/Documents/AutomateSDK/2.1API/Server/PHP/GitLab/SampleApp/zohocrm-php-sdk-sample-application/file/";

		$absoluteFilePath = "/Users/username/Desktop/field/download.png";

		$recordIds = array("34770619873001","34770616606002","34770616603294");

		$jobId = "34770619879007";

		// $names = ["Products", "Tasks", "Vendors", "Calls", "Leads", "Deals", "Campaigns", "Quotes",
		// "Invoices", "Attachments", "Price_Books", "Sales_Orders", "Contacts", "Solutions", "Events",
		//  "Purchase_Orders", "Accounts", "Cases", "Notes"];

		// foreach($names as $name)
		// {
		// 	Record::getRecords($name);
		// }

		Record::getRecord($moduleAPIName, $recordId, $destinationFolder);

		Record::updateRecord($moduleAPIName, $recordId);

		Record::deleteRecord($moduleAPIName, $recordId);

		Record::getRecords($moduleAPIName);

		Record::createRecords($moduleAPIName);

		Record::updateRecords($moduleAPIName);

		Record::deleteRecords($moduleAPIName, $recordIds);

		Record::upsertRecords($moduleAPIName);

		Record::getDeletedRecords($moduleAPIName);

		Record::searchRecords($moduleAPIName);

		Record::convertLead($recordId);

		Record::uploadPhoto($moduleAPIName, $recordId, $absoluteFilePath);

		Record::getPhoto($moduleAPIName, $recordId, $destinationFolder);

		Record::deletePhoto($moduleAPIName, $recordId);

		Record::massUpdateRecords($moduleAPIName);

		Record::getMassUpdateStatus($moduleAPIName, $jobId);
	}

	public static function RelatedList()
	{
		$moduleAPIName = "Leads";

		$relatedListId = "34770616819126";

		// $names = ["Products", "Tasks", "Vendors", "Calls", "Leads", "Deals", "Campaigns", "Quotes", "Invoices", "Attachments", "Price_Books", "Sales_Orders", "Contacts", "Solutions", "Events", "Purchase_Orders", "Accounts", "Cases", "Notes" ];

		// foreach ($names as $name)
		// {
		// 	RelatedList::getRelatedLists($name);
		// }

		RelatedList::getRelatedLists($moduleAPIName);

		RelatedList::getRelatedList($moduleAPIName, $relatedListId);
	}

	public static function RelatedRecords()
	{
		$moduleAPIName = "Products";

		$recordId = "34770619882001";

		$relatedListAPIName = "Price_Books";

		$relatedRecordId = "34770619880016";

		$relatedListIds = array("34770619613018", "3477061988001");

		$destinationFolder =  "/Users/username/Desktop/field/";

		RelatedRecords::getRelatedRecords($moduleAPIName, $recordId, $relatedListAPIName);

		RelatedRecords::updateRelatedRecords($moduleAPIName, $recordId, $relatedListAPIName);

		RelatedRecords::delinkRecords($moduleAPIName, $recordId, $relatedListAPIName, $relatedListIds);

		RelatedRecords::getRelatedRecord($moduleAPIName, $recordId, $relatedListAPIName, $relatedRecordId, $destinationFolder);

		RelatedRecords::updateRelatedRecord($moduleAPIName, $recordId, $relatedListAPIName, $relatedRecordId);

		RelatedRecords::delinkRecord($moduleAPIName, $recordId, $relatedListAPIName, $relatedRecordId);
	}

	public static function Role()
	{
		$roleId = "34770610026008";

		Role::getRoles();

		Role::getRole($roleId);
	}

	public static function SendMail()
	{
		SendMail::getEmailAddresses();

		SendMail::sendMail("34770619479004","Leads");
	}

	public static function ShareRecords()
	{
		$moduleAPIName = "Leads";

		$recordId = "34770615623115";

		ShareRecords::getSharedRecordDetails($moduleAPIName, $recordId);

		ShareRecords::shareRecord($moduleAPIName, $recordId);

		ShareRecords::updateSharePermissions($moduleAPIName, $recordId);

		ShareRecords::revokeSharedRecord($moduleAPIName, $recordId);
	}

	public static function Tags()
	{
		$moduleAPIName = "Leads";

		$tagId = "34770617951002";

		$recordId =  "34770615623115";

		$tagNames = array("addtag1", "addtag12");

		$recordIds = array("34770615623115", "34770616454014");

		$conflictId = "34770617951002";

		Tag::getTags($moduleAPIName);

		Tag::createTags($moduleAPIName);

		Tag::updateTags($moduleAPIName);

		Tag::updateTag($moduleAPIName, $tagId);

		Tag::deleteTag($tagId);

		Tag::mergeTags($tagId, $conflictId);

		Tag::addTagsToRecord($moduleAPIName, $recordId, $tagNames);

		Tag::removeTagsFromRecord($moduleAPIName, $recordId, $tagNames);

		Tag::addTagsToMultipleRecords($moduleAPIName, $recordIds, $tagNames);

		Tag::removeTagsFromMultipleRecords($moduleAPIName, $recordIds, $tagNames);

		Tag::getRecordCountForTag($moduleAPIName, $tagId);
	}

	public static function Tax()
	{
		$taxId = "34770619873024";

		$taxIds = array("34770619885005","34770619885006");

		Tax::getTaxes();

		Tax::createTaxes();

		Tax::updateTaxes();

		Tax::deleteTaxes($taxIds);

		Tax::getTax($taxId);

		Tax::deleteTax($taxId);
	}

	public static function Territory()
	{
		$territoryId = "34770613051397";

		Territory::getTerritories();

		Territory::getTerritory($territoryId);
	}

	public static function User()
	{
		$userId = "34770619881005";

		User::getUsers();

		User::createUser();

		User::updateUsers();

		User::getUser($userId);

		User::updateUser($userId);

		User::deleteUser($userId);
	}

	public static function VariableGroup()
	{
		$variableGroupName = "General";

		$variableGroupId = "34770613089001";

		VariableGroup::getVariableGroups();

		VariableGroup::getVariableGroupById($variableGroupId);

		VariableGroup::getVariableGroupByAPIName($variableGroupName);
	}

	public static function Variable()
	{
		$variableIds = array("34770617035003","34770617294003");

		$variableId = "34770618204001";

		$variableName = "Variable551";

		Variable::getVariables();

		Variable::createVariables();

		Variable::updateVariables();

		Variable::deleteVariables($variableIds);

		Variable::getVariableById($variableId);

		Variable::updateVariableById($variableId);

		Variable::deleteVariable($variableId);

		Variable::getVariableForAPIName($variableName);

		Variable::updateVariableByAPIName($variableName);
	}

	public static function Wizard()
	{
		$wizardId = "34770619497009";

		Wizard::getWizards();

		Wizard::getWizardById($wizardId, "34770610091055");
	}
}

Test::main();
?>