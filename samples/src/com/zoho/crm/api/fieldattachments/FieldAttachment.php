<?php
namespace com\zoho\crm\sample\fieldattachments;

use com\zoho\crm\api\fieldattachments\FieldAttachmentsOperations;

use com\zoho\crm\api\fieldattachments\FileBodyWrapper;

use com\zoho\crm\api\fieldattachments\APIException;

class FieldAttachment
{
    public static function getFieldAttachments(string $moduleAPIName, string $recordId, string $fieldsAttachmentId=null, $destinationFolder=null)
    {
        //Get instance of FieldAttachmentsOperations Class
        $fieldAttachmentsOperations = new FieldAttachmentsOperations($moduleAPIName, $recordId, $fieldsAttachmentId);

        //Call getFieldAttachments method
        $response = $fieldAttachmentsOperations->getFieldAttachments();

        if($response != null)
		{
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

            if($response->getStatusCode() == 204)
            {
                echo("No Content\n");

                return;
            }

            //Get object from response
            $responseHandler = $response->getObject();

            if($responseHandler instanceof FileBodyWrapper)
            {
                //Get the received FileBodyWrapper instance
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
}

?>