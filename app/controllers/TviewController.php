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

		$allFieldsData = array_filter($tview->settings['columns'], function($field){return $field['visible'] === 'true';});
		foreach ($allFieldsData as $fieldDataKey => &$fieldDataValue)
			$fieldDataValue['fieldName'] = $fieldDataKey;

		$fieldsAlias = json_decode(json_encode(EmTypes::findByTable($tview->table)));
		foreach ($fieldsAlias as $alias)
			foreach ($allFieldsData as &$actualFieldData)
				if ($alias->field === $actualFieldData['fieldName'] && !empty($alias->name))
					$actualFieldData['fieldName'] = $alias->name;

		$select = [
			'from'   => $tview->table,
			'fields' => array_keys($allFieldsData)
		];

		$allFields = $this->element->select($select);

		$lastItem = end($allFieldsData);
		$fileContent = "";
		foreach ($allFieldsData as $fieldData)
			if ($fieldData['fieldName'] === $lastItem['fieldName'])
				$fileContent .= json_encode($fieldData['fieldName'], JSON_UNESCAPED_UNICODE) . "\n";
			else
				$fileContent .= json_encode($fieldData['fieldName'], JSON_UNESCAPED_UNICODE) . ",";

		foreach ($allFields['items'] as $fieldDate)
		{
			foreach ($fieldDate as $rowDataKey => &$rowDataValue)
				$rowDataValue = ['value'=>$rowDataValue, 'name'=>$rowDataKey];

			$lastColumn = end($fieldDate)['name'];
			foreach ($fieldDate as $rowData)
			{
				$valueToContent = empty($rowData['value'])
				? ""
				: $rowData['value'];
				if ($rowData['name'] !== $lastColumn)
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