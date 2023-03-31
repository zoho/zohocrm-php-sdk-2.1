<?php
namespace samples\currencies;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\currencies\ResponseWrapper;
use com\zoho\crm\api\currencies\APIException;
use com\zoho\crm\api\currencies\CurrenciesOperations;
require_once "vendor/autoload.php";

class GetCurrency
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

	public static function getCurrency(string $currencyId)
	{
		$currenciesOperations = new CurrenciesOperations();
		$response = $currenciesOperations->getCurrency($currencyId);
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
                $currenciesList = $responseWrapper->getCurrencies();
                foreach($currenciesList as $currency)
                {
                    echo("Currency Symbol: " . $currency->getSymbol() . "\n");
                    echo("Currency CreatedTime: "); print_r($currency->getCreatedTime()); echo("\n");
                    echo("Currency IsActive: " . $currency->getIsActive() . "\n");
                    echo("Currency ExchangeRate: " . $currency->getExchangeRate() . "\n");
                    $format = $currency->getFormat();
                    if($format != null)
                    {
                        echo("Currency Format DecimalSeparator: " . $format->getDecimalSeparator()->getValue() . "\n");
                        echo("Currency Format ThousandSeparator: " . $format->getThousandSeparator()->getValue() . "\n");
                        echo("Currency Format DecimalPlaces: " . $format->getDecimalPlaces()->getValue() . "\n");
                    }
                    $createdBy = $currency->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("Currency CreatedBy User-Name: " . $createdBy->getName() . "\n");
                        echo("Currency CreatedBy User-ID: " . $createdBy->getId() . "\n");
                    }
                    echo("Currency PrefixSymbol: " . $currency->getPrefixSymbol() . "\n");
                    echo("Currency IsBase: " . $currency->getIsBase() . "\n");
                    echo("Currency ModifiedTime: "); print_r($currency->getModifiedTime()); echo("\n");
                    echo("Currency Name: " . $currency->getName() . "\n");
                    $modifiedBy = $currency->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("Currency ModifiedBy User-Name: " . $modifiedBy->getName() . "\n");
                        echo("Currency ModifiedBy User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo("Currency Id: " . $currency->getId() . "\n");
                    echo("Currency IsoCode: " . $currency->getIsoCode() . "\n");
                }
            }
            else if($responseHandler instanceof APIException)
            {
                $exception = $responseHandler;
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

GetCurrency::initialize();
$currencyId = "34770616011001";
GetCurrency::getCurrency($currencyId);