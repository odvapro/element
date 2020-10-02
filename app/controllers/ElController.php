<?php
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;
use Phalcon\Mvc\Model\Resultset;

class ElController extends ControllerBase
{
	/**
	 * delete method SQL
	 * @return json
	 */
	public function deleteAction()
	{
		$delete = $this->request->getPost('delete');

		if (empty($delete))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$resultDelete = $this->element->delete($delete);

		if ($resultDelete === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultDelete]);
	}
	/**
	 * принимает стандартный селект элемента для запроса с id записи
	 * находит эту запись, затем insert ее значения в таблицу
	 * @param  array $duplicateSelect
	 * @return json
	 */
	public function duplicateAction()
	{
		$duplicateSelect = $this->request->getPost('duplicate');

		if (empty($duplicateSelect))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$record = $this->element->select($duplicateSelect);

		if (empty($record))
			return $this->jsonResult(['success' => false, 'message' => 'can\'t find element']);

		$record = $record[0];

		$insertParams = [
			'table'   => $duplicateSelect['from'],
			'columns' => [],
			'values'  => []
		];
		foreach ($record as $propertyKey => $property)
		{
			if ($property['fieldName'] === 'em_primary')
				continue;
			$insertParams['columns'][] = $propertyKey;
			$insertParams['values'][]  = $property['value'];
		}
		$resultDuplicate = $this->eldb->insert($insertParams);

		if ($resultDuplicate === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultDuplicate]);
	}
	/**
	 * insert method SQL
	 * @return json
	 */
	public function insertAction()
	{
		$insert = $this->request->getPost('insert');

		if (empty($insert))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		try {
			$resultInsert = $this->element->insert($insert);
		} catch (Exception $e) {
			return $this->jsonResult(['success' => false, 'message' => $e->getMessage()]);
		}

		if ($resultInsert === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		$lastId = $this->eldb->getLastInsertId();

		return $this->jsonResult([
			'success' => true,
			'result'  => $resultInsert,
			'lastid'  => $lastId
		]);
	}

	/**
	 * update method SQL
	 * @return json
	 */
	public function updateAction()
	{
		$update = $this->request->getPost('update');

		if (empty($update))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		try {
			$resultUpdate = $this->element->update($update);
		} catch (Exception $e) {
			return $this->jsonResult(['success' => false, 'message' => $e->getMessage()]);
		}

		if ($resultUpdate === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true, 'result' => $resultUpdate]);
	}

	/**
	 * select methos SQL
	 * @return json
	 */
	public function selectAction()
	{
		$select = $this->request->get('select');

		$page   = (!empty($select['page'])) ? $select['page'] : 1;
		$limit  = empty($this->request->get('limit')) ? 100 : intval($this->request->get('limit'));

		if (empty($select))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		// Define count of element
		$itemsCount = $this->element->count($select);
		$paginator = new ElPagination([
			'count' => $itemsCount,
			'limit' => $limit,
			'page'  => $page,
		]);
		$pagination = $paginator->getPaginate();

		// Select need page
		$select['limit']  = $limit;
		$select['offset'] = $pagination['offset'];
		$resultSelect = $this->element->select($select);
		if ($resultSelect === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);
		$pagination['items'] = $resultSelect;

		return $this->jsonResult([
			'success' => true,
			'result' => $pagination
		]);
	}

	/**
	 * Get Tables
	 * @return json
	 */
	public function getTablesAction()
	{
		$tables = $this->eldb->getTables();

		foreach ($tables as &$table)
		{
			$emViewsTable = EmViews::findByTable($table['code']);
			$table['columns'] = $this->element->getColumns($table['code']);
			$table['access']  = $this->access->getAccessTable($table['code']);

			if (!count($emViewsTable))
			{
				$tableEmView = new EmViews();
				$tableEmView->name = 'Default view';
				$tableEmView->table = $table['code'];
				$tableEmView->default = '1';
				$tableEmView->save();

				$table['tviews'][] = $tableEmView->toArray();

				continue;
			}

			$table['tviews'] = [];
			$tviews = [];

			foreach ($emViewsTable as $tview)
			{
				$table['tviews'][] = $tview->toArray();
				if ($tview->default != '1')
					continue;

				if (isset($tview->settings['table']['name']))
					$table['name'] = $tview->settings['table']['name'];

				if (isset($tview->settings['table']['visible']))
					$table['visible'] = ($tview->settings['table']['visible'] == "false")?false:true;
				else
					$table['visible'] = false;
			}
		}

		$this->jsonResult([
			'success' => true,
			'tables'  => $tables
		]);
	}

	/**
	 * Set settings for tView
	 */
	public function setTviewSettingsAction()
	{
		$tviewId = $this->request->getPost('tviewId');
		$params  = $this->request->getPost('params');

		if (empty($tviewId))
			return $this->jsonResult(['success' => false, 'message' => 'tview not found']);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'tview not found']);

		$tviewSettings = $tview->settings;

		foreach ($params as $keySetting => $setting)
			$tviewSettings[$keySetting] = $setting;

		$tview->settings = $tviewSettings;
		if ($tview->save() == false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		return $this->jsonResult(['success' => true]);
	}
}
