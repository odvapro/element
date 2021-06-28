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
			return $this->jsonResult(['success' => false, 'message' => 'empty id', 'code' => 1]);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview', 'code' => 10]);
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
			return $this->jsonResult(['success' => false, 'message' => 'empty id', 'code' => 1]);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview', 'code' => 10]);
		$tview->sort = $sort;
		$tview->save();

		return $this->jsonResult(['success' => true]);
	}

	public function exportCsvAction()
	{
		$tviewId = $this->request->get('tviewId');

		if (empty($tviewId))
			return $this->jsonResult(['success' => false, 'message' => 'empty id', 'code' => 1.1]);

		$tview = EmViews::findFirstById($tviewId);
		if(!$tview)
			return $this->jsonResult(['success' => false, 'message' => 'cant find tview', 'code' => 10]);

		$allFieldsData = array_filter($tview->settings['columns'], function($field){return $field['visible'] === 'true';});

		$fieldsAlias = json_decode(json_encode(EmTypes::findByTable($tview->table)));
		foreach ($fieldsAlias as $alias)
			foreach ($allFieldsData as $actualFieldDataName => &$actualFieldData)
				if ($alias->field === $actualFieldDataName && !empty($alias->name))
					$actualFieldDataName = $alias->name;

		$select = [
			'from'   => $tview->table,
			'fields' => array_keys($allFieldsData)
		];

		$selectResult = $this->element->select($select);
		if (!$selectResult['success'])
			return $this->jsonResult(['success' => false, 'message' => $selectResult['message'], 'code' => $selectResult['code']]);
		$allFields = $selectResult['result'];

		$lastItemName = array_key_last($allFieldsData);
		$fileContent = "";
		foreach ($allFieldsData as $fieldDataName => $fieldData)
		{
			if ($fieldDataName === $lastItemName)
				$fileContent .= json_encode($fieldDataName, JSON_UNESCAPED_UNICODE) . "\n";
			else
				$fileContent .= json_encode($fieldDataName, JSON_UNESCAPED_UNICODE) . ",";
		}

		foreach ($allFields as $fieldData)
		{
			$lastColumnName = array_key_last($fieldData);
			foreach ($fieldData as $rowDataName => $rowData)
			{
				$valueToContent = $rowData ?? "";
				if ($rowDataName !== $lastColumnName)
					$fileContent .= json_encode($valueToContent, JSON_UNESCAPED_UNICODE) . ",";
				else
					$fileContent .= json_encode($valueToContent, JSON_UNESCAPED_UNICODE) . "\n";
			}
		}
		$fileName = "{$tview->table}.csv";
		header("Content-type: text/csv");
		header("Content-disposition: attachment; filename = {$fileName}");
		echo $fileContent;
	}
}
