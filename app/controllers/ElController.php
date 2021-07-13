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
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		$deleteResult = $this->element->delete($delete);
		if (!$deleteResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $deleteResult['message'], 'code' => $deleteResult['code']]);

		$deleteResult = $deleteResult['result'];

		return $this->jsonResult(['success' => true, 'result' => $deleteResult]);
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

		if (empty($duplicateSelect) || empty($duplicateSelect['where']['fields'][0]['value']))
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		$selectResult = $this->element->select($duplicateSelect);
		if (!$selectResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $selectResult['message'], 'code' => $selectResult['code']]);

		$row = $selectResult['result'];
		if (empty($row['items']))
			return $this->jsonResult(['success' => false, 'message' => 'wrong_id', 'code' => 5]);
		$duplicateResult = $this->element->duplicate($duplicateSelect);
		if (!$duplicateResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $duplicateResult['message'], 'code' => $duplicateResult['code']]);
		$duplicateResult = $duplicateResult['result'];
		return $this->jsonResult(['success' => $duplicateResult, 'lastId' => $this->eldb->getLastInsertId()]);
	}
	/**
	 * insert method SQL
	 * @return json
	 */
	public function insertAction()
	{
		$insert = $this->request->getPost('insert');

		if (empty($insert))
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		$insertResult = $this->element->insert($insert);
		if (!$insertResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $insertResult['message'], 'code' => $insertResult['code']]);

		$insertResult = $insertResult['result'];
		$lastId = $this->eldb->getLastInsertId();

		return $this->jsonResult([
			'success' => true,
			'result'  => $insertResult,
			'firstid' => +$lastId,
			'lastid'  => +$lastId + count($insert['values']) - 1,
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
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		$updateResult = $this->element->update($update);
		if (!$updateResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $updateResult['message'], 'code' => $updateResult['code']]);

		$updateResult = $updateResult['result'];
		return $this->jsonResult(['success' => $updateResult, 'result' => $updateResult]);
	}

	/**
	 * select methos SQL
	 * @return json
	 */
	public function selectAction()
	{
		$select = $this->request->get('select');

		$page   = (!empty($select['page'])) ? $select['page'] : 1;
		$limit  = empty($select['limit']) || intval($select['limit']) <= 0 ? 100 : intval($select['limit']);

		if (empty($select))
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		// Define count of element
		$itemsCount = $this->element->count($select);
		if (!$itemsCount['success'])
			return $this->jsonResult(['success' => false, 'message' => $itemsCount['message'], 'code' => $itemsCount['code']]);

		$itemsCount = $itemsCount['result'];
		$paginator = new ElPagination([
			'count' => $itemsCount,
			'limit' => $limit,
			'page'  => $page,
		]);
		$pagination = $paginator->getPaginate();

		// Select need page
		$select['limit']  = $limit;
		$select['offset'] = $pagination['offset'];
		$selectResult = $this->element->select($select);
		if (!$selectResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $selectResult['message'], 'code' => $selectResult['code']]);
		$result = $selectResult['result'];

		$pagination = array_merge($pagination, $result);
		return $this->jsonResult([
			'success' => true,
			'result' => $pagination
		]);
	}

	/**
	 * select in table
	 * @return json
	 */
	public function searchAction()
	{
		$select = $this->request->get('select');
		if (empty($select) || empty($select['from']))
			return $this->jsonResult(['success' => false, 'message' => 'empty_request', 'code' => 1]);

		$search = !empty($select['search']) ? $select['search'] : '';
		unset($select['search']);
		$page   = !empty($select['page']) ? $select['page'] : 1;
		$limit  = empty($select['limit']) || intval($select['limit']) <= 0 ? 100 : intval($select['limit']);
		unset($select['limit']);

		$columns = $this->element->getColumns($select['from']);
		$searchedFields = [];

		if (!empty($search))
		{
			$select['where'] = [
				'operation' => 'OR',
				'fields' => [],
			];
			$select['order'] = [];
			foreach ($columns as $columnKey => $columnInfo) {
				if (!in_array($columnInfo['em']['settings']['code'], ['em_string','em_text']))
					continue;

				$select['where']['fields'][] = [
					'code'      => $columnKey,
					'operation' => 'CONTAINS',
					'value'     => $search,
				];
				$select['order'][] = "levenshtein('{$search}', {$columnKey})";
			}
			$select['order'] = [ 'LEAST('.implode(', ', $select['order']).')' ];
		}

		$selectResult = $this->element->select($select);

		if (!$selectResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $selectResult['message'], 'code' => $selectResult['code']]);
		$result = $selectResult['result'];

		$paginator = new ElPagination([
			'count' => count($result['items']),
			'limit' => $limit,
			'page'  => $page,
		]);
		$pagination = $paginator->getPaginate();
		$result['items'] = array_slice($result['items'], $pagination['offset'], $limit);

		$pagination = array_merge($pagination, $result);
		return $this->jsonResult([
			'success' => true,
			'result' => $pagination,
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
			$groupsId = array_column($this->user->groups->toArray(), 'id');

			$hasAccess = array_filter($table['access'], function ($accessItem) use ($groupsId) { return in_array($accessItem['group_id'], $groupsId); });
			if (empty($hasAccess)) continue;

			$emViewsTable = EmViews::findByTable($table['code']);
			$table['columns'] = $this->element->getColumns($table['code']);

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
			$defaultTview = null;

			foreach ($emViewsTable as $tview)
			{
				if (!empty($tview->settings['columns']))
				{
					$tview->settings['columns'] = array_intersect_key($tview->settings['columns'], $table['columns']);
					$tview->save();
					$tview->refresh();
				}
				$table['tviews'][] = $tview->toArray();
				if ($tview->default != '1')
					continue;
				else if (empty($defaultTview))
					$defaultTview = $tview->toArray();

				if (isset($tview->settings['table']['name']))
					$table['name'] = $tview->settings['table']['name'];

				if (isset($tview->settings['table']['visible']))
					$table['visible'] = ($tview->settings['table']['visible'] == "false")?false:true;
				else
					$table['visible'] = false;
			}

			if (!empty($defaultTview) && !empty($defaultTview['settings']['columns']))
			{
				foreach ($defaultTview['settings']['columns'] as $defaultTviewColumnName => $defaultTviewColumn)
					$table['columns'][$defaultTviewColumnName]['order'] = isset($defaultTviewColumn['order']) ? $defaultTviewColumn['order'] : 0;
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
			return $this->jsonResult(['success' => false, 'message' => 'tview_not_found', 'code' => 1]);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'tview_not_found', 'code' => 5]);

		$tviewSettings = $tview->settings;

		foreach ($params as $keySetting => $setting)
			$tviewSettings[$keySetting] = $setting;

		$tview->settings = $tviewSettings;
		if ($tview->save() == false)
			return $this->jsonResult(['success' => false, 'message' => 'some_error', 'code' => 2]);

		return $this->jsonResult(['success' => true]);
	}
}
