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
		$filterString     = $this->request->getPost('filters');
		$filterMap = !$filterString ? [] : json_decode($filterString, true);
		$filter = [];

		if (!empty($filterMap))
			$filter = [
				'code' => $filterMap['field'],
				'operation' => 'IS',
				'value' => $filterMap['value']['value'],
			];


		if(empty($nodeField) || empty($nodeTable))
			return $this->jsonResult(['success' => false, 'message' => 'некорректные настройки']);

		// define primary key
		$columns = $this->element->getColumns($nodeTable);
		$primaryKeyCode = 'id';
		foreach ($columns as $columnKey => $column)
			if($column['key'] == 'PRI')
			{
				$primaryKeyCode = $columnKey;
				break;
			}

		$select  = [
			'from'  => $nodeTable,
			'limit' => 20,
			'where' => [
				'operation' => 'and',
				'fields'    => [[
					'code'      => $nodeSearch,
					'operation' => 'CONTAINS',
					'value'     => $q
				], $filter]
			]
		];

		$selectResult = $this->element->select($select);
		$nodes        = $selectResult['success'] ? $selectResult['result'] : [];
		$result       = [];

		if (!empty($nodes))
			foreach($nodes['items'] as $node)
				$result[] = [
					'value'          => $node[$nodeField],
					'name'           => $node[$nodeSearch],
					'url'            => "/table/{$nodeTable}/el/{$node[$primaryKeyCode]}",
					'primaryKey'     => $node[$primaryKeyCode],
					'primaryKeyCode' => $primaryKeyCode
				];

		return $this->jsonResult(['success' => true, 'result' => $result]);
	}

	public function getFieldValuesAction()
	{
		$nodeField  = $this->request->get('column');
		$nodeTable  = $this->request->get('table');

		$select  = [
			'from'  => $nodeTable,
			'limit' => 10000,
			'fields' => [$nodeField],
		];

		$selectResult = $this->element->select($select)['result']['items'];

		$dataMap  = array_column($selectResult, $nodeField);
		$result = [];

		foreach ($dataMap as $item)
		{
			if (!array_key_exists($item[0]['value'], $result))
				$result[$item[0]['value']] = $item[0];
		}

		return $this->jsonResult([
			'success' => true,
			'result' =>  array_values($result),
		]);
	}
}
