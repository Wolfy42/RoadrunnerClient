<?php
namespace Roadrunner\Model;

use Doctrine\ODM\CouchDB\View\Query;
use Doctrine\ODM\CouchDB\View\DoctrineAssociations;

/**
 * @Document
 */
class Item extends BaseDocument {
	
	static private $maxTempLogs = 20;
	
	public final function __construct() {
        parent::__construct('item');
    }
	
	/** @Field(type="string") */
	private $name;
		
	/** @Field(type="integer") */
	private $tempMin;

	/** @Field(type="integer") */	
	private $tempMax;
		
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setTempMin($temp) {
		$this->tempMin = $temp;
	}

	public function getTempMin() {
		return $this->tempMin;
	}
		
	public function setTempMax($temp) {
		$this->tempMax = $temp;
	}
		
	public function getTempMax() {
		return $this->tempMax;
	}
	
	public function getImage()
	{
		$file = md5($this->getId()) . '.png';
		if (!file_exists($file)) {
			require_once __DIR__ . '/../../../lib/phpqrcode/qrlib.php';
			
			\QRcode::png($this->getId(), __DIR__ . '/../../../web/cache/'
				. $file, 'L', 4, 2);
		}
		return '/cache/' . $file;
	}

	public function getStatus()  {
		$result = self::createQuery('itemstatus')
			->setKey($this->getId())
			->execute()->toArray();
			
		return $result[0]['value']['status'];
	}
	
	public function getRoute()
	{
		$result = self::createQuery('itemroute')
			->setStartKey(array($this->getId()))
			->setEndKey(array($this->getId(), '', ''))
			->setGroupLevel(3);
			
		return $result->execute();
	}
	
	public function getTempLogs()
	{
		$logs = Log::getForItemId($this->getId());
		$data = array();
		$max = $i = count($logs) / self::$maxTempLogs;
		$minTemp = $this->getTempMin();
		$maxTemp = $this->getTempMax();
		
		foreach ($logs as $log) {
			if ("TEMPSENSOR" == $log['value']['logType']) {
				if ($i-- < 0
					|| $log['value']['value'] < $minTemp
					|| $log['value']['value'] > $maxTemp) {
					$data[] = array(
						'timestamp' => (int) $log['value']['timestamp'],
						'value' => (float) $log['value']['value'],
					);
					$i = $max;
				}
			}
		}
		
		return $data;
	}
	
	static public function getAll()
	{
		return self::createQuery('items')->execute();
	}
	
	static public function find($id)
	{
		return self::getManager()->getRepository(__CLASS__)->find($id);
	}
}