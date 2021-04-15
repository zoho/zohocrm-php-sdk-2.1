<?php 
namespace com\zoho\crm\api\territories;

use com\zoho\crm\api\customviews\Criteria;
use com\zoho\crm\api\users\User;
use com\zoho\crm\api\util\Model;

class Territory implements Model
{

	private  $createdTime;
	private  $modifiedTime;
	private  $manager;
	private  $accountRuleCriteria;
	private  $dealRuleCriteria;
	private  $name;
	private  $modifiedBy;
	private  $description;
	private  $id;
	private  $createdBy;
	private  $reportingTo;
	private  $permissionType;
	private  $keyModified=array();

	/**
	 * The method to get the createdTime
	 * @return \DateTime An instance of \DateTime
	 */
	public  function getCreatedTime()
	{
		return $this->createdTime; 

	}

	/**
	 * The method to set the value to createdTime
	 * @param \DateTime $createdTime An instance of \DateTime
	 */
	public  function setCreatedTime(\DateTime $createdTime)
	{
		$this->createdTime=$createdTime; 
		$this->keyModified['created_time'] = 1; 

	}

	/**
	 * The method to get the modifiedTime
	 * @return \DateTime An instance of \DateTime
	 */
	public  function getModifiedTime()
	{
		return $this->modifiedTime; 

	}

	/**
	 * The method to set the value to modifiedTime
	 * @param \DateTime $modifiedTime An instance of \DateTime
	 */
	public  function setModifiedTime(\DateTime $modifiedTime)
	{
		$this->modifiedTime=$modifiedTime; 
		$this->keyModified['modified_time'] = 1; 

	}

	/**
	 * The method to get the manager
	 * @return User An instance of User
	 */
	public  function getManager()
	{
		return $this->manager; 

	}

	/**
	 * The method to set the value to manager
	 * @param User $manager An instance of User
	 */
	public  function setManager(User $manager)
	{
		$this->manager=$manager; 
		$this->keyModified['manager'] = 1; 

	}

	/**
	 * The method to get the accountRuleCriteria
	 * @return Criteria An instance of Criteria
	 */
	public  function getAccountRuleCriteria()
	{
		return $this->accountRuleCriteria; 

	}

	/**
	 * The method to set the value to accountRuleCriteria
	 * @param Criteria $accountRuleCriteria An instance of Criteria
	 */
	public  function setAccountRuleCriteria(Criteria $accountRuleCriteria)
	{
		$this->accountRuleCriteria=$accountRuleCriteria; 
		$this->keyModified['account_rule_criteria'] = 1; 

	}

	/**
	 * The method to get the dealRuleCriteria
	 * @return Criteria An instance of Criteria
	 */
	public  function getDealRuleCriteria()
	{
		return $this->dealRuleCriteria; 

	}

	/**
	 * The method to set the value to dealRuleCriteria
	 * @param Criteria $dealRuleCriteria An instance of Criteria
	 */
	public  function setDealRuleCriteria(Criteria $dealRuleCriteria)
	{
		$this->dealRuleCriteria=$dealRuleCriteria; 
		$this->keyModified['deal_rule_criteria'] = 1; 

	}

	/**
	 * The method to get the name
	 * @return string A string representing the name
	 */
	public  function getName()
	{
		return $this->name; 

	}

	/**
	 * The method to set the value to name
	 * @param string $name A string
	 */
	public  function setName(string $name)
	{
		$this->name=$name; 
		$this->keyModified['name'] = 1; 

	}

	/**
	 * The method to get the modifiedBy
	 * @return User An instance of User
	 */
	public  function getModifiedBy()
	{
		return $this->modifiedBy; 

	}

	/**
	 * The method to set the value to modifiedBy
	 * @param User $modifiedBy An instance of User
	 */
	public  function setModifiedBy(User $modifiedBy)
	{
		$this->modifiedBy=$modifiedBy; 
		$this->keyModified['modified_by'] = 1; 

	}

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
		$this->keyModified['description'] = 1; 

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
	 * The method to get the createdBy
	 * @return User An instance of User
	 */
	public  function getCreatedBy()
	{
		return $this->createdBy; 

	}

	/**
	 * The method to set the value to createdBy
	 * @param User $createdBy An instance of User
	 */
	public  function setCreatedBy(User $createdBy)
	{
		$this->createdBy=$createdBy; 
		$this->keyModified['created_by'] = 1; 

	}

	/**
	 * The method to get the reportingTo
	 * @return Territory An instance of Territory
	 */
	public  function getReportingTo()
	{
		return $this->reportingTo; 

	}

	/**
	 * The method to set the value to reportingTo
	 * @param Territory $reportingTo An instance of Territory
	 */
	public  function setReportingTo(Territory $reportingTo)
	{
		$this->reportingTo=$reportingTo; 
		$this->keyModified['reporting_to'] = 1; 

	}

	/**
	 * The method to get the permissionType
	 * @return string A string representing the permissionType
	 */
	public  function getPermissionType()
	{
		return $this->permissionType; 

	}

	/**
	 * The method to set the value to permissionType
	 * @param string $permissionType A string
	 */
	public  function setPermissionType(string $permissionType)
	{
		$this->permissionType=$permissionType; 
		$this->keyModified['permission_type'] = 1; 

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
