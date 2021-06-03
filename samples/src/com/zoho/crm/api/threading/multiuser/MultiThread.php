<?php
namespace com\zoho\crm\sample\threading\multiuser;

use com\zoho\api\authenticator\OAuthBuilder;

use com\zoho\api\authenticator\store\DBBuilder;

use com\zoho\api\authenticator\store\FileStore;

use com\zoho\crm\api\InitializeBuilder;

use com\zoho\crm\api\UserSignature;

use com\zoho\crm\api\dc\USDataCenter;

use com\zoho\api\logger\LogBuilder;

use com\zoho\api\logger\Levels;

use com\zoho\crm\api\SDKConfigBuilder;

use com\zoho\crm\api\ProxyBuilder;

use com\zoho\crm\api\Initializer;

use com\zoho\crm\api\record\RecordOperations;

use com\zoho\crm\api\record\GetRecordsHeader;

use com\zoho\crm\api\HeaderMap;

use com\zoho\crm\api\ParameterMap;

class MultiThread
{
	public function main()
	{
		$logger = (new LogBuilder())
		->level(Levels::INFO)
		->filePath("/Users/username/php_sdk_log.log")
		->build();

		$environment1 = USDataCenter::PRODUCTION();

		$user1 = new UserSignature("user1@zoho.com");

		$tokenstore = (new DBBuilder())
		->host("hostName")
		->databaseName("databaseName")
		->userName("userName")
		->portNumber("portNumber")
		->tableName("tableName")
		->password("password")
        ->build();

		//Create a Token instance
		$token1 = (new OAuthBuilder())
		->clientId("ClientId")
		// ->id("php_abc_us_prd_")
		->clientSecret("ClientSecret")
		// ->grantToken("GrantToken")
		->refreshToken("RefreshToken")
		->redirectURL("RedirectURL")
		->build();

        $resourcePath = "/Users/username";

        $builderInstance = new SDKConfigBuilder();

        $configInstance = $builderInstance->setPickListValidation(true)->setAutoRefreshFields(false)->build();

        (new InitializeBuilder())
		->user($user1)
		->environment($environment1)
		->token($token1)
		->store($tokenstore)
		->SDKConfig($configInstance)
		->resourcePath($resourcePath)
		->logger($logger)
		->initialize();

        $this->getRecords("Leads");

		$environment2 = USDataCenter::PRODUCTION();

        $user2 = new UserSignature("user2@zoho.com");

        //Create a Token instance
		$token2 = (new OAuthBuilder())
		->clientId("ClientId")
		// ->id("php_abc_us_prd_")
		->clientSecret("ClientSecret")
		// ->grantToken("GrantToken")
		->refreshToken("RefreshToken")
		->redirectURL("RedirectURL")
		->build();

        (new InitializeBuilder())
		->user($user2)
		->environment($environment2)
		->token($token2)
		->store($tokenstore)
		->SDKConfig($configInstance)
        ->switchUser();

        $this->getRecords("Leads");

        Initializer::switchUser($user1, $environment1, $token1, $configInstance);

        $this->getRecords("apiName2");
    }

    public function getRecords($moduleAPIName)
    {
        try
        {
            $recordOperations = new RecordOperations();

            $paramInstance = new ParameterMap();

            $headerInstance = new HeaderMap();

            $ifmodifiedsince = date_create("2020-06-02T11:03:06+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));

            $headerInstance->add(GetRecordsHeader::IfModifiedSince(), $ifmodifiedsince);

            //Call getRecord method that takes paramInstance, moduleAPIName as parameter
            $response = $recordOperations->getRecords($moduleAPIName,$paramInstance, $headerInstance);

            echo($response->getStatusCode() . "\n");

            print_r($response);

            echo("\n");
        }
        catch (\Exception $e)
        {
            print_r($e);
        }
    }
}

$obj = new MultiThread();

$obj->main();

?>