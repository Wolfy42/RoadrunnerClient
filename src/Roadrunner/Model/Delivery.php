<?php
namespace Roadrunner\Model;

use Roadrunner\Model\Address;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\CouchDB\View\Query;
use Doctrine\ODM\CouchDB\View\DoctrineAssociations;

/**
 * @Document
 */
class Delivery extends BaseDocument {
	
	/** @Field(type="datetime") */	 
	private $created_at;
	
	/** @Field(type="datetime") */	
	private $modified_at;
	
    /** @EmbedOne */
    private $from_address;
    
    /** @EmbedOne */
    private $to_address;
    	
	/**
     * @ReferenceMany(targetDocument="Item")
     */
	private $items;
	
	public final function __construct() {
        parent::__construct('delivery');
        $this->items = new ArrayCollection();
        $this->created_at = time();
    }
    
	/**
	 * @return Address
	 */
	public function getFromAddress() {
		return $this->from_address;
	}
	
	/**
	 * @param Address $address
	 */
	public function setFromAddress(Address $address) {
		if ($this->from_address !== $address) {
			$this->from_address = $address;
		}
	}
	
	/**
	 * @return Address
	 */
	public function getToAddress() {
		return $this->to_address;
	}
	
	/**
	 * @param Address $address
	 */
	public function setToAddress(Address $address) {
		if ($this->to_address !== $address) {
			$this->to_address = $address;
		}
	}
	
	/**
	 * @param Item $item
	 */
 	public function addItem(Item $item) {
        $this->items[] = $item;
    }
    
    /**
     * @return Ambigous <\Doctrine\Common\Collections\ArrayCollection, Item>
     */
    public function getItems() {
    	return $this->items;
    }
	
    /**
     * @return number
     */
    public function getCreatedAt() {
    	return $this->created_at;
    }
    
    /**
     * @return number
     */
    public function getModifiedAt() {
    	return $this->modified_at;
    }
    
    public function setModifiedAt() {
    	$this->modified_at = time();
    }
	
	static public function getAll($manager)
	{
		return self::createQuery($manager, 'deliveries')->execute();
	}
	
	static public function find($manager, $id)
	{
		return $manager->getRepository(__CLASS__)->find($id);
	}
}