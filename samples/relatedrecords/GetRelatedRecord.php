<?php
namespace samples\relatedrecords;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\layouts\Layout;
use com\zoho\crm\api\record\FileDetails;
use com\zoho\crm\api\record\LineTax;
use com\zoho\crm\api\record\PricingDetails;
use com\zoho\crm\api\relatedrecords\APIException;
use com\zoho\crm\api\relatedrecords\RelatedRecordsOperations;
use com\zoho\crm\api\relatedrecords\GetRelatedRecordHeader;
use com\zoho\crm\api\relatedrecords\ResponseWrapper;
use com\zoho\crm\api\tags\Tag;
use com\zoho\crm\api\users\User;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\record\Consent;
use com\zoho\crm\api\record\Comment;
use com\zoho\crm\api\record\Participants;
use com\zoho\crm\api\relatedrecords\FileBodyWrapper;
use com\zoho\crm\api\attachments\Attachment;
use com\zoho\crm\api\record\ImageUpload;
require_once "vendor/autoload.php";

class GetRelatedRecord
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

	public static function getRelatedRecord(string $moduleAPIName, string $recordId, string $relatedListAPIName, string $relatedListId, string $destinationFolder)
	{
	    $relatedRecordsOperations = new RelatedRecordsOperations($relatedListAPIName, $moduleAPIName);
		$headerInstance = new HeaderMap();
		$ifmodifiedsince = date_create("2020-07-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$headerInstance->add(GetRelatedRecordHeader::IfModifiedSince(), $ifmodifiedsince);
		$response = $relatedRecordsOperations->getRelatedRecord($relatedListId, $recordId, $headerInstance);
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
				if($responseHandler instanceof ResponseWrapper)
				{
					$responseWrapper = $responseHandler;
					$records = $responseWrapper->getData();
					foreach($records as $record)
					{
						echo("RelatedRecord ID: " . $record->getId() . "\n");
						$createdBy = $record->getCreatedBy();
						if($createdBy != null)
						{
							echo("RelatedRecord Created By User-ID: " . $createdBy->getId() . "\n");
							echo("RelatedRecord Created By User-Name: " . $createdBy->getName() . "\n");
							echo("RelatedRecord Created By User-Email: " . $createdBy->getEmail() . "\n");
						}
						echo("RelatedRecord CreatedTime: ");
						print_r($record->getCreatedTime());
						echo("\n");
						$modifiedBy = $record->getModifiedBy();
						if($modifiedBy != null)
						{
							echo("RelatedRecord Modified By User-ID: " . $modifiedBy->getId() . "\n");
							echo("RelatedRecord Modified By User-Name: " . $modifiedBy->getName() . "\n");
							echo("RelatedRecord Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
						}
						echo("RelatedRecord ModifiedTime: ");
						print_r($record->getModifiedTime());
						echo("\n");
						$tags = $record->getTag();
						if($tags != null)
						{
							foreach($tags as $tag)
							{
								echo("RelatedRecord Tag Name: " . $tag->getName() . "\n");
								echo("RelatedRecord Tag ID: " . $tag->getId() . "\n");
							}
						}
						//To get particular field value
						echo("RelatedRecord Field Value: " . $record->getKeyValue("Last_Name") . "\n");// FieldApiName
						echo("RelatedRecord KeyValues : \n" );
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
											echo("RelatedRecord FileDetails Extn: " . $fileDetail->getExtn() . "\n");
											echo("RelatedRecord FileDetails IsPreviewAvailable: " . $fileDetail->getIsPreviewAvailable() . "\n");
											echo("RelatedRecord FileDetails DownloadUrl: " . $fileDetail->getDownloadUrl() . "\n");
											echo("RelatedRecord FileDetails DeleteUrl: " . $fileDetail->getDeleteUrl() . "\n");
											echo("RelatedRecord FileDetails EntityId: " . $fileDetail->getEntityId() . "\n");
											echo("RelatedRecord FileDetails Mode: " . $fileDetail->getMode() . "\n");
											echo("RelatedRecord FileDetails OriginalSizeByte: " . $fileDetail->getOriginalSizeByte() . "\n");
											echo("RelatedRecord FileDetails PreviewUrl: " . $fileDetail->getPreviewUrl() . "\n");
											echo("RelatedRecord FileDetails FileName: " . $fileDetail->getFileName() . "\n");
											echo("RelatedRecord FileDetails FileId: " . $fileDetail->getFileId() . "\n");
											echo("RelatedRecord FileDetails AttachmentId: " . $fileDetail->getAttachmentId() . "\n");
											echo("RelatedRecord FileDetails FileSize: " . $fileDetail->getFileSize() . "\n");
											echo("RelatedRecord FileDetails CreatorId: " . $fileDetail->getCreatorId() . "\n");
											echo("RelatedRecord FileDetails LinkDocs: " . $fileDetail->getLinkDocs() . "\n");
										}
									}
									else if($value[0] instanceof Tag)
									{
										$tagList = $value;
										foreach($tagList as $tag)
										{
											echo("RelatedRecord Tag Name: " . $tag->getName() . "\n");
											echo("RelatedRecord Tag ID: " . $tag->getId() . "\n");
										}
									}
									else if($value[0] instanceof PricingDetails)
									{
										$pricingDetails = $value;
										foreach($pricingDetails as $pricingDetail)
										{
											echo("RelatedRecord PricingDetails ToRange: " . $pricingDetail->getToRange(). "\n");
											echo("RelatedRecord PricingDetails Discount: " . $pricingDetail->getDiscount(). "\n");
											echo("RelatedRecord PricingDetails ID: " . $pricingDetail->getId() . "\n");
											echo("RelatedRecord PricingDetails FromRange: " . $pricingDetail->getFromRange(). "\n");
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
									else if($value[0] instanceof Record)
									{
										$recordList = $value;
										foreach($recordList as $record1)
										{
											foreach($record1->getKeyValues() as $key => $value1)
											{
												echo($key . " : " );
												print_r($value1);
												echo("\n");
											}
										}
									}
									else if($value[0] instanceof LineTax)
									{
										$lineTaxes = $value;
										foreach($lineTaxes as $lineTax)
										{
											echo("RelatedRecord ProductDetails LineTax Percentage: " . $lineTax->getPercentage(). "\n");
											echo("RelatedRecord ProductDetails LineTax Name: " . $lineTax->getName() . "\n");
											echo("RelatedRecord ProductDetails LineTax Id: " . $lineTax->getId() . "\n");
											echo("RelatedRecord ProductDetails LineTax Value: " . $lineTax->getValue(). "\n");
										}
									}
									else if($value[0] instanceof Choice)
									{
										$choice = $value;
										foreach($choice as $choiceValue)
										{
											echo("RelatedRecord " . $keyName . " : " . $choiceValue->getValue() . "\n");
										}
									}
									else if($value[0] instanceof Comment)
									{
										$comments = $value;
										foreach($comments as $comment)
										{
											echo("Record Comment CommentedBy: " . $comment->getCommentedBy() . "\n");
											echo("Record Comment CommentedTime: ");
											print_r($comment->getCommentedTime());
											echo("\n");
											echo("Record Comment CommentContent: " . $comment->getCommentContent(). "\n");
											echo("Record Comment Id: " . $comment->getId() . "\n");
										}
									}
									else if($value[0] instanceof Attachment)
									{
										$attachments = $value;
										foreach ($attachments as $attachment)
										{
											$owner = $attachment->getOwner();
											if($owner != null)
											{
												echo("RelatedRecord Attachment Owner User-Name: " . $owner->getName() . "\n");
												echo("RelatedRecord Attachment Owner User-ID: " . $owner->getId() . "\n");
												echo("RelatedRecord Attachment Owner User-Email: " . $owner->getEmail() . "\n");
											}
											echo("RelatedRecord Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");
											echo("RelatedRecord Attachment File Name: " . $attachment->getFileName() . "\n");
											echo("RelatedRecord Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");
											echo("RelatedRecord Attachment File Size: " . $attachment->getSize() . "\n");
											$parentId = $attachment->getParentId();
											if($parentId != null)
											{
												echo("RelatedRecord Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");
												echo("RelatedRecord Attachment parent record ID: " . $parentId->getId() . "\n");
											}
											echo("RelatedRecord Attachment is Editable: " . $attachment->getEditable() . "\n");
											echo("RelatedRecord Attachment File ID: " . $attachment->getFileId() . "\n");
											echo("RelatedRecord Attachment File Type: " . $attachment->getType() . "\n");
											echo("RelatedRecord Attachment seModule: " . $attachment->getSeModule() . "\n");
											$modifiedBy = $attachment->getModifiedBy();
											if($modifiedBy != null)
											{
												echo("RelatedRecord Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");
												echo("RelatedRecord Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");
												echo("RelatedRecord Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
											}
											echo("RelatedRecord Attachment State: " . $attachment->getState() . "\n");
											echo("RelatedRecord Attachment ID: " . $attachment->getId() . "\n");
											$createdBy = $attachment->getCreatedBy();
											if($createdBy != null)
											{
												echo("RelatedRecord Attachment Created By User-Name: " . $createdBy->getName() . "\n");
												echo("RelatedRecord Attachment Created By User-ID: " . $createdBy->getId() . "\n");
												echo("RelatedRecord Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
											}
											echo("RelatedRecord Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
										}
									}
									else if($value[0] instanceof ImageUpload)
									{
										$imageUploads = $value;
										foreach($imageUploads as $imageUpload)
										{
											echo("RelatedRecord " . $keyName . " Description: " . $imageUpload->getDescription() . "\n");
											echo("RelatedRecord " . $keyName . " PreviewId: " . $imageUpload->getPreviewId() . "\n");
											echo("RelatedRecord " . $keyName . " File_Name: " . $imageUpload->getFileName() . "\n");
											echo("RelatedRecord " . $keyName . " State: "); print_r($imageUpload->getState()); echo("\n");
											echo("RelatedRecord " . $keyName . " Size: " . $imageUpload->getSize() . "\n");
											echo("RelatedRecord " . $keyName . " SequenceNumber: " . $imageUpload->getSequenceNumber() . "\n");
											echo("RelatedRecord " . $keyName . " Id: " . $imageUpload->getId() . "\n");
											echo("RelatedRecord " . $keyName . " FileId: " . $imageUpload->getFileId() . "\n");
										}
									}
									else
									{
										echo($keyName . " : "); print_r($value); echo("\n");
									}
								}
								else if($value instanceof Record)
								{
									$recordValue = $value;
									echo("RelatedRecord " . $keyName . " ID: " . $recordValue->getId() . "\n");
									echo("RelatedRecord " . $keyName . " Name: " . $recordValue->getKeyValue("name") . "\n");
								}
								else if($value instanceof Layout)
								{
									$layout = $value;
									if($layout != null)
									{
										echo("RelatedRecord " . $keyName. " ID: " . $layout->getId() . "\n");
										echo("RelatedRecord " . $keyName . " Name: " . $layout->getName() . "\n");
									}
								}
								else if($value instanceof User)
								{
									$user = $value;
									if($user != null)
									{
										echo("RelatedRecord " . $keyName . " User-ID: " . $user->getId() . "\n");
										echo("RelatedRecord " . $keyName . " User-Name: " . $user->getName() . "\n");
										echo("RelatedRecord " . $keyName . " User-Email: " . $user->getEmail() . "\n");
									}
								}
								else if($value instanceof Choice)
								{
									$choiceValue = $value;
									echo("RelatedRecord " . $keyName . " : " . $choiceValue->getValue() . "\n");
								}
								else if($value instanceof Consent)
								{
									$consent = $value;
									echo("RelatedRecord Consent ID: " . $consent->getId());
									$owner = $consent->getOwner();
									if($owner != null)
									{
										echo("RelatedRecord Consent Owner Name: " . $owner->getName());
										echo("RelatedRecord Consent Owner ID: " . $owner->getId());
										echo("RelatedRecord Consent Owner Email: " . $owner->getEmail());
									}
									$consentCreatedBy = $consent->getCreatedBy();
									if($consentCreatedBy != null)
									{
										echo("RelatedRecord Consent CreatedBy Name: " . $consentCreatedBy->getName());
										echo("RelatedRecord Consent CreatedBy ID: " . $consentCreatedBy->getId());
										echo("RelatedRecord Consent CreatedBy Email: " . $consentCreatedBy->getEmail());
									}
									$consentModifiedBy = $consent->getModifiedBy();
									if($consentModifiedBy != null)
									{
										echo("RelatedRecord Consent ModifiedBy Name: " . $consentModifiedBy->getName());
										echo("RelatedRecord Consent ModifiedBy ID: " . $consentModifiedBy->getId());
										echo("RelatedRecord Consent ModifiedBy Email: " . $consentModifiedBy->getEmail());
									}
									echo("RelatedRecord Consent CreatedTime: " . $consent->getCreatedTime());
									echo("RelatedRecord Consent ModifiedTime: " . $consent->getModifiedTime());
									echo("RelatedRecord Consent ContactThroughEmail: " . $consent->getContactThroughEmail());
									echo("RelatedRecord Consent ContactThroughSocial: " . $consent->getContactThroughSocial());
									echo("RelatedRecord Consent ContactThroughSurvey: " . $consent->getContactThroughSurvey());
									echo("RelatedRecord Consent ContactThroughPhone: " . $consent->getContactThroughPhone());
									echo("RelatedRecord Consent MailSentTime: " . $consent->getMailSentTime());
									echo("RelatedRecord Consent ConsentDate: " . $consent->getConsentDate());
									echo("RelatedRecord Consent ConsentRemarks: " . $consent->getConsentRemarks());
									echo("RelatedRecord Consent ConsentThrough: " . $consent->getConsentThrough());
									echo("RelatedRecord Consent DataProcessingBasis: " . $consent->getDataProcessingBasis());
									//To get custom values
									echo("RelatedRecord Consent Lawful Reason: " . $consent->getKeyValue("Lawful_Reason"));
								}
								else
								{
									echo($keyName . " : "); print_r($value); echo("\n");
								}
							}
						}
					}
				}
				else if($responseHandler instanceof FileBodyWrapper)
				{
					$fileBodyWrapper = $responseHandler;
					$streamWrapper = $fileBodyWrapper->getFile();
					//Create a file instance with the absolute_file_path
					$fp = fopen($destinationFolder."/".$streamWrapper->getName(), "w");
					$stream = $streamWrapper->getStream();
					fputs($fp, $stream);
					fclose($fp);
				}
				else if($responseHandler instanceof APIException)
				{
					$exception = $responseHandler;
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

GetRelatedRecord::initialize();
$moduleAPIName = "Leads";
$recordId = "347706112109001";
$relatedListAPIName = "products";
$relatedRecordId = "347706110697001";
$destinationFolder =  "/Documents/";
GetRelatedRecord::getRelatedRecord($moduleAPIName, $recordId, $relatedListAPIName, $relatedRecordId, $destinationFolder);