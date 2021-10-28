<?php
namespace com\zoho\crm\sample\emailtemplates;

use com\zoho\crm\api\emailtemplates\EmailTemplatesOperations;

use com\zoho\crm\api\emailtemplates\ResponseWrapper;

use com\zoho\crm\api\emailtemplates\APIException;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\emailtemplates\GetEmailTemplatesParam;

class EmailTemplate
{
    public static function getEmailTemplates(string $module)
    {
        //Get instance of EmailTemplatesOperations Class
        $emailTemplatesOperations = new EmailTemplatesOperations();

        //Get instance of ParameterMap Class
		$paramInstance = new ParameterMap();

        $paramInstance->add(GetEmailTemplatesParam::module(), $module);

        //Call getEmailTemplates method that takes ParameterMap instance as parameter
        $response = $emailTemplatesOperations->getEmailTemplates($paramInstance);

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

                //Get the list of obtained EmailTemplate instances
                $emailTemplates = $responseWrapper->getEmailTemplates();

                foreach($emailTemplates as $emailTemplate)
                {
                    //Get the CreatedTime of each EmailTemplate
                    echo("EmailTemplate CreatedTime: "); print_r($emailTemplate->getCreatedTime()); echo("\n");

                    $attachments = $emailTemplate->getAttachments();

                    if($attachments != null)
                    {
                        foreach($attachments as $attachment)
                        {
                            //Get the Size of each Attachment
                            echo("Attachment Size: " . $attachment->getSize() . "\n");

                            //Get the FileId of each Attachment
                            echo("Attachment FileId: " . $attachment->getFileId() . "\n");

                            //Get the FileName of each Attachment
                            echo("Attachment FileName: " . $attachment->getFileName() . "\n");

                            //Get the Id of each Attachment
                            echo("Attachment ID: " . $attachment->getId() . "\n");
                        }
                    }

                    //Get the Subject of each EmailTemplate
                    echo("EmailTemplate Subject: " . $emailTemplate->getSubject() . "\n");

                    $module = $emailTemplate->getModule();

                    if($module != null)
                    {
                        //Get the Module Name of the EmailTemplate
                        echo("EmailTemplate Module Name : " . $module->getAPIName() . "\n");

                        //Get the Module Id of the EmailTemplate
                        echo("EmailTemplate Module Id : " . $module->getId() . "\n");
                    }

                    //Get the Type of each EmailTemplate
                    echo("EmailTemplate Type: " . $emailTemplate->getType() . "\n");

                    //Get the CreatedBy User instance of each EmailTemplate
                    $createdBy = $emailTemplate->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Id of the CreatedBy User
                        echo("EmailTemplate Created By User-ID: " . $createdBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("EmailTemplate Created By user-Name: " . $createdBy->getName(). "\n");
                    }

                    //Get the ModifiedTime of each EmailTemplate
                    echo("EmailTemplate ModifiedTime: "); print_r($emailTemplate->getModifiedTime()); echo("\n");

                    //Get the Folder instance of each EmailTemplate
                    $folder = $emailTemplate->getFolder();

                    //Check if folder is not null
                    if($folder != null)
                    {
                        //Get the Id of the Folder
                        echo("EmailTemplate Folder Id: " . $folder->getId(). "\n");

                        //Get the Name of the Folder
                        echo("EmailTemplate Folder Name: " . $folder->getName(). "\n");
                    }

                    //Get the LastUsageTime of each EmailTemplate
                    echo("EmailTemplate LastUsageTime: "); print_r($emailTemplate->getLastUsageTime()); echo("\n");

                    //Get the Associated of each EmailTemplate
                    echo("EmailTemplate Associated: "); print_r($emailTemplate->getAssociated()); echo("\n");

                    //Get the name of each EmailTemplate
                    echo("EmailTemplate Name: " . $emailTemplate->getName() . "\n");

                    //Get the ConsentLinked of each EmailTemplate
                    echo("EmailTemplate ConsentLinked: "); print_r($emailTemplate->getConsentLinked()); echo("\n");

                    //Get the modifiedBy User instance of each EmailTemplate
                    $modifiedBy = $emailTemplate->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the ID of the ModifiedBy User
                        echo("EmailTemplate Modified By User-ID: " . $modifiedBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("EmailTemplate Modified By user-Name: " . $modifiedBy->getName(). "\n");
                    }

                    //Get the ID of each EmailTemplate
                    echo("EmailTemplate ID: " . $emailTemplate->getId() . "\n");

                    //Get the EditorMode each EmailTemplate
                    echo("EmailTemplate EditorMode: " . $emailTemplate->getEditorMode() . "\n");

                    //Get the Favorite of each EmailTemplate
                    echo("EmailTemplate Favorite: "); print_r($emailTemplate->getFavorite()); echo("\n");
                }

                $info = $responseWrapper->getInfo();

                echo("EmailTemplate Info PerPage : " . $info->getPerPage() . "\n");

                echo("EmailTemplate Info Count : " . $info->getCount() . "\n");

                echo("EmailTemplate Info Page : " . $info->getPage(). "\n");

                echo("EmailTemplate Info MoreRecords : "); print_r($info->getMoreRecords()); echo("\n");
            }
            //Check if the request returned an exception
            else if($responseHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $responseHandler;

                //Get the Status
                echo("Status: " . $exception->getStatus()->getValue());

                //Get the Code
                echo("Code: " . $exception->getCode()->getValue());

                echo("Details: " );

                //Get the details map
                foreach($exception->getDetails() as $key => $value)
                {
                    //Get each value in the map
                    echo($key . ": " . $value);
                }

                //Get the Message
                echo("Message: " . $exception->getMessage()->getValue());
            }
        }
    }

    public static function getEmailTemplateById(string $Id)
    {
       //Get instance of EmailTemplatesOperations Class
       $emailTemplatesOperations = new EmailTemplatesOperations();

       //Call getEmailTemplateById method that takes Id as parameter
       $response = $emailTemplatesOperations->getEmailTemplateById($Id);

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

               //Get the list of obtained EmailTemplate instances
               $emailTemplates = $responseWrapper->getEmailTemplates();

               foreach($emailTemplates as $emailTemplate)
               {
                    //Get the CreatedTime of each EmailTemplate
                    echo("EmailTemplate CreatedTime: "); print_r($emailTemplate->getCreatedTime()); echo("\n");

                    $attachments = $emailTemplate->getAttachments();

                    if($attachments != null)
                    {
                        foreach($attachments as $attachment)
                        {
                            //Get the Size of each Attachment
                            echo("Attachment Size: " . $attachment->getSize() . "\n");

                            //Get the FileId of each Attachment
                            echo("Attachment FileId: " . $attachment->getFileId() . "\n");

                            //Get the FileName of each Attachment
                            echo("Attachment FileName: " . $attachment->getFileName() . "\n");

                            //Get the Id of each Attachment
                            echo("Attachment ID: " . $attachment->getId() . "\n");
                        }
                    }

                    //Get the Subject of each EmailTemplate
                    echo("EmailTemplate Subject: " . $emailTemplate->getSubject() . "\n");

                    $module = $emailTemplate->getModule();

                    if($module != null)
                    {
                        //Get the Module Name of the EmailTemplate
                        echo("EmailTemplate Module Name : " . $module->getAPIName() . "\n");

                        //Get the Module Id of the EmailTemplate
                        echo("EmailTemplate Module Id : " . $module->getId() . "\n");
                    }

                    //Get the Type of each EmailTemplate
                    echo("EmailTemplate Type: " . $emailTemplate->getType() . "\n");

                    //Get the CreatedBy User instance of each EmailTemplate
                    $createdBy = $emailTemplate->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Id of the CreatedBy User
                        echo("EmailTemplate Created By User-ID: " . $createdBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("EmailTemplate Created By user-Name: " . $createdBy->getName(). "\n");
                    }

                    //Get the Content of each EmailTemplate
                    echo("EmailTemplate Content: " . $emailTemplate->getContent() . "\n");

                    //Get the Folder instance of each EmailTemplate
                    $folder = $emailTemplate->getFolder();

                    //Check if folder is not null
                    if($folder != null)
                    {
                        //Get the Id of the Folder
                        echo("EmailTemplate Folder Id: " . $folder->getId(). "\n");

                        //Get the Name of the Folder
                        echo("EmailTemplate Folder Name: " . $folder->getName(). "\n");
                    }

                    //Get the ModifiedTime of each EmailTemplate
                    echo("EmailTemplate ModifiedTime: "); print_r($emailTemplate->getModifiedTime()); echo("\n");

                    //Get the LastUsageTime of each EmailTemplate
                    echo("EmailTemplate LastUsageTime: "); print_r($emailTemplate->getLastUsageTime()); echo("\n");

                    //Get the Associated of each EmailTemplate
                    echo("EmailTemplate Associated: "); print_r($emailTemplate->getAssociated()); echo("\n");

                    //Get the name of each EmailTemplate
                    echo("EmailTemplate Name: " . $emailTemplate->getName() . "\n");

                    //Get the modifiedBy User instance of each EmailTemplate
                    $modifiedBy = $emailTemplate->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the ID of the ModifiedBy User
                        echo("EmailTemplate Modified By User-ID: " . $modifiedBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("EmailTemplate Modified By User-Name: " . $modifiedBy->getName(). "\n");
                    }

                    //Get the ConsentLinked of each EmailTemplate
                    echo("EmailTemplate ConsentLinked: "); print_r($emailTemplate->getConsentLinked()); echo("\n");

                    //Get the ID of each EmailTemplate
                    echo("EmailTemplate Id: " . $emailTemplate->getId() . "\n");

                    //Get the EditorMode each EmailTemplate
                    echo("EmailTemplate EditorMode: " . $emailTemplate->getEditorMode() . "\n");

                    //Get the Favorite of each EmailTemplate
                    echo("EmailTemplate Favorite: "); print_r($emailTemplate->getFavorite()); echo("\n");
                }
            }
        }
    }
}

?>