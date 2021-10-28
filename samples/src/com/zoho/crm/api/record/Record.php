<?php
namespace com\zoho\crm\sample\record;

use com\zoho\crm\api\HeaderMap;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\layouts\Layout;

use com\zoho\crm\api\record\APIException;

use com\zoho\crm\api\record\ActionWrapper;

use com\zoho\crm\api\record\BodyWrapper;

use com\zoho\crm\api\record\ConvertActionWrapper;

use com\zoho\crm\api\record\ConvertBodyWrapper;

use com\zoho\crm\api\record\DeletedRecordsWrapper;

use com\zoho\crm\api\record\FileBodyWrapper;

use com\zoho\crm\api\record\FileDetails;

use com\zoho\crm\api\record\LeadConverter;

use com\zoho\crm\api\record\LineItemProduct;

use com\zoho\crm\api\record\LineTax;

use com\zoho\crm\api\record\MassUpdate;

use com\zoho\crm\api\record\MassUpdateActionWrapper;

use com\zoho\crm\api\record\MassUpdateBodyWrapper;

use com\zoho\crm\api\record\MassUpdateResponseWrapper;

use com\zoho\crm\api\record\MassUpdateSuccessResponse;

use com\zoho\crm\api\record\Participants;

use com\zoho\crm\api\record\PricingDetails;

use com\zoho\crm\api\record\RecordOperations;

use com\zoho\crm\api\record\RecurringActivity;

use com\zoho\crm\api\record\RemindAt;

use com\zoho\crm\api\record\ResponseWrapper;

use com\zoho\crm\api\record\SuccessResponse;

use com\zoho\crm\api\record\SuccessfulConvert;

use com\zoho\crm\api\tags\Tag;

use com\zoho\crm\api\record\DeleteRecordParam;

use com\zoho\crm\api\record\DeleteRecordsParam;

use com\zoho\crm\api\record\GetDeletedRecordsHeader;

use com\zoho\crm\api\record\GetDeletedRecordsParam;

use com\zoho\crm\api\record\GetMassUpdateStatusParam;

use com\zoho\crm\api\record\GetRecordHeader;

use com\zoho\crm\api\record\GetRecordParam;

use com\zoho\crm\api\record\GetRecordsHeader;

use com\zoho\crm\api\record\GetRecordsParam;

use com\zoho\crm\api\record\SearchRecordsParam;

use com\zoho\crm\api\record\{Cases, Field, Solutions, Accounts, Campaigns, Calls, Leads, Tasks, Deals, Sales_Orders, Contacts, Quotes, Events, Price_Books, Purchase_Orders, Vendors};

use com\zoho\crm\api\util\Choice;

use com\zoho\crm\api\util\StreamWrapper;

use com\zoho\crm\api\record\Territory;

use com\zoho\crm\api\record\Comment;

use com\zoho\crm\api\record\Consent;

use com\zoho\crm\api\attachments\Attachment;

use com\zoho\crm\api\record\CarryOverTags;

use com\zoho\crm\api\record\ImageUpload;

use com\zoho\crm\api\record\Tax;

use com\zoho\crm\api\users\User;

use com\zoho\crm\api\record\UpdateRecordHeader;

use com\zoho\crm\api\record\DeleteRecordHeader;

use com\zoho\crm\api\record\CreateRecordsHeader;

use com\zoho\crm\api\record\UpsertRecordsHeader;

use com\zoho\crm\api\record\RecordCountParam;

use com\zoho\crm\api\record\CountWrapper;

class Record
{
	/**
	 * <h3> Get Record</h3>
	 * This method is used to get a single record of a module with ID and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param recordId - The ID of the record to be obtained.
	 * @param destinationFolder - The absolute path of the destination folder to store the attachment
	 * @throws Exception
	 */
	public static function getRecord(string $moduleAPIName, string $recordId, string $destinationFolder)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "347002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(GetRecordParam::approved(), "false");

		$paramInstance->add(GetRecordParam::converted(), "false");

		$fieldNames = array("Deal_Name", "Company");

		foreach($fieldNames as $fieldName)
		{
			$paramInstance->add(GetRecordParam::fields(), $fieldName);
        }

		$startdatetime = date_create("2020-06-27T15:10:00");

        $paramInstance->add(GetRecordParam::startDateTime(), $startdatetime);

		$enddatetime = date_create("2020-06-29T15:10:00");

		$paramInstance->add(GetRecordParam::endDateTime(), $enddatetime);

		$paramInstance->add(GetRecordParam::territoryId(), "34770613051357");

        $paramInstance->add(GetRecordParam::includeChild(), "true");

		$headerInstance = new HeaderMap();

		$ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		$headerInstance->add(GetRecordHeader::IfModifiedSince(), $ifmodifiedsince);

		// $headerInstance->add(GetRecordHeader::XEXTERNAL(), "Leads.External");

		//Call getRecord method that takes paramInstance, moduleAPIName and recordID as parameter
		$response = $recordOperations->getRecord( $recordId,$moduleAPIName,$paramInstance, $headerInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$responseHandler = $response->getObject();

				if($responseHandler instanceof ResponseWrapper)
				{
					//Get the received ResponseWrapper instance
					$responseWrapper = $responseHandler;

					//Get the list of obtained Record instances
					$records = $responseWrapper->getData();

					if($records != null)
					{
						$recordClass = 'com\zoho\crm\api\record\Record';

						foreach($records as $record)
						{
							//Get the ID of each Record
							echo("Record ID: " . $record->getId() . "\n");

							//Get the createdBy User instance of each Record
							$createdBy = $record->getCreatedBy();

							//Check if createdBy is not null
							if($createdBy != null)
							{
								//Get the ID of the createdBy User
								echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

								//Get the name of the createdBy User
								echo("Record Created By User-Name: " . $createdBy->getName() . "\n");

								//Get the Email of the createdBy User
								echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
							}

							//Get the CreatedTime of each Record
							echo("Record CreatedTime: "); print_r($record->getCreatedTime()); echo("\n");

							//Get the modifiedBy User instance of each Record
							$modifiedBy = $record->getModifiedBy();

							//Check if modifiedBy is not null
							if($modifiedBy != null)
							{
								//Get the ID of the modifiedBy User
								echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

								//Get the name of the modifiedBy User
								echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");

								//Get the Email of the modifiedBy User
								echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
							}

							//Get the ModifiedTime of each Record
							echo("Record ModifiedTime: "); print_r($record->getModifiedTime()); echo("\n");

							//Get the list of Tag instance each Record
							$tags = $record->getTag();

							//Check if tags is not null
							if($tags != null)
							{
								foreach($tags as $tag)
								{
									//Get the Name of each Tag
									echo("Record Tag Name: " . $tag->getName() . "\n");

									//Get the Id of each Tag
									echo("Record Tag ID: " . $tag->getId() . "\n");
								}
							}

							//To get particular field value
							echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName

							echo("Record KeyValues : \n" );

							//Get the KeyValue map
							foreach($record->getKeyValues() as $keyName => $value)
							{
								if($value != null)
								{
									if((is_array($value) && sizeof($value) > 0) && isset($value[0]))
									{
										if($value[0] instanceof FileDetails)
										{
											$fileDetails = $value;

											foreach($fileDetails as $fileDetail)
											{
												//Get the Extn of each FileDetails
												echo("Record FileDetails Extn: " . $fileDetail->getExtn() . "\n");

												//Get the IsPreviewAvailable of each FileDetails
												echo("Record FileDetails IsPreviewAvailable: " . $fileDetail->getIsPreviewAvailable() . "\n");

												//Get the DownloadUrl of each FileDetails
												echo("Record FileDetails DownloadUrl: " . $fileDetail->getDownloadUrl() . "\n");

												//Get the DeleteUrl of each FileDetails
												echo("Record FileDetails DeleteUrl: " . $fileDetail->getDeleteUrl() . "\n");

												//Get the EntityId of each FileDetails
												echo("Record FileDetails EntityId: " . $fileDetail->getEntityId() . "\n");

												//Get the Mode of each FileDetails
												echo("Record FileDetails Mode: " . $fileDetail->getMode() . "\n");

												//Get the OriginalSizeByte of each FileDetails
												echo("Record FileDetails OriginalSizeByte: " . $fileDetail->getOriginalSizeByte() . "\n");

												//Get the PreviewUrl of each FileDetails
												echo("Record FileDetails PreviewUrl: " . $fileDetail->getPreviewUrl() . "\n");

												//Get the FileName of each FileDetails
												echo("Record FileDetails FileName: " . $fileDetail->getFileName() . "\n");

												//Get the FileId of each FileDetails
												echo("Record FileDetails FileId: " . $fileDetail->getFileId() . "\n");

												//Get the AttachmentId of each FileDetails
												echo("Record FileDetails AttachmentId: " . $fileDetail->getAttachmentId() . "\n");

												//Get the FileSize of each FileDetails
												echo("Record FileDetails FileSize: " . $fileDetail->getFileSize() . "\n");

												//Get the CreatorId of each FileDetails
												echo("Record FileDetails CreatorId: " . $fileDetail->getCreatorId() . "\n");

												//Get the LinkDocs of each FileDetails
												echo("Record FileDetails LinkDocs: " . $fileDetail->getLinkDocs() . "\n");
											}
										}
										else if($value[0] instanceof Choice)
										{
											$choice = $value;

											foreach($choice as $choiceValue)
											{
												echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
											}
										}
										else if($value[0] instanceof Tag)
										{
											$tagList = $value;

											foreach($tagList as $tag)
											{
												//Get the Name of each Tag
												echo("Record Tag Name: " . $tag->getName() . "\n");

												//Get the Id of each Tag
												echo("Record Tag ID: " . $tag->getId() . "\n");
											}
										}
										else if($value[0] instanceof PricingDetails)
										{
											$pricingDetails = $value;

											foreach($pricingDetails as $pricingDetail)
											{
												echo("Record PricingDetails ToRange: " . $pricingDetail->getToRange(). "\n");

												echo("Record PricingDetails Discount: " . $pricingDetail->getDiscount(). "\n");

												echo("Record PricingDetails ID: " . $pricingDetail->getId() . "\n");

												echo("Record PricingDetails FromRange: " . $pricingDetail->getFromRange(). "\n");
											}
										}
										else if($value[0] instanceof Participants)
										{
											$participants = $value;

											foreach($participants as $participant)
											{
												echo("RelatedRecord Participants Name: " . $participant->getName() . "\n");

												echo("RelatedRecord Participants Invited: " . $participant->getInvited() . "\n");

												echo("RelatedRecord Participants ID: " . $participant->getId() . "\n");

												echo("RelatedRecord Participants Type: " . $participant->getType() . "\n");

												echo("RelatedRecord Participants Participant: " . $participant->getParticipant() . "\n");

												echo("RelatedRecord Participants Status: " . $participant->getStatus() . "\n");
											}
										}
										else if($value[0] instanceof $recordClass)
										{
											$recordList = $value;

											foreach($recordList as $record1)
											{
												//Get the details map
												foreach($record1->getKeyValues() as $key => $value1)
												{
													//Get each value in the map
													echo($key . " : " ); print_r($value1); echo("\n");
												}
											}
										}
										else if($value[0] instanceof LineTax)
										{
											$lineTaxes = $value;

											foreach($lineTaxes as $lineTax)
											{
												echo("Record ProductDetails LineTax Percentage: " . $lineTax->getPercentage(). "\n");

												echo("Record ProductDetails LineTax Name: " . $lineTax->getName() . "\n");

												echo("Record ProductDetails LineTax Id: " . $lineTax->getId() . "\n");

												echo("Record ProductDetails LineTax Value: " . $lineTax->getValue(). "\n");
											}
										}
										else if($value[0] instanceof Comment)
										{
											$comments = $value;

											foreach($comments as $comment)
											{
												echo("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");

												echo("Record Comment CommentedTime: "); print_r($comment->getCommentedTime()); echo("\n");

												echo("Record Comment CommentContent: " . $comment->getCommentContent(). "\n");

												echo("Record Comment Id: " . $comment->getId() . "\n");
											}
										}
										else if($value[0] instanceof Attachment)
										{
											$attachments = $value;

											foreach ($attachments as $attachment)
											{
												//Get the owner User instance of each attachment
												$owner = $attachment->getOwner();

												//Check if owner is not null
												if($owner != null)
												{
													//Get the Name of the Owner
													echo("Record Attachment Owner User-Name: " . $owner->getName() . "\n");

													//Get the ID of the Owner
													echo("Record Attachment Owner User-ID: " . $owner->getId() . "\n");

													//Get the Email of the Owner
													echo("Record Attachment Owner User-Email: " . $owner->getEmail() . "\n");
												}

												//Get the modified time of each attachment
												echo("Record Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");

												//Get the name of the File
												echo("Record Attachment File Name: " . $attachment->getFileName() . "\n");

												//Get the created time of each attachment
												echo("Record Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");

												//Get the Attachment file size
												echo("Record Attachment File Size: " . $attachment->getSize() . "\n");

												//Get the parentId Record instance of each attachment
												$parentId = $attachment->getParentId();

												//Check if parentId is not null
												if($parentId != null)
												{
													//Get the parent record Name of each attachment
													echo("Record Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");

													//Get the parent record ID of each attachment
													echo("Record Attachment parent record ID: " . $parentId->getId() . "\n");
												}

												//Get the attachment is Editable
												echo("Record Attachment is Editable: " . $attachment->getEditable() . "\n");

												//Get the file ID of each attachment
												echo("Record Attachment File ID: " . $attachment->getFileId() . "\n");

												//Get the type of each attachment
												echo("Record Attachment File Type: " . $attachment->getType() . "\n");

												//Get the seModule of each attachment
												echo("Record Attachment seModule: " . $attachment->getSeModule() . "\n");

												//Get the modifiedBy User instance of each attachment
												$modifiedBy = $attachment->getModifiedBy();

												//Check if modifiedBy is not null
												if($modifiedBy != null)
												{
													//Get the Name of the modifiedBy User
													echo("Record Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");

													//Get the ID of the modifiedBy User
													echo("Record Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");

													//Get the Email of the modifiedBy User
													echo("Record Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
												}

												//Get the state of each attachment
												echo("Record Attachment State: " . $attachment->getState() . "\n");

												//Get the ID of each attachment
												echo("Record Attachment ID: " . $attachment->getId() . "\n");

												//Get the createdBy User instance of each attachment
												$createdBy = $attachment->getCreatedBy();

												//Check if createdBy is not null
												if($createdBy != null)
												{
													//Get the name of the createdBy User
													echo("Record Attachment Created By User-Name: " . $createdBy->getName() . "\n");

													//Get the ID of the createdBy User
													echo("Record Attachment Created By User-ID: " . $createdBy->getId() . "\n");

													//Get the Email of the createdBy User
													echo("Record Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
												}

												//Get the linkUrl of each attachment
												echo("Record Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
											}
										}
										else if($value[0] instanceof ImageUpload)
										{
											$imageUploads = $value;

											foreach($imageUploads as $imageUpload)
											{
												echo("Record " . $keyName . " Description: " . $imageUpload->getDescription() . "\n");

												echo("Record " . $keyName . " PreviewId: " . $imageUpload->getPreviewId() . "\n");

												echo("Record " . $keyName . " File_Name: " . $imageUpload->getFileName() . "\n");

												echo("Record " . $keyName . " State: "); print_r($imageUpload->getState()); echo("\n");

												echo("Record " . $keyName . " Size: " . $imageUpload->getSize() . "\n");

												echo("Record " . $keyName . " SequenceNumber: " . $imageUpload->getSequenceNumber() . "\n");

												echo("Record " . $keyName . " Id: " . $imageUpload->getId() . "\n");

												echo("Record " . $keyName . " FileId: " . $imageUpload->getFileId() . "\n");
											}
										}
										else
										{
											echo($keyName . " : "); print_r($value); echo("\n");
										}
									}
									else if($value instanceof Layout)
									{
										$layout = $value;

										if($layout != null)
										{
											echo("Record " . $keyName. " ID: " . $layout->getId() . "\n");

											echo("Record " . $keyName . " Name: " . $layout->getName() . "\n");
										}
									}
									else if($value instanceof User)
									{
										$user = $value;

										if($user != null)
										{
											echo("Record " . $keyName . " User-ID: " . $user->getId() . "\n");

											echo("Record " . $keyName . " User-Name: " . $user->getName() . "\n");

											echo("Record " . $keyName . " User-Email: " . $user->getEmail() . "\n");
										}
									}
									else if($value instanceof $recordClass)
									{
										$recordValue = $value;

										echo("Record " . $keyName . " ID: " . $recordValue->getId() . "\n");

										echo("Record " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
									}
									else if($value instanceof Choice)
									{
										$choiceValue = $value;

										echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
									}
									else if($value instanceof RemindAt)
									{
										echo($keyName . ": " . $value->getAlarm() . "\n");
									}
									else if($value instanceof RecurringActivity)
									{
										echo($keyName . " : RRULE" . ": " . $value->getRrule() . "\n");
									}
									else if($value instanceof Consent)
									{
										$consent = $value;

										echo("Record Consent ID: " . $consent->getId());

										//Get the Owner User instance of each attachment
										$owner = $consent->getOwner();

										//Check if owner is not null
										if($owner != null)
										{
											//Get the name of the owner User
											echo("Record Consent Owner Name: " . $owner->getName());

											//Get the ID of the owner User
											echo("Record Consent Owner ID: " . $owner->getId());

											//Get the Email of the owner User
											echo("Record Consent Owner Email: " . $owner->getEmail());
										}

										$consentCreatedBy = $consent->getCreatedBy();

										//Check if createdBy is not null
										if($consentCreatedBy != null)
										{
											//Get the name of the CreatedBy User
											echo("Record Consent CreatedBy Name: " . $consentCreatedBy->getName());

											//Get the ID of the CreatedBy User
											echo("Record Consent CreatedBy ID: " . $consentCreatedBy->getId());

											//Get the Email of the CreatedBy User
											echo("Record Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
										}

										$consentModifiedBy = $consent->getModifiedBy();

										//Check if createdBy is not null
										if($consentModifiedBy != null)
										{
											//Get the name of the ModifiedBy User
											echo("Record Consent ModifiedBy Name: " . $consentModifiedBy->getName());

											//Get the ID of the ModifiedBy User
											echo("Record Consent ModifiedBy ID: " . $consentModifiedBy->getId());

											//Get the Email of the ModifiedBy User
											echo("Record Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
										}

										echo("Record Consent CreatedTime: " . $consent->getCreatedTime());

										echo("Record Consent ModifiedTime: " . $consent->getModifiedTime());

										echo("Record Consent ContactThroughEmail: " . $consent->getContactThroughEmail());

										echo("Record Consent ContactThroughSocial: " . $consent->getContactThroughSocial());

										echo("Record Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());

										echo("Record Consent ContactThroughPhone: " . $consent->getContactThroughPhone());

										echo("Record Consent MailSentTime: " . $consent->getMailSentTime());

										echo("Record Consent ConsentDate: " . $consent->getConsentDate());

										echo("Record Consent ConsentRemarks: " . $consent->getConsentRemarks());

										echo("Record Consent ConsentThrough: " . $consent->getConsentThrough());

										echo("Record Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());

										//To get custom values
										echo("Record Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
									}
									else
									{
										//Get each value in the map
										echo($keyName . " : "); print_r($value); echo("\n");
									}
								}
								else
								{
									//Get each value in the map
									echo($keyName . " : "); print_r($value); echo("\n");
								}
							}
						}
					}
				}
				else if($responseHandler instanceof FileBodyWrapper)
				{
					//Get object from response
					$fileBodyWrapper = $responseHandler;

					//Get StreamWrapper instance from the returned FileBodyWrapper instance
					$streamWrapper = $fileBodyWrapper->getFile();

					//Create a file instance with the absolute_file_path
					$fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");

					//Get stream from the response
					$stream = $streamWrapper->getStream();

					fputs($fp, $stream);

					fclose($fp);
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

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Update Record</h3>
	 * This method is used to update a single record of a module with ID and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param recordId - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function updateRecord(string $moduleAPIName, string $recordId)
	{
		//API Name of the module to update record
		//$moduleAPIName = "module_api_name";
		//$recordId = "3477002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();

		//List of Record instances
        $records = array();

        $recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		*/

		$record1->addFieldValue(Leads::City(), "City");

		$record1->addFieldValue(Leads::LastName(), "Last Name");

		$record1->addFieldValue(Leads::FirstName(), "First Name");

		$record1->addFieldValue(Leads::Company(), "KKRNP");

		// $accounts = new $recordClass();

		// $accounts->addKeyValue("id", "34770615848009");

		// $record1->addFieldValue(Contacts::AccountName(), $accounts);

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");

		$record1->addKeyValue("Custom_field_2", "value");

		$record1->addKeyValue("Date_1", new \DateTime('2020-03-08'));

		$record1->addKeyValue("Date_Time_2", date_create("2021-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));

		$fileDetails = array();

		$fileDetail1 = new FileDetails();

		$fileDetail1->setAttachmentId("347005");

		$fileDetail1->setDelete("null");

		array_push($fileDetails, $fileDetail1);

		$fileDetail2 = new FileDetails();

		$fileDetail2->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c32244f4e660f3702f05463e2fd0a2d8c1c");

		array_push($fileDetails, $fileDetail2);

		$fileDetail3 = new FileDetails();

		$fileDetail3->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c326a3f4c7562925ac9afc0f7433dd2098c");

		array_push($fileDetails, $fileDetail3);

		$record1->addKeyValue("File_Upload", $fileDetails);

		$recordOwner = new User();
        
		$recordOwner->setEmail("abc@zoho.com");
        
        $record1->addKeyValue("Owner", $recordOwner);

		//Used when GDPR is enabled
		$dataConsent = new Consent();
		
		$dataConsent->setConsentRemarks("Approved.");
		
		$dataConsent->setConsentThrough("Email");
		
		$dataConsent->setContactThroughEmail(true);
		
		$dataConsent->setContactThroughSocial(false);
		
		$record1->addKeyValue("Data_Processing_Basis_Details", $dataConsent);
		
		$subformList = [];
		
		$subform = new $recordClass();
		
		$subform->addKeyValue("Subform FieldAPIName", "FieldValue");
		
		array_push($subformList, $subform);
		
		$record1->addKeyValue("Subform Name", $subformList);
		
		/** Following methods are being used only by Inventory modules */
		
		$dealName = new $recordClass();
		
		$dealName->addFieldValue(Deals::id(), "3477061012112003");
		
		$record1->addFieldValue(Sales_Orders::DealName(), $dealName);
		
		$contactName = new $recordClass();
		
		$contactName->addFieldValue(Contacts::id(), "3477061011853001");
		
		$record1->addFieldValue(Purchase_Orders::ContactName(), $contactName);
		
		$accountName = new $recordClass();
		
		$accountName->addKeyValue("name", "automatedAccount");
		
		$record1->addFieldValue(Quotes::AccountName(), $accountName);

		$record1->addKeyValue("Discount", 10.5);
		
		$inventoryLineItemList = [];
		
		$inventoryLineItem = new $recordClass();
		
		$lineItemProduct = new LineItemProduct();
		
		$lineItemProduct->setId("3477061012107031");

		// $lineItemProduct->addKeyValue("Products_External", "AutomatedSDKExternal");
		
		$inventoryLineItem->addKeyValue("Description", "asd");
		
		$inventoryLineItem->addKeyValue("Discount", "5");
		
		$parentId = new $recordClass();
		
		$parentId->setId("35240337331017");

//		inventoryLineItem->addKeyValue("Parent_Id", 5);
		
		$inventoryLineItem->addKeyValue("Sequence_Number", "1");
		
		$lineitemProduct = new LineItemProduct();
		
		$lineitemProduct->setId("35240333659082");
		
		$inventoryLineItem->addKeyValue("Product_Name", $lineItemProduct);
		
		$inventoryLineItem->addKeyValue("Sequence_Number", "1");
		
		$inventoryLineItem->addKeyValue("Quantity",123.2);
		
		$inventoryLineItem->addKeyValue("Tax",123.2);
		
		array_push($inventoryLineItemList, $inventoryLineItem);
		
		$productLineTaxes = [];
		
		$productLineTax = new LineTax();
		
		$productLineTax->setName("MyT2ax1134");
		
		$productLineTax->setPercentage(20.0);
		
		array_push($productLineTaxes, $productLineTax);
		
		$inventoryLineItem->addKeyValue("Line_Tax", $productLineTaxes);
		
		array_push($inventoryLineItemList, $inventoryLineItem);

		$record1->addKeyValue("Quoted_Items", $inventoryLineItemList);
		
		$lineTaxes = [];
		
		$lineTax = new LineTax();
		
		$lineTax->setName("MyT2ax1134");
		
		$lineTax->setPercentage(20.0);
		
		array_push($lineTaxes, $lineTax);
		
		$record1->addKeyValue('$line_tax', $lineTaxes);
		
		/** End Inventory **/
		
		$tagList = [];
		
		$tag = new Tag();
		
		$tag->setName("Testtask1");
		
		array_push($tagList, $tag);
		
		$record1->setTag($tagList);

		//Add Record instance to the list
		array_push($records, $record1);

		//Set the list to Records in BodyWrapper instance
		$request->setData($records);

		$trigger = array("approval", "workflow", "blueprint");

		$request->setTrigger($trigger);

		$headerInstance = new HeaderMap();

		// $headerInstance->add(UpdateRecordHeader::XEXTERNAL(), "Quotes.Quoted_Items.Product_Name.Products_External");

		//Call updateRecord method that takes BodyWrapper instance, ModuleAPIName and recordId as parameter.
		$response = $recordOperations->updateRecord($recordId, $moduleAPIName, $request, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Delete Record</h3>
	 * This method is used to delete a single record of a module with ID and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param recordId - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function deleteRecord(string $moduleAPIName, string $recordId)
	{
		//API Name of the module to delete record
		//$moduleAPIName = "module_api_name";
		//$recordId = "3477002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(DeleteRecordParam::wfTrigger(), false);

		$headerInstance = new HeaderMap();

		$headerInstance->add(DeleteRecordHeader::XEXTERNAL(), "Leads.External");

		//Call deleteRecord method that takes paramInstance, ModuleAPIName and recordId as parameter.
		$response = $recordOperations->deleteRecord($recordId,$moduleAPIName, $paramInstance, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ActionWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Get Record Using External Id</h3>
	 * This method is used to get a single record using external id and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param externalFieldValue - 
	 * @param destinationFolder - The absolute path of the destination folder to store the attachment
	 * @throws Exception
	 */
	public static function getRecordUsingExternalId(string $moduleAPIName, string $externalFieldValue, string $destinationFolder)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "347002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(GetRecordParam::approved(), "false");

		$paramInstance->add(GetRecordParam::converted(), "false");

		$fieldNames = array("Deal_Name", "Company");

		foreach($fieldNames as $fieldName)
		{
			$paramInstance->add(GetRecordParam::fields(), $fieldName);
        }

		$startdatetime = date_create("2020-06-27T15:10:00");

        $paramInstance->add(GetRecordParam::startDateTime(), $startdatetime);

		$enddatetime = date_create("2020-06-29T15:10:00");

		$paramInstance->add(GetRecordParam::endDateTime(), $enddatetime);

		$paramInstance->add(GetRecordParam::territoryId(), "34770613051357");

        $paramInstance->add(GetRecordParam::includeChild(), "true");

		$headerInstance = new HeaderMap();

		$ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		// $headerInstance->add(GetRecordHeader::IfModifiedSince(), $ifmodifiedsince);

		$headerInstance->add(GetRecordHeader::XEXTERNAL(), "Leads.External");

		//Call getRecordUsingExternalId method that takes externalFieldValue, moduleAPIName, paramInstance and headerInstance as parameter
		$response = $recordOperations->getRecordUsingExternalId($externalFieldValue, $moduleAPIName, $paramInstance, $headerInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$responseHandler = $response->getObject();

				if($responseHandler instanceof ResponseWrapper)
				{
					//Get the received ResponseWrapper instance
					$responseWrapper = $responseHandler;

					//Get the list of obtained Record instances
					$records = $responseWrapper->getData();

					if($records != null)
					{
						$recordClass = 'com\zoho\crm\api\record\Record';

						foreach($records as $record)
						{
							//Get the ID of each Record
							echo("Record ID: " . $record->getId() . "\n");

							//Get the createdBy User instance of each Record
							$createdBy = $record->getCreatedBy();

							//Check if createdBy is not null
							if($createdBy != null)
							{
								//Get the ID of the createdBy User
								echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

								//Get the name of the createdBy User
								echo("Record Created By User-Name: " . $createdBy->getName() . "\n");

								//Get the Email of the createdBy User
								echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
							}

							//Get the CreatedTime of each Record
							echo("Record CreatedTime: "); print_r($record->getCreatedTime()); echo("\n");

							//Get the modifiedBy User instance of each Record
							$modifiedBy = $record->getModifiedBy();

							//Check if modifiedBy is not null
							if($modifiedBy != null)
							{
								//Get the ID of the modifiedBy User
								echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

								//Get the name of the modifiedBy User
								echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");

								//Get the Email of the modifiedBy User
								echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
							}

							//Get the ModifiedTime of each Record
							echo("Record ModifiedTime: "); print_r($record->getModifiedTime()); echo("\n");

							//Get the list of Tag instance each Record
							$tags = $record->getTag();

							//Check if tags is not null
							if($tags != null)
							{
								foreach($tags as $tag)
								{
									//Get the Name of each Tag
									echo("Record Tag Name: " . $tag->getName() . "\n");

									//Get the Id of each Tag
									echo("Record Tag ID: " . $tag->getId() . "\n");
								}
							}

							//To get particular field value
							echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName

							echo("Record KeyValues : \n" );

							//Get the KeyValue map
							foreach($record->getKeyValues() as $keyName => $value)
							{
								if($value != null)
								{
									if((is_array($value) && sizeof($value) > 0) && isset($value[0]))
									{
										if($value[0] instanceof FileDetails)
										{
											$fileDetails = $value;

											foreach($fileDetails as $fileDetail)
											{
												//Get the Extn of each FileDetails
												echo("Record FileDetails Extn: " . $fileDetail->getExtn() . "\n");

												//Get the IsPreviewAvailable of each FileDetails
												echo("Record FileDetails IsPreviewAvailable: " . $fileDetail->getIsPreviewAvailable() . "\n");

												//Get the DownloadUrl of each FileDetails
												echo("Record FileDetails DownloadUrl: " . $fileDetail->getDownloadUrl() . "\n");

												//Get the DeleteUrl of each FileDetails
												echo("Record FileDetails DeleteUrl: " . $fileDetail->getDeleteUrl() . "\n");

												//Get the EntityId of each FileDetails
												echo("Record FileDetails EntityId: " . $fileDetail->getEntityId() . "\n");

												//Get the Mode of each FileDetails
												echo("Record FileDetails Mode: " . $fileDetail->getMode() . "\n");

												//Get the OriginalSizeByte of each FileDetails
												echo("Record FileDetails OriginalSizeByte: " . $fileDetail->getOriginalSizeByte() . "\n");

												//Get the PreviewUrl of each FileDetails
												echo("Record FileDetails PreviewUrl: " . $fileDetail->getPreviewUrl() . "\n");

												//Get the FileName of each FileDetails
												echo("Record FileDetails FileName: " . $fileDetail->getFileName() . "\n");

												//Get the FileId of each FileDetails
												echo("Record FileDetails FileId: " . $fileDetail->getFileId() . "\n");

												//Get the AttachmentId of each FileDetails
												echo("Record FileDetails AttachmentId: " . $fileDetail->getAttachmentId() . "\n");

												//Get the FileSize of each FileDetails
												echo("Record FileDetails FileSize: " . $fileDetail->getFileSize() . "\n");

												//Get the CreatorId of each FileDetails
												echo("Record FileDetails CreatorId: " . $fileDetail->getCreatorId() . "\n");

												//Get the LinkDocs of each FileDetails
												echo("Record FileDetails LinkDocs: " . $fileDetail->getLinkDocs() . "\n");
											}
										}
										else if($value[0] instanceof Choice)
										{
											$choice = $value;

											foreach($choice as $choiceValue)
											{
												echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
											}
										}
										else if($value[0] instanceof Tag)
										{
											$tagList = $value;

											foreach($tagList as $tag)
											{
												//Get the Name of each Tag
												echo("Record Tag Name: " . $tag->getName() . "\n");

												//Get the Id of each Tag
												echo("Record Tag ID: " . $tag->getId() . "\n");
											}
										}
										else if($value[0] instanceof PricingDetails)
										{
											$pricingDetails = $value;

											foreach($pricingDetails as $pricingDetail)
											{
												echo("Record PricingDetails ToRange: " . $pricingDetail->getToRange(). "\n");

												echo("Record PricingDetails Discount: " . $pricingDetail->getDiscount(). "\n");

												echo("Record PricingDetails ID: " . $pricingDetail->getId() . "\n");

												echo("Record PricingDetails FromRange: " . $pricingDetail->getFromRange(). "\n");
											}
										}
										else if($value[0] instanceof Participants)
										{
											$participants = $value;

											foreach($participants as $participant)
											{
												echo("RelatedRecord Participants Name: " . $participant->getName() . "\n");

												echo("RelatedRecord Participants Invited: " . $participant->getInvited() . "\n");

												echo("RelatedRecord Participants ID: " . $participant->getId() . "\n");

												echo("RelatedRecord Participants Type: " . $participant->getType() . "\n");

												echo("RelatedRecord Participants Participant: " . $participant->getParticipant() . "\n");

												echo("RelatedRecord Participants Status: " . $participant->getStatus() . "\n");
											}
										}
										else if($value[0] instanceof $recordClass)
										{
											$recordList = $value;

											foreach($recordList as $record1)
											{
												//Get the details map
												foreach($record1->getKeyValues() as $key => $value1)
												{
													//Get each value in the map
													echo($key . " : " ); print_r($value1); echo("\n");
												}
											}
										}
										else if($value[0] instanceof LineTax)
										{
											$lineTaxes = $value;

											foreach($lineTaxes as $lineTax)
											{
												echo("Record ProductDetails LineTax Percentage: " . $lineTax->getPercentage(). "\n");

												echo("Record ProductDetails LineTax Name: " . $lineTax->getName() . "\n");

												echo("Record ProductDetails LineTax Id: " . $lineTax->getId() . "\n");

												echo("Record ProductDetails LineTax Value: " . $lineTax->getValue(). "\n");
											}
										}
										else if($value[0] instanceof Comment)
										{
											$comments = $value;

											foreach($comments as $comment)
											{
												echo("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");

												echo("Record Comment CommentedTime: "); print_r($comment->getCommentedTime()); echo("\n");

												echo("Record Comment CommentContent: " . $comment->getCommentContent(). "\n");

												echo("Record Comment Id: " . $comment->getId() . "\n");
											}
										}
										else if($value[0] instanceof Attachment)
										{
											$attachments = $value;

											foreach ($attachments as $attachment)
											{
												//Get the owner User instance of each attachment
												$owner = $attachment->getOwner();

												//Check if owner is not null
												if($owner != null)
												{
													//Get the Name of the Owner
													echo("Record Attachment Owner User-Name: " . $owner->getName() . "\n");

													//Get the ID of the Owner
													echo("Record Attachment Owner User-ID: " . $owner->getId() . "\n");

													//Get the Email of the Owner
													echo("Record Attachment Owner User-Email: " . $owner->getEmail() . "\n");
												}

												//Get the modified time of each attachment
												echo("Record Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");

												//Get the name of the File
												echo("Record Attachment File Name: " . $attachment->getFileName() . "\n");

												//Get the created time of each attachment
												echo("Record Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");

												//Get the Attachment file size
												echo("Record Attachment File Size: " . $attachment->getSize() . "\n");

												//Get the parentId Record instance of each attachment
												$parentId = $attachment->getParentId();

												//Check if parentId is not null
												if($parentId != null)
												{
													//Get the parent record Name of each attachment
													echo("Record Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");

													//Get the parent record ID of each attachment
													echo("Record Attachment parent record ID: " . $parentId->getId() . "\n");
												}

												//Get the attachment is Editable
												echo("Record Attachment is Editable: " . $attachment->getEditable() . "\n");

												//Get the file ID of each attachment
												echo("Record Attachment File ID: " . $attachment->getFileId() . "\n");

												//Get the type of each attachment
												echo("Record Attachment File Type: " . $attachment->getType() . "\n");

												//Get the seModule of each attachment
												echo("Record Attachment seModule: " . $attachment->getSeModule() . "\n");

												//Get the modifiedBy User instance of each attachment
												$modifiedBy = $attachment->getModifiedBy();

												//Check if modifiedBy is not null
												if($modifiedBy != null)
												{
													//Get the Name of the modifiedBy User
													echo("Record Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");

													//Get the ID of the modifiedBy User
													echo("Record Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");

													//Get the Email of the modifiedBy User
													echo("Record Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
												}

												//Get the state of each attachment
												echo("Record Attachment State: " . $attachment->getState() . "\n");

												//Get the ID of each attachment
												echo("Record Attachment ID: " . $attachment->getId() . "\n");

												//Get the createdBy User instance of each attachment
												$createdBy = $attachment->getCreatedBy();

												//Check if createdBy is not null
												if($createdBy != null)
												{
													//Get the name of the createdBy User
													echo("Record Attachment Created By User-Name: " . $createdBy->getName() . "\n");

													//Get the ID of the createdBy User
													echo("Record Attachment Created By User-ID: " . $createdBy->getId() . "\n");

													//Get the Email of the createdBy User
													echo("Record Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
												}

												//Get the linkUrl of each attachment
												echo("Record Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
											}
										}
										else if($value[0] instanceof ImageUpload)
										{
											$imageUploads = $value;

											foreach($imageUploads as $imageUpload)
											{
												echo("Record " . $keyName . " Description: " . $imageUpload->getDescription() . "\n");

												echo("Record " . $keyName . " PreviewId: " . $imageUpload->getPreviewId() . "\n");

												echo("Record " . $keyName . " File_Name: " . $imageUpload->getFileName() . "\n");

												echo("Record " . $keyName . " State: "); print_r($imageUpload->getState()); echo("\n");

												echo("Record " . $keyName . " Size: " . $imageUpload->getSize() . "\n");

												echo("Record " . $keyName . " SequenceNumber: " . $imageUpload->getSequenceNumber() . "\n");

												echo("Record " . $keyName . " Id: " . $imageUpload->getId() . "\n");

												echo("Record " . $keyName . " FileId: " . $imageUpload->getFileId() . "\n");
											}
										}
										else
										{
											echo($keyName . " : "); print_r($value); echo("\n");
										}
									}
									else if($value instanceof Layout)
									{
										$layout = $value;

										if($layout != null)
										{
											echo("Record " . $keyName. " ID: " . $layout->getId() . "\n");

											echo("Record " . $keyName . " Name: " . $layout->getName() . "\n");
										}
									}
									else if($value instanceof User)
									{
										$user = $value;

										if($user != null)
										{
											echo("Record " . $keyName . " User-ID: " . $user->getId() . "\n");

											echo("Record " . $keyName . " User-Name: " . $user->getName() . "\n");

											echo("Record " . $keyName . " User-Email: " . $user->getEmail() . "\n");
										}
									}
									else if($value instanceof $recordClass)
									{
										$recordValue = $value;

										echo("Record " . $keyName . " ID: " . $recordValue->getId() . "\n");

										echo("Record " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
									}
									else if($value instanceof Choice)
									{
										$choiceValue = $value;

										echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
									}
									else if($value instanceof RemindAt)
									{
										echo($keyName . ": " . $value->getAlarm() . "\n");
									}
									else if($value instanceof RecurringActivity)
									{
										echo($keyName . " : RRULE" . ": " . $value->getRrule() . "\n");
									}
									else if($value instanceof Consent)
									{
										$consent = $value;

										echo("Record Consent ID: " . $consent->getId());

										//Get the Owner User instance of each attachment
										$owner = $consent->getOwner();

										//Check if owner is not null
										if($owner != null)
										{
											//Get the name of the owner User
											echo("Record Consent Owner Name: " . $owner->getName());

											//Get the ID of the owner User
											echo("Record Consent Owner ID: " . $owner->getId());

											//Get the Email of the owner User
											echo("Record Consent Owner Email: " . $owner->getEmail());
										}

										$consentCreatedBy = $consent->getCreatedBy();

										//Check if createdBy is not null
										if($consentCreatedBy != null)
										{
											//Get the name of the CreatedBy User
											echo("Record Consent CreatedBy Name: " . $consentCreatedBy->getName());

											//Get the ID of the CreatedBy User
											echo("Record Consent CreatedBy ID: " . $consentCreatedBy->getId());

											//Get the Email of the CreatedBy User
											echo("Record Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
										}

										$consentModifiedBy = $consent->getModifiedBy();

										//Check if createdBy is not null
										if($consentModifiedBy != null)
										{
											//Get the name of the ModifiedBy User
											echo("Record Consent ModifiedBy Name: " . $consentModifiedBy->getName());

											//Get the ID of the ModifiedBy User
											echo("Record Consent ModifiedBy ID: " . $consentModifiedBy->getId());

											//Get the Email of the ModifiedBy User
											echo("Record Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
										}

										echo("Record Consent CreatedTime: " . $consent->getCreatedTime());

										echo("Record Consent ModifiedTime: " . $consent->getModifiedTime());

										echo("Record Consent ContactThroughEmail: " . $consent->getContactThroughEmail());

										echo("Record Consent ContactThroughSocial: " . $consent->getContactThroughSocial());

										echo("Record Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());

										echo("Record Consent ContactThroughPhone: " . $consent->getContactThroughPhone());

										echo("Record Consent MailSentTime: " . $consent->getMailSentTime());

										echo("Record Consent ConsentDate: " . $consent->getConsentDate());

										echo("Record Consent ConsentRemarks: " . $consent->getConsentRemarks());

										echo("Record Consent ConsentThrough: " . $consent->getConsentThrough());

										echo("Record Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());

										//To get custom values
										echo("Record Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
									}
									else
									{
										//Get each value in the map
										echo($keyName . " : "); print_r($value); echo("\n");
									}
								}
								else
								{
									//Get each value in the map
									echo($keyName . " : "); print_r($value); echo("\n");
								}
							}
						}
					}
				}
				else if($responseHandler instanceof FileBodyWrapper)
				{
					//Get object from response
					$fileBodyWrapper = $responseHandler;

					//Get StreamWrapper instance from the returned FileBodyWrapper instance
					$streamWrapper = $fileBodyWrapper->getFile();

					//Create a file instance with the absolute_file_path
					$fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");

					//Get stream from the response
					$stream = $streamWrapper->getStream();

					fputs($fp, $stream);

					fclose($fp);
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

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> UpdateRecordUsingExternalId</h3>
	 * This method is used to update a single record of a module with ID and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param externalFieldValue - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function updateRecordUsingExternalId(string $moduleAPIName, string $externalFieldValue)
	{
		//API Name of the module to update record
		//$moduleAPIName = "module_api_name";
		//$recordId = "3477002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();

		//List of Record instances
        $records = array();

        $recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		*/

		$record1->addFieldValue(Leads::City(), "City");

		$record1->addFieldValue(Leads::LastName(), "Last Name");

		$record1->addFieldValue(Leads::FirstName(), "First Name");

		$record1->addFieldValue(Leads::Company(), "KKRNP");

		// $accounts = new $recordClass();

		// $accounts->addKeyValue("id", "34770615848009");

		// $record1->addFieldValue(Contacts::AccountName(), $accounts);

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");

		$record1->addKeyValue("Custom_field_2", "value");

		$record1->addKeyValue("Date_1", new \DateTime('2020-03-08'));

		$record1->addKeyValue("Date_Time_2", date_create("2021-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get())));

		$fileDetails = array();

		$fileDetail1 = new FileDetails();

		$fileDetail1->setAttachmentId("347005");

		$fileDetail1->setDelete("null");

		array_push($fileDetails, $fileDetail1);

		$fileDetail2 = new FileDetails();

		$fileDetail2->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c32244f4e660f3702f05463e2fd0a2d8c1c");

		array_push($fileDetails, $fileDetail2);

		$fileDetail3 = new FileDetails();

		$fileDetail3->setFileId("ae9c7cefa418aec1d6a5cc2d9ab35c326a3f4c7562925ac9afc0f7433dd2098c");

		array_push($fileDetails, $fileDetail3);

		$record1->addKeyValue("File_Upload", $fileDetails);

		$recordOwner = new User();
        
		$recordOwner->setEmail("abc@zoho.com");
        
        $record1->addKeyValue("Owner", $recordOwner);

		//Used when GDPR is enabled
		$dataConsent = new Consent();
		
		$dataConsent->setConsentRemarks("Approved.");
		
		$dataConsent->setConsentThrough("Email");
		
		$dataConsent->setContactThroughEmail(true);
		
		$dataConsent->setContactThroughSocial(false);
		
		$record1->addKeyValue("Data_Processing_Basis_Details", $dataConsent);
		
		$subformList = [];
		
		$subform = new $recordClass();
		
		$subform->addKeyValue("Subform FieldAPIName", "FieldValue");
		
		array_push($subformList, $subform);
		
		$record1->addKeyValue("Subform Name", $subformList);
		
		/** Following methods are being used only by Inventory modules */
		
		$dealName = new $recordClass();
		
		$dealName->addFieldValue(Deals::id(), "3477061012112003");
		
		$record1->addFieldValue(Sales_Orders::DealName(), $dealName);
		
		$contactName = new $recordClass();
		
		$contactName->addFieldValue(Contacts::id(), "3477061011853001");
		
		$record1->addFieldValue(Purchase_Orders::ContactName(), $contactName);
		
		$accountName = new $recordClass();
		
		$accountName->addKeyValue("name", "automatedAccount");
		
		$record1->addFieldValue(Quotes::AccountName(), $accountName);

		$record1->addKeyValue("Discount", 10.5);
		
		$inventoryLineItemList = [];
		
		$inventoryLineItem = new $recordClass();
		
		$lineItemProduct = new LineItemProduct();
		
		$lineItemProduct->setId("3477061012107031");

		// $lineItemProduct->addKeyValue("Products_External", "AutomatedSDKExternal");
		
		$inventoryLineItem->addKeyValue("Description", "asd");
		
		$inventoryLineItem->addKeyValue("Discount", "5");
		
		$parentId = new $recordClass();
		
		$parentId->setId("35240337331017");

//		inventoryLineItem->addKeyValue("Parent_Id", 5);
		
		$inventoryLineItem->addKeyValue("Sequence_Number", "1");
		
		$lineitemProduct = new LineItemProduct();
		
		$lineitemProduct->setId("35240333659082");
		
		$inventoryLineItem->addKeyValue("Product_Name", $lineItemProduct);
		
		$inventoryLineItem->addKeyValue("Sequence_Number", "1");
		
		$inventoryLineItem->addKeyValue("Quantity",123.2);
		
		$inventoryLineItem->addKeyValue("Tax",123.2);
		
		array_push($inventoryLineItemList, $inventoryLineItem);
		
		$productLineTaxes = [];
		
		$productLineTax = new LineTax();
		
		$productLineTax->setName("MyT2ax1134");
		
		$productLineTax->setPercentage(20.0);
		
		array_push($productLineTaxes, $productLineTax);
		
		$inventoryLineItem->addKeyValue("Line_Tax", $productLineTaxes);
		
		array_push($inventoryLineItemList, $inventoryLineItem);

		$record1->addKeyValue("Quoted_Items", $inventoryLineItemList);
		
		$lineTaxes = [];
		
		$lineTax = new LineTax();
		
		$lineTax->setName("MyT2ax1134");
		
		$lineTax->setPercentage(20.0);
		
		array_push($lineTaxes, $lineTax);
		
		$record1->addKeyValue('$line_tax', $lineTaxes);
		
		/** End Inventory **/
		
		$tagList = [];
		
		$tag = new Tag();
		
		$tag->setName("Testtask1");
		
		array_push($tagList, $tag);
		
		$record1->setTag($tagList);

		//Add Record instance to the list
		array_push($records, $record1);

		//Set the list to Records in BodyWrapper instance
		$request->setData($records);

		$trigger = array("approval", "workflow", "blueprint");

		$request->setTrigger($trigger);

		$headerInstance = new HeaderMap();

		$headerInstance->add(UpdateRecordHeader::XEXTERNAL(), "Quotes.Quoted_Items.Product_Name.Products_External");

		//Call updateRecordUsingExternalId method that takes externalFieldValue, ModuleAPIName, BodyWrapper instance and headerInstance as parameter.
		$response = $recordOperations->updateRecordUsingExternalId($externalFieldValue, $moduleAPIName, $request, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Delete RecordUsingExternalId</h3>
	 * This method is used to delete a single record of a module with ID and print the response.
	 * @param moduleAPIName - The API Name of the record's module.
	 * @param externalFieldValue - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function deleteRecordUsingExternalId(string $moduleAPIName, string $externalFieldValue)
	{
		//API Name of the module to delete record
		//$moduleAPIName = "module_api_name";
		//$recordId = "3477002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(DeleteRecordParam::wfTrigger(), false);

		$headerInstance = new HeaderMap();

		$headerInstance->add(DeleteRecordHeader::XEXTERNAL(), "Leads.External");

		//Call deleteRecordUsingExternalId method that takes externalFieldValue, ModuleAPIName, paramInstance and headerInstance as parameter.
		$response = $recordOperations->deleteRecordUsingExternalId($externalFieldValue, $moduleAPIName, $paramInstance, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ActionWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Get Records</h3>
	 * This method is used to get all the records of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 *
	 */
	public static function getRecords(string $moduleAPIName)
	{
		//example
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class that takes moduleAPIName as parameter
		$recordOperations = new RecordOperations();

		$paramInstance = new ParameterMap();

		$paramInstance->add(GetRecordsParam::approved(), "true");

		$paramInstance->add(GetRecordsParam::converted(), "1234");

		$paramInstance->add(GetRecordsParam::cvid(), "34770610089005");

		$ids = array("TestExternalLead111", "34770614352001");

		foreach($ids as $id)
		{
			$paramInstance->add(GetRecordsParam::ids(), $id);
		}

		$paramInstance->add(GetRecordsParam::uid(), "34770615181008");

		$fieldNames = array("Last_Name", "City");

		foreach($fieldNames as $fieldName)
		{
			$paramInstance->add(GetRecordsParam::fields(), "id");
		}

		$paramInstance->add(GetRecordsParam::sortBy(), "Email");

		$paramInstance->add(GetRecordsParam::sortOrder(), "desc");

		$paramInstance->add(GetRecordsParam::page(), 1);

		$paramInstance->add(GetRecordsParam::perPage(), 3);

		$startdatetime = date_create("2020-06-27T15:10:00+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		$paramInstance->add(GetRecordsParam::startDateTime(), $startdatetime);

		$enddatetime = date_create("2020-06-29T15:10:00+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		$paramInstance->add(GetRecordsParam::endDateTime(), $enddatetime);

		$paramInstance->add(GetRecordsParam::territoryId(), "34770613051357");

		$paramInstance->add(GetRecordsParam::includeChild(), true);

		$headerInstance = new HeaderMap();

		$datetime = date_create("2021-02-26T15:28:34+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		$headerInstance->add(GetRecordsHeader::IfModifiedSince(), $datetime);

		$headerInstance->add(GetRecordsHeader::XEXTERNAL(), "Leads.External");

		//Call getRecords method
		$response = $recordOperations->getRecords($moduleAPIName, $paramInstance, $headerInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$responseHandler = $response->getObject();

				if($responseHandler instanceof ResponseWrapper)
				{
					//Get the received ResponseWrapper instance
					$responseWrapper = $responseHandler;

					//Get the list of obtained Record instances
					$records = $responseWrapper->getData();

					if($records != null)
					{
						$recordClass = 'com\zoho\crm\api\record\Record';

						foreach($records as $record)
						{
							//Get the ID of each Record
							echo("Record ID: " . $record->getId() . "\n");

							//Get the createdBy User instance of each Record
							$createdBy = $record->getCreatedBy();

							//Check if createdBy is not null
							if($createdBy != null)
							{
								//Get the ID of the createdBy User
								echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

								//Get the name of the createdBy User
								echo("Record Created By User-Name: " . $createdBy->getName() . "\n");

								//Get the Email of the createdBy User
								echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
							}

							//Get the CreatedTime of each Record
							echo("Record CreatedTime: "); print_r($record->getCreatedTime()); echo("\n");

							//Get the modifiedBy User instance of each Record
							$modifiedBy = $record->getModifiedBy();

							//Check if modifiedBy is not null
							if($modifiedBy != null)
							{
								//Get the ID of the modifiedBy User
								echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

								//Get the name of the modifiedBy User
								echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");

								//Get the Email of the modifiedBy User
								echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
							}

							//Get the ModifiedTime of each Record
							echo("Record ModifiedTime: "); print_r($record->getModifiedTime()); echo("\n");

							//Get the list of Tag instance each Record
							$tags = $record->getTag();

							//Check if tags is not null
							if($tags != null)
							{
								foreach($tags as $tag)
								{
									//Get the Name of each Tag
									echo("Record Tag Name: " . $tag->getName() . "\n");

									//Get the Id of each Tag
									echo("Record Tag ID: " . $tag->getId() . "\n");
								}
							}

							//To get particular field value
							echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName

							echo("Record KeyValues : \n" );

							//Get the KeyValue map
							foreach($record->getKeyValues() as $keyName => $value)
							{
								if($value != null)
								{
									if((is_array($value) && sizeof($value) > 0) && isset($value[0]))
									{
										if($value[0] instanceof FileDetails)
										{
											$fileDetails = $value;

											foreach($fileDetails as $fileDetail)
											{
												//Get the Extn of each FileDetails
												echo("Record FileDetails Extn: " . $fileDetail->getExtn() . "\n");

												//Get the IsPreviewAvailable of each FileDetails
												echo("Record FileDetails IsPreviewAvailable: " . $fileDetail->getIsPreviewAvailable() . "\n");

												//Get the DownloadUrl of each FileDetails
												echo("Record FileDetails DownloadUrl: " . $fileDetail->getDownloadUrl() . "\n");

												//Get the DeleteUrl of each FileDetails
												echo("Record FileDetails DeleteUrl: " . $fileDetail->getDeleteUrl() . "\n");

												//Get the EntityId of each FileDetails
												echo("Record FileDetails EntityId: " . $fileDetail->getEntityId() . "\n");

												//Get the Mode of each FileDetails
												echo("Record FileDetails Mode: " . $fileDetail->getMode() . "\n");

												//Get the OriginalSizeByte of each FileDetails
												echo("Record FileDetails OriginalSizeByte: " . $fileDetail->getOriginalSizeByte() . "\n");

												//Get the PreviewUrl of each FileDetails
												echo("Record FileDetails PreviewUrl: " . $fileDetail->getPreviewUrl() . "\n");

												//Get the FileName of each FileDetails
												echo("Record FileDetails FileName: " . $fileDetail->getFileName() . "\n");

												//Get the FileId of each FileDetails
												echo("Record FileDetails FileId: " . $fileDetail->getFileId() . "\n");

												//Get the AttachmentId of each FileDetails
												echo("Record FileDetails AttachmentId: " . $fileDetail->getAttachmentId() . "\n");

												//Get the FileSize of each FileDetails
												echo("Record FileDetails FileSize: " . $fileDetail->getFileSize() . "\n");

												//Get the CreatorId of each FileDetails
												echo("Record FileDetails CreatorId: " . $fileDetail->getCreatorId() . "\n");

												//Get the LinkDocs of each FileDetails
												echo("Record FileDetails LinkDocs: " . $fileDetail->getLinkDocs() . "\n");
											}
										}
										else if($value[0] instanceof Choice)
										{
											$choice = $value;

											foreach($choice as $choiceValue)
											{
												echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
											}
										}
										else if($value[0] instanceof Tag)
										{
											$tagList = $value;

											foreach($tagList as $tag)
											{
												//Get the Name of each Tag
												echo("Record Tag Name: " . $tag->getName() . "\n");

												//Get the Id of each Tag
												echo("Record Tag ID: " . $tag->getId() . "\n");
											}
										}
										else if($value[0] instanceof PricingDetails)
										{
											$pricingDetails = $value;

											foreach($pricingDetails as $pricingDetail)
											{
												echo("Record PricingDetails ToRange: " . $pricingDetail->getToRange(). "\n");

												echo("Record PricingDetails Discount: " . $pricingDetail->getDiscount(). "\n");

												echo("Record PricingDetails ID: " . $pricingDetail->getId() . "\n");

												echo("Record PricingDetails FromRange: " . $pricingDetail->getFromRange(). "\n");
											}
										}
										else if($value[0] instanceof Participants)
										{
											$participants = $value;

											foreach($participants as $participant)
											{
												echo("RelatedRecord Participants Name: " . $participant->getName() . "\n");

												echo("RelatedRecord Participants Invited: " . $participant->getInvited() . "\n");

												echo("RelatedRecord Participants ID: " . $participant->getId() . "\n");

												echo("RelatedRecord Participants Type: " . $participant->getType() . "\n");

												echo("RelatedRecord Participants Participant: " . $participant->getParticipant() . "\n");

												echo("RelatedRecord Participants Status: " . $participant->getStatus() . "\n");
											}
										}
										else if($value[0] instanceof $recordClass)
										{
											$recordList = $value;

											foreach($recordList as $record1)
											{
												//Get the details map
												foreach($record1->getKeyValues() as $key => $value1)
												{
													//Get each value in the map
													echo($key . " : " ); print_r($value1); echo("\n");
												}
											}
										}
										else if($value[0] instanceof LineTax)
										{
											$lineTaxes = $value;

											foreach($lineTaxes as $lineTax)
											{
												echo("Record ProductDetails LineTax Percentage: " . $lineTax->getPercentage(). "\n");

												echo("Record ProductDetails LineTax Name: " . $lineTax->getName() . "\n");

												echo("Record ProductDetails LineTax Id: " . $lineTax->getId() . "\n");

												echo("Record ProductDetails LineTax Value: " . $lineTax->getValue(). "\n");
											}
										}
										else if($value[0] instanceof Comment)
										{
											$comments = $value;

											foreach($comments as $comment)
											{
												echo("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");

												echo("Record Comment CommentedTime: "); print_r($comment->getCommentedTime()); echo("\n");

												echo("Record Comment CommentContent: " . $comment->getCommentContent(). "\n");

												echo("Record Comment Id: " . $comment->getId() . "\n");
											}
										}
										else if($value[0] instanceof Attachment)
										{
											$attachments = $value;

											foreach ($attachments as $attachment)
											{
												//Get the owner User instance of each attachment
												$owner = $attachment->getOwner();

												//Check if owner is not null
												if($owner != null)
												{
													//Get the Name of the Owner
													echo("Record Attachment Owner User-Name: " . $owner->getName() . "\n");

													//Get the ID of the Owner
													echo("Record Attachment Owner User-ID: " . $owner->getId() . "\n");

													//Get the Email of the Owner
													echo("Record Attachment Owner User-Email: " . $owner->getEmail() . "\n");
												}

												//Get the modified time of each attachment
												echo("Record Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");

												//Get the name of the File
												echo("Record Attachment File Name: " . $attachment->getFileName() . "\n");

												//Get the created time of each attachment
												echo("Record Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");

												//Get the Attachment file size
												echo("Record Attachment File Size: " . $attachment->getSize() . "\n");

												//Get the parentId Record instance of each attachment
												$parentId = $attachment->getParentId();

												//Check if parentId is not null
												if($parentId != null)
												{
													//Get the parent record Name of each attachment
													echo("Record Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");

													//Get the parent record ID of each attachment
													echo("Record Attachment parent record ID: " . $parentId->getId() . "\n");
												}

												//Get the attachment is Editable
												echo("Record Attachment is Editable: " . $attachment->getEditable() . "\n");

												//Get the file ID of each attachment
												echo("Record Attachment File ID: " . $attachment->getFileId() . "\n");

												//Get the type of each attachment
												echo("Record Attachment File Type: " . $attachment->getType() . "\n");

												//Get the seModule of each attachment
												echo("Record Attachment seModule: " . $attachment->getSeModule() . "\n");

												//Get the modifiedBy User instance of each attachment
												$modifiedBy = $attachment->getModifiedBy();

												//Check if modifiedBy is not null
												if($modifiedBy != null)
												{
													//Get the Name of the modifiedBy User
													echo("Record Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");

													//Get the ID of the modifiedBy User
													echo("Record Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");

													//Get the Email of the modifiedBy User
													echo("Record Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
												}

												//Get the state of each attachment
												echo("Record Attachment State: " . $attachment->getState() . "\n");

												//Get the ID of each attachment
												echo("Record Attachment ID: " . $attachment->getId() . "\n");

												//Get the createdBy User instance of each attachment
												$createdBy = $attachment->getCreatedBy();

												//Check if createdBy is not null
												if($createdBy != null)
												{
													//Get the name of the createdBy User
													echo("Record Attachment Created By User-Name: " . $createdBy->getName() . "\n");

													//Get the ID of the createdBy User
													echo("Record Attachment Created By User-ID: " . $createdBy->getId() . "\n");

													//Get the Email of the createdBy User
													echo("Record Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
												}

												//Get the linkUrl of each attachment
												echo("Record Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
											}
										}
										else if($value[0] instanceof ImageUpload)
										{
											$imageUploads = $value;

											foreach($imageUploads as $imageUpload)
											{
												echo("Record " . $keyName . " Description: " . $imageUpload->getDescription() . "\n");

												echo("Record " . $keyName . " PreviewId: " . $imageUpload->getPreviewId() . "\n");

												echo("Record " . $keyName . " File_Name: " . $imageUpload->getFileName() . "\n");

												echo("Record " . $keyName . " State: "); print_r($imageUpload->getState()); echo("\n");

												echo("Record " . $keyName . " Size: " . $imageUpload->getSize() . "\n");

												echo("Record " . $keyName . " SequenceNumber: " . $imageUpload->getSequenceNumber() . "\n");

												echo("Record " . $keyName . " Id: " . $imageUpload->getId() . "\n");

												echo("Record " . $keyName . " FileId: " . $imageUpload->getFileId() . "\n");
											}
										}
										else
										{
											echo($keyName . " : "); print_r($value); echo("\n");
										}
									}
									else if($value instanceof Layout)
									{
										$layout = $value;

										if($layout != null)
										{
											echo("Record " . $keyName. " ID: " . $layout->getId() . "\n");

											echo("Record " . $keyName . " Name: " . $layout->getName() . "\n");
										}
									}
									else if($value instanceof User)
									{
										$user = $value;

										if($user != null)
										{
											echo("Record " . $keyName . " User-ID: " . $user->getId() . "\n");

											echo("Record " . $keyName . " User-Name: " . $user->getName() . "\n");

											echo("Record " . $keyName . " User-Email: " . $user->getEmail() . "\n");
										}
									}
									else if($value instanceof $recordClass)
									{
										$recordValue = $value;

										echo("Record " . $keyName . " ID: " . $recordValue->getId() . "\n");

										echo("Record " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
									}
									else if($value instanceof Choice)
									{
										$choiceValue = $value;

										echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
									}
									else if($value instanceof RemindAt)
									{
										echo($keyName . ": " . $value->getAlarm() . "\n");
									}
									else if($value instanceof RecurringActivity)
									{
										echo($keyName . " : RRULE" . ": " . $value->getRrule() . "\n");
									}
									else if($value instanceof Consent)
									{
										$consent = $value;

										echo("Record Consent ID: " . $consent->getId());

										//Get the Owner User instance of each attachment
										$owner = $consent->getOwner();

										//Check if owner is not null
										if($owner != null)
										{
											//Get the name of the owner User
											echo("Record Consent Owner Name: " . $owner->getName());

											//Get the ID of the owner User
											echo("Record Consent Owner ID: " . $owner->getId());

											//Get the Email of the owner User
											echo("Record Consent Owner Email: " . $owner->getEmail());
										}

										$consentCreatedBy = $consent->getCreatedBy();

										//Check if createdBy is not null
										if($consentCreatedBy != null)
										{
											//Get the name of the CreatedBy User
											echo("Record Consent CreatedBy Name: " . $consentCreatedBy->getName());

											//Get the ID of the CreatedBy User
											echo("Record Consent CreatedBy ID: " . $consentCreatedBy->getId());

											//Get the Email of the CreatedBy User
											echo("Record Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
										}

										$consentModifiedBy = $consent->getModifiedBy();

										//Check if createdBy is not null
										if($consentModifiedBy != null)
										{
											//Get the name of the ModifiedBy User
											echo("Record Consent ModifiedBy Name: " . $consentModifiedBy->getName());

											//Get the ID of the ModifiedBy User
											echo("Record Consent ModifiedBy ID: " . $consentModifiedBy->getId());

											//Get the Email of the ModifiedBy User
											echo("Record Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
										}

										echo("Record Consent CreatedTime: " . $consent->getCreatedTime());

										echo("Record Consent ModifiedTime: " . $consent->getModifiedTime());

										echo("Record Consent ContactThroughEmail: " . $consent->getContactThroughEmail());

										echo("Record Consent ContactThroughSocial: " . $consent->getContactThroughSocial());

										echo("Record Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());

										echo("Record Consent ContactThroughPhone: " . $consent->getContactThroughPhone());

										echo("Record Consent MailSentTime: " . $consent->getMailSentTime());

										echo("Record Consent ConsentDate: " . $consent->getConsentDate());

										echo("Record Consent ConsentRemarks: " . $consent->getConsentRemarks());

										echo("Record Consent ConsentThrough: " . $consent->getConsentThrough());

										echo("Record Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());

										//To get custom values
										echo("Record Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
									}
									else
									{
										//Get each value in the map
										echo($keyName . " : "); print_r($value); echo("\n");
									}
								}
								else
								{
									//Get each value in the map
									echo($keyName . " : "); print_r($value); echo("\n");
								}
							}
						}
					}

					$info = $responseWrapper->getInfo();

					echo("Record Info PerPage : " . $info->getPerPage() . "\n");

					echo("Record Info Count : " . $info->getCount() . "\n");

					echo("Record Info Page : " . $info->getPage(). "\n");

					echo("Record Info MoreRecords : "); print_r($info->getMoreRecords()); echo("\n");
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

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Create Records</h3>
	 * This method is used to create records of a module and print the response
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function createRecords(string $moduleAPIName)
	{
		//API Name of the module to create records
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class that takes moduleAPIName as parameter
		$recordOperations = new RecordOperations();

		//Get instance of BodyWrapper Class that will contain the request body
		$bodyWrapper = new BodyWrapper();

		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

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

		$tagList = array();

		$tag = new Tag();

		$tag->setName("Testtask");

		array_push($tagList, $tag);

		//Set the list to Tags in Record instance
		$record1->setTag($tagList);

		//Add Record instance to the list
		array_push($records, $record1);

		//Set the list to Records in BodyWrapper instance
		$bodyWrapper->setData($records);

		$trigger = array("approval", "workflow", "blueprint");

		$bodyWrapper->setTrigger($trigger);

		//bodyWrapper.setLarId("34770610087515");

		$headerInstance = new HeaderMap();

		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Quotes.Quoted_Items.Product_Name.Products_External");

		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Products.Products_External");

		//Call createRecords method that takes BodyWrapper instance as parameter.
		$response = $recordOperations->createRecords($moduleAPIName, $bodyWrapper, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ActionWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : "); print_r($value); echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Update Records</h3>
	 * This method is used to update records of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function updateRecords(string $moduleAPIName)
	{
		//API Name of the module to create records
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();

		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

		$record1->setId("3477061012266");

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */

		$record1->addFieldValue(Leads::City(), "City");

		$record1->addFieldValue(Leads::LastName(), "Last Name");

		$record1->addFieldValue(Leads::FirstName(), "First Name");

		$record1->addFieldValue(Leads::Company(), "KKRNP");

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");

		$record1->addKeyValue("Custom_field_2", "value");

		$recordOwner = new User();
        
		$recordOwner->setEmail("abc@zoho.com");
        
        $record1->addKeyValue("Owner", $recordOwner);

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

		// $lineItemProduct->setId("3477061012261");

		$lineItemProduct->addKeyValue("Products_External", "Products_External");

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

		//Add Record instance to the list
		array_push($records, $record1);

		//Get instance of Record Class
		$record2 = new $recordClass();

		$record2->setId("34770619873001");

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$record2->addFieldValue(Leads::City(), "City");

		$record2->addFieldValue(Leads::LastName(), "Last Name");

		$record2->addFieldValue(Leads::FirstName(), "First Name");

		$record2->addFieldValue(Leads::Company(), "KKRNP");

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record2->addKeyValue("Custom_field", "Value");

		$record2->addKeyValue("Custom_field_2", "value");

		//Add Record instance to the list
		array_push($records, $record2);

		//Set the list to Records in BodyWrapper instance
		$request->setData($records);

		$trigger = array("approval", "workflow", "blueprint");

		$request->setTrigger($trigger);

		$headerInstance = new HeaderMap();

		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Quotes.Quoted_Items.Product_Name.Products_External");

		//Call createRecords method that takes moduleAPIName, BodyWrapper instance and $headerInstance as parameter.
		$response = $recordOperations->updateRecords($moduleAPIName, $request, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Delete Records</h3>
	 * This method is used to delete records of a module and print the response
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @param recordIds - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function deleteRecords(string $moduleAPIName, array $recordIds)
	{
		//API Name of the module to update record
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		foreach($recordIds as $id)
		{
			$paramInstance->add(DeleteRecordsParam::ids(), $id);
		}

		$paramInstance->add(DeleteRecordsParam::wfTrigger(), true);

		$headerInstance = new HeaderMap();

		// $headerInstance->add(CreateRecordsHeader::XEXTERNAL(), "Leads.External");

		//Call deleteRecord method that takes ModuleAPIName, $paramInstance and $headerInstance as parameter.
		$response = $recordOperations->deleteRecords($moduleAPIName, $paramInstance, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ActionWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Upsert Records</h3>
	 * This method is used to Upsert records of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function upsertRecords(string $moduleAPIName)
	{
		//API Name of the module to create records
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class that takes moduleAPIName as parameter
		$recordOperations = new RecordOperations();

		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();

		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$field = new Field("");

		$record1->addFieldValue(Leads::City(), "City");

		$record1->addFieldValue(Leads::LastName(), "Last Name");

		$record1->addFieldValue(Leads::FirstName(), "First Name");

		$record1->addFieldValue(Leads::Company(), "Company1");

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("Custom_field", "Value");

		$record1->addKeyValue("Custom_field_2", "value");

		//Add Record instance to the list
		array_push($records, $record1);

		//Get instance of Record Class
		$record2 = new $recordClass();

		/*
		 * Call addFieldValue method that takes two arguments
		 * 1 -> Call Field "." and choose the module from the displayed list and press "." and choose the field name from the displayed list.
		 * 2 -> Value
		 */
		$record2->addFieldValue(Leads::City(), "City");

		$record2->addFieldValue(Leads::LastName(), "Last Name");

		$record2->addFieldValue(Leads::FirstName(), "First Name");

		$record2->addFieldValue(Leads::Company(), "Company12");

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record2->addKeyValue("Custom_field", "Value");

		$record2->addKeyValue("Custom_field_2", "value");

		$record2->addKeyValue("External", "External1");

		//Add Record instance to the list
		array_push($records, $record2);

		$duplicateCheckFields = array("City", "Last_Name", "First_Name");

		$request->setDuplicateCheckFields($duplicateCheckFields);

		//Set the list to Records in BodyWrapper instance
		$request->setData($records);

		$headerInstance = new HeaderMap();

		// $headerInstance->add(UpsertRecordsHeader::XEXTERNAL(), "Leads.External");

		//Call UpsertRecordsHeader method that takes BodyWrapper instance as parameter.
		$response = $recordOperations->upsertRecords($moduleAPIName, $request, $headerInstance);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Get Deleted Records</h3>
	 * This method is used to deleted records of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function getDeletedRecords(string $moduleAPIName)
	{
		//example, $moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(GetDeletedRecordsParam::type(), "recycle");//all, recycle, permanent

		$paramInstance->add(GetDeletedRecordsParam::page(), 1);

		$paramInstance->add(GetDeletedRecordsParam::perPage(), 2);

		//Get instance of HeaderMap Class
		$headerInstance = new HeaderMap();

		$ifModifiedSince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

		$headerInstance->add(GetDeletedRecordsHeader::IfModifiedSince(), $ifModifiedSince);

		//Call getDeletedRecords method that takes paramInstance, headerInstance and moduleAPIName as parameter
		$response = $recordOperations->getDeletedRecords($moduleAPIName,$paramInstance, $headerInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get the object from response
				$deletedRecordsHandler = $response->getObject();

				if($deletedRecordsHandler instanceof DeletedRecordsWrapper)
				{
					//Get the received DeletedRecordsWrapper instance
					$deletedRecordsWrapper = $deletedRecordsHandler;

					//Get the list of obtained DeletedRecord instances
					$deletedRecords = $deletedRecordsWrapper->getData();

					foreach($deletedRecords as $deletedRecord)
					{
						//Get the deletedBy User instance of each DeletedRecord
						$deletedBy = $deletedRecord->getDeletedBy();

						//Check if deletedBy is not null
						if($deletedBy != null)
						{
							//Get the name of the deletedBy User
							echo("DeletedRecord Deleted By User-Name: " . $deletedBy->getName() . "\n");

							//Get the ID of the deletedBy User
							echo("DeletedRecord Deleted By User-ID: " . $deletedBy->getId() . "\n");
						}

						//Get the ID of each DeletedRecord
						echo("DeletedRecord ID: " . $deletedRecord->getId() . "\n");

						//Get the DisplayName of each DeletedRecord
						echo("DeletedRecord DisplayName: " . $deletedRecord->getDisplayName() . "\n");

						//Get the Type of each DeletedRecord
						echo("DeletedRecord Type: " . $deletedRecord->getType() . "\n");

						//Get the createdBy User instance of each DeletedRecord
						$createdBy = $deletedRecord->getCreatedBy();

						//Check if createdBy is not null
						if($createdBy != null)
						{
							//Get the name of the createdBy User
							echo("DeletedRecord Created By User-Name: " . $createdBy->getName() . "\n");

							//Get the ID of the createdBy User
							echo("DeletedRecord Created By User-ID: " . $createdBy->getId() . "\n");
						}

						//Get the DeletedTime of each DeletedRecord
						echo("DeletedRecord DeletedTime: ");

						print_r($deletedRecord->getDeletedTime());

						echo("\n");
					}

					//Get the Object obtained Info instance
					$info = $deletedRecordsWrapper->getInfo();

					//Check if info is not null
					if($info != null)
					{
						if($info->getPerPage() != null)
						{
							//Get the PerPage of the Info
							echo("Deleted Record Info PerPage: " . $info->getPerPage() . "\n");
						}

						if($info->getCount() != null)
						{
							//Get the Count of the Info
							echo("Deleted Record Info Count: " . $info->getCount() . "\n");
						}

						if($info->getPage() != null)
						{
							//Get the Page of the Info
							echo("Deleted Record Info Page: " . $info->getPage() . "\n");
						}

						if($info->getMoreRecords() != null)
						{
							//Get the MoreRecords of the Info
							echo("Deleted Record Info MoreRecords: " . $info->getMoreRecords() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($deletedRecordsHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $deletedRecordsHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * <h3> Search Records</h3>
	 * This method is used to search records of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function searchRecords(string $moduleAPIName)
	{
		//example, moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class that takes moduleAPIName as parameter
		$recordOperations = new RecordOperations();

		$paramInstance = new ParameterMap();

		$paramInstance->add(SearchRecordsParam::criteria(), "((Last_Name:starts_with:Last Name) or (Company:starts_with:fasf\\(123\\) K))");

		// $paramInstance->add(SearchRecordsParam::criteria(), "(External:in:External1)");
		
		$paramInstance->add(SearchRecordsParam::email(), "username@gmail.com");

		$paramInstance->add(SearchRecordsParam::phone(), "234567890");

		$paramInstance->add(SearchRecordsParam::word(), "First Name Last Name");

		$paramInstance->add(SearchRecordsParam::converted(), "both");

		$paramInstance->add(SearchRecordsParam::approved(), "both");

		$paramInstance->add(SearchRecordsParam::page(), 1);

		$paramInstance->add(SearchRecordsParam::perPage(), 2);

		$headerInstance = new HeaderMap();

		$headerInstance->add(UpdateRecordHeader::XEXTERNAL(), "Leads.External");

		//Call searchRecords method
		$response = $recordOperations->searchRecords($moduleAPIName, $paramInstance, $headerInstance);

		if($response->isExpected())
		{
			//Get object from response
			$responseHandler = $response->getObject();

			if($responseHandler instanceof ResponseWrapper)
			{
				//Get the received ResponseWrapper instance
				$responseWrapper = $responseHandler;

				//Get the list of obtained Record instances
				$records = $responseWrapper->getData();

				if($records != null)
				{
					$recordClass = 'com\zoho\crm\api\record\Record';

					foreach($records as $record)
					{
						//Get the ID of each Record
						echo("Record ID: " . $record->getId() . "\n");

						//Get the createdBy User instance of each Record
						$createdBy = $record->getCreatedBy();

						//Check if createdBy is not null
						if($createdBy != null)
						{
							//Get the ID of the createdBy User
							echo("Record Created By User-ID: " . $createdBy->getId() . "\n");

							//Get the name of the createdBy User
							echo("Record Created By User-Name: " . $createdBy->getName() . "\n");

							//Get the Email of the createdBy User
							echo("Record Created By User-Email: " . $createdBy->getEmail() . "\n");
						}

						//Get the CreatedTime of each Record
						echo("Record CreatedTime: "); print_r($record->getCreatedTime()); echo("\n");

						//Get the modifiedBy User instance of each Record
						$modifiedBy = $record->getModifiedBy();

						//Check if modifiedBy is not null
						if($modifiedBy != null)
						{
							//Get the ID of the modifiedBy User
							echo("Record Modified By User-ID: " . $modifiedBy->getId() . "\n");

							//Get the name of the modifiedBy User
							echo("Record Modified By User-Name: " . $modifiedBy->getName() . "\n");

							//Get the Email of the modifiedBy User
							echo("Record Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
						}

						//Get the ModifiedTime of each Record
						echo("Record ModifiedTime: "); print_r($record->getModifiedTime()); echo("\n");

						//Get the list of Tag instance each Record
						$tags = $record->getTag();

						//Check if tags is not null
						if($tags != null)
						{
							foreach($tags as $tag)
							{
								//Get the Name of each Tag
								echo("Record Tag Name: " . $tag->getName() . "\n");

								//Get the Id of each Tag
								echo("Record Tag ID: " . $tag->getId() . "\n");
							}
						}

						//To get particular field value
						echo("Record Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName

						echo("Record KeyValues : \n" );

						//Get the KeyValue map
						foreach($record->getKeyValues() as $keyName => $value)
						{
							if($value != null)
							{
								if((is_array($value) && sizeof($value) > 0) && isset($value[0]))
								{
									if($value[0] instanceof FileDetails)
									{
										$fileDetails = $value;

										foreach($fileDetails as $fileDetail)
										{
											//Get the Extn of each FileDetails
											echo("Record FileDetails Extn: " . $fileDetail->getExtn() . "\n");

											//Get the IsPreviewAvailable of each FileDetails
											echo("Record FileDetails IsPreviewAvailable: " . $fileDetail->getIsPreviewAvailable() . "\n");

											//Get the DownloadUrl of each FileDetails
											echo("Record FileDetails DownloadUrl: " . $fileDetail->getDownloadUrl() . "\n");

											//Get the DeleteUrl of each FileDetails
											echo("Record FileDetails DeleteUrl: " . $fileDetail->getDeleteUrl() . "\n");

											//Get the EntityId of each FileDetails
											echo("Record FileDetails EntityId: " . $fileDetail->getEntityId() . "\n");

											//Get the Mode of each FileDetails
											echo("Record FileDetails Mode: " . $fileDetail->getMode() . "\n");

											//Get the OriginalSizeByte of each FileDetails
											echo("Record FileDetails OriginalSizeByte: " . $fileDetail->getOriginalSizeByte() . "\n");

											//Get the PreviewUrl of each FileDetails
											echo("Record FileDetails PreviewUrl: " . $fileDetail->getPreviewUrl() . "\n");

											//Get the FileName of each FileDetails
											echo("Record FileDetails FileName: " . $fileDetail->getFileName() . "\n");

											//Get the FileId of each FileDetails
											echo("Record FileDetails FileId: " . $fileDetail->getFileId() . "\n");

											//Get the AttachmentId of each FileDetails
											echo("Record FileDetails AttachmentId: " . $fileDetail->getAttachmentId() . "\n");

											//Get the FileSize of each FileDetails
											echo("Record FileDetails FileSize: " . $fileDetail->getFileSize() . "\n");

											//Get the CreatorId of each FileDetails
											echo("Record FileDetails CreatorId: " . $fileDetail->getCreatorId() . "\n");

											//Get the LinkDocs of each FileDetails
											echo("Record FileDetails LinkDocs: " . $fileDetail->getLinkDocs() . "\n");
										}
									}
									else if($value[0] instanceof Choice)
									{
										$choice = $value;

										foreach($choice as $choiceValue)
										{
											echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
										}
									}
									else if($value[0] instanceof Tag)
									{
										$tagList = $value;

										foreach($tagList as $tag)
										{
											//Get the Name of each Tag
											echo("Record Tag Name: " . $tag->getName() . "\n");

											//Get the Id of each Tag
											echo("Record Tag ID: " . $tag->getId() . "\n");
										}
									}
									else if($value[0] instanceof PricingDetails)
									{
										$pricingDetails = $value;

										foreach($pricingDetails as $pricingDetail)
										{
											echo("Record PricingDetails ToRange: " . $pricingDetail->getToRange(). "\n");

											echo("Record PricingDetails Discount: " . $pricingDetail->getDiscount(). "\n");

											echo("Record PricingDetails ID: " . $pricingDetail->getId() . "\n");

											echo("Record PricingDetails FromRange: " . $pricingDetail->getFromRange(). "\n");
										}
									}
									else if($value[0] instanceof Participants)
									{
										$participants = $value;

										foreach($participants as $participant)
										{
											echo("RelatedRecord Participants Name: " . $participant->getName() . "\n");

											echo("RelatedRecord Participants Invited: " . $participant->getInvited() . "\n");

											echo("RelatedRecord Participants ID: " . $participant->getId() . "\n");

											echo("RelatedRecord Participants Type: " . $participant->getType() . "\n");

											echo("RelatedRecord Participants Participant: " . $participant->getParticipant() . "\n");

											echo("RelatedRecord Participants Status: " . $participant->getStatus() . "\n");
										}
									}
									else if($value[0] instanceof $recordClass)
									{
										$recordList = $value;

										foreach($recordList as $record1)
										{
											//Get the details map
											foreach($record1->getKeyValues() as $key => $value1)
											{
												//Get each value in the map
												echo($key . " : " ); print_r($value1); echo("\n");
											}
										}
									}
									else if($value[0] instanceof LineTax)
									{
										$lineTaxes = $value;

										foreach($lineTaxes as $lineTax)
										{
											echo("Record ProductDetails LineTax Percentage: " . $lineTax->getPercentage(). "\n");

											echo("Record ProductDetails LineTax Name: " . $lineTax->getName() . "\n");

											echo("Record ProductDetails LineTax Id: " . $lineTax->getId() . "\n");

											echo("Record ProductDetails LineTax Value: " . $lineTax->getValue(). "\n");
										}
									}
									else if($value[0] instanceof Comment)
									{
										$comments = $value;

										foreach($comments as $comment)
										{
											echo("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");

											echo("Record Comment CommentedTime: "); print_r($comment->getCommentedTime()); echo("\n");

											echo("Record Comment CommentContent: " . $comment->getCommentContent(). "\n");

											echo("Record Comment Id: " . $comment->getId() . "\n");
										}
									}
									else if($value[0] instanceof Attachment)
									{
										$attachments = $value;

										foreach ($attachments as $attachment)
										{
											//Get the owner User instance of each attachment
											$owner = $attachment->getOwner();

											//Check if owner is not null
											if($owner != null)
											{
												//Get the Name of the Owner
												echo("Record Attachment Owner User-Name: " . $owner->getName() . "\n");

												//Get the ID of the Owner
												echo("Record Attachment Owner User-ID: " . $owner->getId() . "\n");

												//Get the Email of the Owner
												echo("Record Attachment Owner User-Email: " . $owner->getEmail() . "\n");
											}

											//Get the modified time of each attachment
											echo("Record Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");

											//Get the name of the File
											echo("Record Attachment File Name: " . $attachment->getFileName() . "\n");

											//Get the created time of each attachment
											echo("Record Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");

											//Get the Attachment file size
											echo("Record Attachment File Size: " . $attachment->getSize() . "\n");

											//Get the parentId Record instance of each attachment
											$parentId = $attachment->getParentId();

											//Check if parentId is not null
											if($parentId != null)
											{
												//Get the parent record Name of each attachment
												echo("Record Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");

												//Get the parent record ID of each attachment
												echo("Record Attachment parent record ID: " . $parentId->getId() . "\n");
											}

											//Get the attachment is Editable
											echo("Record Attachment is Editable: " . $attachment->getEditable() . "\n");

											//Get the file ID of each attachment
											echo("Record Attachment File ID: " . $attachment->getFileId() . "\n");

											//Get the type of each attachment
											echo("Record Attachment File Type: " . $attachment->getType() . "\n");

											//Get the seModule of each attachment
											echo("Record Attachment seModule: " . $attachment->getSeModule() . "\n");

											//Get the modifiedBy User instance of each attachment
											$modifiedBy = $attachment->getModifiedBy();

											//Check if modifiedBy is not null
											if($modifiedBy != null)
											{
												//Get the Name of the modifiedBy User
												echo("Record Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");

												//Get the ID of the modifiedBy User
												echo("Record Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");

												//Get the Email of the modifiedBy User
												echo("Record Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
											}

											//Get the state of each attachment
											echo("Record Attachment State: " . $attachment->getState() . "\n");

											//Get the ID of each attachment
											echo("Record Attachment ID: " . $attachment->getId() . "\n");

											//Get the createdBy User instance of each attachment
											$createdBy = $attachment->getCreatedBy();

											//Check if createdBy is not null
											if($createdBy != null)
											{
												//Get the name of the createdBy User
												echo("Record Attachment Created By User-Name: " . $createdBy->getName() . "\n");

												//Get the ID of the createdBy User
												echo("Record Attachment Created By User-ID: " . $createdBy->getId() . "\n");

												//Get the Email of the createdBy User
												echo("Record Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
											}

											//Get the linkUrl of each attachment
											echo("Record Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
										}
									}
									else if($value[0] instanceof ImageUpload)
									{
										$imageUploads = $value;

										foreach($imageUploads as $imageUpload)
										{
											echo("Record " . $keyName . " Description: " . $imageUpload->getDescription() . "\n");

											echo("Record " . $keyName . " PreviewId: " . $imageUpload->getPreviewId() . "\n");

											echo("Record " . $keyName . " File_Name: " . $imageUpload->getFileName() . "\n");

											echo("Record " . $keyName . " State: "); print_r($imageUpload->getState()); echo("\n");

											echo("Record " . $keyName . " Size: " . $imageUpload->getSize() . "\n");

											echo("Record " . $keyName . " SequenceNumber: " . $imageUpload->getSequenceNumber() . "\n");

											echo("Record " . $keyName . " Id: " . $imageUpload->getId() . "\n");

											echo("Record " . $keyName . " FileId: " . $imageUpload->getFileId() . "\n");
										}
									}
									else
									{
										echo($keyName . " : "); print_r($value); echo("\n");
									}
								}
								else if($value instanceof Layout)
								{
									$layout = $value;

									if($layout != null)
									{
										echo("Record " . $keyName. " ID: " . $layout->getId() . "\n");

										echo("Record " . $keyName . " Name: " . $layout->getName() . "\n");
									}
								}
								else if($value instanceof User)
								{
									$user = $value;

									if($user != null)
									{
										echo("Record " . $keyName . " User-ID: " . $user->getId() . "\n");

										echo("Record " . $keyName . " User-Name: " . $user->getName() . "\n");

										echo("Record " . $keyName . " User-Email: " . $user->getEmail() . "\n");
									}
								}
								else if($value instanceof $recordClass)
								{
									$recordValue = $value;

									echo("Record " . $keyName . " ID: " . $recordValue->getId() . "\n");

									echo("Record " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
								}
								else if($value instanceof Choice)
								{
									$choiceValue = $value;

									echo("Record " . $keyName . " : " . $choiceValue->getValue() . "\n");
								}
								else if($value instanceof RemindAt)
								{
									echo($keyName . ": " . $value->getAlarm() . "\n");
								}
								else if($value instanceof RecurringActivity)
								{
									echo($keyName . " : RRULE" . ": " . $value->getRrule() . "\n");
								}
								else if($value instanceof Consent)
								{
									$consent = $value;

									echo("Record Consent ID: " . $consent->getId());

									//Get the Owner User instance of each attachment
									$owner = $consent->getOwner();

									//Check if owner is not null
									if($owner != null)
									{
										//Get the name of the owner User
										echo("Record Consent Owner Name: " . $owner->getName());

										//Get the ID of the owner User
										echo("Record Consent Owner ID: " . $owner->getId());

										//Get the Email of the owner User
										echo("Record Consent Owner Email: " . $owner->getEmail());
									}

									$consentCreatedBy = $consent->getCreatedBy();

									//Check if createdBy is not null
									if($consentCreatedBy != null)
									{
										//Get the name of the CreatedBy User
										echo("Record Consent CreatedBy Name: " . $consentCreatedBy->getName());

										//Get the ID of the CreatedBy User
										echo("Record Consent CreatedBy ID: " . $consentCreatedBy->getId());

										//Get the Email of the CreatedBy User
										echo("Record Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
									}

									$consentModifiedBy = $consent->getModifiedBy();

									//Check if createdBy is not null
									if($consentModifiedBy != null)
									{
										//Get the name of the ModifiedBy User
										echo("Record Consent ModifiedBy Name: " . $consentModifiedBy->getName());

										//Get the ID of the ModifiedBy User
										echo("Record Consent ModifiedBy ID: " . $consentModifiedBy->getId());

										//Get the Email of the ModifiedBy User
										echo("Record Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
									}

									echo("Record Consent CreatedTime: " . $consent->getCreatedTime());

									echo("Record Consent ModifiedTime: " . $consent->getModifiedTime());

									echo("Record Consent ContactThroughEmail: " . $consent->getContactThroughEmail());

									echo("Record Consent ContactThroughSocial: " . $consent->getContactThroughSocial());

									echo("Record Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());

									echo("Record Consent ContactThroughPhone: " . $consent->getContactThroughPhone());

									echo("Record Consent MailSentTime: " . $consent->getMailSentTime());

									echo("Record Consent ConsentDate: " . $consent->getConsentDate());

									echo("Record Consent ConsentRemarks: " . $consent->getConsentRemarks());

									echo("Record Consent ConsentThrough: " . $consent->getConsentThrough());

									echo("Record Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());

									//To get custom values
									echo("Record Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
								}
								else
								{
									//Get each value in the map
									echo($keyName . " : "); print_r($value); echo("\n");
								}
							}
							else
							{
								//Get each value in the map
								echo($keyName . " : "); print_r($value); echo("\n");
							}
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

				//Get the details map
				foreach($exception->getDetails() as $key => $value)
				{
					//Get each value in the map
					echo($key . " : " . $value . "\n");
				}

				//Get the Message
				echo("Message: " . $exception->getMessage()->getValue() . "\n");
			}
		}
		else
		{
			print_r($response);
		}
	}

	/**
	 * <h3> Convert Lead</h3>
	 * This method is used to Convert Lead record of a module and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @param recordId - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function convertLead(string $recordId)
	{
		//API Name of the module to update record
		//$recordId = "02034003";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ConvertBodyWrapper Class that will contain the request body
		$request = new ConvertBodyWrapper();

		//List of LeadConverter instances
		$data = array();

		//Get instance of LeadConverter Class
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

		//Add Record instance to the list
		array_push($data, $record1);

		//Set the list to Records in BodyWrapper instance
		$request->setData($data);

		//Call updateRecord method that takes BodyWrapper instance, ModuleAPIName and recordId as parameter.
		$response = $recordOperations->convertLead($recordId,$request );

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$convertActionHandler = $response->getObject();

				if($convertActionHandler instanceof ConvertActionWrapper)
				{
					//Get the received ConvertActionWrapper instance
					$convertActionWrapper = $convertActionHandler;

					//Get the list of obtained ConvertActionResponse instances
					$convertActionResponses = $convertActionWrapper->getData();

					foreach($convertActionResponses as $convertActionResponse)
					{
						//Check if the request is successful
						if($convertActionResponse instanceof SuccessfulConvert)
						{
							//Get the received SuccessfulConvert instance
							$successfulConvert = $convertActionResponse;

							//Get the Accounts ID of  Record
							echo("LeadConvert Accounts ID: " . $successfulConvert->getAccounts() . "\n");

							//Get the Contacts ID of  Record
							echo("LeadConvert Contacts ID: " . $successfulConvert->getContacts() . "\n");

							//Get the Deals ID of  Record
							echo("LeadConvert Deals ID: " . $successfulConvert->getDeals() . "\n");
						}
						//Check if the request returned an exception
						else if($convertActionResponse instanceof APIException)
						{
							//Get the received APIException instance
							$exception = $convertActionResponse;

							//Get the Status
							echo("Status: " . $exception->getStatus()->getValue() . "\n");

							//Get the Code
							echo("Code: " . $exception->getCode()->getValue() . "\n");

							if($exception->getDetails() != null)
							{
								echo("Details: " );

								//Get the details map
								foreach($exception->getDetails() as $key => $value)
								{
									//Get each value in the map
									echo($key . " : " . $value . "\n");
								}
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($convertActionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $convertActionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					if($exception->getDetails() != null)
					{
						echo("Details: " );

						//Get the details map
						foreach($exception->getDetails() as $key => $value)
						{
							//Get each value in the map
							echo($key . " : " . $value . "\n");
						}
					}
					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * This method is used to download a photo associated with a module.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @param recordId - The ID of the record to be obtained.
	 * @param destinationFolder - The absolute path of the destination folder to store the photo.
	 * @throws Exception
	 */
	public static function getPhoto(string $moduleAPIName, string $recordId, string $destinationFolder)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "34770615177002";
		//$destinationFolder = "/Users/user_name/Desktop";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Call downloadAttachment method that takes moduleAPIName and recordId as parameters
		$response = $recordOperations->getPhoto($recordId,$moduleAPIName );

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$downloadHandler = $response->getObject();

				if($downloadHandler instanceof FileBodyWrapper)
				{
					//Get object from response
					$fileBodyWrapper = $downloadHandler;

					//Get StreamWrapper instance from the returned FileBodyWrapper instance
					$streamWrapper = $fileBodyWrapper->getFile();

					//Create a file instance with the absolute_file_path
					$fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");

					//Get stream from the response
					$stream = $streamWrapper->getStream();

					fputs($fp, $stream);

					fclose($fp);
				}
				//Check if the request returned an exception
				else if($downloadHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $downloadHandler;

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
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * This method is used to attach a photo to a $record-> You must include the photo in the request with content type as multipart/form data.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @param recordId - The ID of the record to be obtained.
	 * @param absoluteFilePath - The absolute file path of the file to be uploads
	 * @throws Exception
	 */
	public static function uploadPhoto(string $moduleAPIName, string $recordId, string $absoluteFilePath)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "34770615177002";
		//$absoluteFilePath = "/Users/use_name/Desktop/image.png"

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of FileBodyWrapper class that will contain the request file
		$fileBodyWrapper = new FileBodyWrapper();

		//Get instance of StreamWrapper class that takes absolute path of the file to be attached as parameter
		$streamWrapper = new StreamWrapper(null, null, $absoluteFilePath);

		//Set file to the FileBodyWrapper instance
		$fileBodyWrapper->setFile($streamWrapper);

		//Call uploadPhoto method that takes FileBodyWrapper instance, moduleAPIName and recordId as parameter
		$response = $recordOperations->uploadPhoto($recordId, $moduleAPIName,$fileBodyWrapper);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$fileHandler = $response->getObject();

				//Check if the request is successful
				if($fileHandler instanceof SuccessResponse)
				{
					//Get the received SuccessResponse instance
					$successResponse = $fileHandler;

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
				else if($fileHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $fileHandler;

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
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * This method is used to delete a photo from a record in a module.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @param recordId - The ID of the record to be obtained.
	 * @throws Exception
	 */
	public static function deletePhoto(string $moduleAPIName, string $recordId)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "34770615177002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Call getAttachments method that takes moduleAPIName and recordId as parameter
		$response = $recordOperations->deletePhoto($recordId,$moduleAPIName);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$fileHandler = $response->getObject();

				//Check if the request is successful
				if($fileHandler instanceof SuccessResponse)
				{
					//Get the received SuccessResponse instance
					$successResponse = $fileHandler;

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
				else if($fileHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $fileHandler;

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
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * This method is used to update the values of specific fields for multiple records and print the response.
	 * @param moduleAPIName - The API Name of the module to obtain records.
	 * @throws Exception
	 */
	public static function massUpdateRecords(string $moduleAPIName)
	{
		//API Name of the module to massUpdateRecords
		//$moduleAPIName = "module_api_name";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of MassUpdateBodyWrapper Class that will contain the request body
		$request = new MassUpdateBodyWrapper();

		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';

		//Get instance of Record Class
		$record1 = new $recordClass();

		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		$record1->addKeyValue("City", "Value");

		//Add Record instance to the list
		array_push($records, $record1);

		//Set the list to Records in BodyWrapper instance
		$request->setData($records);

		$request->setCvid("34770610087501");

		$ids = array("3477061012054037");

		$request->setIds($ids);

		// $territory = new Territory();

		// $territory->setId("34770613051357");

		// $territory->setIncludeChild(true);

		// $request->setTerritory($territory);

		$request->setOverWrite(true);

		//Call massUpdateRecords method that takes BodyWrapper instance, ModuleAPIName as parameter.
		$response = $recordOperations->massUpdateRecords($moduleAPIName, $request);

		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$massUpdateActionHandler = $response->getObject();

				if($massUpdateActionHandler instanceof MassUpdateActionWrapper)
				{
					//Get the received MassUpdateActionWrapper instance
					$massUpdateActionWrapper = $massUpdateActionHandler;

					//Get the list of obtained MassUpdateActionResponse instances
					$massUpdateActionResponses = $massUpdateActionWrapper->getData();

					foreach($massUpdateActionResponses as $massUpdateActionResponse)
					{
						//Check if the request is successful
						if($massUpdateActionResponse instanceof MassUpdateSuccessResponse)
						{
							//Get the received MassUpdateSuccessResponse instance
							$massUpdateSuccessResponse = $massUpdateActionResponse;

							//Get the Status
							echo("Status: " . $massUpdateSuccessResponse->getStatus()->getValue() . "\n");

							//Get the Code
							echo("Code: " . $massUpdateSuccessResponse->getCode()->getValue() . "\n");

							if($massUpdateSuccessResponse->getDetails() != null)
							{
								echo("Details: " );

								//Get the details map
								foreach ($massUpdateSuccessResponse->getDetails() as $keyName => $keyValue)
								{
									//Get each value in the map
									echo($keyName . ": ");

									print_r($keyValue);

									echo("\n");
								}
							}

							//Get the Message
							echo("Message: " . $massUpdateSuccessResponse->getMessage()->getValue() . "\n");
						}
						//Check if the request returned an exception
						else if($massUpdateActionResponse instanceof APIException)
						{
							//Get the received APIException instance
							$exception = $massUpdateActionResponse;

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
				//Check if the request returned an exception
				else if($massUpdateActionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $massUpdateActionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					if($exception->getDetails() != null)
					{
						echo("Details: " );

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
			else
			{
				print_r($response);
			}
		}
	}

	/**
	 * This method is used to get the status of the mass update job scheduled previously and print the response.
	 * @param moduleAPIName- The API Name of the module to obtain records.
	 * @param jobId - The ID of the job from the response of Mass Update Records.
	 * @throws Exception
	 */
	public static function getMassUpdateStatus(string $moduleAPIName, string $jobId)
	{
		//example
		//$moduleAPIName = "module_api_name";
		//$recordId = "34770615177002";

		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();

		//Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

		$paramInstance->add(GetMassUpdateStatusParam::jobId(), $jobId);

		//Call getRecord method that takes paramInstance, moduleAPIName as parameter
		$response = $recordOperations->getMassUpdateStatus($moduleAPIName,$paramInstance);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$massUpdateResponseHandler = $response->getObject();

				if($massUpdateResponseHandler instanceof MassUpdateResponseWrapper)
				{
					//Get the received MassUpdateResponseWrapper instance
					$massUpdateResponseWrapper = $massUpdateResponseHandler;

					//Get the list of obtained MassUpdateResponse instances
					$massUpdateResponses = $massUpdateResponseWrapper->getData();

					foreach($massUpdateResponses as $massUpdateResponse)
					{
						//Check if the request is successful
						if($massUpdateResponse instanceof MassUpdate)
						{
							//Get the received MassUpdate instance
							$massUpdate = $massUpdateResponse;

							//Get the Status of each MassUpdate
							echo("MassUpdate Status: " . $massUpdate->getStatus()->getValue() . "\n");

							//Get the FailedCount of each MassUpdate
							echo("MassUpdate FailedCount: " . $massUpdate->getFailedCount() . "\n");

							//Get the UpdatedCount of each MassUpdate
							echo("MassUpdate UpdatedCount: " . $massUpdate->getUpdatedCount() . "\n");

							//Get the NotUpdatedCount of each MassUpdate
							echo("MassUpdate NotUpdatedCount: " . $massUpdate->getNotUpdatedCount() . "\n");

							//Get the TotalCount of each MassUpdate
							echo("MassUpdate TotalCount: " . $massUpdate->getTotalCount() . "\n");

						}
						//Check if the request returned an exception
						else if($massUpdateResponse instanceof APIException)
						{
							//Get the received APIException instance
							$exception = $massUpdateResponse;

							//Get the Status
							echo("Status: " . $exception->getStatus()->getValue() . "\n");

							//Get the Code
							echo("Code: " . $exception->getCode()->getValue() . "\n");

							if($exception->getDetails() != null)
							{
								echo("Details: " );

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
				//Check if the request returned an exception
				else if($massUpdateResponseHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $massUpdateResponseHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					if($exception->getDetails() != null)
					{
						echo("Details: " );

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
			else
			{
				print_r($response);
			}
		}
	}

	public static function getRecordCount()
	{
		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();
		
		$moduleAPIName = "Leads";
				
		$paramInstance = new ParameterMap();
		
		$paramInstance->add(RecordCountParam::phone(), "(990) -0");
		
		$response = $recordOperations->recordCount($moduleAPIName, $paramInstance);
		
		if($response != null)
		{
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
			}

			if($response->isExpected())
			{
				//Get object from response
				$countHandler = $response->getObject();
				
				if($countHandler instanceof CountWrapper)
				{
					//Get the received CountWrapper instance
					$countWrapper = $countHandler;
					
					//Get the Count of Tag
					echo("Record Count: " . $countWrapper->getCount());
				}
				//Check if the request returned an exception
				else if($countHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $countHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{//If response is not as expected
				
				print_r($response);
			}
		}
	}
	
	public static function assignTerritoriesToMultipleRecords($moduleAPIName)
	{
		//API Name of the module to assignTerritoriesToMultipleRecords
		//$moduleAPIName = "Leads";
		
		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();
		
		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();
		
		//List of Record instances
		$records = [];

		$recordClass = 'com\zoho\crm\api\record\Record';
		
		//Get instance of Record Class
		$record1 = new $recordClass();
		
		$record1->setId("3477061012107002");
	
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		
		$territory = new Territory();
		
		$territory->setId("34770613051397");
		
		$record1->addKeyValue("Territories", [$territory]);
		
		//Add Record instance to the list
		array_push($records, $record1);
		
		//Set the list to Records in BodyWrapper instance
		$request->setData($records);
		
		//Call assignTerritoriesToMultipleRecords method that takes ModuleAPIName and  BodyWrapper instance as parameter.
		$response = $recordOperations->assignTerritoriesToMultipleRecords($moduleAPIName, $request);
		
		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();
				
				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ActionWrapper instance
					$actionWrapper = $actionHandler;
					
					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();
					
					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{//If response is not as expected
				
				print_r($response);
			}
		}
	}

	public static function assignTerritoryToRecord($moduleAPIName, $id)
	{
		//API Name of the module to assignTerritoryToRecord
		//$moduleAPIName = "Leads";
		
		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();
		
		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();
		
		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';
		
		//Get instance of Record Class
		$record1 = new $recordClass();
		
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		
		$territory = new Territory();
		
		$territory->setId("34770613051397");
		
		$record1->addKeyValue("Territories", [$territory]);
		
		//Add Record instance to the list
		array_push($records, $record1);
		
		//Set the list to Records in BodyWrapper instance
		$request->setData($records);
		
		//Call assignTerritoryToRecord method that takes ModuleAPIName, id and  BodyWrapper instance as parameter.
		$response = $recordOperations->assignTerritoryToRecord($moduleAPIName, $id, $request);
		
		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}
	
	public static function removeTerritoriesFromMultipleRecords($moduleAPIName)
	{
		//example
		//$moduleAPIName = "Leads";
		//$recordId = 34770615177002L;
		
		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();
		
		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();
		
		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';
		
		//Get instance of Record Class
		$record1 = new $recordClass();
		
		$record1->setId("3477061012107002");
	
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		
		$territory = new Territory();
		
		$territory->setId("34770613051397");
		
		$record1->addKeyValue("Territories", [$territory]);
		
		//Add Record instance to the list
		array_push($records, $record1);
		
		//Set the list to Records in BodyWrapper instance
		$request->setData($records);
		
		//Call removeTerritoriesFromMultipleRecords method that takes moduleAPIName and BodyWrapper instance as parameter
		$response = $recordOperations->removeTerritoriesFromMultipleRecords($moduleAPIName, $request);
		
		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
					echo("Message: " . $exception->getMessage()->getValue() . "\n");
				}
			}
			else
			{
				print_r($response);
			}
		}
	}
	
	public static function removeTerritoriesFromRecord($moduleAPIName, $id)
	{
		//example
		//$moduleAPIName = "Leads";
		//$recordId = 34770615177002L;
		
		//Get instance of RecordOperations Class
		$recordOperations = new RecordOperations();
		
		//Get instance of BodyWrapper Class that will contain the request body
		$request = new BodyWrapper();
		
		//List of Record instances
		$records = array();

		$recordClass = 'com\zoho\crm\api\record\Record';
		
		//Get instance of Record Class
		$record1 = new $recordClass();
	
		/*
		 * Call addKeyValue method that takes two arguments
		 * 1 -> A string that is the Field's API Name
		 * 2 -> Value
		 */
		
		$territory = new Territory();
		
		$territory->setId("34770613051397");
		
		$record1->addKeyValue("Territories", [$territory]);
		
		//Add Record instance to the list
		array_push($records, $record1);
		
		//Set the list to Records in BodyWrapper instance
		$request->setData($records);
		
		//Call removeTerritoriesFromRecord method that takes moduleAPIName, recordId and BodyWrapper instance as parameter
		$response = $recordOperations->removeTerritoriesFromRecord($moduleAPIName, $id, $request);
		
		if($response != null)
		{
			//Get the status code from response
			echo("Status Code: " . $response->getStatusCode() . "\n");

			if($response->isExpected())
			{
				//Get object from response
				$actionHandler = $response->getObject();

				if($actionHandler instanceof ActionWrapper)
				{
					//Get the received ResponseWrapper instance
					$actionWrapper = $actionHandler;

					//Get the list of obtained ActionResponse instances
					$actionResponses = $actionWrapper->getData();

					foreach($actionResponses as $actionResponse)
					{
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

							//Get the details map
							foreach($successResponse->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : ");

								print_r($value);

								echo("\n");
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

							//Get the details map
							foreach($exception->getDetails() as $key => $value)
							{
								//Get each value in the map
								echo($key . " : " . $value . "\n");
							}

							//Get the Message
							echo("Message: " . $exception->getMessage()->getValue() . "\n");
						}
					}
				}
				//Check if the request returned an exception
				else if($actionHandler instanceof APIException)
				{
					//Get the received APIException instance
					$exception = $actionHandler;

					//Get the Status
					echo("Status: " . $exception->getStatus()->getValue() . "\n");

					//Get the Code
					echo("Code: " . $exception->getCode()->getValue() . "\n");

					echo("Details: " );

					//Get the details map
					foreach($exception->getDetails() as $key => $value)
					{
						//Get each value in the map
						echo($key . " : " . $value . "\n");
					}

					//Get the Message
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