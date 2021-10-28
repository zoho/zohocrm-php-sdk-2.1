<?php 
namespace com\zoho\crm\api\inventorytemplates;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\ParameterMap;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class InventoryTemplatesOperations
{

	private  $sortBy;
	private  $sortOrder;
	private  $category;

	/**
	 * Creates an instance of InventoryTemplatesOperations with the given parameters
	 * @param string $sortBy A string
	 * @param string $sortOrder A string
	 * @param string $category A string
	 */
	public function __Construct(string $sortBy=null, string $sortOrder=null, string $category=null)
	{
		$this->sortBy=$sortBy; 
		$this->sortOrder=$sortOrder; 
		$this->category=$category; 

	}

	/**
	 * The method to get inventory templates
	 * @param ParameterMap $paramInstance An instance of ParameterMap
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getInventoryTemplates(ParameterMap $paramInstance=null)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/inventory_templates'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('sort_by', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatesParam'), $this->sortBy); 
		$handlerInstance->addParam(new Param('sort_order', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatesParam'), $this->sortOrder); 
		$handlerInstance->addParam(new Param('category', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatesParam'), $this->category); 
		$handlerInstance->setParam($paramInstance); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to get inventory template by id
	 * @param string $id A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getInventoryTemplateById(string $id)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/inventory_templates/'); 
		$apiPath=$apiPath.(strval($id)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('sort_by', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatebyIDParam'), $this->sortBy); 
		$handlerInstance->addParam(new Param('sort_order', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatebyIDParam'), $this->sortOrder); 
		$handlerInstance->addParam(new Param('category', 'com.zoho.crm.api.InventoryTemplates.GetInventoryTemplatebyIDParam'), $this->category); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}
} 
