<?php
namespace samples\emailtemplates;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\emailtemplates\EmailTemplatesOperations;
use com\zoho\crm\api\emailtemplates\ResponseWrapper;
use com\zoho\crm\api\emailtemplates\APIException;
require_once "vendor/autoload.php";

class GetEmailTemplateById
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

    public static function getEmailTemplateById(string $Id)
    {
       $emailTemplatesOperations = new EmailTemplatesOperations();
       $response = $emailTemplatesOperations->getEmailTemplateById($Id);
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
               $emailTemplates = $responseWrapper->getEmailTemplates();
               foreach($emailTemplates as $emailTemplate)
               {
                    echo("EmailTemplate CreatedTime: "); print_r($emailTemplate->getCreatedTime()); echo("\n");
                    $attachments = $emailTemplate->getAttachments();
                    if($attachments != null)
                    {
                        foreach($attachments as $attachment)
                        {
                            echo("Attachment Size: " . $attachment->getSize() . "\n");
                            echo("Attachment FileId: " . $attachment->getFileId() . "\n");
                            echo("Attachment FileName: " . $attachment->getFileName() . "\n");
                            echo("Attachment ID: " . $attachment->getId() . "\n");
                        }
                    }
                    echo("EmailTemplate Subject: " . $emailTemplate->getSubject() . "\n");
                    $module = $emailTemplate->getModule();
                    if($module != null)
                    {
                        echo("EmailTemplate Module Name : " . $module->getAPIName() . "\n");
                        echo("EmailTemplate Module Id : " . $module->getId() . "\n");
                    }
                    echo("EmailTemplate Type: " . $emailTemplate->getType() . "\n");
                    $createdBy = $emailTemplate->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("EmailTemplate Created By User-ID: " . $createdBy->getId(). "\n");
                        echo("EmailTemplate Created By user-Name: " . $createdBy->getName(). "\n");
                    }
                    echo("EmailTemplate Content: " . $emailTemplate->getContent() . "\n");
                    $folder = $emailTemplate->getFolder();
                    if($folder != null)
                    {
                        echo("EmailTemplate Folder Id: " . $folder->getId(). "\n");
                        echo("EmailTemplate Folder Name: " . $folder->getName(). "\n");
                    }
                    echo("EmailTemplate ModifiedTime: "); print_r($emailTemplate->getModifiedTime()); echo("\n");
                    echo("EmailTemplate LastUsageTime: "); print_r($emailTemplate->getLastUsageTime()); echo("\n");
                    echo("EmailTemplate Associated: "); print_r($emailTemplate->getAssociated()); echo("\n");
                    echo("EmailTemplate Name: " . $emailTemplate->getName() . "\n");
                    $modifiedBy = $emailTemplate->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("EmailTemplate Modified By User-ID: " . $modifiedBy->getId(). "\n");
                        echo("EmailTemplate Modified By User-Name: " . $modifiedBy->getName(). "\n");
                    }
                    echo("EmailTemplate ConsentLinked: "); print_r($emailTemplate->getConsentLinked()); echo("\n");
                    echo("EmailTemplate Id: " . $emailTemplate->getId() . "\n");
                    echo("EmailTemplate EditorMode: " . $emailTemplate->getEditorMode() . "\n");
                    echo("EmailTemplate Favorite: "); print_r($emailTemplate->getFavorite()); echo("\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
                echo("Status: " . $exception->getStatus()->getValue());
                echo("Code: " . $exception->getCode()->getValue());
                echo("Details: " );
                foreach($exception->getDetails() as $key => $value)
                {
                    echo($key . ": " . $value);
                }
                echo("Message: " . $exception->getMessage()->getValue());
            }
        }
    }
}
GetEmailTemplateById::initialize();
$id = "347706179";
GetEmailTemplateById::getEmailTemplateById($id);
?>