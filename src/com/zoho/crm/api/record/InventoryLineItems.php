<?php 
namespace com\zoho\crm\api\record;

use com\zoho\crm\api\record\Record;
use com\zoho\crm\api\util\Model;

class InventoryLineItems extends Record implements Model
{


	/**
	 * The method to get the productName
	 * @return LineItemProduct An instance of LineItemProduct
	 */
	public  function getProductName()
	{
		return $this->getKeyValue('Product_Name'); 

	}

	/**
	 * The method to set the value to productName
	 * @param LineItemProduct $productName An instance of LineItemProduct
	 */
	public  function setProductName(LineItemProduct $productName)
	{
		$this->addKeyValue('Product_Name', $productName); 

	}

	/**
	 * The method to get the quantity
	 * @return float A float representing the quantity
	 */
	public  function getQuantity()
	{
		return $this->getKeyValue('Quantity'); 

	}

	/**
	 * The method to set the value to quantity
	 * @param float $quantity A float
	 */
	public  function setQuantity(float $quantity)
	{
		$this->addKeyValue('Quantity', $quantity); 

	}

	/**
	 * The method to get the discount
	 * @return string A string representing the discount
	 */
	public  function getDiscount()
	{
		return $this->getKeyValue('Discount'); 

	}

	/**
	 * The method to set the value to discount
	 * @param string $discount A string
	 */
	public  function setDiscount(string $discount)
	{
		$this->addKeyValue('Discount', $discount); 

	}

	/**
	 * The method to get the totalAfterDiscount
	 * @return float A float representing the totalAfterDiscount
	 */
	public  function getTotalAfterDiscount()
	{
		return $this->getKeyValue('Total_After_Discount'); 

	}

	/**
	 * The method to set the value to totalAfterDiscount
	 * @param float $totalAfterDiscount A float
	 */
	public  function setTotalAfterDiscount(float $totalAfterDiscount)
	{
		$this->addKeyValue('Total_After_Discount', $totalAfterDiscount); 

	}

	/**
	 * The method to get the netTotal
	 * @return float A float representing the netTotal
	 */
	public  function getNetTotal()
	{
		return $this->getKeyValue('Net_Total'); 

	}

	/**
	 * The method to set the value to netTotal
	 * @param float $netTotal A float
	 */
	public  function setNetTotal(float $netTotal)
	{
		$this->addKeyValue('Net_Total', $netTotal); 

	}

	/**
	 * The method to get the priceBookName
	 * @return float A float representing the priceBookName
	 */
	public  function getPriceBookName()
	{
		return $this->getKeyValue('Price_Book_Name'); 

	}

	/**
	 * The method to set the value to priceBookName
	 * @param float $priceBookName A float
	 */
	public  function setPriceBookName(float $priceBookName)
	{
		$this->addKeyValue('Price_Book_Name', $priceBookName); 

	}

	/**
	 * The method to get the tax
	 * @return float A float representing the tax
	 */
	public  function getTax()
	{
		return $this->getKeyValue('Tax'); 

	}

	/**
	 * The method to set the value to tax
	 * @param float $tax A float
	 */
	public  function setTax(float $tax)
	{
		$this->addKeyValue('Tax', $tax); 

	}

	/**
	 * The method to get the listPrice
	 * @return float A float representing the listPrice
	 */
	public  function getListPrice()
	{
		return $this->getKeyValue('List_Price'); 

	}

	/**
	 * The method to set the value to listPrice
	 * @param float $listPrice A float
	 */
	public  function setListPrice(float $listPrice)
	{
		$this->addKeyValue('List_Price', $listPrice); 

	}

	/**
	 * The method to get the unitPrice
	 * @return float A float representing the unitPrice
	 */
	public  function getUnitPrice()
	{
		return $this->getKeyValue('unit_price'); 

	}

	/**
	 * The method to set the value to unitPrice
	 * @param float $unitPrice A float
	 */
	public  function setUnitPrice(float $unitPrice)
	{
		$this->addKeyValue('unit_price', $unitPrice); 

	}

	/**
	 * The method to get the quantityInStock
	 * @return float A float representing the quantityInStock
	 */
	public  function getQuantityInStock()
	{
		return $this->getKeyValue('quantity_in_stock'); 

	}

	/**
	 * The method to set the value to quantityInStock
	 * @param float $quantityInStock A float
	 */
	public  function setQuantityInStock(float $quantityInStock)
	{
		$this->addKeyValue('quantity_in_stock', $quantityInStock); 

	}

	/**
	 * The method to get the total
	 * @return float A float representing the total
	 */
	public  function getTotal()
	{
		return $this->getKeyValue('Total'); 

	}

	/**
	 * The method to set the value to total
	 * @param float $total A float
	 */
	public  function setTotal(float $total)
	{
		$this->addKeyValue('Total', $total); 

	}

	/**
	 * The method to get the description
	 * @return string A string representing the description
	 */
	public  function getDescription()
	{
		return $this->getKeyValue('Description'); 

	}

	/**
	 * The method to set the value to description
	 * @param string $description A string
	 */
	public  function setDescription(string $description)
	{
		$this->addKeyValue('Description', $description); 

	}

	/**
	 * The method to get the lineTax
	 * @return array A array representing the lineTax
	 */
	public  function getLineTax()
	{
		return $this->getKeyValue('Line_Tax'); 

	}

	/**
	 * The method to set the value to lineTax
	 * @param array $lineTax A array
	 */
	public  function setLineTax(array $lineTax)
	{
		$this->addKeyValue('Line_Tax', $lineTax); 

	}
} 
