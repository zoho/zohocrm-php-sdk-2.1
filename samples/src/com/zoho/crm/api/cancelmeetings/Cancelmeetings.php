<?php

namespace com\zoho\crm\sample\cancelmeetings;

use com\zoho\crm\api\cancelmeetings\ActionWrapper;
use com\zoho\crm\api\cancelmeetings\APIException;
use com\zoho\crm\api\cancelmeetings\CancelMeetingsOperations;
use com\zoho\crm\api\cancelmeetings\BodyWrapper;
use com\zoho\crm\api\cancelmeetings\Notify;
use com\zoho\crm\api\cancelmeetings\SuccessResponse;
use com\zoho\crm\api\util\APIResponse;

class CancelMeetings
{
    public static function cancelmeetings(int $event_id,bool $send_cancel_mail){
        $cmo=new CancelMeetingsOperations($event_id);

        $bodyWrapper = new BodyWrapper();

        $notify= new Notify();

        $notify->setSendCancellingMail($send_cancel_mail);

        $notify_list=array();

        array_push($notify_list,$notify);

        $bodyWrapper->setData($notify_list);

        $response = $cmo->cancelMeetings($bodyWrapper);

        if($response != null)
        {
            //Get the status code from response
            echo("Status code" . $response->getStatusCode() . "\n");

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

