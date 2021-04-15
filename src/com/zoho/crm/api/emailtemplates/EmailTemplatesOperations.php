<?php 
namespace com\zoho\crm\api\emailtemplates;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class EmailTemplatesOperations
{

	private  $module;

	/**
	 * Creates an instance of EmailTemplatesOperations with the given parameters
	 * @param string $module A string
	 */
	public function __Construct(string $module=null)
	{
		$this->module=$module; 

	}

	/**
	 * The method to get email templates
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getEmailTemplates()
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/email_templates'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('module', 'com.zoho.crm.api.EmailTemplates.GetEmailTemplatesParam'), $this->module); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to get email template by id
	 * @param string $id A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getEmailTemplateById(string $id)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/email_templates/'); 
		$apiPath=$apiPath.(strval($id)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('module', 'com.zoho.crm.api.EmailTemplates.GetEmailTemplatebyIDParam'), $this->module); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}
} 
