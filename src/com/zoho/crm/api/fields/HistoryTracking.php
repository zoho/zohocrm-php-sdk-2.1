<?php 
namespace com\zoho\crm\api\fields;

use com\zoho\crm\api\util\Model;

class HistoryTracking implements Model
{

	private  $module;
	private  $durationConfiguredField;
	private  $keyModified=array();

	/**
	 * The method to get the module
	 * @return Module An instance of Module
	 */
	public  function getModule()
	{
		return $this->module; 

	}

	/**
	 * The method to set the value to module
	 * @param Module $module An instance of Module
	 */
	public  function setModule(Module $module)
	{
		$this->module=$module; 
		$this->keyModified['module'] = 1; 

	}

	/**
	 * The method to get the durationConfiguredField
	 * @return Field An instance of Field
	 */
	public  function getDurationConfiguredField()
	{
		return $this->durationConfiguredField; 

	}

	/**
	 * The method to set the value to durationConfiguredField
	 * @param Field $durationConfiguredField An instance of Field
	 */
	public  function setDurationConfiguredField(Field $durationConfiguredField)
	{
		$this->durationConfiguredField=$durationConfiguredField; 
		$this->keyModified['duration_configured_field'] = 1; 

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
