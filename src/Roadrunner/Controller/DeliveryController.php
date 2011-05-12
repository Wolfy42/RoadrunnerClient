<?php
namespace Roadrunner\Controller;

use Roadrunner\Model\Delivery;
use Roadrunner\Model\Address;

class DeliveryController extends BaseController {
	
	public function executeIndex()
	{
		return $this->executeList();
	}
	
	public function executeList()
	{		
		return $this->render('delivery.list.twig', array(
			'delivery_list' => Delivery::getAll($this->getDocumentManager()),
		));
	}
	
	public function executeView()
	{
		return $this->render('delivery.view.twig', array(
			'delivery' => Delivery::find(
				$this->getDocumentManager(),
				$this->getRequest('id')
			),
		));
	}
	
	public function executeAdd()
	{
		return $this->render('delivery.add.twig', array(
			'form_action' => url_for('/delivery/create'),
		));
	}
	
	public function executeCreate()
	{
		$delivery = new Delivery();
		$delivery->setFromAddress(new Address($this->getRequest('from')));
		$delivery->setToAddress(new Address($this->getRequest('to')));
		
		$manager = $this->getDocumentManager();
		$manager->persist($delivery);
		$manager->flush();
		
		$this->redirect('/delivery/view/' . $delivery->getId());
	}
	
}