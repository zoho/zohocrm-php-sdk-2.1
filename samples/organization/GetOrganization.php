<?php
namespace samples\organization;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\UserSignature;
use com\zoho\crm\api\org\APIException;
use com\zoho\crm\api\org\OrgOperations;
use com\zoho\crm\api\org\ResponseWrapper;
require_once "vendor/autoload.php";

class GetOrganization
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

	public static function getOrganization()
	{
		$orgOperations = new OrgOperations();
		$response = $orgOperations->getOrganization();
		if($response != null)
		{
			echo("Status Code: " . $response->getStatusCode() . "\n");
            $responseHandler = $response->getObject();
            if($responseHandler instanceof ResponseWrapper)
            {
                $responseWrapper = $responseHandler;
                $orgs = $responseWrapper->getOrg();
                foreach($orgs as $org)
                {
                    echo("Organization Country: " . $org->getCountry() . "\n");
                    $hierarchyPreferences = $org->getHierarchyPreferences();
                    if($hierarchyPreferences != null)
                    {
                        echo("Organization HierarchyPreferences Type: " . $hierarchyPreferences->getType() . "\n");
                    }
                    echo("Organization PhotoId: " . $org->getPhotoId() . "\n");
                    echo("Organization City: " . $org->getCity() . "\n");
                    echo("Organization Description: " . $org->getDescription() . "\n");
                    echo("Organization McStatus: "); print_r($org->getMcStatus()); echo("\n");
                    echo("Organization GappsEnabled: "); print_r($org->getGappsEnabled()); echo("\n");
                    echo("Organization DomainName: " . $org->getDomainName() . "\n");
                    echo("Organization TranslationEnabled: "); print_r($org->getTranslationEnabled()); echo("\n");
                    echo("Organization Street: " . $org->getStreet() . "\n");
                    echo("Organization Alias: " . $org->getAlias() . "\n");
                    echo("Organization Currency: " . $org->getCurrency() . "\n");
                    echo("Organization Id: " . $org->getId() . "\n");
                    echo("Organization State: " . $org->getState() . "\n");
                    echo("Organization Fax: " . $org->getFax() . "\n");
                    echo("Organization EmployeeCount: " . $org->getEmployeeCount() . "\n");
                    echo("Organization Zip: " . $org->getZip() . "\n");
                    echo("Organization Website: " . $org->getWebsite() . "\n");
                    echo("Organization CurrencySymbol: " . $org->getCurrencySymbol() . "\n");
                    echo("Organization Mobile: " . $org->getMobile() . "\n");
                    echo("Organization CurrencyLocale: " . $org->getCurrencyLocale() . "\n");
                    echo("Organization PrimaryZuid: " . $org->getPrimaryZuid() . "\n");
                    echo("Organization ZiaPortalId: " . $org->getZiaPortalId() . "\n");
                    echo("Organization TimeZone: " . $org->getTimeZone() . "\n");
                    echo("Organization Zgid: " . $org->getZgid() . "\n");
                    echo("Organization CountryCode: " . $org->getCountryCode() . "\n");
                    $licenseDetails = $org->getLicenseDetails();
                    if($licenseDetails != null)
                    {
                        echo("Organization LicenseDetails PaidExpiry: "); print_r($licenseDetails->getPaidExpiry()); echo("\n");
                        echo("Organization LicenseDetails UsersLicensePurchased: " . $licenseDetails->getUsersLicensePurchased() . "\n");
                        echo("Organization LicenseDetails TrialType: " . $licenseDetails->getTrialType() . "\n");
                        echo("Organization LicenseDetails TrialExpiry: " . $licenseDetails->getTrialExpiry() . "\n");
                        echo("Organization LicenseDetails Paid: "); print_r($licenseDetails->getPaid()); echo("\n");
                        echo("Organization LicenseDetails PaidType: " . $licenseDetails->getPaidType() . "\n");
                    }
                    echo("Organization Phone: " . $org->getPhone() . "\n");
                    echo("Organization CompanyName: " . $org->getCompanyName() . "\n");
                    echo("Organization PrivacySettings: "); print_r($org->getPrivacySettings()); echo("\n");
                    echo("Organization PrimaryEmail: " . $org->getPrimaryEmail() . "\n");
                    echo("Organization HipaaComplianceEnabled: "); print_r($org->getHipaaComplianceEnabled()); echo("\n");
                    echo("Organization IsoCode: " . $org->getIsoCode() . "\n");
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

GetOrganization::initialize();
GetOrganization::getOrganization();