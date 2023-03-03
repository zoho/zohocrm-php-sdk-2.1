<?php 
namespace com\zoho\crm\api\cancelmeetings;

use com\zoho\crm\api\exception\SDKException;
use com\zoho\crm\api\util\CommonAPIHandler;
use com\zoho\crm\api\util\Constants;
use com\zoho\crm\api\util\APIResponse;

class CancelMeetingsOperations
{

	private  $eventId;

	/**
	 * Creates an instance of CancelMeetingsOperations with the given parameters
	 * @param string $eventId A string
	 */
	public function __Construct(string $eventId)
	{
		$this->eventId=$eventId; 

	}

	/**
	 * The method to cancel meetings
	 * @param BodyWrapper $request An instance of BodyWrapper
	 * @return APIResponse An instance of APIResponse
	 */
	public  function cancelMeetings(BodyWrapper $request)
	{
		$handlerInstance=new CommonAPIHandler(); 
		$apiPath=""; 
		$apiPath=$apiPath.('/crm/v2.1/Events/'); 
		$apiPath=$apiPath.(strval($this->eventId)); 
		$apiPath=$apiPath.('/actions/cancel'); 
		$handlerInstance->setAPIPath($apiPath); 
		$handlerInstance->setHttpMethod(Constants::REQUEST_METHOD_POST); 
		$handlerInstance->setCategoryMethod(Constants::REQUEST_CATEGORY_ACTION); 
		$handlerInstance->setContentType('application/json'); 
		$handlerInstance->setRequest($request); 
		return $handlerInstance->apiCall(ActionHandler::class, 'application/json'); 

	}
} 
