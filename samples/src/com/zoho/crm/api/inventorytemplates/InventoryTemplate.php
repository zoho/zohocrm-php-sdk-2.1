<?php
namespace com\zoho\crm\sample\inventorytemplates;

use com\zoho\crm\api\inventorytemplates\InventoryTemplatesOperations;

use com\zoho\crm\api\inventorytemplates\ResponseWrapper;

use com\zoho\crm\api\inventorytemplates\APIException;

class InventoryTemplate
{
    public static function getInventoryTemplates(string $module=null, string $sortBy=null, string $sortOrder=null, string $category=null)
    {
        //Get instance of InventoryTemplatesOperations Class
        $inventoryTemplatesOperations = new InventoryTemplatesOperations($module, $sortBy, $sortOrder, $category);

        //Call getInventoryTemplates method
        $response = $inventoryTemplatesOperations->getInventoryTemplates();

        if($response != null)
        {
            //Get the status code from response
            echo("Status code " . $response->getStatusCode() . "\n");

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

                //Get the list of obtained InventoryTemplate instances
                $inventoryTemplates = $responseWrapper->getInventoryTemplates();

                foreach($inventoryTemplates as $inventoryTemplate)
                {
                    //Get the CreatedTime of each InventoryTemplate
                    echo("InventoryTemplate CreatedTime: "); print_r($inventoryTemplate->getCreatedTime()); echo("\n");

                    //Get the ModifiedTime of each InventoryTemplate
                    echo("InventoryTemplate ModifiedTime: "); print_r($inventoryTemplate->getModifiedTime()); echo("\n");

                    //Get the Folder instance of each InventoryTemplate
                    $folder = $inventoryTemplate->getFolder();

                    //Check if folder is not null
                    if($folder != null)
                    {
                        //Get the Id of the Folder
                        echo("InventoryTemplate Folder Id: " . $folder->getId(). "\n");

                        //Get the Name of the Folder
                        echo("InventoryTemplate Folder Name: " . $folder->getName(). "\n");
                    }

                    //Get the LastUsageTime of each InventoryTemplate
                    echo("InventoryTemplate LastUsageTime: "); print_r($inventoryTemplate->getLastUsageTime()); echo("\n");

                    $module = $inventoryTemplate->getModule();

                    if($module != null)
                    {
                        //Get the Module Name of the InventoryTemplate
                        echo("InventoryTemplate Module Name : " . $module->getAPIName() . "\n");

                        //Get the Module Id of the InventoryTemplate
                        echo("InventoryTemplate Module Id : " . $module->getId() . "\n");
                    }

                    //Get the name of each InventoryTemplate
                    echo("InventoryTemplate Name: " . $inventoryTemplate->getName() . "\n");

                    //Get the modifiedBy User instance of each InventoryTemplate
                    $modifiedBy = $inventoryTemplate->getModifiedBy();

                    //Check if modifiedBy is not null
                    if($modifiedBy != null)
                    {
                        //Get the ID of the ModifiedBy User
                        echo("InventoryTemplate Modified By User-ID: " . $modifiedBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("InventoryTemplate Modified By User-Name: " . $modifiedBy->getName(). "\n");
                    }

                    //Get the ID of each InventoryTemplate
                    echo("InventoryTemplate Id: " . $inventoryTemplate->getId() . "\n");

                    //Get the EditorMode each InventoryTemplate
                    echo("InventoryTemplate EditorMode: " . $inventoryTemplate->getEditorMode() . "\n");

                    //Get the Type of each InventoryTemplate
                    echo("InventoryTemplate Type: " . $inventoryTemplate->getType() . "\n");

                    //Get the Favorite of each InventoryTemplate
                    echo("InventoryTemplate Favorite: "); print_r($inventoryTemplate->getFavorite()); echo("\n");

                    //Get the CreatedBy User instance of each InventoryTemplate
                    $createdBy = $inventoryTemplate->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Id of the CreatedBy User
                        echo("InventoryTemplate Created By User-ID: " . $createdBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("InventoryTemplate Created By user-Name: " . $createdBy->getName(). "\n");
                    }
                }

                $info = $responseWrapper->getInfo();

                echo("InventoryTemplate Info PerPage : " . $info->getPerPage() . "\n");

                echo("InventoryTemplate Info Count : " . $info->getCount() . "\n");

                echo("InventoryTemplate Info Page : " . $info->getPage(). "\n");

                echo("InventoryTemplate Info MoreRecords : "); print_r($info->getMoreRecords()); echo("\n");
            }
        }
    }

    public static function getInventoryTemplateById(string $Id, string $module=null, string $sortBy=null, string $sortOrder=null, string $category=null)
    {
       //Get instance of InventoryTemplatesOperations Class
       $inventoryTemplatesOperations = new InventoryTemplatesOperations($module, $sortBy, $sortOrder, $category);

       //Call getInventoryTemplateById method that takes Id as parameter
       $response = $inventoryTemplatesOperations->getInventoryTemplateById($Id);

       if($response != null)
       {
           //Get the status code from response
           echo("Status code " . $response->getStatusCode() . "\n");

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

                //Get the list of obtained InventoryTemplate instances
                $inventoryTemplates = $responseWrapper->getInventoryTemplates();

                foreach($inventoryTemplates as $inventoryTemplate)
                {
                    //Get the CreatedTime of each InventoryTemplate
                    echo("InventoryTemplate CreatedTime: "); print_r($inventoryTemplate->getCreatedTime()); echo("\n");

                    $module = $inventoryTemplate->getModule();

                    if($module != null)
                    {
                        //Get the Module Name of the InventoryTemplate
                        echo("InventoryTemplate Module Name : " . $module->getAPIName() . "\n");

                        //Get the Module Id of the InventoryTemplate
                        echo("InventoryTemplate Module Id : " . $module->getId() . "\n");
                    }

                    //Get the Type of each InventoryTemplate
                    echo("InventoryTemplate Type: " . $inventoryTemplate->getType() . "\n");

                    //Get the CreatedBy User instance of each InventoryTemplate
                    $createdBy = $inventoryTemplate->getCreatedBy();

                    //Check if createdBy is not null
                    if($createdBy != null)
                    {
                        //Get the Id of the CreatedBy User
                        echo("InventoryTemplate Created By User-ID: " . $createdBy->getId(). "\n");

                        //Get the Name of the CreatedBy User
                        echo("InventoryTemplate Created By user-Name: " . $createdBy->getName(). "\n");
                    }

                    //Get the Content of each InventoryTemplate
                    echo("InventoryTemplate Content: " . $inventoryTemplate->getContent() . "\n");

                    //Get the Folder instance of each InventoryTemplate
                    $folder = $inventoryTemplate->getFolder();

                    //Check if folder is not null
                    if($folder != null)
                    {
                        //Get the Id of the Folder
                        echo("InventoryTemplate Folder Id: " . $folder->getId(). "\n");

                        //Get the Name of the Folder
                        echo("InventoryTemplate Folder Name: " . $folder->getName(). "\n");
                    }

                    //Get the ModifiedTime of each InventoryTemplate
                    echo("InventoryTemplate ModifiedTime: "); print_r($inventoryTemplate->getModifiedTime()); echo("\n");

                    //Get the LastUsageTime of each InventoryTemplate
                    echo("InventoryTemplate LastUsageTime: "); print_r($inventoryTemplate->getLastUsageTime()); echo("\n");

                    //Get the name of each InventoryTemplate
                    echo("InventoryTemplate Name: " . $inventoryTemplate->getName() . "\n");

                    //Get the modifiedBy User instance of each InventoryTemplate
                    $modifiedBy = $inventoryTemplate->getModifiedBy();

                    //Check if modifiedBy is not null
                   if($modifiedBy != null)
                   {
                       //Get the ID of the ModifiedBy User
                       echo("InventoryTemplate Modified By User-ID: " . $modifiedBy->getId(). "\n");

                       //Get the Name of the CreatedBy User
                       echo("InventoryTemplate Modified By User-Name: " . $modifiedBy->getName(). "\n");
                   }

                   //Get the ID of each InventoryTemplate
                   echo("InventoryTemplate Id: " . $inventoryTemplate->getId() . "\n");

                   //Get the EditorMode each InventoryTemplate
                   echo("InventoryTemplate EditorMode: " . $inventoryTemplate->getEditorMode() . "\n");

                   //Get the Favorite of each InventoryTemplate
                   echo("InventoryTemplate Favorite: "); print_r($inventoryTemplate->getFavorite()); echo("\n");
                }
           }
       }
    }
}

?>