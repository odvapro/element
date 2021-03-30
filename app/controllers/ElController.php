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
			$delete = $this->request->get('delete');

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

		if (empty($duplicateSelect) || empty($duplicateSelect['where']['fields'][0]['value']))
			$duplicateSelect = $duplicateSelect = $this->request->get('duplicate');

		if (empty($duplicateSelect) || empty($duplicateSelect['where']['fields'][0]['value']))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$row = $this->element->select($duplicateSelect);
		if (empty($row['items']))
			return $this->jsonResult(['success' => false, 'message' => 'wrong id']);
		$resultDuplicate = $this->element->duplicate($duplicateSelect);

		return $this->jsonResult(['success' => $resultDuplicate, 'lastId' => $this->eldb->getLastInsertId()]);
	}
	/**
	 * insert method SQL
	 * @return json
	 */
	public function insertAction()
	{
		$insert = $this->request->getPost('insert');

		if (empty($insert))
			$insert = $this->request->get('insert');

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
			$update = $this->request->get('update');

		if (empty($update))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		try {
			$resultUpdate = $this->element->update($update);
		} catch (Exception $e) {
			return $this->jsonResult(['success' => false, 'message' => $e->getMessage()]);
		} finally {
			if ($resultUpdate === false)
				return $this->jsonResult(['success' => false, 'message' => 'some error']);

			return $this->jsonResult(['success' => true, 'result' => $resultUpdate]);
		}
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

		$pagination = array_merge($pagination, $resultSelect);
		return $this->jsonResult([
			'success' => true,
			'result' => $pagination
		]);
	}

	public function searchAction()
	{
		$select = $this->request->get('select');

		if (empty($select))
			return $this->jsonResult(['success' => false, 'message' => 'empty request']);

		$search = !empty($select['search']) ? $select['search'] : '';
		$page   = !empty($select['page']) ? $select['page'] : 1;
		$limit  = empty($this->request->get('limit')) ? 100 : intval($this->request->get('limit'));

		$resultSelect = $this->element->select($select);

		if ($resultSelect === false)
			return $this->jsonResult(['success' => false, 'message' => 'some error']);

		$searchedFields = array_keys(array_intersect($resultSelect['columns_types'], ['em_string','em_text']));

		if (!empty($searchedFields) && !empty($search))
		{
			$resultSelect['items'] = array_filter($resultSelect['items'], function($item) use ($searchedFields, $search)
			{
				foreach ($searchedFields as $searchedField) {
					if (mb_stripos(json_encode($item[$searchedField], JSON_UNESCAPED_UNICODE), $search))
						return true;
				}
				return false;
			});
		}

		// Define count of element
		$itemsCount = $this->element->count($select);
		$paginator = new ElPagination([
			'count' => count($resultSelect['items']),
			'limit' => $limit,
			'page'  => $page,
		]);
		$pagination = $paginator->getPaginate();
		$resultSelect['items'] = array_slice($resultSelect['items'], $pagination['offset'], $pagination['offset'] + $limit);

		$pagination = array_merge($pagination, $resultSelect);
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
