<?php
namespace com\zoho\crm\sample\blueprint;

use com\zoho\crm\api\blueprint\BluePrintOperations;

use com\zoho\crm\api\blueprint\BodyWrapper;

use com\zoho\crm\api\blueprint\ResponseWrapper;

use com\zoho\crm\api\blueprint\APIException;

use com\zoho\crm\api\record\Record;

use com\zoho\crm\api\blueprint\SuccessResponse;

class BluePrint
{
	/**
	 * <h3> Get Blueprint </h3>
	 * This method is used to get a single record's Blueprint details with ID and print the response.
	 * @param moduleAPIName The API Name of the record's module
	 * @param recordId The ID of the record to get Blueprint
	 * @throws Exception
	 */
	public static function getBlueprint(string $moduleAPIName, string $recordId)
	{
		//Get instance of BluePrintOperations Class that takes recordId and moduleAPIName as parameter
	    $bluePrintOperations = new BluePrintOperations($recordId,$moduleAPIName);

		//Call getBlueprint method
        $response = $bluePrintOperations->getBlueprint();

        if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
            }

            //Get object from response
            $responseHandler = $response->getObject();

            if($responseHandler instanceof ResponseWrapper)
            {
                //Get the received ResponseWrapper instance
                $responseWrapper = $responseHandler;

                //Get the obtained BluePrint instance
                $bluePrint = $responseWrapper->getBlueprint();

                //Get the ProcessInfo instance of the obtained BluePrint
                $processInfo = $bluePrint->getProcessInfo();

                //Check if ProcessInfo is not null
                if($processInfo != null)
                {
                    //Get the Field ID of the ProcessInfo
                    echo("ProcessInfo Field-ID: " . $processInfo->getFieldId() . "\n");

                    $escalation = $processInfo->getEscalation();

                    if($escalation != null)
                    {
                        echo("ProcessInfo Escalation Days : " . $escalation->getDays() . "\n");

                        echo("ProcessInfo Escalation Status : " . $escalation->getStatus() . "\n");
                    }

                    //Get the isContinuous of the ProcessInfo
                    echo("ProcessInfo isContinuous: " . $processInfo->getIsContinuous() . "\n");

                    //Get the API Name of the ProcessInfo
                    echo("ProcessInfo API Name: " . $processInfo->getAPIName() . "\n");

                    //Get the Continuous of the ProcessInfo
                    echo("ProcessInfo Continuous: " . $processInfo->getContinuous() . "\n");

                    //Get the FieldLabel of the ProcessInfo
                    echo("ProcessInfo FieldLabel: " . $processInfo->getFieldLabel() . "\n");

                    //Get the Name of the ProcessInfo
                    echo("ProcessInfo Name: " . $processInfo->getName() . "\n");

                    //Get the ColumnName of the ProcessInfo
                    echo("ProcessInfo ColumnName: " . $processInfo->getColumnName() . "\n");

                    //Get the FieldValue of the ProcessInfo
                    echo("ProcessInfo FieldValue: " . $processInfo->getFieldValue() . "\n");

                    //Get the ID of the ProcessInfo
                    echo("ProcessInfo ID: " . $processInfo->getId() . "\n");

                    //Get the FieldName of the ProcessInfo
                    echo("ProcessInfo FieldName: " . $processInfo->getFieldName() . "\n");
                }

                //Get the list of transitions from BluePrint instance
                $transitions = $bluePrint->getTransitions();

                foreach($transitions as $transition)
                {
                    $nextTransitions = $transition->getNextTransitions();

                    foreach($nextTransitions as $nextTransition)
                    {
                        //Get the ID of the NextTransition
                        echo("NextTransition ID: " . $nextTransition->getId() . "\n");

                        //Get the Name of the NextTransition
                        echo("NextTransition Name: " . $nextTransition->getName() . "\n");
                    }

                    $data = $transition->getData();

                    if($data != null)
                    {
                        //Get the ID of each record
                        echo("Record ID: " . $data->getId() . "\n");

                        //Get the createdBy User instance of each record
                        $createdBy = $data->getCreatedBy();

                        if($createdBy != null)
                        {
                            //Get the ID of the createdBy User
                            echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

                            //Get the name of the createdBy User
                            echo("Record Created By User-Name: " . $createdBy->getName() . "\n");
                        }

                        //Check if the created time is not null
                        if($data->getCreatedTime() != null)
                        {
                            //Get the created time of each record
                            echo("Record Created Time: " . $data->getCreatedTime() . "\n");
                        }

                        //Check if the modified time is not null
                        if($data->getModifiedTime() != null)
                        {
                            //Get the modified time of each record
                            echo("Record Modified Time: " . $data->getModifiedTime() . "\n");
                        }

                        //Get the modifiedBy User instance of each record
                        $modifiedBy = $data->getModifiedBy();

                        //Check if modifiedByUser is not null
                        if($modifiedBy != null)
                        {
                            //Get the ID of the modifiedBy User
                            echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

                            //Get the name of the modifiedBy User
                            echo("Record Modified By user-Name: " . $modifiedBy->getName() . "\n");
                        }

                        //Get all entries from the keyValues map
                        foreach($data->getKeyValues() as $key => $value)
                        {
                            //Get each value from the map
                            echo($key . ": " . $value . "\n");
                        }
                    }

                    //Get the NextFieldValue of the Transition
                    echo("Transition NextFieldValue: " . $transition->getNextFieldValue() . "\n");

                    //Get the Name of each Transition
                    echo("Transition Name: " . $transition->getName() . "\n");

                    //Get the CriteriaMatched of the Transition
                    echo("Transition CriteriaMatched: " . $transition->getCriteriaMatched() . "\n");

                    //Get the ID of the Transition
                    echo("Transition ID: " . $transition->getId() . "\n");

                    $fields = $transition->getFields();

                    foreach($fields as $field)
                    {
                        if($field->getSystemMandatory() != null)
                        {
                            //Get the SystemMandatory of each Field
                            echo("Field is SystemMandatory: " . $field->getSystemMandatory() . "\n");
                        }

                        //Get the private of each Field
                        echo("Field is Private" . $field->getPrivate() . "\n");

                        //Get the webhook of each Field
                        echo("Field Webhook" . $field->getWebhook() . "\n");

                        //Get the JsonType of each Field
                        echo("Field JsonType: " . $field->getJsonType() . "\n");

                        //Get the Crypt of each Field
                        echo("Field Crypt: " . $field->getCrypt() . "\n");

                        //Get the FieldLabel of each Field
                        echo("Field FieldLabel: " . $field->getFieldLabel() . "\n");

                        //Get the Tooltip of each Field
                        $toolTip = $field->getTooltip();

                        if($toolTip != null)
                        {
                            //Get the Tooltip Name
                            echo("Field Tooltip Name: " . $toolTip->getName() . "\n");

                            //Get the Tooltip Value
                            echo("Field Tooltip Value: " . $toolTip->getValue() . "\n");
                        }

                        //Get the CreatedSource of each Field
                        echo("Field CreatedSource: " . $field->getCreatedSource() . "\n");

                        $layout = $field->getLayouts();

                        if($layout != null)
                        {
                            //Get the ID of the Layout
                            echo("Field Layout ID: " . $layout->getId() . "\n");

                            //Get the name of the Layout
                            echo("Field Layout Name: " . $layout->getName() . "\n");
                        }

                        if($field->getFieldReadOnly() != null)
                        {
                            //Get the FieldReadOnly of each Field
                            echo("Field ReadOnly: " . $field->getFieldReadOnly() . "\n");
                        }

                        //Get the Content of each Field
                        echo("Field Content: " . $field->getContent() . "\n");

                        //Get the DisplayLabel of each Field
                        echo("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");

                        if($field->getDisplayType() != null)
                        {
                            //Get the DisplayType of each Field
                            echo("Field DisplayType: " . $field->getDisplayType()->getValue() . "\n");
                        }

                        //Get the UiType of each Field
                        echo("Field UiType: " . $field->getUiType() . "\n");

                        //Get the ValidationRule of each Field
                        echo("Field ValidationRule: " . $field->getValidationRule() . "\n");

                        if($field->getReadOnly() != null)
                        {
                            //Get the ReadOnly of each Field
                            echo("Field ReadOnly: " . $field->getReadOnly() . "\n");
                        }

                        //Get the AssociationDetails of each Field
                        echo("Field AssociationDetails: " . $field->getAssociationDetails() . "\n");

                        if($field->getQuickSequenceNumber() != null)
                        {
                            //Get the QuickSequenceNumber of each Field
                            echo("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }

                        //Get the MultiModuleLookup of each Field
                        $multiModuleLookup = $field->getMultiModuleLookup();

                        if($multiModuleLookup != null)
                        {
                            $module = $multiModuleLookup->getModule();

                            if($module != null)
                            {
                                //Get the APIName of MultiModuleLookup Module
                                echo("Field MultiModuleLookup Module APIName: " . $module->getAPIName() . "\n");

                                //Get the Id of MultiModuleLookup Module
                                echo("Field MultiModuleLookup Module Id: " . $module->getId() . "\n");
                            }

                            //Get the APIName of MultiModuleLookup
                            echo("Field MultiModuleLookup Name: " . $multiModuleLookup->getName() . "\n");

                            //Get the Id of MultiModuleLookup
                            echo("Field MultiModuleLookup Id: " . $multiModuleLookup->getId() . "\n");
                        }

                        //Get the Object obtained Currency instance
                        $currency = $field->getCurrency();

                        //Check if currency is not null
                        if($currency != null)
                        {
                            //Get the RoundingOption of the Currency
                            echo("Field Currency RoundingOption: " . $currency->getRoundingOption() . "\n");

                            if($currency->getPrecision() != null)
                            {
                                //Get the Precision of the Currency
                                echo("Field Currency Precision: " . $currency->getPrecision() . "\n");
                            }
                        }

                        //Get the ID of each Field
                        echo("Field ID: " . $field->getId() . "\n");

                        if($field->getCustomField() != null)
                        {
                            //Get the CustomField of each Field
                            echo("Field CustomField: " . $field->getCustomField() . "\n");
                        }

                        //Get the Object obtained Module instance
                        $lookup = $field->getLookup();

                        //Check if lookup is not null
                        if($lookup != null)
                        {
                            //Get the Object obtained Layout instance
                            $layout = $lookup->getLayout();

                            //Check if layout is not null
                            if($layout != null)
                            {
                                //Get the ID of the Layout
                                echo("Field Lookup Layout ID: " . $layout->getId() . "\n");

                                //Get the Name of the Layout
                                echo("Field Lookup Layout Name: " . $layout->getName() . "\n");
                            }

                            //Get the DisplayLabel of the Module
                            echo("Field Lookup DisplayLabel: " . $lookup->getDisplayLabel() . "\n");

                            //Get the APIName of the Module
                            echo("Field Lookup APIName: " . $lookup->getAPIName() . "\n");

                            //Get the Module of the Module
                            echo("Field Lookup Module: " . $lookup->getModule() . "\n");

                            if($lookup->getId() != null)
                            {
                                //Get the ID of the Module
                                echo("Field Lookup ID: " . $lookup->getId() . "\n");
                            }
                        }

                        //Get the Filterable of each Field
                        echo("Field Filterable: " . $field->getFilterable() . "\n");

                        //Check if ConvertMapping is not null
                        if($field->getConvertMapping() != null)
                        {
                            //Get the details map
                            foreach($field->getConvertMapping() as $key => $value)
                            {
                                //Get each value in the map
                                echo($key . " : " . $value . "\n");
                            }
                        }

                        if($field->getVisible() != null)
                        {
                            //Get the Visible of each Field
                            echo("Field Visible: " . $field->getVisible() . "\n");
                        }

                        $profiles = $field->getProfiles();

                        if($profiles != null)
                        {
                            foreach($profiles as $profile)
                            {
                                //Get the PermissionType of each Profile
                                echo("Field Profile PermissionType: " . $profile->getPermissionType() . "\n");

                                //Get the Name of each Profile
                                echo("Field Profile Name: " . $profile->getName() . "\n");

                                //Get the Id of each Profile
                                echo("Field Profile Id: " . $profile->getId() . "\n");
                            }
                        }

                        if($field->getLength() != null)
                        {
                            //Get the Length of each Field
                            echo("Field Length: " . $field->getLength() . "\n");
                        }

                        //Get the ColumnName of each Field
                        echo("Field ColumnName: " . $field->getColumnName() . "\n");

                        //Get the Type of each Field
                        echo("Field Type: " . $field->getType() . "\n");

                        $viewType = $field->getViewType();

                        if($viewType != null)
                        {
                            //Get the View of the ViewType
                            echo("Field View: " . $viewType->getView() . "\n");

                            //Get the Edit of the ViewType
                            echo("Field Edit: " . $viewType->getEdit() . "\n");

                            //Get the Create of the ViewType
                            echo("Field Create: " . $viewType->getCreate() . "\n");

                            //Get the View of the ViewType
                            echo("Field QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }

                        //Get the PickListValuesSortedLexically of each Field
                        echo("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");

                        //Get the Sortable of each Field
                        echo("Field Sortable: " . $field->getSortable() . "\n");

                        //Get the TransitionSequence of each Field
                        echo("Field TransitionSequence: " . $field->getTransitionSequence() . "\n");

                        $external = $field->getExternal();

                        if($external != null)
                        {
                            //Get the Show of External
                            echo("Field External Show: " . $external->getShow() . "\n");

                            //Get the Type of External
                            echo("Field External Type: " . $external->getType() . "\n");

                            //Get the AllowMultipleConfig of External
                            echo("Field External AllowMultipleConfig: " . $external->getAllowMultipleConfig() . "\n");
                        }

                        //Get the APIName of each Field
                        echo("Field APIName: " . $field->getAPIName() . "\n");

                        //Get the Object obtained Unique instance
                        $unique = $field->getUnique();

                        //Check if unique is not null
                        if($unique != null)
                        {
                            //Get the Casesensitive of the Unique
                            echo("Field Unique Casesensitive : " . $unique->getCasesensitive() . "\n");
                        }

                        if($field->getHistoryTracking() != null)
                        {
                            //Get the HistoryTracking of each Field
                            echo("Field HistoryTracking: " . print_r($field->getHistoryTracking()) . "\n");
                        }

                        //Get the DataType of each Field
                        echo("Field DataType: " . $field->getDataType() . "\n");

                        //Get the Object obtained Formula instance
                        $formula = $field->getFormula();

                        //Check if formula is not null
                        if($formula != null)
                        {
                            //Get the ReturnType of the Formula
                            echo("Field Formula ReturnType : " . $formula->getReturnType() . "\n");

                            if($formula->getExpression() != null)
                            {
                                //Get the Expression of the Formula
                                echo("Field Formula Expression : " . $formula->getExpression() . "\n");
                            }
                        }

                        if($field->getDecimalPlace() != null)
                        {
                            //Get the DecimalPlace of each Field
                            echo("Field DecimalPlace: " . $field->getDecimalPlace() . "\n");
                        }

                        //Get all entries from the MultiSelectLookup instance
                        $multiSelectLookup = $field->getMultiselectlookup();

                        if($multiSelectLookup != null)
                        {
                            //Get the DisplayValue of the MultiSelectLookup
                            echo("Field MultiSelectLookup DisplayLabel: " . $multiSelectLookup->getDisplayLabel() . "\n");

                            //Get the LinkingModule of the MultiSelectLookup
                            echo("Field MultiSelectLookup LinkingModule: " . $multiSelectLookup->getLinkingModule() . "\n");

                            //Get the LookupApiname of the MultiSelectLookup
                            echo("Field MultiSelectLookup LookupApiname: " . $multiSelectLookup->getLookupApiname() . "\n");

                            //Get the APIName of the MultiSelectLookup
                            echo("Field MultiSelectLookup APIName: " . $multiSelectLookup->getAPIName() . "\n");

                            //Get the ConnectedlookupApiname of the MultiSelectLookup
                            echo("Field MultiSelectLookup ConnectedlookupApiname: " . $multiSelectLookup->getConnectedlookupApiname() . "\n");

                            //Get the ID of the MultiSelectLookup
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

                        //Get the AutoNumber of each Field
                        $autoNumber = $field->getAutoNumber();

                        if($autoNumber != null)
                        {
                            //Get the Prefix of the AutoNumber
                            echo("Field AutoNumber Prefix: " . $autoNumber->getPrefix() . "\n");

                            //Get the Suffix of the AutoNumber
                            echo("Field AutoNumber Suffix: " . $autoNumber->getSuffix() . "\n");

                            if($autoNumber->getStartNumber() != null)
                            {
                                //Get the StartNumber of the AutoNumber
                                echo("Field AutoNumber StartNumber: " . $autoNumber->getStartNumber() . "\n");
                            }
                        }

                        //Get the PersonalityName of each Field
                        echo("Field PersonalityName: " . $field->getPersonalityName() . "\n");

                        if($field->getMandatory() != null)
                        {
                            //Get the Mandatory of each Field
                            echo("Field Mandatory: " . $field->getMandatory() . "\n");
                        }
                    }

                    //Get the type of each Transition
                    echo("Transition Type: " . $transition->getType() . "\n");

                    //Get the CriteriaMessage of each Transition
                    echo("Transition CriteriaMessage: " . $transition->getCriteriaMessage() . "\n");

                    //Get the PercentPartialSave of each Transition
                    echo("Transition PercentPartialSave: " . $transition->getPercentPartialSave() . "\n");

                    //Get the executionTime of each Transition
                    echo("Transition ExecutionTime: " . $transition->getExecutionTime() . "\n");
                }
            }
            //Check if the request returned an exception
            else if($responseHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $responseHandler;

                //Get the Status
                echo("Status: " . $exception->getStatus()->getValue() . "\n");

                //Get the Code
                echo("Code: " . $exception->getCode()->getValue() . "\n");

                if($exception->getDetails() != null)
                {
                    echo("Details: \n");

                    //Get the details map
                    foreach ($exception->getDetails() as $keyName => $keyValue)
                    {
                        //Get each value in the map
                        echo($keyName . ": " . $keyValue . "\n");
                    }
                }

                //Get the Message
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }

	/**
	 * <h3> Update Blueprint </h3>
	 * This method is used to update a single record's Blueprint details with ID and print the response.
	 * @param moduleAPIName The API Name of the record's module
	 * @param recordId The ID of the record to get Blueprint
	 * @param transitionId The ID of the Blueprint transition Id
	 * @throws Exception
	 */
	public static function updateBlueprint(string $moduleAPIName, string $recordId, string $transitionId)
	{
		//ID of the BluePrint to be updated
		//$transitionId = "3477061173096";

		//Get instance of BluePrintOperations Class that takes moduleAPIName and recordId as parameter
	    $bluePrintOperations = new BluePrintOperations($recordId,$moduleAPIName);

		//Get instance of BodyWrapper Class that will contain the request body
		$bodyWrapper = new BodyWrapper();

		//List of BluePrint instances
		$bluePrintList = array();

        $bluePrintClass = 'com\zoho\crm\api\blueprint\BluePrint';

		//Get instance of BluePrint Class
        $bluePrint = new $bluePrintClass();

		//Set transition_id to the BluePrint instance
		$bluePrint->setTransitionId($transitionId);

		//Get instance of Record Class
        $data = new Record();

		$lookup = array();

		$lookup["Phone"] = "8940372937";

		$lookup["id"] = "8940372937";

		// $data->addKeyValue("Lookup_2", $lookup);

		$data->addKeyValue("Phone", "8940372937");

        $data->addKeyValue("Notes", "Updated via blueprint");

        $attachments = array();

        $attachment = array();

		$fileIds = array();

		array_push($fileIds, "blojtd2d13b5f044e4041a3315e0793fb21ef");

        $attachment['$file_id'] = $fileIds;

        array_push($attachments, $attachment);

		$data->addKeyValue("Attachments", $attachments);

		$checkLists = array();

		$list = array();

		$list["list 1"] = true;

		array_push($checkLists, $list);

		$list = array();

		$list["list 2"] = true;

		array_push($checkLists, $list);

		$list = array();

		$list["list 3"] =  true;

		array_push($checkLists, $list);

		$data->addKeyValue("CheckLists", $checkLists);

		//Set data to the BluePrint instance
		$bluePrint->setData($data);

		//Add BluePrint instance to the list
        array_push($bluePrintList, $bluePrint);

		//Set the list to bluePrint in BodyWrapper instance
        $bodyWrapper->setBlueprint($bluePrintList);

        // var_dump($bodyWrapper);

		//Call updateBluePrint method that takes BodyWrapper instance
        $response = $bluePrintOperations->updateBlueprint($bodyWrapper);

        if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            //Get object from response
            $actionResponse = $response->getObject();

            //Check if the request is successful
            if($actionResponse instanceof SuccessResponse)
            {
                //Get the received SuccessResponse instance
                $successResponse = $actionResponse;

                //Get the Status
                echo("Status: " . $successResponse->getStatus()->getValue() . "\n");

                //Get the Code
                echo("Code: " . $successResponse->getCode()->getValue() . "\n");

                echo("Details: " );

                if($successResponse->getDetails() != null)
                {
                    //Get the details map
                    foreach ($successResponse->getDetails() as $keyName => $keyValue)
                    {
                        //Get each value in the map
                        echo($keyName . ": " . $keyValue . "\n");
                    }
                }

                //Get the Message
                echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
            }
            //Check if the request returned an exception
            else if($actionResponse instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $actionResponse;

                //Get the Status
                echo("Status: " . $exception->getStatus()->getValue() . "\n");

                //Get the Code
                echo("Code: " . $exception->getCode()->getValue() . "\n");

                echo("Details: " );

                if($exception->getDetails() != null)
                {
                    //Get the details map
                    foreach ($exception->getDetails() as $keyName => $keyValue)
                    {
                        //Get each value in the map
                        echo($keyName . ": " . $keyValue . "\n");
                    }
                }

                //Get the Message
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }

    private static function printPickListValue($pickListValue)
    {
        //Get the DisplayValue of each PickListValues
        echo("Field PickListValue DisplayValue: " . $pickListValue->getDisplayValue() . "\n");

        if($pickListValue->getSequenceNumber() != null)
        {
            //Get the SequenceNumber of each PickListValues
            echo(" Field PickListValue SequenceNumber: " . $pickListValue->getSequenceNumber() . "\n");
        }

        //Get the ExpectedDataType of each PickListValues
        echo("Field PickListValue ExpectedDataType: " . $pickListValue->getExpectedDataType() . "\n");

        if($pickListValue->getMaps() != null)
        {
            foreach($pickListValue->getMaps() as $map)
            {
                echo("Field PickListValue Maps APIName: " . $map->getAPIName() . "\n");

                //Get the PickListValue of each Maps
                $pickListValues = $map->getPickListValues();

                //Check if formula is not null
                if($pickListValues != null)
                {
                    foreach($pickListValues as $pickListValue1)
                    {
                        self::printPickListValue($pickListValue1);
                    }
                }
            }
        }

        //Get the ActualValue of each PickListValues
        echo("Field PickListValue ActualValue: " . $pickListValue->getActualValue() . "\n");

        //Get the SysRefName of each PickListValues
        echo("Field PickListValue SysRefName: " . $pickListValue->getSysRefName() . "\n");

        //Get the Type of each PickListValues
        echo("Field PickListValue Type: " . $pickListValue->getType() . "\n");

        //Get the Id of each PickListValues
        echo("Field PickListValue Id: " . $pickListValue->getId() . "\n");
    }
}
