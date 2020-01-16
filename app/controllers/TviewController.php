<?php
/**
 * Conroller for table views manipulations
 */
class TviewController extends ControllerBase
{
	/**
	 * Сохранить фильтры
	 */
	public function saveFiltersAction()
	{
		$tviewId = $this->request->get('tviewId');
		$filters = $this->request->get('filters');

		if (empty($tviewId))
			return $this->jsonResult(['success' => false, 'message' => 'empty id']);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview']);
		$tview->filter = $filters;
		$tview->save();

		return $this->jsonResult(['success' => true]);
	}
	/**
	 * Сохранить сортировку
	 */
	public function saveSortAction()
	{
		$tviewId = $this->request->get('tviewId');
		$sort    = $this->request->get('sort');

		if (empty($tviewId))
			return $this->jsonResult(['success' => false, 'message' => 'empty id']);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview']);
		$tview->sort = $sort;
		$tview->save();

		return $this->jsonResult(['success' => true]);
	}

	public function exportCsvAction()
	{
		$tviewId = $this->request->get('tviewId');

		if (empty($tviewId))
			return $this->jsonResult(['success' => false, 'message' => 'empty id']);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview']);

		$sql = "SELECT *
				FROM $tview->table";
		$tableData = $this->db->fetchAll(
			$sql,
			\Phalcon\Db::FETCH_ASSOC
		);

		$fieldsAlias = json_decode(json_encode(EmTypes::findByTable($tview->table)));
		$visibleFields = array_filter($tview->settings['columns'], function($field){return $field['visible'] === 'true';});

		foreach ($fieldsAlias as $visFieldAliasData)
			if (!empty($visibleFields[$visFieldAliasData->field]))
				if (!empty($visFieldAliasData->name))
					$visibleFields[$visFieldAliasData->field]['name'] = $visFieldAliasData->name;

		foreach ($visibleFields as $visibleFieldsKey => &$visibleFieldsVal)
			if (!isset($visibleFieldsVal['name']) || empty($visibleFieldsVal['name']))
				$visibleFieldsVal['name'] = $visibleFieldsKey;

		$fileContent = "";
		foreach ($visibleFields as $visField)
		{
			if (end($visibleFields)['name'] !== $visField['name'])
				$fileContent .= "'{$visField['name']}',";
			else
				$fileContent .= "{$visField['name']}'\n";
		}
		$tableDataRowLastKey = '';
		foreach (array_keys($tableData[0]) as $tableDataRowLastKeyName)
			$tableDataRowLastKey = $tableDataRowLastKeyName;

		foreach ($tableData as $tableDataRow)
			foreach ($tableDataRow as $tableDataRowFieldKey => $tableDataRowFieldVal)
			{
				if ($tableDataRowFieldKey !== $tableDataRowLastKey)
					$fileContent .= "'$tableDataRowFieldVal',";
				else
					$fileContent .= "'$tableDataRowFieldVal'\n";
			}
		$fileName = "{$tview->table}.csv";
		header("Content-type: text/csv");
		header("Content-disposition: attachment; filename = {$fileName}");
		echo $fileContent;
	}
}
