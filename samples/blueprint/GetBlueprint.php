<?php
namespace samples\blueprint;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\blueprint\BluePrintOperations;
use com\zoho\crm\api\blueprint\ResponseWrapper;
use com\zoho\crm\api\blueprint\APIException;
require_once "vendor/autoload.php";

class GetBlueprint
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

	public static function getBlueprint(string $moduleAPIName, string $recordId)
	{
	    $bluePrintOperations = new BluePrintOperations($recordId,$moduleAPIName);
        $response = $bluePrintOperations->getBlueprint();
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
                $bluePrint = $responseWrapper->getBlueprint();
                $processInfo = $bluePrint->getProcessInfo();
                if($processInfo != null)
                {
                    echo("ProcessInfo Field-ID: " . $processInfo->getFieldId() . "\n");
                    $escalation = $processInfo->getEscalation();
                    if($escalation != null)
                    {
                        echo("ProcessInfo Escalation Days : " . $escalation->getDays() . "\n");
                        echo("ProcessInfo Escalation Status : " . $escalation->getStatus() . "\n");
                    }
                    echo("ProcessInfo isContinuous: " . $processInfo->getIsContinuous() . "\n");
                    echo("ProcessInfo API Name: " . $processInfo->getAPIName() . "\n");
                    echo("ProcessInfo Continuous: " . $processInfo->getContinuous() . "\n");
                    echo("ProcessInfo FieldLabel: " . $processInfo->getFieldLabel() . "\n");
                    echo("ProcessInfo Name: " . $processInfo->getName() . "\n");
                    echo("ProcessInfo ColumnName: " . $processInfo->getColumnName() . "\n");
                    echo("ProcessInfo FieldValue: " . $processInfo->getFieldValue() . "\n");
                    echo("ProcessInfo ID: " . $processInfo->getId() . "\n");
                    echo("ProcessInfo FieldName: " . $processInfo->getFieldName() . "\n");
                }
                $transitions = $bluePrint->getTransitions();
                foreach($transitions as $transition)
                {
                    $nextTransitions = $transition->getNextTransitions();
                    foreach($nextTransitions as $nextTransition)
                    {
                        echo("NextTransition ID: " . $nextTransition->getId() . "\n");
                        echo("NextTransition Name: " . $nextTransition->getName() . "\n");
                    }
                    $data = $transition->getData();
                    if($data != null)
                    {
                        echo("Record ID: " . $data->getId() . "\n");
                        $createdBy = $data->getCreatedBy();
                        if($createdBy != null)
                        {
                            echo("Record Created By User-ID: " . $createdBy->getId() . "\n");
                            echo("Record Created By User-Name: " . $createdBy->getName() . "\n");
                        }
                        if($data->getCreatedTime() != null)
                        {
                            echo("Record Created Time: " . $data->getCreatedTime() . "\n");
                        }
                        if($data->getModifiedTime() != null)
                        {
                            echo("Record Modified Time: " . $data->getModifiedTime() . "\n");
                        }
                        $modifiedBy = $data->getModifiedBy();
                        if($modifiedBy != null)
                        {
                            echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");
                            echo("Record Modified By user-Name: " . $modifiedBy->getName() . "\n");
                        }
                        foreach($data->getKeyValues() as $key => $value)
                        {
                            echo($key . ": " . $value . "\n");
                        }
                    }
                    echo("Transition NextFieldValue: " . $transition->getNextFieldValue() . "\n");
                    echo("Transition Name: " . $transition->getName() . "\n");
                    echo("Transition CriteriaMatched: " . $transition->getCriteriaMatched() . "\n");
                    echo("Transition ID: " . $transition->getId() . "\n");
                    $fields = $transition->getFields();
                    foreach($fields as $field)
                    {
                        if($field->getSystemMandatory() != null)
                        {
                            echo("Field is SystemMandatory: " . $field->getSystemMandatory() . "\n");
                        }
                        echo("Field is Private" . $field->getPrivate() . "\n");
                        echo("Field Webhook" . $field->getWebhook() . "\n");
                        echo("Field JsonType: " . $field->getJsonType() . "\n");
                        echo("Field Crypt: " . $field->getCrypt() . "\n");
                        echo("Field FieldLabel: " . $field->getFieldLabel() . "\n");
                        $toolTip = $field->getTooltip();
                        if($toolTip != null)
                        {
                            echo("Field Tooltip Name: " . $toolTip->getName() . "\n");
                            echo("Field Tooltip Value: " . $toolTip->getValue() . "\n");
                        }
                        echo("Field CreatedSource: " . $field->getCreatedSource() . "\n");
                        $layout = $field->getLayouts();
                        if($layout != null)
                        {
                            echo("Field Layout ID: " . $layout->getId() . "\n");
                            echo("Field Layout Name: " . $layout->getName() . "\n");
                        }
                        if($field->getFieldReadOnly() != null)
                        {
                            echo("Field ReadOnly: " . $field->getFieldReadOnly() . "\n");
                        }
                        echo("Field Content: " . $field->getContent() . "\n");
                        echo("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");
                        if($field->getDisplayType() != null)
                        {
                            echo("Field DisplayType: " . $field->getDisplayType()->getValue() . "\n");
                        }
                        echo("Field UiType: " . $field->getUiType() . "\n");
                        echo("Field ValidationRule: " . $field->getValidationRule() . "\n");
                        if($field->getReadOnly() != null)
                        {
                            echo("Field ReadOnly: " . $field->getReadOnly() . "\n");
                        }
                        echo("Field AssociationDetails: " . $field->getAssociationDetails() . "\n");
                        if($field->getQuickSequenceNumber() != null)
                        {
                            echo("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }
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
                        if($field->getCustomField() != null)
                        {
                            echo("Field CustomField: " . $field->getCustomField() . "\n");
                        }
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
                        }
                        echo("Field Filterable: " . $field->getFilterable() . "\n");
                        if($field->getConvertMapping() != null)
                        {
                            foreach($field->getConvertMapping() as $key => $value)
                            {
                                echo($key . " : " . $value . "\n");
                            }
                        }
                        if($field->getVisible() != null)
                        {
                            echo("Field Visible: " . $field->getVisible() . "\n");
                        }
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
                        echo("Field ColumnName: " . $field->getColumnName() . "\n");
                        echo("Field Type: " . $field->getType() . "\n");
                        $viewType = $field->getViewType();
                        if($viewType != null)
                        {
                            echo("Field View: " . $viewType->getView() . "\n");
                            echo("Field Edit: " . $viewType->getEdit() . "\n");
                            echo("Field Create: " . $viewType->getCreate() . "\n");
                            echo("Field QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }
                        echo("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");
                        echo("Field Sortable: " . $field->getSortable() . "\n");
                        echo("Field TransitionSequence: " . $field->getTransitionSequence() . "\n");
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
                        $multiSelectLookup = $field->getMultiselectlookup();
                        if($multiSelectLookup != null)
                        {
                            echo("Field MultiSelectLookup DisplayLabel: " . $multiSelectLookup->getDisplayLabel() . "\n");
                            echo("Field MultiSelectLookup LinkingModule: " . $multiSelectLookup->getLinkingModule() . "\n");
                            echo("Field MultiSelectLookup LookupApiname: " . $multiSelectLookup->getLookupApiname() . "\n");
                            echo("Field MultiSelectLookup APIName: " . $multiSelectLookup->getAPIName() . "\n");
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
                        echo("Field PersonalityName: " . $field->getPersonalityName() . "\n");
                        if($field->getMandatory() != null)
                        {
                            echo("Field Mandatory: " . $field->getMandatory() . "\n");
                        }
                    }
                    echo("Transition Type: " . $transition->getType() . "\n");
                    echo("Transition CriteriaMessage: " . $transition->getCriteriaMessage() . "\n");
                    echo("Transition PercentPartialSave: " . $transition->getPercentPartialSave() . "\n");
                    echo("Transition ExecutionTime: " . $transition->getExecutionTime() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                echo("Code: " . $exception->getCode()->getValue() . "\n");
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
    }
	
    private static function printPickListValue($pickListValue)
    {
        echo("Field PickListValue DisplayValue: " . $pickListValue->getDisplayValue() . "\n");
        if($pickListValue->getSequenceNumber() != null)
        {
            echo(" Field PickListValue SequenceNumber: " . $pickListValue->getSequenceNumber() . "\n");
        }
        echo("Field PickListValue ExpectedDataType: " . $pickListValue->getExpectedDataType() . "\n");
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

GetBlueprint::initialize();
$moduleAPIName = "Leads";
$recordId = "34770614381002";
GetBlueprint::getBlueprint($moduleAPIName, $recordId);
