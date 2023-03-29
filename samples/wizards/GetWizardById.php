<?php
namespace samples\wizards;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\wizards\WizardsOperations;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\wizards\GetWizardbyIDParam;
use com\zoho\crm\api\wizards\ResponseWrapper;
use com\zoho\crm\api\wizards\APIException;
require_once "vendor/autoload.php";

class GetWizardById
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

    public static function getWizardById(string $wizardId, string $layoutId)
    {
       $wizardsOperations = new WizardsOperations();
       $paramInstance = new ParameterMap();
       $paramInstance->add(GetWizardbyIDParam::layoutId(), $layoutId);
       //Call getWizardById method that takes wizardId as parameter
       $response = $wizardsOperations->getWizardById($wizardId, $paramInstance);
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
                $wizards = $responseWrapper->getWizards();
                foreach($wizards as $wizard)
                {
                    echo("Wizard CreatedTime: " ); print_r($wizard->getCreatedTime()); echo("\n");
                    echo("Wizard ModifiedTime: " ); print_r($wizard->getModifiedTime()); echo("\n");
                    $module = $wizard->getModule();
                    if($module != null)
                    {
                        echo("Wizard Module APIName: " . $module->getAPIName() . "\n");
                        echo("Wizard Module Id: " . $module->getId() . "\n");
                    }
                    echo("Wizard Name: " . $wizard->getName() . "\n");
                    $modifiedBy = $wizard->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("Wizard Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo("Wizard Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    $profiles = $wizard->getProfiles();
                    if($profiles != null)
                    {
                        foreach($profiles as $profile)
                        {
                            echo("Wizard Profile Name: " . $profile->getName() . "\n");
                            echo("Wizard Profile ID: " . $profile->getId() . "\n");
                        }
                    }
                    echo("Wizard Active: " ); print_r($wizard->getActive()); echo("\n");
                    $containers = $wizard->getContainers();
                    if($containers != null)
                    {
                        foreach($containers as $container)
                        {
                            $layout = $container->getLayout();
                            if($layout != null)
                            {
                                echo("Wizard Container Layout Name: " . $layout->getName() . "\n");
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
                            echo("Wizard Container ID: " . $container->getId() . "\n");
                        }
                    }
                    echo("Wizard ID: " . $wizard->getId() . "\n");
                    $createdBy = $wizard->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("Wizard Created By User-Name: " . $createdBy->getName() . "\n");
                        echo("Wizard Created By User-ID: " . $createdBy->getId() . "\n");
                    }
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
		    echo("Criteria Comparator: " . $criteria->getComparator()->getValue() . "\n");
        }
        $field = $criteria->getField();
        if($field != null)
        {
            echo("Criteria Field: " . $field->getAPIName() . "\n");
            echo("Criteria Field: " . $field->getId() . "\n");
        }
        echo("Criteria Value: "); print_r($criteria->getValue()); echo("\n");
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
		    echo("Criteria Group Operator: " . $criteria->getGroupOperator()->getValue() . "\n");
        }
    }
}

GetWizardById::initialize();
$wizardId = "34770619497009";
$layoutId = "34770610091055";
GetWizardById::getWizardById($wizardId, $layoutId);
?>
