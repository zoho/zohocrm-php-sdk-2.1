<?php
namespace samples\fields;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\fields\APIException;
use com\zoho\crm\api\fields\FieldsOperations;
use com\zoho\crm\api\fields\ResponseWrapper;
require_once "vendor/autoload.php";

class GetField
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

	public static function getField(string $moduleAPIName, string $fieldId)
	{
		$fieldOperations = new FieldsOperations($moduleAPIName);
		$response = $fieldOperations->getField($fieldId);
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
                $fields = $responseWrapper->getFields();
                if($fields != null)
                {
                    foreach($fields as $field)
                    {
                        echo("Field SystemMandatory: " ); print_r($field->getSystemMandatory()); echo("\n");
                        $privateInfo = $field->getPrivate();
                        if($privateInfo != null)
                        {
                            echo("Private Details\n");
                            echo("Field Private Type: " . $privateInfo->getType() . "\n");
                            echo("Field Private Export: " . $privateInfo->getExport() . "\n");
                            echo("Field Private Restricted: " . $privateInfo->getRestricted() . "\n");
                        }
                        echo("Field Webhook: "); print_r($field->getWebhook()); print_r("\n");
                        echo("Field JsonType: " . $field->getJsonType() . "\n");
                        $crypt = $field->getCrypt();
                        if($crypt != null)
                        {
                            echo("Field Crypt Mode: " . $crypt->getMode() . "\n");
                            echo("Field Crypt Column: " . $crypt->getColumn() . "\n");
                            $encFldIds = $crypt->getEncfldids();
                            if($encFldIds != null)
                            {
                                echo("EncFldIds : ");
                                foreach($encFldIds as $encFldId)
                                {
                                    echo($encFldId . "\n");
                                }
                            }
                            echo("Field Crypt Notify: " . $crypt->getNotify() . "\n");
                            echo("Field Crypt Table: " . $crypt->getTable() . "\n");
                            echo("Field Crypt Status: " . $crypt->getStatus() . "\n");
                        }
                        echo("Field FieldLabel: " . $field->getFieldLabel() . "\n");
                        $tooltip = $field->getTooltip();
                        if($tooltip != null)
                        {
                            echo("Field ToolTip Name: " . $tooltip->getName() . "\n");
                            echo("Field ToolTip Value: " . $tooltip->getValue() . "\n");
                        }
                        echo("Field CreatedSource: " . $field->getCreatedSource() . "\n");
                        echo("Field Type: " . $field->getType() . "\n");
                        echo("Field FieldReadOnly: "); print_r($field->getFieldReadOnly()); echo("\n");
                        echo("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");
                        echo("Field DisplayType: " . $field->getDisplayType()->getValue() . "\n");
                        echo("Field UIType: " . $field->getUiType() . "\n");
                        echo("Field ReadOnly: "); print_r($field->getReadOnly()); echo("\n");
                        $associationDetails = $field->getAssociationDetails();
                        if($associationDetails != null)
                        {
                            $lookupField = $associationDetails->getLookupField();
                            if($lookupField != null)
                            {
                                echo("Field AssociationDetails LookupField ID: " . $lookupField->getId() . "\n");
                                echo("Field AssociationDetails LookupField Name: " . $lookupField->getName() . "\n");
                            }
                            $relatedField = $associationDetails->getRelatedField();
                            if($relatedField != null)
                            {
                                echo("Field AssociationDetails RelatedField ID: " . $relatedField->getId() . "\n");
                                echo("Field AssociationDetails RelatedField Name: " . $relatedField->getName() . "\n");
                            }
                        }
                        if($field->getQuickSequenceNumber() != null)
                        {
                            echo("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }
                        echo("Field BusinesscardSupported: "); print_r($field->getBusinesscardSupported()); echo("\n");
                        $multiModuleLookup = $field->getMultiModuleLookup();
                        if($multiModuleLookup != null)
                        {
                            $module = $multiModuleLookup->getModule();
                            if($module != null)
                            {
                                echo("Field MultiModuleLookup Module APIName: " . $module->getAPIName() . "\n");
                                echo("Field MultiModuleLookup Module Id: " . $module->getId() . "\n");
                            }
                            echo("Field MultiModuleLookup Name: " . $multiModuleLookup->getName() . "\n");
                            echo("Field MultiModuleLookup Id: " . $multiModuleLookup->getId() . "\n");
                        }
                        $currency = $field->getCurrency();
                        if($currency != null)
                        {
                            echo("Field Currency RoundingOption: " . $currency->getRoundingOption() . "\n");
                            if($currency->getPrecision() != null)
                            {
                                echo("Field Currency Precision: " . $currency->getPrecision() . "\n");
                            }
                        }
                        echo("Field ID: " . $field->getId() . "\n");
                        echo("Field CustomField: "); print_r($field->getCustomField()); echo("\n");
                        $lookup = $field->getLookup();
                        if($lookup != null)
                        {
                            $layout = $lookup->getLayout();
                            if($layout != null)
                            {
                                echo("Field Lookup Layout ID: " . $layout->getId() . "\n");
                                echo("Field Lookup Layout Name: " . $layout->getName() . "\n");
                            }
                            echo("Field Lookup DisplayLabel: " . $lookup->getDisplayLabel() . "\n");
                            echo("Field Lookup APIName: " . $lookup->getAPIName() . "\n");
                            echo("Field Lookup Module: " . $lookup->getModule() . "\n");
                            if($lookup->getId() != null)
                            {
                                echo("Field Lookup ID: " . $lookup->getId() . "\n");
                            }
                            echo("Field Lookup ModuleName: " . $lookup->getModuleName() . "\n");
                        }
                        echo("Field Filterable: " . $field->getFilterable() . "\n");
                        if($field->getConvertMapping() != null)
                        {
                            echo("Field ConvertMapping: \n");
                            foreach($field->getConvertMapping() as $key => $value)
                            {
                                echo($key . " : " . $value . "\n");
                            }
                        }
                        echo("Field Visible: "); print_r($field->getVisible()); echo("\n");
                        $profiles = $field->getProfiles();
                        if($profiles != null)
                        {
                            foreach($profiles as $profile)
                            {
                                echo("Field Profile PermissionType: " . $profile->getPermissionType() . "\n");
                                echo("Field Profile Name: " . $profile->getName() . "\n");
                                echo("Field Profile Id: " . $profile->getId() . "\n");
                            }
                        }
                        if($field->getLength() != null)
                        {
                            echo("Field Length: " . $field->getLength() . "\n");
                        }
                        $viewType = $field->getViewType();
                        if($viewType != null)
                        {
                            echo("Field ViewType View: " . $viewType->getView() . "\n");
                            echo("Field ViewType Edit: " . $viewType->getEdit() . "\n");
                            echo("Field ViewType Create: " . $viewType->getCreate() . "\n");
                            echo("Field ViewType QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }
                        if($field->getDisplayField() != null) 
						{
							echo("Field DisplayField " . $field->getDisplayField());
						}
                        echo("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");
                        echo("Field Sortable: "); print_r($field->getSortable()); echo("\n");
                        $subform = $field->getSubform();
                        if($subform != null)
                        {
                            $layout = $subform->getLayout();
                            if($layout != null)
                            {
                                echo("Field Subform Layout ID: " . $layout->getId() . "\n");
                                echo("Field Subform Layout Name: " . $layout->getName() . "\n");
                            }
                            if($subform->getDisplayLabel() != null)
                            {
                                echo("Field Subform DisplayLabel: " . $subform->getDisplayLabel() . "\n");
                            }
                            echo("Field Subform APIName: " . $subform->getAPIName() . "\n");
                            echo("Field Subform Module: " . $subform->getModule() . "\n");
                            if($subform->getId() != null)
                            {
                                echo("Field Subform ID: " . $subform->getId() . "\n");
                            }
                        }
                        $external = $field->getExternal();
                        if($external != null)
                        {
                            echo("Field External Show: " . $external->getShow() . "\n");
                            echo("Field External Type: " . $external->getType() . "\n");
                            echo("Field External AllowMultipleConfig: " . $external->getAllowMultipleConfig() . "\n");
                        }
                        echo("Field APIName: " . $field->getAPIName() . "\n");
                        $unique = $field->getUnique();
                        if($unique != null)
                        {
                            echo("Field Unique Casesensitive : " . $unique->getCasesensitive() . "\n");
                        }
                        if($field->getHistoryTracking() != null)
                        {
                            echo("Field HistoryTracking: " . print_r($field->getHistoryTracking()) . "\n");
                        }
                        echo("Field DataType: " . $field->getDataType() . "\n");
                        $formula = $field->getFormula();
                        if($formula != null)
                        {
                            echo("Field Formula ReturnType : " . $formula->getReturnType() . "\n");
                            if($formula->getExpression() != null)
                            {
                                echo("Field Formula Expression : " . $formula->getExpression() . "\n");
                            }
                        }
                        if($field->getDecimalPlace() != null)
                        {
                            echo("Field DecimalPlace: " . $field->getDecimalPlace() . "\n");
                        }
                        echo("Field MassUpdate: " . $field->getMassUpdate() . "\n");
                        if($field->getBlueprintSupported() != null)
                        {
                            echo("Field BlueprintSupported: " . $field->getBlueprintSupported());
                        }
                        $multiSelectLookup = $field->getMultiselectlookup();
                        if($multiSelectLookup != null)
                        {
                            echo("Field MultiSelectLookup DisplayLabel: " . $multiSelectLookup->getDisplayLabel() . "\n");
                            echo("Field MultiSelectLookup LinkingModule: " . $multiSelectLookup->getLinkingModule() . "\n");
                            echo("Field MultiSelectLookup LookupApiname: " . $multiSelectLookup->getLookupApiname() . "\n");
                            echo("Field MultiSelectLookup APIName: " . $multiSelectLookup->getAPIName() . "\n");
                            echo("Field MultiSelectLookup ConnectedModule: " . $multiSelectLookup->getConnectedModule() . "\n");
                            echo("Field MultiSelectLookup ConnectedlookupApiname: " . $multiSelectLookup->getConnectedlookupApiname() . "\n");
                            echo("Field MultiSelectLookup ID: " . $multiSelectLookup->getId() . "\n");
                        }
                        $pickListValues = $field->getPickListValues();
                        if($pickListValues != null)
                        {
                            foreach($pickListValues as $pickListValue)
                            {
                                self::printPickListValue($pickListValue);
                            }
                        }
                        $autoNumber = $field->getAutoNumber();
                        if($autoNumber != null)
                        {
                            echo("Field AutoNumber Prefix: " . $autoNumber->getPrefix() . "\n");
                            echo("Field AutoNumber Suffix: " . $autoNumber->getSuffix() . "\n");
                            if($autoNumber->getStartNumber() != null)
                            {
                                echo("Field AutoNumber StartNumber: " . $autoNumber->getStartNumber() . "\n");
                            }
                        }
                        if($field->getDefaultValue() != null)
						{
							echo("Field DefaultValue: " . $field->getDefaultValue());
						}
						
						if($field->getValidationRule() != null)
						{
							foreach($field->getValidationRule() as $key => $value )
							{
								echo($key . ": " . $value);
							}
						}
						if ($field->getMultiuserlookup() != null) 
						{
							$multiuserlookup = $field->getMultiuserlookup();
							echo("Get multiuserlookup display label" . $multiuserlookup->getDisplayLabel());
							echo("Get multiuserlookup linking module" . $multiuserlookup->getLinkingModule());
							echo("Get multiuserlookup lookup api_name" . $multiuserlookup->getLookupApiname());
							echo("Get multiuserlookup api name" . $multiuserlookup->getAPIName());
							echo("Get multiuserlookup id" . $multiuserlookup->getId());
							echo("Get multiuserlookup connected module" . $multiuserlookup->getConnectedModule());
							echo("Get multiuserlookup connected lookup api name" . $multiuserlookup->getConnectedlookupApiname());
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
                    echo($key . ": " .$value . "\n");
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }
    private static function printPickListValue($pickListValue)
    {
        echo("Field PickListValue DisplayValue: " . $pickListValue->getDisplayValue() . "\n");
		echo(" Fields PickListValue Probability: " . $pickListValue->getProbability());
		
		echo(" Fields PickListValue ForecastCategory: " . $pickListValue->getForecastCategory());
        if($pickListValue->getSequenceNumber() != null)
        {
            echo(" Field PickListValue SequenceNumber: " . $pickListValue->getSequenceNumber() . "\n");
        }
        echo("Field PickListValue ExpectedDataType: " . $pickListValue->getExpectedDataType() . "\n");
        echo(" Fields PickListValue ForecastType: " . $pickListValue->getForecastType());
        if($pickListValue->getMaps() != null)
        {
            foreach($pickListValue->getMaps() as $map)
            {
                echo("Field PickListValue Maps APIName: " . $map->getAPIName() . "\n");
                $pickListValues = $map->getPickListValues();
                if($pickListValues != null)
                {
                    foreach($pickListValues as $pickListValue1)
                    {
                        self::printPickListValue($pickListValue1);
                    }
                }
            }
        }
        echo("Field PickListValue ActualValue: " . $pickListValue->getActualValue() . "\n");
        echo("Field PickListValue SysRefName: " . $pickListValue->getSysRefName() . "\n");
        echo("Field PickListValue Type: " . $pickListValue->getType() . "\n");
        echo("Field PickListValue Id: " . $pickListValue->getId() . "\n");
    }
}

GetField::initialize();
$moduleAPIName = "Leads";
$fieldId = "34770610022011";
GetField::getField($moduleAPIName, $fieldId);