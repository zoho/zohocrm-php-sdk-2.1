<?php
namespace samples\relatedlist;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\relatedlists\APIException;
use com\zoho\crm\api\relatedlists\RelatedListsOperations;
use com\zoho\crm\api\relatedlists\ResponseWrapper;
require_once "vendor/autoload.php";

class GetRelatedLists
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

	public static function getRelatedLists(string $moduleAPIName)
	{
		$relatedListsOperations = new RelatedListsOperations($moduleAPIName);
		$response = $relatedListsOperations->getRelatedLists();
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
                $relatedLists = $responseWrapper->getRelatedLists();
                foreach($relatedLists as $relatedList)
                {
                    echo("RelatedList SequenceNumber: " . $relatedList->getSequenceNumber() . "\n");
                    echo("RelatedList DisplayLabel: " . $relatedList->getDisplayLabel() . "\n");
                    echo("RelatedList APIName: " . $relatedList->getAPIName() . "\n");
                    echo("RelatedList Module: " . $relatedList->getModule() . "\n");
                    echo("RelatedList Name: " . $relatedList->getName() . "\n");
                    echo("RelatedList Action: " . $relatedList->getAction() . "\n");
                    echo("RelatedList ID: " . $relatedList->getId() . "\n");
                    echo("RelatedList Href: " . $relatedList->getHref() . "\n");
                    echo("RelatedList Type: " . $relatedList->getType() . "\n");
                    echo("RelatedList Connectedmodule: " . $relatedList->getConnectedmodule() . "\n");
                    echo("RelatedList Linkingmodule: " . $relatedList->getLinkingmodule() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
			{
				$exception = $responseHandler;
				echo("Status: " . $exception->getStatus()->getValue() . "\n");
				echo("Code: " . $exception->getCode()->getValue() . "\n");
				echo("Details: " );
				foreach($exception->getDetails() as $key => $value)
				{
					echo($key . ": " .$value . "\n");
				}
				echo("Message: " . $exception->getMessage()->getValue() . "\n");
			}
		}
	}
}

GetRelatedLists::initialize();
$moduleAPIName = "Leads";
GetRelatedLists::getRelatedLists($moduleAPIName);