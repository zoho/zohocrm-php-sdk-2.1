<?php
namespace com\zoho\crm\sample\assignmentrules;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\assignmentrules\AssignmentRulesOperations;

use com\zoho\crm\api\assignmentrules\GetAssignmentRuleParam;

use com\zoho\crm\api\assignmentrules\ResponseWrapper;

use com\zoho\crm\api\assignmentrules\APIException;

class AssignmentRules
{
    public static function getAssignmentRules()
    {
        //Get instance of AssignmentRulesOperations Class
        $assignmentRulesOperations = new AssignmentRulesOperations();

        //Call getAssignmentRules method
        $response = $assignmentRulesOperations->getAssignmentRules();

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

                //Get the list of obtained AssignmentRule instances
                $assignmentRules = $responseWrapper->getAssignmentRules();

                foreach($assignmentRules as $assignmentRule)
                {
                    //Get the ID of each AssignmentRule
                    echo("AssignmentRule ID : " . $assignmentRule->getId() . "\n");

                    //Get the name of each AssignmentRule
                    echo("AssignmentRule Name : " . $assignmentRule->getName() . "\n");

                    //Get the  ModifiedTime of each AssignmentRule
                    echo("AssignmentRule ModifiedTime : "); print_r($assignmentRule->getModifiedTime());

                    //Get the  createdTime of each AssignmentRule
                    echo("AssignmentRule CreatedTime : "); print_r($assignmentRule->getCreatedTime());

                    $defaultAssignee = $assignmentRule->getDefaultAssignee();

                    if($defaultAssignee != null)
                    {
                        //Get the id of DefaultAssignee
                        echo("AssignmentRule DefaultAssignee Id : ". $defaultAssignee->getId() . "\n");

                        //Get the name of DefaultAssignee
                        echo("AssignmentRule DefaultAssignee Name : ". $defaultAssignee->getName() . "\n");
                    }

                    $module = $assignmentRule->getModule();

                    if($module != null)
                    {
                        //Get the id of  Module
                        echo("AssignmentRule Module Id : ". $module->getId() . "\n");

                        //Get the apiName of  Module
                        echo("AssignmentRule Module APIName : " . $module->getAPIName() . "\n");
                    }

                    $modifiedBy = $assignmentRule->getModifiedBy();

                    if($modifiedBy != null)
                    {
                        //Get the id of ModifiedBy
                        echo("AssignmentRule ModifiedBy Id : ". $modifiedBy->getId() . "\n");

                        //Get the name of ModifiedBy
                        echo("AssignmentRule ModifiedBy Name : ". $modifiedBy->getName() . "\n");
                    }

                    //Get the  description of each AssignmentRule
                    echo("AssignmentRule Description : " . $assignmentRule->getDescription() . "\n");

                    $createdBy = $assignmentRule->getCreatedBy();

                    if($createdBy != null)
                    {
                        //Get the id of CreatedBy
                        echo("AssignmentRule CreatedBy Id : ". $createdBy->getId() . "\n");

                        //Get the name of CreatedBy
                        echo("AssignmentRule CreatedBy Name : ". $createdBy->getName() . "\n");
                    }
                }
            }
            //Check if the request returned an exception
            else if($responseHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $responseHandler;

                //Get the Status
                echo("Status : " . $exception->getStatus()->getValue());

                //Get the Code
                echo("Code : " . $exception->getCode()->getValue());

                echo("Details : " );

                //Get the details map
                foreach($exception->getDetails() as $key => $value)
                {
                    //Get each value in the map
                    echo($key . " : " . $value);
                }

                //Get the Message
                echo("Message : " . $exception->getMessage()->getValue());
            }
        }
    }

    public static function getAssignmentRule(string $ruleId)
    {
        $paramInstance = new ParameterMap();

        $paramInstance->add(GetAssignmentRuleParam::module(), "leads");

       //Get instance of AssignmentRulesOperations Class
       $assignmentRulesOperations = new AssignmentRulesOperations();

       //Call getAssignmentRule method that takes ruleId and ParameterMap instance as parameter
       $response = $assignmentRulesOperations->getAssignmentRule($ruleId, $paramInstance);

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

               //Get the list of obtained AssignmentRule instances
               $assignmentRules = $responseWrapper->getAssignmentRules();

               foreach($assignmentRules as $assignmentRule)
               {
                   //Get the id of each AssignmentRule
                   echo("AssignmentRule Id : " . $assignmentRule->getId() . "\n");

                   //Get the name of each AssignmentRule
                   echo("AssignmentRule Name : " . $assignmentRule->getName() . "\n");

                   //Get the  modifiedTime of each AssignmentRule
                   echo("AssignmentRule ModifiedTime : "); print_r($assignmentRule->getModifiedTime());

                   //Get the  createdTime of each AssignmentRule
                   echo("AssignmentRule CreatedTime : "); print_r($assignmentRule->getCreatedTime());

                   $defaultAssignee = $assignmentRule->getDefaultAssignee();

                   if($defaultAssignee != null)
                   {
                       //Get the id of DefaultAssignee
                       echo("AssignmentRule DefaultAssignee Id : ". $defaultAssignee->getId() . "\n");

                       //Get the name of DefaultAssignee
                       echo("AssignmentRule DefaultAssignee Name : ". $defaultAssignee->getName() . "\n");
                   }

                   $module = $assignmentRule->getModule();

                   if($module != null)
                   {
                       //Get the id of  Module
                       echo("AssignmentRule Module Id : ". $module->getId() . "\n");

                       //Get the apiName of  Module
                       echo("AssignmentRule Module APIName : " . $module->getAPIName() . "\n");
                   }

                   $modifiedBy = $assignmentRule->getModifiedBy();

                   if($modifiedBy != null)
                   {
                       //Get the id of ModifiedBy
                       echo("AssignmentRule ModifiedBy Id : ". $modifiedBy->getId() . "\n");

                       //Get the name of ModifiedBy
                       echo("AssignmentRule ModifiedBy Name : ". $modifiedBy->getName() . "\n");
                   }

                   //Get the  description of each AssignmentRule
                   echo("AssignmentRule Description : " . $assignmentRule->getDescription() . "\n");

                   $createdBy = $assignmentRule->getCreatedBy();

                   if($createdBy != null)
                   {
                       //Get the id of CreatedBy
                       echo("AssignmentRule CreatedBy Id : ". $createdBy->getId() . "\n");

                       //Get the name of CreatedBy
                       echo("AssignmentRule CreatedBy Name : ". $createdBy->getName() . "\n");
                   }
               }
           }
           //Check if the request returned an exception
           else if($responseHandler instanceof APIException)
           {
               //Get the received APIException instance
               $exception = $responseHandler;

               //Get the Status
               echo("Status : " . $exception->getStatus()->getValue());

               //Get the Code
               echo("Code : " . $exception->getCode()->getValue());

               echo("Details : " );

               //Get the details map
               foreach($exception->getDetails() as $key => $value)
               {
                   //Get each value in the map
                   echo($key . " : " . $value);
               }

               //Get the Message
               echo("Message : " . $exception->getMessage()->getValue());
           }
       }
    }
}

?>