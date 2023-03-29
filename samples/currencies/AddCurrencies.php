<?php
namespace samples\currencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\currencies\Format;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\currencies\ActionWrapper;
use com\zoho\crm\api\currencies\APIException;
use com\zoho\crm\api\currencies\SuccessResponse;
use com\zoho\crm\api\currencies\BodyWrapper;
use com\zoho\crm\api\currencies\CurrenciesOperations;
require_once "vendor/autoload.php";

class AddCurrencies
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

	public static function addCurrencies()
	{
		$currenciesOperations = new CurrenciesOperations();
		$bodyWrapper = new BodyWrapper();
		$currencies = array();
        $currencyClass = "com\zoho\crm\api\currencies\Currency";
		$currency = new $currencyClass();
		$currency->setPrefixSymbol(true);
		$currency->setName("USD");
		$currency->setIsoCode("USD");
		$currency->setSymbol("$");
		$currency->setExchangeRate("20.");
		$currency->setIsActive(true);
		$format = new Format();
		$format->setDecimalSeparator(new Choice("Period"));
		$format->setThousandSeparator(new Choice("Comma"));
		$format->setDecimalPlaces(new Choice("2"));
		$currency->setFormat($format);
		array_push($currencies, $currency);
		$bodyWrapper->setCurrencies($currencies);
		$response = $currenciesOperations->addCurrencies($bodyWrapper);
		if($response != null)
		{
            echo("Status code : " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $actionWrapper = $actionHandler;
                $actionResponses = $actionWrapper->getCurrencies();
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

AddCurrencies::initialize();
AddCurrencies::addCurrencies();