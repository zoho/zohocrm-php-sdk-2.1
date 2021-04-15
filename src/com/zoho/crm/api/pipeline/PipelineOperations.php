<?php 
namespace com\zoho\crm\api\pipeline;

use com\zoho\crm\api\Param;
use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class PipelineOperations
{

	private  $layoutId;

	/**
	 * Creates an instance of PipelineOperations with the given parameters
	 * @param string $layoutId A string
	 */
	public function __Construct(string $layoutId=null)
	{
		$this->layoutId=$layoutId; 

	}

	/**
	 * The method to transfer and delete
	 * @param TransferAndDeleteWrapper $request An instance of TransferAndDeleteWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function transferAndDelete(TransferAndDeleteWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline/actions/transfer'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_POST); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_CREATE); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		$handlerInstance->setMandatoryChecker(true); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.TransferAndDeleteParam'), $this->layoutId); 
		return $handlerInstance->apiCall(TransferActionHandler::class, 'application/json'); 

	}

	/**
	 * The method to get pipelines
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getPipelines()
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.GetPipelinesParam'), $this->layoutId); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to create pipelines
	 * @param BodyWrapper $request An instance of BodyWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function createPipelines(BodyWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_POST); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_CREATE); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		$handlerInstance->setMandatoryChecker(true); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.CreatePipelinesParam'), $this->layoutId); 
		return $handlerInstance->apiCall(ActionHandler::class, 'application/json'); 

	}

	/**
	 * The method to update pipelines
	 * @param BodyWrapper $request An instance of BodyWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function updatePipelines(BodyWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_PUT); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_UPDATE); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		$handlerInstance->setMandatoryChecker(true); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.UpdatePipelinesParam'), $this->layoutId); 
		return $handlerInstance->apiCall(ActionHandler::class, 'application/json'); 

	}

	/**
	 * The method to get pipeline
	 * @param string $pipelineId A string
	 * @return APIResponse An instance of APIResponse
	 */
	public  function getPipeline(string $pipelineId)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline/'); 
		$apiPath=$apiPath.(strval($pipelineId)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_GET); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_READ); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.GetPipelineParam'), $this->layoutId); 
		return $handlerInstance->apiCall(ResponseHandler::class, 'application/json'); 

	}

	/**
	 * The method to update pipeline
	 * @param string $pipelineId A string
	 * @param BodyWrapper $request An instance of BodyWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function updatePipeline(string $pipelineId, BodyWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/settings/pipeline/'); 
		$apiPath=$apiPath.(strval($pipelineId)); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_PUT); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_UPDATE); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		$handlerInstance->addParam(new Param('layout_id', 'com.zoho.crm.api.Pipeline.UpdatePipelineParam'), $this->layoutId); 
		return $handlerInstance->apiCall(ActionHandler::class, 'application/json'); 

	}
} 
