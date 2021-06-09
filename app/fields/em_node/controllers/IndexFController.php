<?php

class IndexFController extends ControllerBase
{
	/**
	 * Поиск полей для автокомплита, работает только при ajax запросах
	 * @var $_POST['nodeTableCode'] string таблица привязки
	 * @var $_POST['nodeFieldCode'] string поле привязки - например id
	 * @var $_POST['nodeSearchCode'] string поле по которому ищется привязываемый элемент - например name
	 * @var $_POST['q'] string строка поиска
	 * @return JSON
	 */
	public function autoCompleteAction()
	{
		$nodeField  = $this->request->getPost('nodeFieldCode');
		$nodeTable  = $this->request->getPost('nodeTableCode');
		$nodeSearch = $this->request->getPost('nodeSearchCode');
		$q          = $this->request->getPost('q');

		if(empty($nodeField) || empty($nodeTable))
			return $this->jsonResult(['success' => false, 'message' => 'некорректные настройки']);

		$select  = [
			'from'  => $nodeTable,
			'limit' => 7,
			'where' => [
				'operation' => 'and',
				'fields'    => [[
					'code'      => $nodeSearch,
					'operation' => 'CONTAINS',
					'value'     => $q
				]]
			]
		];

		$selectResult  = $this->element->select($select);
		$nodes  = $selectResult['success'] ? $selectResult['result'] : [];
		$result = [];

		if (!empty($nodes))
		{
			foreach($nodes['items'] as $node)
			{
				$result[] = [
					'id'   => $node[$nodeField],
					'name' => $node[$nodeSearch],
				];
			}
		}

		return $this->jsonResult(['success' => true, 'result' => $result]);
	}
}
