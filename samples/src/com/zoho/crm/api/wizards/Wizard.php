<?php
namespace com\zoho\crm\sample\wizards;

use com\zoho\crm\api\wizards\WizardsOperations;

use com\zoho\crm\api\ParameterMap;

use com\zoho\crm\api\wizards\GetWizardbyIDParam;

use com\zoho\crm\api\wizards\ResponseWrapper;

use com\zoho\crm\api\wizards\APIException;

class Wizard
{
    public static function getWizards()
    {
        //Get instance of WizardsOperations Class
        $wizardsOperations = new WizardsOperations();

        //Call getWizards method
        $response = $wizardsOperations->getWizards();

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

                //Get the list of obtained Wizard instances
                $wizards = $responseWrapper->getWizards();

                foreach($wizards as $wizard)
                {
                    //Get the CreatedTime of each Wizard
                    echo("Wizard CreatedTime: " ); print_r($wizard->getCreatedTime()); echo("\n");

                    //Get the PermissionType of each Wizard
                    echo("Wizard ModifiedTime: " ); print_r($wizard->getModifiedTime()); echo("\n");

                    //Get the manager User instance of each Wizard
                    $module = $wizard->getModule();

                    //Check if manager is not null
                    if($module != null)
                    {
                        //Get the Name of the Manager
                        echo("Wizard Module APIName: " . $module->getAPIName() . "\n");

                        //Get the ID of the Manager
                        echo("Wizard Module Id: " . $module->getId() . "\n");
                    }

                    //Get the Name of each Wizard
                    echo("Wizard Name: " . $wizard->getName() . "\n");

                    //Get the modifiedBy User instance of each Wizard
                    $modifiedBy = $wizard->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the Name of the modifiedBy User
                        echo("Wizard Modified By User-Name: " . $modifiedBy->getName() . "\n");

                        //Get the ID of the modifiedBy User
                        echo("Wizard Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }

                    //Get the array of Profile instance each Wizard
                    $profiles = $wizard->getProfiles();

                    //Check if profiles is not null
                    if($profiles != null)
                    {
                        foreach($profiles as $profile)
                        {
                            //Get the Name of each Profile
                            echo("Wizard Profile Name: " . $profile->getName() . "\n");

                            //Get the ID of each Profile
                            echo("Wizard Profile ID: " . $profile->getId() . "\n");
                        }
                    }

                    //Get the Active of each Wizard
                    echo("Wizard Active: " ); print_r($wizard->getActive()); echo("\n");

                    //Get the array of Container instance each Wizard
                    $containers = $wizard->getContainers();

                    //Check if containers is not null
                    if($containers != null)
                    {
                        foreach($containers as $container)
                        {
                            //Get the array of Layout instance each Wizard
                            $layout = $container->getLayout();

                            //Check if layout is not null
                            if($layout != null)
                            {
                                //Get the Name of Layout
                                echo("Wizard Container Layout Name: " . $layout->getName() . "\n");

                                //Get the ID of Layout
                                echo("Wizard Container Layout ID: " . $layout->getId() . "\n");
                            }

                            //Get the ID of each Container
                            echo("Wizard Container ID: " . $container->getId() . "\n");
                        }
                    }

                    //Get the ID of each Wizard
                    echo("Wizard ID: " . $wizard->getId() . "\n");

                    //Get the createdBy User instance of each Wizard
                    $createdBy = $wizard->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Name of the createdBy Wizard
                        echo("Wizard Created By User-Name: " . $createdBy->getName() . "\n");

                        //Get the ID of the createdBy Wizard
                        echo("Wizard Created By User-ID: " . $createdBy->getId() . "\n");
                    }
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

    public static function getWizardById(string $wizardId, string $layoutId)
    {
       //Get instance of WizardsOperations Class
       $wizardsOperations = new WizardsOperations();

       $paramInstance = new ParameterMap();

       $paramInstance->add(GetWizardbyIDParam::layoutId(), $layoutId);

       //Call getWizardById method that takes wizardId as parameter
       $response = $wizardsOperations->getWizardById($wizardId, $paramInstance);

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

                //Get the list of obtained Wizard instances
                $wizards = $responseWrapper->getWizards();

                foreach($wizards as $wizard)
                {
                    //Get the CreatedTime of each Wizard
                    echo("Wizard CreatedTime: " ); print_r($wizard->getCreatedTime()); echo("\n");

                    //Get the PermissionType of each Wizard
                    echo("Wizard ModifiedTime: " ); print_r($wizard->getModifiedTime()); echo("\n");

                    //Get the manager User instance of each Wizard
                    $module = $wizard->getModule();

                    //Check if manager is not null
                    if($module != null)
                    {
                        //Get the Name of the Manager
                        echo("Wizard Module APIName: " . $module->getAPIName() . "\n");

                        //Get the ID of the Manager
                        echo("Wizard Module Id: " . $module->getId() . "\n");
                    }

                    //Get the Name of each Wizard
                    echo("Wizard Name: " . $wizard->getName() . "\n");

                    //Get the modifiedBy User instance of each Wizard
                    $modifiedBy = $wizard->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the Name of the modifiedBy User
                        echo("Wizard Modified By User-Name: " . $modifiedBy->getName() . "\n");

                        //Get the ID of the modifiedBy User
                        echo("Wizard Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }

                    //Get the array of Profile instance each Wizard
                    $profiles = $wizard->getProfiles();

                    //Check if profiles is not null
                    if($profiles != null)
                    {
                        foreach($profiles as $profile)
                        {
                            //Get the Name of each Profile
                            echo("Wizard Profile Name: " . $profile->getName() . "\n");

                            //Get the ID of each Profile
                            echo("Wizard Profile ID: " . $profile->getId() . "\n");
                        }
                    }

                    //Get the Active of each Wizard
                    echo("Wizard Active: " ); print_r($wizard->getActive()); echo("\n");

                    //Get the array of Container instance each Wizard
                    $containers = $wizard->getContainers();

                    //Check if containers is not null
                    if($containers != null)
                    {
                        foreach($containers as $container)
                        {
                            //Get the array of Layout instance each Wizard
                            $layout = $container->getLayout();

                            //Check if layout is not null
                            if($layout != null)
                            {
                                //Get the Name of Layout
                                echo("Wizard Container Layout Name: " . $layout->getName() . "\n");

                                //Get the ID of Layout
                                echo("Wizard Container Layout ID: " . $layout->getId() . "\n");
                            }

                            $chartData = $container->getChartData();

                            if($chartData != null)
                            {
                                $nodes = $chartData->getNodes();

                                if($nodes != null)
                                {
                                    foreach($nodes as $node)
                                    {
                                        echo("Wizard Container ChartData Node PosY: " . $node->getPosY() . "\n");

                                        echo("Wizard Container ChartData Node PosX: " . $node->getPosX() . "\n");

                                        echo("Wizard Container ChartData Node StartNode: "); print_r($node->getStartNode()); echo("\n");

                                        $screen = $node->getScreen();

                                        if($screen != null)
                                        {
                                            echo("Wizard Container ChartData Node Screen DisplayLabel: " . $screen->getDisplayLabel() . "\n");

                                            echo("Wizard Container ChartData Node Screen ID: " . $screen->getId() . "\n");
                                        }
                                    }
                                }

                                $connections = $chartData->getConnections();

                                if($connections != null)
                                {
                                    foreach($connections as $connection)
                                    {
                                        $sourceButton = $connection->getSourceButton();

                                        if($sourceButton != null)
                                        {
                                            self::printButton($sourceButton);
                                        }

                                        $targetScreen = $connection->getTargetScreen();

                                        if($targetScreen != null)
                                        {
                                            self::printScreen(targetScreen);
                                        }
                                    }
                                }

                                echo("Wizard Container ChartData CanvasWidth: " . $chartData->getCanvasWidth() . "\n");

                                echo("Wizard Container ChartData CanvasHeight: " . $chartData->getCanvasHeight() . "\n");
                            }

                            $screens = $container->getScreens();

                            if($screens != null)
                            {
                                foreach($screens as $screen)
                                {
                                    self::printScreen($screen);
                                }
                            }

                            //Get the ID of each Container
                            echo("Wizard Container ID: " . $container->getId() . "\n");
                        }
                    }

                    //Get the ID of each Wizard
                    echo("Wizard ID: " . $wizard->getId() . "\n");

                    //Get the createdBy User instance of each Wizard
                    $createdBy = $wizard->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Name of the createdBy Wizard
                        echo("Wizard Created By User-Name: " . $createdBy->getName() . "\n");

                        //Get the ID of the createdBy Wizard
                        echo("Wizard Created By User-ID: " . $createdBy->getId() . "\n");
                    }
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

    private static function printScreen($screen)
    {
        echo("Screen Id: " . $screen->getId() . "\n");

        echo("Screen DisplayLabel: " . $screen->getDisplayLabel() . "\n");

        $segments = $screen->getSegments();

        if($segments != null)
        {
            foreach($segments as $segment)
            {
                self::printSegment($segment);
            }
        }
    }

    private static function printSegment($segment)
    {
        echo("Segment Id: " . $segment->getId() . "\n");

        echo("Segment SequenceNumber: " . $segment->getSequenceNumber() . "\n");

        echo("Segment DisplayLabel: " . $segment->getDisplayLabel() . "\n");

        echo("Segment Type: " . $segment->getType() . "\n");

        echo("Segment ColumnCount: " . $segment->getColumnCount() . "\n");

        $fields = $segment->getFields();

        if($fields != null)
        {
            foreach($fields as $field)
            {
                echo("Segment Field SequenceNumber: " . $field->getSequenceNumber() . "\n");

                echo("Segment Field APIName: " . $field->getAPIName() . "\n");

                echo("Segment Field Id: " . $field->getId() . "\n");
            }
        }

        $buttons = $segment->getButtons();

        if($buttons != null)
        {
            foreach($buttons as $button)
            {
                if($button != null)
                {
                    self::printButton($button);
                }
            }
        }
    }

    private static function printButton($button)
    {
        echo("Button Id: " . $button->getId() . "\n");

        echo("Button SequenceNumber: " . $button->getSequenceNumber() . "\n");

        echo("Button DisplayLabel: " . $button->getDisplayLabel() . "\n");

        $criteria = $button->getCriteria();

        //Check if criteria is not null
        if($criteria != null)
        {
            self::printCriteria($criteria);
        }

        $targetScreen = $button->getTargetScreen();

        if($targetScreen != null)
        {
            echo("Button TargetScreen DisplayLabel: " . $targetScreen->getDisplayLabel() . "\n");

            echo("Button TargetScreen Id: " . $targetScreen->getId() . "\n");
        }

        echo("Button Type: " . $button->getType() . "\n");

        echo("Button Color: " . $button->getColor() . "\n");

        echo("Button Shape: " . $button->getShape() . "\n");

        echo("Button BackgroundColor: " . $button->getBackgroundColor() . "\n");

        echo("Button Visibility: " . $button->getVisibility() . "\n");

        $transition = $button->getTransition();

        if($transition != null)
        {
            echo("Button Transition Name: " . $transition->getName() . "\n");

            echo("Button Transition Id: " . $transition->getId() . "\n");
        }
    }

    private static function printCriteria($criteria)
    {
        if( $criteria->getComparator() != null)
        {
            //Get the Comparator of the Criteria
		    echo("Criteria Comparator: " . $criteria->getComparator()->getValue() . "\n");
        }

        //Get the Field of the Criteria
        $field = $criteria->getField();

        if($field != null)
        {
            echo("Criteria Field: " . $field->getAPIName() . "\n");

            echo("Criteria Field: " . $field->getId() . "\n");
        }

		//Get the Value of the Criteria
        echo("Criteria Value: "); print_r($criteria->getValue()); echo("\n");

		// Get the List of Criteria instance of each Criteria
		$criteriaGroup = $criteria->getGroup();

		if($criteriaGroup != null)
		{
			foreach($criteriaGroup as $criteria1)
			{
				self::printCriteria($criteria1);
			}
		}

        if($criteria->getGroupOperator() != null)
        {
            //Get the Group Operator of the Criteria
		    echo("Criteria Group Operator: " . $criteria->getGroupOperator()->getValue() . "\n");
        }
    }
}

?>