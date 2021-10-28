<?php
namespace com\zoho\crm\sample\customview;

use com\zoho\crm\api\customviews\APIException;

use com\zoho\crm\api\customviews\CustomViewsOperations;

use com\zoho\crm\api\customviews\ResponseWrapper;

class CustomView
{
	/**
	 * <h3> Get CustomViews </h3>
	 * This method is used to get the custom views data of a particular module.
	 * Specify the module name in your API request whose custom view data you want to retrieve.
	 * @param moduleAPIName - Specify the API name of the required module.
	 * @throws Exception
	 */
	public static function getCustomViews(string $moduleAPIName)
	{
		//example
		//$moduleAPIName = "module_api_name";

		//Get instance of CustomViewOperations Class that takes moduleAPIName as parameter
		$customViewsOperations = new CustomViewsOperations($moduleAPIName);

		//Call getCustomViews method
		$response = $customViewsOperations->getCustomViews();

		if($response != null)
		{
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
            }

			//Get object from response
            $responseHandler = $response->getObject();

            if($responseHandler instanceof ResponseWrapper)
            {
                //Get the received ResponseWrapper instance
                $responseWrapper = $responseHandler;

                //Get the list of obtained CustomView instances
                $customViews = $responseWrapper->getCustomViews();

                foreach($customViews as $customView)
                {
                    //Get the DisplayValue of each CustomView
                    echo("CustomView DisplayValue: " . $customView->getDisplayValue() . "\n");

                    //Get the Default of each CustomView
                    echo("CustomView Default: " . $customView->getDefault() . "\n");

                    //Get the ModifiedTime of each CustomView
                    echo("CustomView ModifiedTime: "); print_r($customView->getModifiedTime()); echo("\n");

                    //Get the SystemName of each CustomView
                    echo("CustomView SystemName: " . $customView->getSystemName() . "\n");

                    //Get the Name of each CustomView
                    echo("CustomView Name: " . $customView->getName() . "\n");

                    //Get the SystemDefined of each CustomView
                    echo("CustomView SystemDefined: " . $customView->getSystemDefined() . "\n");

                    //Get the modifiedBy User instance of each CustomView
                    $modifiedBy = $customView->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the Name of the modifiedBy User
                        echo("CustomView Modified By User-Name: " . $modifiedBy->getName() . "\n");

                        //Get the ID of the modifiedBy User
                        echo("CustomView Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }

                    //Get the ID of each CustomView
                    echo("CustomView ID: " . $customView->getId() . "\n");

                    //Get the Category of each CustomView
                    echo("CustomView Category: " . $customView->getCategory() . "\n");

                    //Get the LastAccessedTime of each CustomView
                    echo("CustomView LastAccessedTime: "); print_r($customView->getLastAccessedTime()); echo("\n");

                    if($customView->getFavorite() != null)
                    {
                        //Get the Favorite of each CustomView
                        echo("CustomView Favorite: " . $customView->getFavorite() . "\n");
                    }

                    //Get the createdBy User instance of each CustomView
                    $createdBy = $customView->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Name of the createdBy User
                        echo("CustomView Created By User-Name: " . $createdBy->getName() . "\n");

                        //Get the ID of the createdBy User
                        echo("CustomView Created By User-ID: " . $createdBy->getId() . "\n");
                    }
                }

                //Get the Object obtained Info instance
                $info = $responseWrapper->getInfo();

                //Check if info is not null
                if($info != null)
                {
                    if($info->getPerPage() != null)
                    {
                        //Get the PerPage of the Info
                        echo("CustomView Info PerPage: " . $info->getPerPage() . "\n");
                    }

                    if($info->getDefault() != null)
                    {
                        //Get the Default of the Info
                        echo("CustomView Info Default: " . $info->getDefault() . "\n");
                    }

                    if($info->getCount() != null)
                    {
                        //Get the Count of the Info
                        echo("CustomView Info Count: " . $info->getCount() . "\n");
                    }

                    //Get the Translation instance of CustomView
                    $translation = $info->getTranslation();

                    if($translation != null)
                    {
                        //Get the PublicViews of the Translation
                        echo("CustomView Info Translation PublicViews: " . $translation->getPublicViews() . "\n");

                        //Get the OtherUsersViews of the Translation
                        echo("CustomView Info Translation OtherUsersViews: " . $translation->getOtherUsersViews() . "\n");

                        //Get the SharedWithMe of the Translation
                        echo("CustomView Info Translation SharedWithMe: " . $translation->getSharedWithMe() . "\n");

                        //Get the CreatedByMe of the Translation
                        echo("CustomView Info Translation CreatedByMe: " . $translation->getCreatedByMe() . "\n");
                    }

                    if($info->getPage() != null)
                    {
                        //Get the Page of the Info
                        echo("CustomView Info Page: " . $info->getPage() . "\n");
                    }

                    //Get the MoreRecords of the Info
                    echo("CustomView Info MoreRecords: "); print_r($info->getMoreRecords()); echo("\n");
                }
            }
            //Check if the request returned an exception
            else if($responseHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $responseHandler;

                //Get the Status
                echo("Status: " . $exception->getStatus()->getValue() . "\n");

                //Get the Code
                echo("Code: " . $exception->getCode()->getValue() . "\n");

                echo("Details: " );

                //Get the details map4
                foreach($exception->getDetails() as $key => $value)
                {
                    //Get each value in the map
                    echo($key . ": " .$value . "\n");
                }

                //Get the Message
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}

	/**
	 * This method is used to get the data of any specific custom view of the module.
	 * Specify the custom view ID of the module in your API request whose custom view data you want to retrieve.
	 * @param moduleAPIName - Specify the API name of the required module.
	 * @param customID - ID of the CustomView to be obtainted.
	 * @throws Exception
	 */
	public static function getCustomView(string $moduleAPIName, string $customViewId)
	{
		//example
		//String moduleAPIName = "module_api_name";
		//$customViewId = "3477065629003";

		//Get instance of CustomViewOperations Class that takes moduleAPIName as parameter
		$customViewsOperations = new CustomViewsOperations($moduleAPIName);

		//Call getCustomView method that takes customViewId as parameter
		$response = $customViewsOperations->getCustomView($customViewId);

		if($response != null)
		{
            //Get the status code from response
            echo("Status code : " . $response->getStatusCode() . "\n");

            if(in_array($response->getStatusCode(), array(204, 304)))
            {
                echo($response->getStatusCode() == 204? "No Content\n" : "Not Modified\n");

                return;
            }

			//Get object from response
            $responseHandler = $response->getObject();

            if($responseHandler instanceof ResponseWrapper)
            {
                //Get the received ResponseWrapper instance
                $responseWrapper = $responseHandler;

                //Get the list of obtained CustomView instances
                $customViews = $responseWrapper->getCustomViews();

                foreach($customViews as $customView)
                {
                    //Get the DisplayValue of each CustomView
                    echo("CustomView DisplayValue: " . $customView->getDisplayValue() . "\n");

                    //Get the AccessType of each CustomView
                    echo("CustomView AccessType: " . $customView->getAccessType() . "\n");

                    // Get the Criteria instance of each Query
                    $criteria = $customView->getCriteria();

                    //Check if criteria is not null
                    if($criteria != null)
                    {
                        self::printCriteria($criteria);
                    }

                    //Get the SystemName of each CustomView
                    echo("CustomView SystemName: " . $customView->getSystemName() . "\n");

                    //Get the SortBy of the each CustomView
                    echo("CustomView SortBy: " . $customView->getSortBy() . "\n");

                    //Get the createdBy User instance of each CustomView
                    $createdBy = $customView->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Name of the createdBy User
                        echo("CustomView Created By User-Name: " . $createdBy->getName() . "\n");

                        //Get the ID of the createdBy User
                        echo("CustomView Created By User-ID: " . $createdBy->getId() . "\n");
                    }

                    //Get the createdBy SharedTo instance of each CustomView
                    $sharedToArray = $customView->getSharedTo();

                    //Check if sharedToArray is not null
                    if($sharedToArray != null)
                    {
                        foreach($sharedToArray as $sharedTo)
                        {
                            //Get the Name of the sharedTo
                            echo("CustomView sharedTo Name: " . $sharedTo->getName() . "\n");

                            //Get the ID of the sharedTo
                            echo("CustomView sharedTo Id: " . $sharedTo->getId() . "\n");

                            //Get the Type of the sharedTo
                            echo("CustomView sharedTo Type: " . $sharedTo->getType() . "\n");

                            //Get the Subordinates of the sharedTo
                            echo("CustomView sharedTo Subordinates: " . $sharedTo->getSubordinates() . "\n");
                        }
                    }

                    //Get the Default of each CustomView
                    echo("CustomView Default: "); print_r($customView->getDefault()); echo("\n");

                    //Get the ModifiedTime of each CustomView
                    echo("CustomView ModifiedTime: "); print_r($customView->getModifiedTime()); echo("\n");

                    //Get the Name of each CustomView
                    echo("CustomView Name: " . $customView->getName() . "\n");

                    //Get the SystemDefined of each CustomView
                    echo("CustomView SystemDefined: " . $customView->getSystemDefined() . "\n");

                    //Get the modifiedBy User instance of each CustomView
                    $modifiedBy = $customView->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the Name of the modifiedBy User
                        echo("CustomView Modified By User-Name: " . $modifiedBy->getName() . "\n");

                        //Get the ID of the modifiedBy User
                        echo("CustomView Modified By User-ID: " . $modifiedBy->getId() . "\n");
                    }

                    //Get the ID of each CustomView
                    echo("CustomView ID: " . $customView->getId() . "\n");

                    //Get the list of string  each CustomView
                    $fields = $customView->getFields();

                    if($fields != null)
                    {
                        foreach($fields as $field)
                        {
                            //Get the APIName of the Fields
                            echo("CustomView Fields APIName: " . $field->getAPIName() . "\n");

                            //Get the Id of the Fields
                            echo("CustomView Fields Id: " . $field->getId() . "\n");
                        }
                    }

                    //Get the Category of each CustomView
                    echo("CustomView Category: " . $customView->getCategory() . "\n");

                    //Get the LastAccessedTime of each CustomView
                    echo("CustomView LastAccessedTime: "); print_r($customView->getLastAccessedTime()); echo("\n");

                    //Get the SortOrder of each CustomView
                    echo("CustomView SortOrder: "); print_r($customView->getSortOrder()); echo("\n");

                    if($customView->getFavorite() != null)
                    {
                        //Get the Favorite of each CustomView
                        echo("CustomView Favorite: " . $customView->getFavorite() . "\n");
                    }
                }

                //Get the Object obtained Info instance
                $info = $responseWrapper->getInfo();

                //Check if info is not null
                if($info != null)
                {
                    //Get the Translation instance of CustomView
                    $translation = $info->getTranslation();

                    if($translation != null)
                    {
                        //Get the PublicViews of the Translation
                        echo("CustomView Info Translation PublicViews: " . $translation->getPublicViews() . "\n");

                        //Get the OtherUsersViews of the Translation
                        echo("CustomView Info Translation OtherUsersViews: " . $translation->getOtherUsersViews() . "\n");

                        //Get the SharedWithMe of the Translation
                        echo("CustomView Info Translation SharedWithMe: " .  $translation->getSharedWithMe() . "\n");

                        //Get the CreatedByMe of the Translation
                        echo("CustomView Info Translation CreatedByMe: " .  $translation->getCreatedByMe() . "\n");
                    }
                }
            }
            //Check if the request returned an exception
            else if($responseHandler instanceof APIException)
            {
                //Get the received APIException instance
                $exception = $responseHandler;

                //Get the Status
                echo("Status: " . $exception->getStatus()->getValue() . "\n");

                //Get the Code
                echo("Code: " . $exception->getCode()->getValue() . "\n");

                echo("Details: " );

                //Get the details map4
                foreach($exception->getDetails() as $key => $value)
                {
                    //Get each value in the map
                    echo($key . ": " .$value . "\n");
                }

                //Get the Message
                echo("Message: " . $exception->getMessage()->getValue() . "\n");
            }
		}
	}

	private static function printCriteria($criteria)
    {
        if( $criteria->getComparator() != null)
        {
            //Get the Comparator of the Criteria
		    echo("CustomView Criteria Comparator: " . $criteria->getComparator()->getValue() . "\n");
        }

        //Get the Field of the Criteria
        $field = $criteria->getField();

        if($field != null)
        {
            echo("CustomView Criteria Field: " . $field->getAPIName() . "\n");

            echo("CustomView Criteria Field: " . $field->getId() . "\n");
        }

		//Get the Value of the Criteria
        echo("CustomView Criteria Value: "); print_r($criteria->getValue()); echo("\n");

		// Get the List of Criteria instance of each Criteria
		$criteriaGroup = $criteria->getGroup();

		if($criteriaGroup != null)
		{
			foreach($criteriaGroup as $criteria1)
			{
				self::printCriteria($criteria1);
			}
		}

        if($criteria->getGroupOperator() != null)
        {
            //Get the Group Operator of the Criteria
		    echo("CustomView Criteria Group Operator: " . $criteria->getGroupOperator()->getValue() . "\n");
        }
    }
}
