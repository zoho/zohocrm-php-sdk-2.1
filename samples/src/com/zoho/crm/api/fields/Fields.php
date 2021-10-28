<?php
namespace com\zoho\crm\sample\fields;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\fields\APIException;

use com\zoho\crm\api\fields\FieldsOperations;

use com\zoho\crm\api\fields\GetFieldsParam;

use com\zoho\crm\api\fields\ResponseWrapper;

class Fields
{
	/**
	 * <h3> Get Fields </h3>
	 * This method is used to get metadata about all the fields of a module and print the response.
	 * @throws Exception
	 * @param moduleAPIName The API Name of the module to get fields
	 */
	public static function getFields(string $moduleAPIName)
	{
		//example, moduleAPIName = "module_api_name";

		//Get instance of FieldsOperations Class that takes moduleAPIName as parameter
		$fieldOperations = new FieldsOperations($moduleAPIName);

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		// $paramInstance->add(GetFieldsParam::type(), "unused");

		//Call getFields method that takes paramInstance as parameter
		$response = $fieldOperations->getFields($paramInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

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

                //Get the list of obtained Field instances
                $fields = $responseWrapper->getFields();

                if($fields != null)
                {
                    foreach($fields as $field)
                    {
                        //Get the SystemMandatory of each Field
                        echo("Field SystemMandatory: " ); print_r($field->getSystemMandatory()); echo("\n");

                        //Get the private info of each field
                        $privateInfo = $field->getPrivate();

                        //Check if privateInfo is not null
                        if($privateInfo != null)
                        {
                            echo("Private Details\n");

                            //Get the type
                            echo("Field Private Type: " . $privateInfo->getType() . "\n");

                            //Get the Export
                            echo("Field Private Export: " . $privateInfo->getExport() . "\n");

                            //Get the Restricted
                            echo("Field Private Restricted: " . $privateInfo->getRestricted() . "\n");
                        }

                        //Get the Webhook of each Field
                        echo("Field Webhook: "); print_r($field->getWebhook()); print_r("\n");

                        //Get the JsonType of each Field
                        echo("Field JsonType: " . $field->getJsonType() . "\n");

                        //Get the obtained Crypt instance
                        $crypt = $field->getCrypt();

                        //Check if crypt is not null
                        if($crypt != null)
                        {
                            //Get the Mode of the Crypt
                            echo("Field Crypt Mode: " . $crypt->getMode() . "\n");

                            //Get the Column of the Crypt
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

                            //Get the Notify of the Crypt
                            echo("Field Crypt Notify: " . $crypt->getNotify() . "\n");

                            //Get the Table of the Crypt
                            echo("Field Crypt Table: " . $crypt->getTable() . "\n");

                            //Get the Status of the Crypt
                            echo("Field Crypt Status: " . $crypt->getStatus() . "\n");
                        }

                        //Get the FieldLabel of each Field
                        echo("Field FieldLabel: " . $field->getFieldLabel() . "\n");

                        //Get the Object obtained ToolTip instance
                        $tooltip = $field->getTooltip();

                        //Check if tooltip is not null
                        if($tooltip != null)
                        {
                            //Get the Name of the ToolTip
                            echo("Field ToolTip Name: " . $tooltip->getName() . "\n");

                            //Get the Value of the ToolTip
                            echo("Field ToolTip Value: " . $tooltip->getValue() . "\n");
                        }

                        //Get the CreatedSource of each Field
                        echo("Field CreatedSource: " . $field->getCreatedSource() . "\n");

                        //Get the Type of each Field
                        echo("Field Type: " . $field->getType() . "\n");

                        //Get the FieldReadOnly of each Field
                        echo("Field FieldReadOnly: "); print_r($field->getFieldReadOnly()); echo("\n");

                        //Get the DisplayLabel of each Field
                        echo("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");

                        //Get the DisplayType of each Field
                        echo("Field DisplayType: " . $field->getDisplayType()->getValue() . "\n");

                        //Get the UIType of each Field
                        echo("Field UIType: " . $field->getUiType() . "\n");

                        //Get the ReadOnly of each Field
                        echo("Field ReadOnly: "); print_r($field->getReadOnly()); echo("\n");

                        //Get the Object obtained AssociationDetails instance
                        $associationDetails = $field->getAssociationDetails();

                        //Check if associationDetails is not null
                        if($associationDetails != null)
                        {
                            //Get the Object obtained LookupField instance
                            $lookupField = $associationDetails->getLookupField();

                            //Check if lookupField is not null
                            if($lookupField != null)
                            {
                                //Get the ID of the LookupField
                                echo("Field AssociationDetails LookupField ID: " . $lookupField->getId() . "\n");

                                //Get the Name of the LookupField
                                echo("Field AssociationDetails LookupField Name: " . $lookupField->getName() . "\n");
                            }

                            //Get the Object obtained LookupField instance
                            $relatedField = $associationDetails->getRelatedField();

                            //Check if relatedField is not null
                            if($relatedField != null)
                            {
                                //Get the ID of the LookupField
                                echo("Field AssociationDetails RelatedField ID: " . $relatedField->getId() . "\n");

                                //Get the Name of the LookupField
                                echo("Field AssociationDetails RelatedField Name: " . $relatedField->getName() . "\n");
                            }
                        }

                        if($field->getQuickSequenceNumber() != null)
                        {
                            //Get the QuickSequenceNumber of each Field
                            echo("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }

                        //Get the BusinesscardSupported of each Field
                        echo("Field BusinesscardSupported: "); print_r($field->getBusinesscardSupported()); echo("\n");

                        $multiModuleLookup = $field->getMultiModuleLookup();
                        //Check if multiModuleLookup is not null
						if($multiModuleLookup != null)
						{
							//Get the Name of the MultiModuleLookup
							echo("Field MultiModuleLookup Name: " . $multiModuleLookup->getName());echo("\n");
							
							//Get the Value of the MultiModuleLookup
							echo("Field MultiModuleLookup Id: " . $multiModuleLookup->getId());echo("\n");
							
							//Get the Object obtained Module instance
							$module = $multiModuleLookup->getModule();
							
							//Check if module is not null
							if($module != null)
							{
								//Get the ID of the Module
								echo("Field MultiModuleLookup Module ID: " . $module->getId());echo("\n");
								
								//Get the Name of the Module
								echo("Field MultiModuleLookup Module APIName: " . $module->getAPIName());echo("\n");
							}
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

                        //Get the CustomField of each Field
                        echo("Field CustomField: "); print_r($field->getCustomField()); echo("\n");

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

                            //Get the ModuleName of the Module
                            echo("Field Lookup ModuleName: " . $lookup->getModuleName() . "\n");
                        }

                        //Get the Filterable of each Field
                        echo("Field Filterable: " . $field->getFilterable() . "\n");

                        //Check if ConvertMapping is not null
                        if($field->getConvertMapping() != null)
                        {
                            echo("Field ConvertMapping: \n");

                            //Get the details map
                            foreach($field->getConvertMapping() as $key => $value)
                            {
                                //Get each value in the map
                                echo($key . " : " . $value . "\n");
                            }
                        }

                        //Get the Visible of each Field
                        echo("Field Visible: "); print_r($field->getVisible()); echo("\n");

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

                        //Get the Object obtained ViewType instance
                        $viewType = $field->getViewType();

                        //Check if viewType is not null
                        if($viewType != null)
                        {
                            //Get the View of the ViewType
                            echo("Field ViewType View: " . $viewType->getView() . "\n");

                            //Get the Edit of the ViewType
                            echo("Field ViewType Edit: " . $viewType->getEdit() . "\n");

                            //Get the Create of the ViewType
                            echo("Field ViewType Create: " . $viewType->getCreate() . "\n");

                            //Get the View of the ViewType
                            echo("Field ViewType QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }

                        //Get the PickListValuesSortedLexically of each Field
                        echo("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");

                        //Get the Sortable of each Field
                        echo("Field Sortable: "); print_r($field->getSortable()); echo("\n");

                        //Get the Object obtained Module instance
                        $subform = $field->getSubform();

                        //Check if subform is not null
                        if($subform != null)
                        {
                            //Get the Object obtained Layout instance
                            $layout = $subform->getLayout();

                            //Check if layout is not null
                            if($layout != null)
                            {
                                //Get the ID of the Layout
                                echo("Field Subform Layout ID: " . $layout->getId() . "\n");

                                //Get the Name of the Layout
                                echo("Field Subform Layout Name: " . $layout->getName() . "\n");
                            }

                            if($subform->getDisplayLabel() != null)
                            {
                                //Get the DisplayLabel of the Module
                                echo("Field Subform DisplayLabel: " . $subform->getDisplayLabel() . "\n");
                            }

                            //Get the APIName of the Module
                            echo("Field Subform APIName: " . $subform->getAPIName() . "\n");

                            //Get the Module of the Module
                            echo("Field Subform Module: " . $subform->getModule() . "\n");

                            if($subform->getId() != null)
                            {
                                //Get the ID of the Module
                                echo("Field Subform ID: " . $subform->getId() . "\n");
                            }
                        }

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

                            $historytracking = $field->getHistoryTracking();
							
							//Get the Module  of history tracking 
							$module =  $historytracking->getModule();
							
							if ($module != null) 
							{
								$moduleLayout = $module->getLayout();

								if ($moduleLayout != null) 
								{
									echo("Module layout id" . $moduleLayout->getId());
								}

								echo("Module layout display label" . $module->getDisplayLabel());
								
                                echo("Module layout api name" . $module->getAPIName());
								
                                echo("Module layout module" . $module->getId());
								
                                echo("Module layout id" . $module->getModule());
								
                                echo("Module layout module name" . $module->getModuleName());
							}

							//Get the duration configured field of each history tracking
							$durationConfigured = $historytracking->getDurationConfiguredField();
							
                            if($durationConfigured != null) 
							{
								echo("historytracking duration configured field" . $durationConfigured->getId());
							}
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

                        //Get the MassUpdate of each Field
                        echo("Field MassUpdate: " . $field->getMassUpdate() . "\n");

                        //Get all entries from the MultiSelectLookup instance
                        $multiSelectLookup = $field->getMultiselectlookup();

                        //Check if formula is not null
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

                            //Get the ConnectedModule of the MultiSelectLookup
                            echo("Field MultiSelectLookup ConnectedModule: " . $multiSelectLookup->getConnectedModule() . "\n");

                            //Get the ConnectedlookupApiname of the MultiSelectLookup
                            echo("Field MultiSelectLookup ConnectedlookupApiname: " . $multiSelectLookup->getConnectedlookupApiname() . "\n");

                            //Get the ID of the MultiSelectLookup
                            echo("Field MultiSelectLookup ID: " . $multiSelectLookup->getId() . "\n");
                        }

                        //Get the PickListValue of each Field
                        $pickListValues = $field->getPickListValues();

                        //Check if formula is not null
                        if($pickListValues != null)
                        {
                            foreach($pickListValues as $pickListValue)
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
                                        //Get each value from the map
                                        echo($map . "\n");
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

                        //Get the AutoNumber of each Field
                        $autoNumber = $field->getAutoNumber();

                        //Check if formula is not null
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

                    echo("Details: " );

                    //Get the details map4
                    foreach($exception->getDetails() as $key => $value)
                    {
                        //Get each value in the map
                        echo($key . ": " .$value . "\n");
                    }

                    //Get the Message
                    echo("Message: " . $exception->getMessage()->getValue() . "\n");
                }
            }
		}
	}

	/**
	 * <h3> Get Field </h3>
	 * This method is used to get metadata about a single field of a module with fieldID and print the response.
	 * @param moduleAPIName The API Name of the field's module
	 * @param fieldId The ID of the field to be obtained
	 * @throws Exception
	 */
	public static function getField(string $moduleAPIName, string $fieldId)
	{
		//example,
		//moduleAPIName = "module_api_name";
		//fieldId = "5255012";

		//Get instance of FieldsOperations Class that takes moduleAPIName as parameter
		$fieldOperations = new FieldsOperations($moduleAPIName);

		//Call getField method which takes fieldId as parameter
		$response = $fieldOperations->getField($fieldId);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

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

                //Get the list of obtained Field instances
                $fields = $responseWrapper->getFields();

                if($fields != null)
                {
                    foreach($fields as $field)
                    {
                        //Get the SystemMandatory of each Field
                        echo("Field SystemMandatory: " ); print_r($field->getSystemMandatory()); echo("\n");

                        //Get the private info of each field
                        $privateInfo = $field->getPrivate();

                        //Check if privateInfo is not null
                        if($privateInfo != null)
                        {
                            echo("Private Details\n");

                            //Get the type
                            echo("Field Private Type: " . $privateInfo->getType() . "\n");

                            //Get the Export
                            echo("Field Private Export: " . $privateInfo->getExport() . "\n");

                            //Get the Restricted
                            echo("Field Private Restricted: " . $privateInfo->getRestricted() . "\n");
                        }

                        //Get the Webhook of each Field
                        echo("Field Webhook: "); print_r($field->getWebhook()); print_r("\n");

                        //Get the JsonType of each Field
                        echo("Field JsonType: " . $field->getJsonType() . "\n");

                        //Get the obtained Crypt instance
                        $crypt = $field->getCrypt();

                        //Check if crypt is not null
                        if($crypt != null)
                        {
                            //Get the Mode of the Crypt
                            echo("Field Crypt Mode: " . $crypt->getMode() . "\n");

                            //Get the Column of the Crypt
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

                            //Get the Notify of the Crypt
                            echo("Field Crypt Notify: " . $crypt->getNotify() . "\n");

                            //Get the Table of the Crypt
                            echo("Field Crypt Table: " . $crypt->getTable() . "\n");

                            //Get the Status of the Crypt
                            echo("Field Crypt Status: " . $crypt->getStatus() . "\n");
                        }

                        //Get the FieldLabel of each Field
                        echo("Field FieldLabel: " . $field->getFieldLabel() . "\n");

                        //Get the Object obtained ToolTip instance
                        $tooltip = $field->getTooltip();

                        //Check if tooltip is not null
                        if($tooltip != null)
                        {
                            //Get the Name of the ToolTip
                            echo("Field ToolTip Name: " . $tooltip->getName() . "\n");

                            //Get the Value of the ToolTip
                            echo("Field ToolTip Value: " . $tooltip->getValue() . "\n");
                        }

                        //Get the CreatedSource of each Field
                        echo("Field CreatedSource: " . $field->getCreatedSource() . "\n");

                        //Get the Type of each Field
                        echo("Field Type: " . $field->getType() . "\n");

                        //Get the FieldReadOnly of each Field
                        echo("Field FieldReadOnly: "); print_r($field->getFieldReadOnly()); echo("\n");

                        //Get the DisplayLabel of each Field
                        echo("Field DisplayLabel: " . $field->getDisplayLabel() . "\n");

                        //Get the DisplayType of each Field
                        echo("Field DisplayType: " . $field->getDisplayType()->getValue() . "\n");

                        //Get the UIType of each Field
                        echo("Field UIType: " . $field->getUiType() . "\n");

                        //Get the ReadOnly of each Field
                        echo("Field ReadOnly: "); print_r($field->getReadOnly()); echo("\n");

                        //Get the Object obtained AssociationDetails instance
                        $associationDetails = $field->getAssociationDetails();

                        //Check if associationDetails is not null
                        if($associationDetails != null)
                        {
                            //Get the Object obtained LookupField instance
                            $lookupField = $associationDetails->getLookupField();

                            //Check if lookupField is not null
                            if($lookupField != null)
                            {
                                //Get the ID of the LookupField
                                echo("Field AssociationDetails LookupField ID: " . $lookupField->getId() . "\n");

                                //Get the Name of the LookupField
                                echo("Field AssociationDetails LookupField Name: " . $lookupField->getName() . "\n");
                            }

                            //Get the Object obtained LookupField instance
                            $relatedField = $associationDetails->getRelatedField();

                            //Check if relatedField is not null
                            if($relatedField != null)
                            {
                                //Get the ID of the LookupField
                                echo("Field AssociationDetails RelatedField ID: " . $relatedField->getId() . "\n");

                                //Get the Name of the LookupField
                                echo("Field AssociationDetails RelatedField Name: " . $relatedField->getName() . "\n");
                            }
                        }

                        if($field->getQuickSequenceNumber() != null)
                        {
                            //Get the QuickSequenceNumber of each Field
                            echo("Field QuickSequenceNumber: " . $field->getQuickSequenceNumber() . "\n");
                        }

                        //Get the BusinesscardSupported of each Field
                        echo("Field BusinesscardSupported: "); print_r($field->getBusinesscardSupported()); echo("\n");

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

                        //Get the CustomField of each Field
                        echo("Field CustomField: "); print_r($field->getCustomField()); echo("\n");

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

                            //Get the ModuleName of the Module
                            echo("Field Lookup ModuleName: " . $lookup->getModuleName() . "\n");
                        }

                        //Get the Filterable of each Field
                        echo("Field Filterable: " . $field->getFilterable() . "\n");

                        //Check if ConvertMapping is not null
                        if($field->getConvertMapping() != null)
                        {
                            echo("Field ConvertMapping: \n");

                            //Get the details map
                            foreach($field->getConvertMapping() as $key => $value)
                            {
                                //Get each value in the map
                                echo($key . " : " . $value . "\n");
                            }
                        }

                        //Get the Visible of each Field
                        echo("Field Visible: "); print_r($field->getVisible()); echo("\n");

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

                        //Get the Object obtained ViewType instance
                        $viewType = $field->getViewType();

                        //Check if viewType is not null
                        if($viewType != null)
                        {
                            //Get the View of the ViewType
                            echo("Field ViewType View: " . $viewType->getView() . "\n");

                            //Get the Edit of the ViewType
                            echo("Field ViewType Edit: " . $viewType->getEdit() . "\n");

                            //Get the Create of the ViewType
                            echo("Field ViewType Create: " . $viewType->getCreate() . "\n");

                            //Get the View of the ViewType
                            echo("Field ViewType QuickCreate: " . $viewType->getQuickCreate() . "\n");
                        }

                        if($field->getDisplayField() != null) 
						{
							//check if field is DisplayField
							echo("Field DisplayField " . $field->getDisplayField());
						}

                        //Get the PickListValuesSortedLexically of each Field
                        echo("Field PickListValuesSortedLexically: " . $field->getPickListValuesSortedLexically() . "\n");

                        //Get the Sortable of each Field
                        echo("Field Sortable: "); print_r($field->getSortable()); echo("\n");

                        //Get the Object obtained Module instance
                        $subform = $field->getSubform();

                        //Check if subform is not null
                        if($subform != null)
                        {
                            //Get the Object obtained Layout instance
                            $layout = $subform->getLayout();

                            //Check if layout is not null
                            if($layout != null)
                            {
                                //Get the ID of the Layout
                                echo("Field Subform Layout ID: " . $layout->getId() . "\n");

                                //Get the Name of the Layout
                                echo("Field Subform Layout Name: " . $layout->getName() . "\n");
                            }

                            if($subform->getDisplayLabel() != null)
                            {
                                //Get the DisplayLabel of the Module
                                echo("Field Subform DisplayLabel: " . $subform->getDisplayLabel() . "\n");
                            }

                            //Get the APIName of the Module
                            echo("Field Subform APIName: " . $subform->getAPIName() . "\n");

                            //Get the Module of the Module
                            echo("Field Subform Module: " . $subform->getModule() . "\n");

                            if($subform->getId() != null)
                            {
                                //Get the ID of the Module
                                echo("Field Subform ID: " . $subform->getId() . "\n");
                            }
                        }

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

                        //Get the MassUpdate of each Field
                        echo("Field MassUpdate: " . $field->getMassUpdate() . "\n");

                        if($field->getBlueprintSupported() != null)
                        {
                            //Get the BlueprintSupported of each Field
                            echo("Field BlueprintSupported: " . $field->getBlueprintSupported());
                        }

                        //Get all entries from the MultiSelectLookup instance
                        $multiSelectLookup = $field->getMultiselectlookup();

                        //Check if formula is not null
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

                            //Get the ConnectedModule of the MultiSelectLookup
                            echo("Field MultiSelectLookup ConnectedModule: " . $multiSelectLookup->getConnectedModule() . "\n");

                            //Get the ConnectedlookupApiname of the MultiSelectLookup
                            echo("Field MultiSelectLookup ConnectedlookupApiname: " . $multiSelectLookup->getConnectedlookupApiname() . "\n");

                            //Get the ID of the MultiSelectLookup
                            echo("Field MultiSelectLookup ID: " . $multiSelectLookup->getId() . "\n");
                        }

                        //Get the PickListValue of each Field
                        $pickListValues = $field->getPickListValues();

                        //Check if formula is not null
                        if($pickListValues != null)
                        {
                            foreach($pickListValues as $pickListValue)
                            {
                                self::printPickListValue($pickListValue);
                            }
                        }

                        //Get the AutoNumber of each Field
                        $autoNumber = $field->getAutoNumber();

                        //Check if formula is not null
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

                        if($field->getDefaultValue() != null)
						{
							//Get the DefaultValue of each Field
							echo("Field DefaultValue: " . $field->getDefaultValue());
						}
						
						//Check if ValidationRule is not null
						if($field->getValidationRule() != null)
						{
							//Get the details map
							foreach($field->getValidationRule() as $key => $value )
							{
								//Get each value in the map
								echo($key . ": " . $value);
							}
						}

                        //get multi user lookup for field
						if ($field->getMultiuserlookup() != null) 
						{
							$multiuserlookup = $field->getMultiuserlookup();
							
							//get displaylabel of multiuser lookup
							echo("Get multiuserlookup display label" . $multiuserlookup->getDisplayLabel());
							
							//get linking module of multiuser lookup
							echo("Get multiuserlookup linking module" . $multiuserlookup->getLinkingModule());
							
							//get lookup apiname of multiuser lookup
							echo("Get multiuserlookup lookup api_name" . $multiuserlookup->getLookupApiname());
							
							//get apiname of multiuser lookup
							echo("Get multiuserlookup api name" . $multiuserlookup->getAPIName());
							
							//get id of multiuser lookup
							echo("Get multiuserlookup id" . $multiuserlookup->getId());
							
							//get connected module of multiuser lookup
							echo("Get multiuserlookup connected module" . $multiuserlookup->getConnectedModule());
							
							//get connected lookup apiname of multiuser lookup
							echo("Get multiuserlookup connected lookup api name" . $multiuserlookup->getConnectedlookupApiname());
						}
                    }
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

                echo("Details: " );

                //Get the details map4
                foreach($exception->getDetails() as $key => $value)
                {
                    //Get each value in the map
                    echo($key . ": " .$value . "\n");
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

        //Get the Probability of each PickListValues
		echo(" Fields PickListValue Probability: " . $pickListValue->getProbability());
		
		//Get the ForecastCategory of each PickListValues
		echo(" Fields PickListValue ForecastCategory: " . $pickListValue->getForecastCategory());

        if($pickListValue->getSequenceNumber() != null)
        {
            //Get the SequenceNumber of each PickListValues
            echo(" Field PickListValue SequenceNumber: " . $pickListValue->getSequenceNumber() . "\n");
        }

        //Get the ExpectedDataType of each PickListValues
        echo("Field PickListValue ExpectedDataType: " . $pickListValue->getExpectedDataType() . "\n");

        //Get the ForecastType of each PickListValues
        echo(" Fields PickListValue ForecastType: " . $pickListValue->getForecastType());

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
