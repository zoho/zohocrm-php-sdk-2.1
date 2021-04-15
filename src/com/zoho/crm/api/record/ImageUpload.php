<?php 
namespace com\zoho\crm\api\record;

use com\zoho\crm\api\util\Model;

class ImageUpload implements Model
{

	private  $description;
	private  $previewId;
	private  $encryptedId;
	private  $fileName;
	private  $state;
	private  $fileId;
	private  $size;
	private  $sequenceNumber;
	private  $id;
	private  $keyModified=array();

	/**
	 * The method to get the description
	 * @return string A string representing the description
	 */
	public  function getDescription()
	{
		return $this->description; 

	}

	/**
	 * The method to set the value to description
	 * @param string $description A string
	 */
	public  function setDescription(string $description)
	{
		$this->description=$description; 
		$this->keyModified['Description'] = 1; 

	}

	/**
	 * The method to get the previewId
	 * @return string A string representing the previewId
	 */
	public  function getPreviewId()
	{
		return $this->previewId; 

	}

	/**
	 * The method to set the value to previewId
	 * @param string $previewId A string
	 */
	public  function setPreviewId(string $previewId)
	{
		$this->previewId=$previewId; 
		$this->keyModified['Preview_Id'] = 1; 

	}

	/**
	 * The method to get the encryptedId
	 * @return string A string representing the encryptedId
	 */
	public  function getEncryptedId()
	{
		return $this->encryptedId; 

	}

	/**
	 * The method to set the value to encryptedId
	 * @param string $encryptedId A string
	 */
	public  function setEncryptedId(string $encryptedId)
	{
		$this->encryptedId=$encryptedId; 
		$this->keyModified['Encrypted_Id'] = 1; 

	}

	/**
	 * The method to get the fileName
	 * @return string A string representing the fileName
	 */
	public  function getFileName()
	{
		return $this->fileName; 

	}

	/**
	 * The method to set the value to fileName
	 * @param string $fileName A string
	 */
	public  function setFileName(string $fileName)
	{
		$this->fileName=$fileName; 
		$this->keyModified['File_Name'] = 1; 

	}

	/**
	 * The method to get the state
	 * @return string A string representing the state
	 */
	public  function getState()
	{
		return $this->state; 

	}

	/**
	 * The method to set the value to state
	 * @param string $state A string
	 */
	public  function setState(string $state)
	{
		$this->state=$state; 
		$this->keyModified['State'] = 1; 

	}

	/**
	 * The method to get the fileId
	 * @return string A string representing the fileId
	 */
	public  function getFileId()
	{
		return $this->fileId; 

	}

	/**
	 * The method to set the value to fileId
	 * @param string $fileId A string
	 */
	public  function setFileId(string $fileId)
	{
		$this->fileId=$fileId; 
		$this->keyModified['File_Id'] = 1; 

	}

	/**
	 * The method to get the size
	 * @return string A string representing the size
	 */
	public  function getSize()
	{
		return $this->size; 

	}

	/**
	 * The method to set the value to size
	 * @param string $size A string
	 */
	public  function setSize(string $size)
	{
		$this->size=$size; 
		$this->keyModified['Size'] = 1; 

	}

	/**
	 * The method to get the sequenceNumber
	 * @return int A int representing the sequenceNumber
	 */
	public  function getSequenceNumber()
	{
		return $this->sequenceNumber; 

	}

	/**
	 * The method to set the value to sequenceNumber
	 * @param int $sequenceNumber A int
	 */
	public  function setSequenceNumber(int $sequenceNumber)
	{
		$this->sequenceNumber=$sequenceNumber; 
		$this->keyModified['Sequence_Number'] = 1; 

	}

	/**
	 * The method to get the id
	 * @return string A string representing the id
	 */
	public  function getId()
	{
		return $this->id; 

	}

	/**
	 * The method to set the value to id
	 * @param string $id A string
	 */
	public  function setId(string $id)
	{
		$this->id=$id; 
		$this->keyModified['id'] = 1; 

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
