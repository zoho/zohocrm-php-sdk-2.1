<?php
namespace com\zoho\crm\sample\pipeline;

use com\zoho\crm\api\pipeline\PipelineOperations;

use com\zoho\crm\api\pipeline\TransferAndDeleteWrapper;

use com\zoho\crm\api\pipeline\PickListValue;

use com\zoho\crm\api\pipeline\TransferStructure;

use com\zoho\crm\api\pipeline\Stage;

use com\zoho\crm\api\pipeline\TransferPipeLine;

use com\zoho\crm\api\pipeline\ActionWrapper;

use com\zoho\crm\api\pipeline\SuccessResponse;

use com\zoho\crm\api\pipeline\APIException;

use com\zoho\crm\api\pipeline\ResponseWrapper;

use com\zoho\crm\api\pipeline\TransferActionWrapper;

class PipeLine
{
    public static function getPipelines($layoutId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        //Call getPipelines method
        $response = $pipelineOperations->getPipelines();

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

                //Get the list of obtained Pipeline instances
                $pipelines = $responseWrapper->getPipeline();

                foreach($pipelines as $pipeline)
                {
                    //Get the DisplayValue of each Pipeline
                    echo("Pipeline DisplayValue: " . $pipeline->getDisplayValue() . "\n");

                    //Get the Default of each Pipeline
                    echo("Pipeline Default: "); print_r($pipeline->getDefault()); echo("\n");

                    $maps = $pipeline->getMaps();

                    if($maps != null)
                    {
                        foreach($maps as $map)
                        {
                            //Get the Maps DisplayValue of each Pipeline
                            echo("Pipeline Maps DisplayValue: " . $map->getDisplayValue() . "\n");

                            //Get the Maps SequenceNumber of each Pipeline
                            echo("Pipeline Maps SequenceNumber: " . $map->getSequenceNumber() . "\n");

                            $forecastCategory = $map->getForecastCategory();

                            if($forecastCategory != null)
                            {
                                //Get the Maps ForecastCategory Name of each Pipeline
                                echo("Pipeline Maps ForecastCategory Name: " . $forecastCategory->getName() . "\n");

                                //Get the Maps ForecastCategory Id of each Pipeline
                                echo("Pipeline Maps ForecastCategory Id: " . $forecastCategory->getId() . "\n");
                            }

                            //Get the Maps ActualValue of each Pipeline
                            echo("Pipeline Maps ActualValue: " . $map->getActualValue() . "\n");

                            //Get the Maps Id of each Pipeline
                            echo("Pipeline Maps Id: " . $map->getId() . "\n");

                            //Get the Maps ForecastType of each Pipeline
                            echo("Pipeline Maps ForecastType: " . $map->getForecastType() . "\n");
                        }
                    }

                    //Get the ActualValue of each Pipeline
                    echo("Pipeline Maps ActualValue: " . $pipeline->getActualValue() . "\n");

                    //Get the Id of each Pipeline
                    echo("Pipeline Id: " . $pipeline->getId() . "\n");
                }
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

    public static function transferAndDelete($layoutId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        $transferAndDeleteWrapper = new TransferAndDeleteWrapper();

        $transferPipeLine = new TransferPipeLine();

        $pipelineClass = "com\\zoho\\crm\\api\\pipeline\\PipeLine";

        $pipeline = new $pipelineClass();

        $pipeline->setFrom("36523973712004");

        $pipeline->setTo("36523973097007");

        $transferPipeLine->setPipeline($pipeline);

        $stage = new Stage();

        $stage->setFrom("36523970006817");

        $stage->setTo("36523970006819");

        $transferPipeLine->setStages([$stage]);

        $transferAndDeleteWrapper->setTransferPipeline([$transferPipeLine]);

        //Call transferAndDelete method
        $response = $pipelineOperations->transferAndDelete($transferAndDeleteWrapper);

        if($response != null)
        {
            //Get the status code from response
            echo("Status code" . $response->getStatusCode() . "\n");

            //Get object from response
            $actionHandler = $response->getObject();

            if($actionHandler instanceof TransferActionWrapper)
            {
                //Get the received ActionWrapper instance
                $actionWrapper = $actionHandler;

                //Get the list of obtained action responses
                $actionResponses = $actionWrapper->getTransferPipeline();

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

    public static function createPipelines($layoutId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        $pipelineClass = "com\\zoho\\crm\\api\\pipeline\\PipeLine";

        $pipeline = new $pipelineClass();

		$pipeline->setDisplayValue("Adfasfsad13");

		$pickList = new PickListValue();

		$pickList->setId("34770610006801");

		$pickList->setSequenceNumber(1);

		$pipeline->setMaps([$pickList]);

        $bodyWrapper = "com\\zoho\\crm\\api\\pipeline\\BodyWrapper";

		$body = new $bodyWrapper();

		$body->setPipeline([$pipeline]);

        //Call createPipelines method that takes BodyWrapper instance as parameter
        $response = $pipelineOperations->createPipelines($body);

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
                $actionResponses = $actionWrapper->getPipeline();

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

    public static function updatePipelines($layoutId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        $pipelineClass = "com\\zoho\\crm\\api\\pipeline\\PipeLine";

        $pipeline = new $pipelineClass();

        $pipeline->setId("34770619482001");

		$pipeline->setDisplayValue("Qualification");

		$pickList = new PickListValue();

		$pickList->setId("34770610006801");

		$pickList->setSequenceNumber(1);

		$pipeline->setMaps([$pickList]);

        $bodyWrapper = "com\\zoho\\crm\\api\\pipeline\\BodyWrapper";

		$body = new $bodyWrapper();

		$body->setPipeline([$pipeline]);

        //Call createPipelines method that takes BodyWrapper instance as parameter
        $response = $pipelineOperations->updatePipelines($body);

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
                $actionResponses = $actionWrapper->getPipeline();

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

    public static function getPipeline($layoutId, $pipelineId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        //Call getPipeline method
        $response = $pipelineOperations->getPipeline($pipelineId);

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

                //Get the list of obtained Pipeline instances
                $pipelines = $responseWrapper->getPipeline();

                foreach($pipelines as $pipeline)
                {
                    //Get the DisplayValue of each Pipeline
                    echo("Pipeline DisplayValue: " . $pipeline->getDisplayValue() . "\n");

                    //Get the Default of each Pipeline
                    echo("Pipeline Default: "); print_r($pipeline->getDefault()); echo("\n");

                    $maps = $pipeline->getMaps();

                    if($maps != null)
                    {
                        foreach($maps as $map)
                        {
                            //Get the Maps DisplayValue of each Pipeline
                            echo("Pipeline Maps DisplayValue: " . $map->getDisplayValue() . "\n");

                            //Get the Maps SequenceNumber of each Pipeline
                            echo("Pipeline Maps SequenceNumber: " . $map->getSequenceNumber() . "\n");

                            $forecastCategory = $map->getForecastCategory();

                            if($forecastCategory != null)
                            {
                                //Get the Maps ForecastCategory Name of each Pipeline
                                echo("Pipeline Maps ForecastCategory Name: " . $forecastCategory->getName() . "\n");

                                //Get the Maps ForecastCategory Id of each Pipeline
                                echo("Pipeline Maps ForecastCategory Id: " . $forecastCategory->getId() . "\n");
                            }

                            //Get the Maps ActualValue of each Pipeline
                            echo("Pipeline Maps ActualValue: " . $map->getActualValue() . "\n");

                            //Get the Maps Id of each Pipeline
                            echo("Pipeline Maps Id: " . $map->getId() . "\n");

                            //Get the Maps ForecastType of each Pipeline
                            echo("Pipeline Maps ForecastType: " . $map->getForecastType() . "\n");
                        }
                    }

                    //Get the ActualValue of each Pipeline
                    echo("Pipeline Maps ActualValue: " . $pipeline->getActualValue() . "\n");

                    //Get the Id of each Pipeline
                    echo("Pipeline Id: " . $pipeline->getId() . "\n");
                }
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

    public static function updatePipeline($layoutId, $pipelineId)
    {
        //Get instance of PipelineOperations Class
        $pipelineOperations = new PipelineOperations($layoutId);

        $pipelineClass = "com\\zoho\\crm\\api\\pipeline\\PipeLine";

        $pipeline = new $pipelineClass();

		$pipeline->setDisplayValue("Qualification");

		$pickList = new PickListValue();

		$pickList->setId("34770610006801");

		$pickList->setSequenceNumber(1);

		$pipeline->setMaps([$pickList]);

        $bodyWrapper = "com\\zoho\\crm\\api\\pipeline\\BodyWrapper";

		$body = new $bodyWrapper();

		$body->setPipeline([$pipeline]);

        //Call updatePipeline method that takes BodyWrapper instance as parameter
        $response = $pipelineOperations->updatePipeline($pipelineId, $body);

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
                $actionResponses = $actionWrapper->getPipeline();

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