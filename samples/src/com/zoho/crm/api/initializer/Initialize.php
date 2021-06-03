<?php
namespace com\zoho\crm\sample\initializer;

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

class Initialize
{
    public static function initialize()
    {
        /*
		 * Create an instance of Logger Class that takes two parameters
		 * 1 -> Level of the log messages to be logged. Can be configured by typing Levels "." and choose any level from the list displayed.
		 * 2 -> Absolute file path, where messages need to be logged.
		 */
		$logger = (new LogBuilder())
		->level(Levels::INFO)
		->filePath("/Users/username/php_sdk_log.log")
		->build();

        //Create an UserSignature instance that takes user Email as parameter
        $user = new UserSignature("abc@zoho.com");

        /*
		 * Configure the environment
		 * which is of the pattern Domain.Environment
		 * Available Domains: USDataCenter, EUDataCenter, INDataCenter, CNDataCenter, AUDataCenter
		 * Available Environments: PRODUCTION, DEVELOPER, SANDBOX
		 */
        $environment = USDataCenter::PRODUCTION();

        //Create a Token instance
		$token = (new OAuthBuilder())
		->clientId("ClientId")
		// ->id("php_abc_us_prd_")
		->clientSecret("ClientSecret")
		// ->grantToken("GrantToken")
		->refreshToken("RefreshToken")
		->redirectURL("RedirectURL")
		->build();

		// $tokenstore = (new DBBuilder())
		// ->host("hostName")
		// ->databaseName("databaseName")
		// ->userName("userName")
		// ->portNumber("portNumber")
		// ->tableName("tableName")
		// ->password("password")
		// ->build();

        $tokenstore = new FileStore("/Users/username/php_sdk_token.txt");

		$resourcePath = "/Users/username";

		$autoRefreshFields = true;

		$pickListValidation = false;

		$enableSSLVerification = true;

		$builderInstance = new SDKConfigBuilder();

		$connectionTimeout = 50; //The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.

    	$timeout = 50; //The maximum number of seconds to allow cURL functions to execute.

		$configInstance = $builderInstance
		->autoRefreshFields($autoRefreshFields)
		->pickListValidation($pickListValidation)
		->sslVerification($enableSSLVerification)
		->connectionTimeout($connectionTimeout)
		->timeout($timeout)
		->build();

		$requestProxy = (new ProxyBuilder())->host("proxyHost")->port(3306)->user("proxyUser")->password("password")->build();

       	/*
		 * Call static initialize method of Initializer class that takes the arguments
		 * 1 -> UserSignature instance
		 * 2 -> Environment instance
		 * 3 -> Token instance
		 * 4 -> TokenStore instance
		 * 5 ->
		 * 6 -> The path containing the absolute directory path to store user specific JSON files containing module fields information.
		 * 7 -> Logger instance
		 * 8 -> RequestProxy instance
		*/
		(new InitializeBuilder())
		->user($user)
		->environment($environment)
		->token($token)
		->store($tokenstore)
		->SDKConfig($configInstance)
		->resourcePath($resourcePath)
		->logger($logger)
		// ->requestProxy($requestProxy)
		->initialize();
    }
}
?>