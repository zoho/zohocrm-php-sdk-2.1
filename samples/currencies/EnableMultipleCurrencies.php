<?php
namespace samples\currencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\currencies\Format;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\currencies\APIException;
use com\zoho\crm\api\currencies\SuccessResponse;
use com\zoho\crm\api\currencies\BaseCurrencyWrapper;
use com\zoho\crm\api\currencies\BaseCurrencyActionWrapper;
use com\zoho\crm\api\currencies\CurrenciesOperations;
require_once "vendor/autoload.php";

class EnableMultipleCurrencies
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

	public static function enableMultipleCurrencies()
	{
		$currenciesOperations = new CurrenciesOperations();
		$bodyWrapper = new BaseCurrencyWrapper();
        $currencyClass = "com\zoho\crm\api\currencies\Currency";
        $currency = new $currencyClass();
		$currency->setPrefixSymbol(true);
		$currency->setName("Angolan Kwanza - AOA");
		$currency->setIsoCode("AOA");
		$currency->setSymbol("Kz");
		$currency->setExchangeRate("1.00");
		$currency->setIsActive(true);
		$format = new Format();
		$format->setDecimalSeparator(new Choice("Period"));
		$format->setThousandSeparator(new Choice("Comma"));
		$format->setDecimalPlaces(new Choice("2"));
		$currency->setFormat($format);
		$bodyWrapper->setBaseCurrency($currency);
		$response = $currenciesOperations->enableMultipleCurrencies($bodyWrapper);
		if($response != null)
		{
            echo("Status code" . $response->getStatusCode() . "\n");
            $baseCurrencyActionHandler = $response->getObject();
            if($baseCurrencyActionHandler instanceof BaseCurrencyActionWrapper)
            {
                $baseCurrencyActionWrapper = $baseCurrencyActionHandler;
                $actionResponse = $baseCurrencyActionWrapper->getBaseCurrency();
                if($actionResponse instanceof SuccessResponse)
                {
                    $successResponse = $actionResponse;
                    echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                    echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                    echo("Details: " );
                    foreach($successResponse->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value);
                    }
                    echo("Message: " . $successResponse->getMessage()->getValue() . "\n");
                }
                else if($actionResponse instanceof APIException)
                {
                    $exception = $actionResponse;
                    echo("Status: " . $exception->getStatus()->getValue() . "\n");
                    echo("Code: " . $exception->getCode()->getValue() . "\n");
                    if($exception->getDetails() != null)
                    {
                        echo("Details: " );
                        foreach($exception->getDetails() as $key => $value)
                        {
                            echo($key . ": " . $value);
                        }
                    }
                    echo("Message: " . $exception->getMessage()->getValue() . "\n");
                }
            }
            else if($baseCurrencyActionHandler instanceof APIException)
            {
                $exception = $baseCurrencyActionHandler;
                echo("Status: " . $exception->getStatus()->getValue() . "\n");
                echo("Code: " . $exception->getCode()->getValue() . "\n");
                if($exception->getDetails() != null)
                {
                    echo("Details: " );
                    foreach($exception->getDetails() as $key => $value)
                    {
                        echo($key . ": " . $value . "\n");
                    }
                }
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}
}

EnableMultipleCurrencies::initialize();
EnableMultipleCurrencies::enableMultipleCurrencies();