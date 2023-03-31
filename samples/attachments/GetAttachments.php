<?php
namespace samples\attachments;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\attachments\AttachmentsOperations;
use com\zoho\crm\api\attachments\ResponseWrapper;
use com\zoho\crm\api\attachments\APIException;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\attachments\GetAttachmentsParam;
require_once "vendor/autoload.php";

class GetAttachments
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

    public static function getAttachments(string $moduleAPIName, string $recordId)
    {
        $attachmentOperations = new AttachmentsOperations($moduleAPIName, $recordId);
        $paramInstance = new ParameterMap();
        // $paramInstance->add(GetAttachmentsParam::fields(), "id,Modified_Time");
        $paramInstance->add(GetAttachmentsParam::page(), 1);
        $paramInstance->add(GetAttachmentsParam::perPage(), 100);
        $response = $attachmentOperations->getAttachments($paramInstance);
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
                $attachments = $responseWrapper->getData();
                foreach ($attachments as $attachment)
                {
                    $owner = $attachment->getOwner();
                    if($owner != null)
                    {
                        echo("Attachment Owner User-Name: " . $owner->getName() . "\n");
                        echo("Attachment Owner User-ID: " . $owner->getId() . "\n");
                        echo("Attachment Owner User-Email: " . $owner->getEmail() . "\n");
                    }
                    echo("Attachment Modified Time: "); print_r($attachment->getModifiedTime()); echo("\n");
                    echo("Attachment File Name: " . $attachment->getFileName() . "\n");
                    echo("Attachment Created Time: " ); print_r($attachment->getCreatedTime()); echo("\n");
                    echo("Attachment File Size: " . $attachment->getSize() . "\n");
                    $parentId = $attachment->getParentId();
                    if($parentId != null)
                    {
                        echo("Attachment parent record Name: " . $parentId->getKeyValue("name") . "\n");
                        echo("Attachment parent record ID: " . $parentId->getId() . "\n");
                    }
                    echo("Attachment is Editable: " . $attachment->getEditable() . "\n");
                    echo("Attachment SharingPermission: " . $attachment->getSharingPermission() . "\n");
                    echo("Attachment File ID: " . $attachment->getFileId() . "\n");
                    echo("Attachment File Type: " . $attachment->getType() . "\n");
                    echo("Attachment seModule: " . $attachment->getSeModule() . "\n");
                    $modifiedBy = $attachment->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("Attachment Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo("Attachment Modified By User-ID: " . $modifiedBy->getId() . "\n");
                        echo("Attachment Modified By User-Email: " . $modifiedBy->getEmail() . "\n");
                    }
                    echo("Attachment Type: " . $attachment->getAttachmentType() . "\n");
                    echo("Attachment State: " . $attachment->getState() . "\n");
                    echo("Attachment ID: " . $attachment->getId() . "\n");
                    $createdBy = $attachment->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("Attachment Created By User-Name: " . $createdBy->getName() . "\n");
                        echo("Attachment Created By User-ID: " . $createdBy->getId() . "\n");
                        echo("Attachment Created By User-Email: " . $createdBy->getEmail() . "\n");
                    }
                    echo("Attachment LinkUrl: " . $attachment->getLinkUrl() . "\n");
                }
                $info = $responseWrapper->getInfo();
                echo("Attachment Info PerPage : " . $info->getPerPage() . "\n");
                echo("Attachment Info Count : " . $info->getCount() . "\n");
                echo("Attachment Info Page : " . $info->getPage(). "\n");
                echo("Attachment Info MoreRecords : "); print_r($info->getMoreRecords()); echo("\n");
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
}

GetAttachments::initialize();
$moduleAPIName = "Leads";
$recordId = "347706111829018";
GetAttachments::getAttachments($moduleAPIName, $recordId);
