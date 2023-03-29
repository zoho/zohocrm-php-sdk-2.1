<?php
namespace samples\users;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\roles\Role;
use com\zoho\crm\api\users\APIException;
use com\zoho\crm\api\users\ActionWrapper;
use com\zoho\crm\api\users\BodyWrapper;
use com\zoho\crm\api\users\SuccessResponse;
use com\zoho\crm\api\users\UsersOperations;
require_once "vendor/autoload.php";

class UpdateUser
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

	public static function updateUser(string $userId)
	{
		$usersOperations = new UsersOperations();
		$request = new BodyWrapper();
		$userList = array();
		$userClass = "com\zoho\crm\api\users\User";
		$user1 = new $userClass();
		$role = new Role();
		$role->setId("34770610026008");
		$user1->setRole($role);
		$user1->setCountryLocale("en_US");
		array_push($userList, $user1);
		$request->setUsers($userList);
		$response = $usersOperations->updateUser($userId,$request);
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $actionHandler = $response->getObject();
            if($actionHandler instanceof ActionWrapper)
            {
                $responseWrapper = $actionHandler;
                $actionResponses = $responseWrapper->getUsers();
                foreach($actionResponses as $actionResponse)
                {
                    if($actionResponse instanceof SuccessResponse)
                    {
                        $successResponse = $actionResponse;
                        echo("Status: " . $successResponse->getStatus()->getValue() . "\n");
                        echo("Code: " . $successResponse->getCode()->getValue() . "\n");
                        echo("Details: " );
                        foreach($successResponse->getDetails() as $key => $value)
                        {
                            echo($key . " : " . $value . "\n");
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

UpdateUser::initialize();
$userId = "347706118213050";
UpdateUser::updateUser($userId);