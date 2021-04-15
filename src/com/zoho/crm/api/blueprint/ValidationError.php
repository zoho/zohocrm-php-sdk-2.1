<?php 
namespace com\zoho\crm\api\blueprint;

use com\zoho\crm\api\util\Model;

class ValidationError implements Model
{

	private  $apiName;
	private  $infoMessage;
	private  $message;
	private  $index;
	private  $parentAPIName;
	private  $keyModified=array();

	/**
	 * The method to get the aPIName
	 * @return string A string representing the apiName
	 */
	public  function getAPIName()
	{
		return $this->apiName; 

	}

	/**
	 * The method to set the value to aPIName
	 * @param string $apiName A string
	 */
	public  function setAPIName(string $apiName)
	{
		$this->apiName=$apiName; 
		$this->keyModified['api_name'] = 1; 

	}

	/**
	 * The method to get the infoMessage
	 * @return string A string representing the infoMessage
	 */
	public  function getInfoMessage()
	{
		return $this->infoMessage; 

	}

	/**
	 * The method to set the value to infoMessage
	 * @param string $infoMessage A string
	 */
	public  function setInfoMessage(string $infoMessage)
	{
		$this->infoMessage=$infoMessage; 
		$this->keyModified['info_message'] = 1; 

	}

	/**
	 * The method to get the message
	 * @return string A string representing the message
	 */
	public  function getMessage()
	{
		return $this->message; 

	}

	/**
	 * The method to set the value to message
	 * @param string $message A string
	 */
	public  function setMessage(string $message)
	{
		$this->message=$message; 
		$this->keyModified['message'] = 1; 

	}

	/**
	 * The method to get the index
	 * @return int A int representing the index
	 */
	public  function getIndex()
	{
		return $this->index; 

	}

	/**
	 * The method to set the value to index
	 * @param int $index A int
	 */
	public  function setIndex(int $index)
	{
		$this->index=$index; 
		$this->keyModified['index'] = 1; 

	}

	/**
	 * The method to get the parentAPIName
	 * @return string A string representing the parentAPIName
	 */
	public  function getParentAPIName()
	{
		return $this->parentAPIName; 

	}

	/**
	 * The method to set the value to parentAPIName
	 * @param string $parentAPIName A string
	 */
	public  function setParentAPIName(string $parentAPIName)
	{
		$this->parentAPIName=$parentAPIName; 
		$this->keyModified['parent_api_name'] = 1; 

	}

	/**
	 * The method to check if the user has modified the given key
	 * @param string $key A string
	 * @return int A int representing the modification
	 */
	public  function isKeyModified(string $key)
	{
		if(((array_key_exists($key, $this->keyModified))))
		{
			return $this->keyModified[$key]; 

		}
		return null; 

	}

	/**
	 * The method to mark the given key as modified
	 * @param string $key A string
	 * @param int $modification A int
	 */
	public  function setKeyModified(string $key, int $modification)
	{
		$this->keyModified[$key] = $modification; 

	}
} 
