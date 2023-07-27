<?php

class IndexFController extends ControllerBase
{
	/**
	 * Поиск полей для автокомплита, работает только при ajax запросах
	 * @var $_POST['sectionFieldCode'] string таблица привязки
	 * @var $_POST['sectionTableCode'] string поле привязки - например id
	 * @var $_POST['sectionSearchCode'] string поле по которому ищется привязываемый элемент - например name
	 * @var $_POST['parentId'] int значение родительского id
	 * @var $_POST['q'] string строка поиска
	 * @return JSON
	 */
	public function autoCompleteAction()
	{
		$sectionTableCode        = $this->request->getPost('sectionTableCode');
		$sectionFieldCode        = $this->request->getPost('sectionFieldCode');
		$sectionSearchCode       = $this->request->getPost('sectionSearchCode');
		$sectionParentsFieldCode = $this->request->getPost('sectionParentsFieldCode');
		$parentId                = $this->request->getPost('parentId');
		$q                       = $this->request->getPost('q');

		if(empty($sectionTableCode) || empty($sectionFieldCode))
			return $this->jsonResult(['success' => false, 'message' => 'некорректные настройки']);

		// TODO
		// переделать - не безоспастно
		$db             = $this->di->get('db');
		$parentId       = empty($parentId)?"(a.{$sectionParentsFieldCode} IS NULL OR a.{$sectionParentsFieldCode} = 0)":" a.{$sectionParentsFieldCode} = {$parentId}";
		$q              = empty($q)?'':"a.{$sectionSearchCode} LIKE '%{$q}%'";
		$whereCondition = !empty($q)?$q:$parentId;

		$sql = "SELECT
					a.{$sectionFieldCode},a.{$sectionSearchCode},a.{$sectionParentsFieldCode}, COUNT(b.{$sectionFieldCode}) childsCount
					FROM {$sectionTableCode} a
				LEFT JOIN {$sectionTableCode} b ON a.{$sectionFieldCode} = b.{$sectionParentsFieldCode}
				WHERE  {$whereCondition}
				GROUP BY a.{$sectionFieldCode}, a.{$sectionSearchCode}";

		// Send a SQL statement to the database system
		$db->prepare($sql);
		$select = $db->fetchAll($sql, \Phalcon\Db\Enum::FETCH_ASSOC, [] );

		$sections  = count($select) > 0 ? $select : [];
		return $this->jsonResult(['success' => true, 'result' => $sections]);
	}
}
