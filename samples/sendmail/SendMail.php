<?php
namespace samples\sendmail;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\sendmail\SendMailOperations;
use com\zoho\crm\api\sendmail\Mail;
use com\zoho\crm\api\inventorytemplates\InventoryTemplate;
use com\zoho\crm\api\sendmail\BodyWrapper;
use com\zoho\crm\api\sendmail\UserAddress;
use com\zoho\crm\api\sendmail\APIException;
use com\zoho\crm\api\sendmail\ActionWrapper;
use com\zoho\crm\api\sendmail\SuccessResponse;
require_once "vendor/autoload.php";

class SendMail
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

    public static function sendMail(string $recordId, string $moduleAPIName)
    {
        $sendMailOperations = new SendMailOperations();
        $mail = new Mail();
        $from = new UserAddress();
        $from->setUserName("user");
        $from->setEmail("abc@zoho.com");
        $mail->setFrom($from);
        $to = new UserAddress();
        $to->setUserName("user2");
        $to->setEmail("abc1@zoho.com");
        $mail->setTo([$to]);
        $mail->setSubject("Mail subject");
        $mail->setContent("<br><a href=\"{ConsentForm.en_US}\" id=\"ConsentForm\" class=\"en_US\" target=\"_blank\">Consent form link</a><br><br><br><br><br><h3><span style=\"background-color: rgb(254, 255, 102)\">REGARDS,</span></h3><div><span style=\"background-color: rgb(254, 255, 102)\">AZ</span></div><div><span style=\"background-color: rgb(254, 255, 102)\">ADMIN</span></div> <div></div>");
        // $mail->setConsentEmail(true);
        $mail->setMailFormat("html");
        $template = new InventoryTemplate();
        $template->setId("34770610174009");
        $mail->setTemplate($template);
        $wrapper = new BodyWrapper();
        $wrapper->setData([$mail]);
       
        //Call sendMail method
        $response = $sendMailOperations->sendMail($recordId, $moduleAPIName, $wrapper);
        if($response != null)
        {
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getData();
                foreach ($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details: " );
                        if($successResponse->getDetails() != null)
                        {
                            foreach ($successResponse->getDetails() as $keyName => $keyValue)
                            {
                                echo($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
                    }
                    else if($actionResponse instanceof APIException)
                    {
                        $exception = $actionResponse;
                        echo("Status: " . $exception->getStatus()->getValue() . "\n");
                        echo("Code: " . $exception->getCode()->getValue() . "\n");
                        echo("Details: " );
                        if($exception->getDetails() != null)
                        {
                            foreach ($exception->getDetails() as $keyName => $keyValue)
                            {
                                echo($keyName . ": " . $keyValue . "\n");
                            }
                        }
                        echo("Message: " . $exception->getMessage()->getValue() . "\n");
                    }
                }
            }
            else if($actionHandler instanceof APIException)
            {
                $exception = $actionHandler;
                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                echo("Code: " . $exception->getCode()->getValue() . "\n");
                if($exception->getDetails() != null)
                {
                    echo("Details: \n");
                    foreach ($exception->getDetails() as $keyName => $keyValue)
                    {
                        echo($keyName . ": " . $keyValue . "\n");
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
        }
    }
}

SendMail::initialize();
$recordId = "347706112984009";
$moduleAPIName = "Leads";
SendMail::sendMail($recordId, $moduleAPIName);
?>
