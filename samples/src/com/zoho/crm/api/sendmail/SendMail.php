<?php
namespace com\zoho\crm\sample\sendmail;

use com\zoho\crm\api\sendmail\SendMailOperations;

use com\zoho\crm\api\sendmail\ResponseWrapper;

use com\zoho\crm\api\sendmail\Mail;

use com\zoho\crm\api\inventorytemplates\InventoryTemplate;

use com\zoho\crm\api\sendmail\BodyWrapper;

use com\zoho\crm\api\sendmail\UserAddress;

use com\zoho\crm\api\sendmail\APIException;

use com\zoho\crm\api\sendmail\ActionWrapper;

use com\zoho\crm\api\sendmail\SuccessResponse;

class SendMail
{
    public static function getEmailAddresses()
    {
        //Get instance of SendMailOperations Class
        $sendMailOperations = new SendMailOperations();

        //Call getEmailAddresses method that takes ParameterMap instance as parameter
        $response = $sendMailOperations->getEmailAddresses();

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

                //Get the list of obtained Mail instances
                $emails = $responseWrapper->getFromAddresses();

                foreach($emails as $email)
                {
                    echo("UserName: " . $email->getUserName() . "\n");

                    echo("Mail Type: " . $email->getType() . "\n");

                    echo("Mail : " . $email->getEmail() . "\n");

                    echo("Mail ID: " . $email->getId() . "\n");

                    echo("Mail Default: " . $email->getDefault() . "\n");
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

    public static function sendMail(string $recordId, string $moduleAPIName)
    {
        //Get instance of SendMailOperations Class
        $sendMailOperations = new SendMailOperations();

        $mail = new Mail();

        $from = new UserAddress();

        $from->setUserName("user");

        $from->setEmail("user@zohocorp.com");

        $mail->setFrom($from);

        $to = new UserAddress();

        $to->setUserName("user2");

        $to->setEmail("user2@gmail.com");

        $mail->setTo([$to]);

        $mail->setSubject("Mail subject");

        $mail->setContent("<br><a href=\"{ConsentForm.en_US}\" id=\"ConsentForm\" class=\"en_US\" target=\"_blank\">Consent form link</a><br><br><br><br><br><h3><span style=\"background-color: rgb(254, 255, 102)\">REGARDS,</span></h3><div><span style=\"background-color: rgb(254, 255, 102)\">AZ</span></div><div><span style=\"background-color: rgb(254, 255, 102)\">ADMIN</span></div> <div></div>");

        $mail->setConsentEmail(true);

        $mail->setMailFormat("html");

        $template = new InventoryTemplate();

        $template->setId("347706179");

        $mail->setTemplate($template);

        $wrapper = new BodyWrapper();

        $wrapper->setData([$mail]);

        //Call sendMail method
        $response = $sendMailOperations->sendMail($recordId, $moduleAPIName, $wrapper);

        if($response != null)
        {
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

            //Get object from response
            $actionHandler = $response->getObject();

            if($actionHandler instanceof ActionWrapper)
            {
                //Get the received ActionWrapper instance
                $actionWrapper = $actionHandler;

                //Get the list of obtained action responses
                $actionResponses = $actionWrapper->getData();

                foreach ($actionResponses as $actionResponse)
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
            //Check if the request returned an exception
            else if($actionHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $actionHandler;

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