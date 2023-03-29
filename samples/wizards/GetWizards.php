<?php
namespace samples\wizards;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\wizards\WizardsOperations;
use com\zoho\crm\api\wizards\ResponseWrapper;
use com\zoho\crm\api\wizards\APIException;
require_once "vendor/autoload.php";

class GetWizards
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

    public static function getWizards()
    {
        $wizardsOperations = new WizardsOperations();
        $response = $wizardsOperations->getWizards();
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
}

GetWizards::initialize();
GetWizards::getWizards();