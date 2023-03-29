<?php
namespace samples\users;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\HeaderMap;
use com\zoho\crm\api\users\APIException;
use com\zoho\crm\api\users\ResponseWrapper;
use com\zoho\crm\api\users\UsersOperations;
use com\zoho\crm\api\users\GetUserHeader;
require_once "vendor/autoload.php";

class GetUser
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

	public static function getUser(string $userId)
	{
		$usersOperations = new UsersOperations();
        $headerInstance = new HeaderMap();
        $ifmodifiedsince = date_create("2020-07-15T17:58:47+05:30")->setTimezone(new \DateTimeZone(date_default_timezone_get()));
		$headerInstance->add(GetUserHeader::IfModifiedSince(), $ifmodifiedsince);
		$response = $usersOperations->getUser($userId,$headerInstance);
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
                $users = $responseWrapper->getUsers();
                foreach($users as $user)
                {
                    echo("User Country: " . $user->getCountry() . "\n");
                    $role = $user->getRole();
                    if($role != null)
                    {
                        echo("User Role Name: " . $role->getName() . "\n");
                        echo("User Role ID: " . $role->getId() . "\n");
                    }
                    $customizeInfo = $user->getCustomizeInfo();
                    if($customizeInfo != null)
                    {
                        echo("User CustomizeInfo NotesDesc: "); print_r($customizeInfo->getNotesDesc()); echo("\n");
                        echo("User CustomizeInfo ShowRightPanel: "); print_r($customizeInfo->getShowRightPanel()); echo("\n");
                        echo("User CustomizeInfo BcView: "); print_r($customizeInfo->getBcView()); echo("\n");
                        echo("User CustomizeInfo ShowHome: "); print_r($customizeInfo->getShowHome()); echo("\n");
                        echo("User CustomizeInfo ShowDetailView: "); print_r($customizeInfo->getShowDetailView()); echo("\n");
                        echo("User CustomizeInfo UnpinRecentItem: "); print_r($customizeInfo->getUnpinRecentItem()); echo("\n");
                    }
                    echo("User City: " . $user->getCity() . "\n");
                    echo("User Signature: " . $user->getSignature() . "\n");
                    echo("User SortOrderPreference: " . $user->getSortOrderPreference() . "\n");
                    echo("User NameFormat: " . $user->getNameFormat() . "\n");
                    echo("User Language: " . $user->getLanguage() . "\n");
                    echo("User Locale: " . $user->getLocale() . "\n");
                    echo("User Microsoft: "); print_r($user->getMicrosoft()); echo("\n");
                    echo("User PersonalAccount: "); print_r($user->getPersonalAccount()); echo("\n");
                    echo("User Isonline: "); print_r($user->getIsonline()); echo("\n");
                    echo("User DefaultTabGroup: " . $user->getDefaultTabGroup() . "\n");
                    $modifiedBy = $user->getModifiedBy();
                    if($modifiedBy != null)
                    {
                        echo("User Modified By User-Name: " . $modifiedBy->getName() . "\n");
                        echo("User Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }
                    echo("User Street: " . $user->getStreet() . "\n");
                    echo("User Currency: " . $user->getCurrency() . "\n");
                    echo("User Alias: " . $user->getAlias() . "\n");
                    $theme = $user->getTheme();
                    if($theme != null)
                    {
                        $normalTab = $theme->getNormalTab();
                        if($normalTab != null)
                        {
                            echo("User Theme NormalTab FontColor: " . $normalTab->getFontColor() . "\n");
                            echo("User Theme NormalTab Name: " . $normalTab->getBackground() . "\n");
                        }
                        $selectedTab = $theme->getSelectedTab();
                        if($selectedTab != null)
                        {
                            echo("User Theme SelectedTab FontColor: " . $selectedTab->getFontColor() . "\n");
                            echo("User Theme SelectedTab Name: " . $selectedTab->getBackground() . "\n");
                        }
                        echo("User Theme NewBackground: " . $theme->getNewBackground() . "\n");
                        echo("User Theme Background: " . $theme->getBackground() . "\n");
                        echo("User Theme Screen: " . $theme->getScreen() . "\n");
                        echo("User Theme Type: " . $theme->getType() . "\n");
                    }
                    echo("User ID: " . $user->getId() . "\n");
                    echo("User State: " . $user->getState() . "\n");
                    echo("User Fax: " . $user->getFax() . "\n");
                    echo("User CountryLocale: " . $user->getCountryLocale() . "\n");
                    echo("User SandboxDeveloper: "); print_r($user->getSandboxDeveloper()); echo("\n");
                    echo("User FirstName: " . $user->getFirstName() . "\n");
                    echo("User Email: " . $user->getEmail() . "\n");
                    $reportingTo = $user->getReportingTo();
                    if($reportingTo != null)
                    {
                        echo("User ReportingTo Name: " . $reportingTo->getName() . "\n");
                        echo("User ReportingTo ID: " . $reportingTo->getId() . "\n");
                    }
                    echo("User Zip: " . $user->getZip() . "\n");
                    echo("User DecimalSeparator: " . $user->getDecimalSeparator() . "\n");
                    echo("User CreatedTime: "); print_r($user->getCreatedTime()); echo("\n");
                    echo("User Website: " . $user->getWebsite() . "\n");
                    echo("User ModifiedTime: "); print_r($user->getModifiedTime()); echo("\n");
                    echo("User TimeFormat: " . $user->getTimeFormat() . "\n");
                    echo("User Offset: " . $user->getOffset() . "\n");
                    $profile = $user->getProfile();
                    if($profile != null)
                    {
                        echo("User Profile Name: " . $profile->getName() . "\n");
                        echo("User Profile ID: " . $profile->getId() . "\n");
                    }
                    echo("User Mobile: " . $user->getMobile() . "\n");
                    echo("User LastName: " . $user->getLastName() . "\n");
                    echo("User TimeZone: " . $user->getTimeZone() . "\n");
                    $createdBy = $user->getCreatedBy();
                    if($createdBy != null)
                    {
                        echo("User Created By User-Name: " . $createdBy->getName() . "\n");
                        echo("User Created By User-ID: " . $createdBy->getId() . "\n");
                    }
                    echo("User Zuid: " . $user->getZuid() . "\n");
                    echo("User Confirm: "); print_r($user->getConfirm()); echo("\n");
                    echo("User FullName: " . $user->getFullName() . "\n");
                    $territories = $user->getTerritories();
                    if($territories != null)
                    {
                        foreach($territories as $territory)
                        {
                            echo("User Territory Manager: "); print_r($territory->getManager()); echo("\n");
                            echo("User Territory Name: " . $territory->getName() . "\n");
                            echo("User Territory ID: " . $territory->getId() . "\n");
                        }
                    }
                    echo("User Phone: " . $user->getPhone() . "\n");
                    echo("User DOB: " . $user->getDob() . "\n");
                    echo("User DateFormat: " . $user->getDateFormat() . "\n");
                    echo("User Status: " . $user->getStatus() . "\n");
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

GetUser::initialize();
$userId = "347706118213050";
GetUser::getUser($userId);