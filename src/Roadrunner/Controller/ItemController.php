<?php
namespace Roadrunner\Controller;

use Roadrunner\Model\Item;

class ItemController extends BaseController {
	
	public function executeIndex()
	{
		return $this->executeList();
	}
	
	public function executeList()  {
		return $this->render('item.list.twig', array(
			'item_list' => Item::getAll(),
		));
	}
	
	public function executeAdd()
	{
		return $this->render('item.add.twig', array(
			'form_action' => '/item/create',
		));
	}
	
	public function executeView()
	{
		$item = Item::find($this->getRequest()->get('id'));

		return $this->render('item.view.twig', array(
			'item' => $item,
		));	
	}
	
	public function executeEdit()
	{
		$id = $this->getRequest()->get('id');
		$item = Item::find($id);
		
		return $this->render('item.edit.twig', array(
			'item' => $item,
			'form_action' => "/item/update/{$id}",
			'hello',
		));
	}
	
	public function executeUpdate()
	{
		
		$item = Item::find($this->getRequest()->get('id'));
		
		$name = $this->app->escape($this->getRequest()->get('name'));
		$tempMin = $this->app->escape($this->getRequest()->get('tempMin'));
		$tempMax = $this->app->escape($this->getRequest()->get('tempMax'));
		
		$item->setName($name);
		$item->setTempMin($tempMin);
		$item->setTempMax($tempMax);
		
		// FIXME: VALIDATE
		$item->save();
		
		return $this->redirect('/item/view/' . $item->getId());
	}
	
	/**
	 * @deprecated
	 */
	public function executeCreate()
	{			
		$name = $this->app->escape($this->getRequest()->get('name'));
		$tempMin = $this->app->escape($this->getRequest()->get('tempMin'));
		$tempMax = $this->app->escape($this->getRequest()->get('tempMax'));
		
		if (empty($name)) {
			throw new ControllerException("Name of item is not set.");
		}
		
		$item = new Item();
		$item->setName($name);
		$item->setTempMin($tempMin);
		$item->setTempMax($tempMax);
		
		$item->save();
		
		return $this->redirect('/item/view/' . $item->getId());
	}
	
	public function executeStatus()
	{
		if (!$this->getRequest()->isXmlHttpRequest()) {
			throw new ControllerException("Status cannot be retrieved directly.");
		}

		$id = $this->getRequest()->get('id');
		$item = Item::find($id);
		
		if (is_null($item)) {
			throw new ControllerException("Item does not exist.");
		}
		
		return json_encode(array(
			'id'     => $item->getId(),
			'status' => $item->getStatus())
		);
	}
	
}